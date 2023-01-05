<template>
  <Dialog-component
    v-model="innerValue"
    :prompt="false"
    cancel-title=""
    confirm-title=""
    width="500"
    with-close-icon
    controls-justify="justify-end"
    add-class="dialog_settings"
  >
    <template #title>
      <span>Настройка параметров отображения реестра</span>
    </template>

    <template #content>
      <v-list :key="componentKey" flat>
        <v-subheader inset>
          <img :src="'/icons/info.svg'" class="iconSettings" />
          Вы можете поменять порядок следования колонок просто перетащив их в нужное место
        </v-subheader>

        <v-list-item-group multiple>
          <draggable
            :list="listDraggable"
            class="list-group"
            ghost-class="ghost"
            @start="dragging = true"
            @end="(dragging) => handleEnd(dragging)"
          >
            <template v-for="(item, i) in listDraggable">
              <v-list-item :key="`item-${i}`" :value="item" :class="item.isShow && 'v-list-item--active'">
                <template>
                  <v-list-item-content>
                    <div class="checkbox-wrapper">
                      <checkbox-component
                        id="all_list"
                        :value="item.isShow"
                        name="all_list"
                        @change="(v) => changeIsShow(v, i)"
                      />
                    </div>

                    <v-list-item-title v-text="item.text" />
                  </v-list-item-content>

                  <v-list-item-action>
                    <div>
                      <button
                        class="settingsSpan btn-sort"
                        :disabled="!item.isShow || !item.sortable"
                        :class="(!item.isShow || !item.sortable) && 'settingsSpan--disabled'"
                        @click="changeDirection('ASC', i)"
                      >
                        <img
                          alt=""
                          :src="
                            item.activeASC && !item.sortDisabledASC ? '/icons/sortAsc_active.svg' : '/icons/sortAsc.svg'
                          "
                          class="iconSettings"
                        />
                      </button>
                      <button
                        class="settingsSpan btn-sort"
                        :disabled="!item.isShow || !item.sortable"
                        :class="(!item.isShow || !item.sortable) && 'settingsSpan--disabled'"
                        @click="changeDirection('DESC', i)"
                      >
                        <img
                          alt=""
                          :src="
                            item.activeDESC && !item.sortDisabledDESC
                              ? '/icons/sortAsc_active.svg'
                              : '/icons/sortAsc.svg'
                          "
                          class="iconSettings"
                          :style="{
                            transform: 'rotateX(180deg)',
                          }"
                        />
                      </button>
                    </div>
                  </v-list-item-action>
                </template>
              </v-list-item>
            </template>
          </draggable>
        </v-list-item-group>
      </v-list>

      <div>
        <v-row justify="end">
          <v-col cols="12" class="col-exclude">
            <DefaultButton title="Сбросить" @click="resetSettings" />
            <DefaultButton title="Отмена" @click="cancelSettings" />
            <DefaultButton variant="primary" title="Применить" @click="applySettings" />
          </v-col>
        </v-row>
      </div>
    </template>
  </Dialog-component>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import has from 'lodash/has';
import isEqual from 'lodash/isEqual';
import cloneDeep from 'lodash/cloneDeep';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import draggable from 'vuedraggable';
import genHash from 'object-hash';
import { merge } from '@/utils/global';

import { SETTINGS_KEY } from '@/components/common/ViewSettings/consts';

export type TSortMap = { [key: string]: string | string[] };
export type TSortDirection = 'DESC' | 'ASC';
export type TColumn = { text: string; value: string };
export type TSort = { property: string; direction: TSortDirection; activeDESC?: boolean; activeASC?: boolean };
export type TFilter = {
  filter?: string;
  pageNumber?: number;
  columns: TColumn[];
  sort: TSort[];
};

@Component({
  name: 'view-settings',
  components: {
    DefaultButton,
    DialogComponent,
    SelectComponent,
    CheckboxComponent,
    draggable,
  },
})
export default class ViewSettingsModal extends Vue {
  /** Уникальный идентификатор реестра. */
  @Prop({ type: String }) readonly id;
  /** Флаг открытия модального окна. */
  @Prop({ type: Boolean }) readonly value;
  @Prop({ type: Array, default: () => [] }) readonly headers!: any[];
  @Prop({ type: Object, default: () => ({}) }) readonly sortMap!: TSortMap;
  @Prop({ type: Object, default: () => null }) readonly defaultSorting!: TSort;
  @Prop({ type: Array, default: () => [] }) readonly additionalSystemHeaders!: string[];
  /* Ограничение количества сортируемых колонок (МАРТ-ИНФО) */
  @Prop({ type: Number }) readonly maxSortLength!: number;

  perPages = [
    { text: 'Все', value: 9999 },
    { text: 5, value: 5 },
    { text: 10, value: 10 },
    { text: 20, value: 20 },
    { text: 50, value: 50 },
    { text: 100, value: 100 },
  ];

  // Сначала в form.columns Записываем значение отфильтрованного this.headers (это this.list)
  // Потом в инит перезаписываем тем, что пришло из локального хранилища
  // И потом уже работаем с этим form
  form = {
    columns: this.list || [],
    sort: [this.sortByDefault],
  };

  listDraggable = cloneDeep(this.form.columns);
  appliedListDraggable: any[] = [];
  componentKey = 0;

  get innerValue() {
    return this.value;
  }

  set innerValue(value) {
    this.$emit('input', value);
  }

  created() {
    this.initSettings();
    this.getListDraggable();
  }

  mounted() {
    this.getListDraggable();
    this.applySettings(false);
  }

  get list() {
    return this.headers.filter(
      (item: any) => item.text && !['actions', 'check', ...this.additionalSystemHeaders].includes(item.value)
    );
  }

  // eslint-disable-next-line max-lines-per-function
  getListDraggable() {
    const getDirection = (item) => {
      if (this.form.sort.some((itemSettings) => itemSettings.property === item.value)) {
        return this.form.sort.filter((itemSettings) => itemSettings.property === item.value)[0].direction;
      } else {
        return item.value === this.sortByDefault.property
          ? this.sortByDefault.direction
          : has(item, 'direction')
          ? item.direction
          : null;
      }
    };

    const getActiveDESC = (item) => {
      if (this.form.sort.some((itemSettings) => itemSettings.property === item.value)) {
        return this.form.sort.filter((itemSettings) => itemSettings.property === item.value)[0].direction === 'DESC';
      } else {
        return (item.value === this.sortByDefault.property && this.sortByDefault.direction) === 'DESC';
      }
    };

    const getActiveASC = (item) => {
      if (this.form.sort.some((itemSettings) => itemSettings.property === item.value)) {
        return this.form.sort.filter((itemSettings) => itemSettings.property === item.value)[0].direction === 'ASC';
      } else {
        return (item.value === this.sortByDefault.property && this.sortByDefault.direction) === 'ASC';
      }
    };

    this.listDraggable.forEach((item) => {
        item.sortable = has(item, 'sortable') ? item.sortable : true,
          item.isShow = this.form.columns.some(itemSettings => itemSettings.value === item.value),
          item.direction = getDirection(item),
          item.activeDESC = getActiveDESC(item),
          item.activeASC = getActiveASC(item)
      }
    )

    // Ограничение количества сортируемых колонок (МАРТ-ИНФО)
    if (this.maxSortLength) {
      this.listDraggable.forEach((item, i) => {
        if (i >= this.maxSortLength) {
          item.sortable = false;
        }
      });
    }
  }

  get settings(): { [key: string]: { [key: string]: any } } {
    return JSON.parse(localStorage.getItem(SETTINGS_KEY) || '{}');
  }

  get hash() {
    return genHash(this.headers);
  }

  get sortByDefault() {
    return (
      this.defaultSorting || {
        property: this.list?.[0]?.value,
        direction: 'DESC',
      }
    );
  }

  get direction() {
    return [
      { text: 'По возрастанию', value: 'ASC' },
      { text: 'По убыванию', value: 'DESC' },
    ];
  }

  get systemColumns() {
    return this.headers.reduce((result, item: any, index) => {
      if (['actions', 'check', ...this.additionalSystemHeaders].includes(item.value)) {
        return [
          ...result,
          {
            index,
            item,
          },
        ];
      }

      return result;
    }, []);
  }

  /** Безопасная запись значения фильтра. */
  setValue(partial: Partial<any>) {
    this.form = merge(this.form, partial);
  }

  initSettings() {
    const { [this.id]: saved } = this.settings;

    const filteredColumns = saved.columns.filter(
      (item: any) => item.text && !['actions', 'check', ...this.additionalSystemHeaders].includes(item.value)
    );
    const settings = { columns: filteredColumns, hash: saved.hash, sort: saved.sort };

    if (settings?.hash === this.hash) {
      this.setValue(this.form);
    }

    this.setValue(merge(this.form, settings));
  }

  // eslint-disable-next-line max-lines-per-function
  mapSettings() {
    const listIsShow = this.listDraggable.filter((item) => !!item.isShow);
    const listIsSortable = this.listDraggable.filter((item) => !!item.isShow && item.sortable && item.direction);

    const columns = listIsShow.map((item) => {
      return {
        text: item.text,
        value: item.value,
        sortable: item.sortable,
        direction: item.direction,
      };
    });

    let sort = listIsSortable.map((item) => {
      return {
        direction: item.direction,
        property: item.value,
      };
    });

    if (this.sortMap || Object.keys(this.sortMap).length) {
      sort = sort.reduce<TSort[]>((result, item) => {
        const rule = this.sortMap[item.property];

        if (rule?.length) {
          const list = Array.isArray(rule) ? rule : [rule];

          return [
            ...result,
            ...list.map((key) => ({
              ...item,
              property: key,
            })),
          ];
        }

        return [...result, item];
      }, []);
    }

    this.systemColumns.forEach(({ index, item }) => {
      columns?.splice(index, 0, item);
    });

    return {
      columns,
      sort,
    };
  }

  applySettings(close = true) {
    this.appliedListDraggable = cloneDeep(this.listDraggable);

    const form = this.mapSettings();

    localStorage.setItem(
      SETTINGS_KEY,
      JSON.stringify({
        ...this.settings,
        [this.id]: {
          ...form,
          hash: this.hash,
        },
      })
    );
    this.$emit('apply-settings', form);
    if (close) {
      this.$emit('close');
    }
  }

  changeDirection(direction, i) {
    this.listDraggable[i].direction = direction;

    if (direction === 'ASC') {
      if (!has(this.listDraggable[i], 'activeASC')) {
        this.listDraggable[i].activeASC = true;
        this.listDraggable[i].activeDESC = false;
      } else if (has(this.listDraggable[i], 'activeASC') && this.listDraggable[i].activeASC) {
        this.listDraggable[i].activeASC = false;
        this.listDraggable[i].sortDisabledASC = true;
      } else {
        this.listDraggable[i].activeASC = true;
        this.listDraggable[i].activeDESC = false;
        this.listDraggable[i].sortDisabledASC = false;
      }
    }

    if (direction === 'DESC') {
      if (!has(this.listDraggable[i], 'activeDESC')) {
        this.listDraggable[i].activeDESC = true;
        this.listDraggable[i].activeASC = false;
      } else if (has(this.listDraggable[i], 'activeDESC') && this.listDraggable[i].activeDESC) {
        this.listDraggable[i].activeDESC = false;
        this.listDraggable[i].sortDisabledDESC = true;
      } else {
        this.listDraggable[i].activeDESC = true;
        this.listDraggable[i].activeASC = false;
        this.listDraggable[i].sortDisabledDESC = false;
      }
    }

    if (!this.listDraggable[i].activeASC && !this.listDraggable[i].activeDESC) {
      this.listDraggable[i].direction = null;
    }

    this.forceRerender();
  }

  forceRerender(): void {
    this.componentKey += 1;
  }

  handleEnd(dragging) {
    dragging = false;
    this.forceRerender();
  }

  changeIsShow(value, i) {
    this.listDraggable[i].isShow = value;
    this.forceRerender();
  }

  resetSettings() {
    this.listDraggable = cloneDeep(this.list);
    this.getListDraggable();
    this.forceRerender();
  }

  cancelSettings() {
    this.$emit('close');

    if (this.appliedListDraggable.length && !isEqual(this.appliedListDraggable, this.listDraggable)) {
      this.listDraggable = cloneDeep(this.appliedListDraggable);
    }
  }
}
</script>

<style lang="scss" scoped>
.select-row {
  align-items: flex-end;
}
</style>

<style lang="scss">
@import '@/assets/styles/_variables';

.dialog_settings {
  .v-card__title {
    font-size: 1.15rem;
    line-height: 0;
  }

  .v-subheader {
    &--inset {
      position: relative;
      margin-left: 20px;
      line-height: 1.3;

      img {
        position: absolute;
        left: -10px;
      }
    }
  }

  .checkbox-wrapper {
    width: 16px;
    margin-right: 10px;
  }

  .v-list-item {
    color: $medium-grey-color;
    min-height: 0;
    padding: 0;
    margin: 8px 0;

    .v-list-item__content {
      color: $medium-grey-color;

      & > .v-list-item__title {
        flex: 1 1 0;
        white-space: normal;
      }
    }

    &--active {
      .v-list-item__content {
        background: $check-bg;
      }
    }

    &--link::before {
      background-color: transparent;
    }
  }

  .v-list-item__action {
    margin: 0;
  }

  .v-list-item__content {
    background: $white-color;
    padding: 10px;
    border-radius: 4px;

    & > * {
      flex: 0 0 auto;
    }
  }

  .settingsSpan {
    &--disabled {
      cursor: default;
      background: #f6f8f9 !important;
      opacity: 0.4 !important;
    }
  }

  .btn-sort {
    display: inline-block;
    box-shadow: 2px 0 5px rgba(88, 87, 87, 0.2);
    border-radius: 4px;
    margin-left: 0;
  }
}
</style>
