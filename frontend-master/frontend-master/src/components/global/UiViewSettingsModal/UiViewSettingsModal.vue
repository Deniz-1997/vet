<template>
  <div :class="[type === 'button' && 'settings_wrap']">
    <Dialog-component
      v-model="isShow"
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
        <v-list flat>
          <v-subheader inset>
            <img :src="'/icons/info.svg'" class="iconSettings" />
            Вы можете поменять порядок следования колонок просто перетащив их в нужное место
          </v-subheader>

          <v-list-item-group multiple>
            <draggable v-model="listDraggable" class="list-group" ghost-class="ghost">
              <transition-group name="list">
                <v-list-item
                  v-for="(item, i) in listDraggable"
                  :key="item.value"
                  :value="item"
                  :class="item.isShow && 'v-list-item--active'"
                >
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
                        type="button"
                        :disabled="!item.isShow || !item.sortable"
                        :class="(!item.isShow || !item.sortable) && 'settingsSpan--disabled'"
                        @click.prevent="changeDirection('ASC', i)"
                      >
                        <img
                          :src="
                            item.activeASC && !item.sortDisabledASC ? '/icons/sortAsc_active.svg' : '/icons/sortAsc.svg'
                          "
                          class="iconSettings"
                        />
                      </button>
                      <button
                        class="settingsSpan btn-sort"
                        type="button"
                        :disabled="!item.isShow || !item.sortable"
                        :class="(!item.isShow || !item.sortable) && 'settingsSpan--disabled'"
                        @click.prevent="changeDirection('DESC', i)"
                      >
                        <img
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
                </v-list-item>
              </transition-group>
            </draggable>
          </v-list-item-group>
        </v-list>

        <div>
          <v-row justify="end">
            <v-col cols="12" class="col-exclude">
              <DefaultButton title="Сбросить" @click="resetSettings" />
              <DefaultButton title="Отмена" @click="isShow = false" />
              <DefaultButton variant="primary" title="Применить" @click="applySettings" />
            </v-col>
          </v-row>
        </div>
      </template>
    </Dialog-component>
    <span v-if="type === 'button'" class="text-decoration-underline" @click="isShow = true">
      <img src="/icons/settings-grey.svg" class="iconSettings mr-1" :style="{ width: '15px' }" />
    </span>
    <span v-else class="settingsSpan text-decoration-underline" @click="isShow = true">
      <img src="/icons/settings.svg" class="iconSettings mr-1" />
      Настроить вид
    </span>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator';
import cloneDeep from 'lodash/cloneDeep';
import isEmpty from 'lodash/isEmpty';
import pick from 'lodash/pick';
import genHash from 'object-hash';
import { merge } from '@/utils/global';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import { TInnerFilter } from '@/services/models/common';
import { TableHeaders } from '@/components/common/DataTable/DataTable.types';
import draggable from 'vuedraggable';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import has from 'lodash/has';

export type TSortMap = { [key: string]: string | string[] };
export type TSortDirection = 'DESC' | 'ASC';
export type TSort = { property: string; direction: TSortDirection };

const SETTINGS_KEY = '@giszp/appearance';
const collumnsNoSetting = ['actions', 'check', 'isProcessor'];

@Component({
  name: 'UiViewSettingsModal',
  components: {
    DefaultButton,
    DialogComponent,
    SelectComponent,
    CheckboxComponent,
    draggable,
  },
})
export default class UiViewSettingsModal extends Vue {
  /** Идентификатор для хранения настроек в localStorage. */
  @Prop({ type: String, required: true }) readonly id!: string;
  /** Текущее состояние фильтра. */
  @Prop({ type: Object, required: true }) readonly value!: TInnerFilter;
  @Prop({ type: Object, default: () => ({}) }) readonly sortMap!: TSortMap;
  @Prop({ type: Object, default: () => null }) readonly defaultSorting!: TSort;
  @Prop({ type: String, default: 'default', validator: (v: string) => ['button', 'default'].includes(v) })
  readonly type!: string;

  /** Отображение окна настроек. */
  isShow = false;
  /** Внутренняя форма для редактирования фильтра. Используется для того, чтобы применять настройки только при подтверждении. */
  form: TInnerFilter = cloneDeep(pick(this.value, 'columns', 'pageable.sort'));
  /** Настройки по умолчанию, инициализируются единожды из первого значения, проброшенного в `value`. */
  defaultSettings: TInnerFilter = cloneDeep(pick(this.value, 'columns', 'pageable.sort'));
  listDraggable: any = cloneDeep(this.list);

  get innerValue() {
    return this.value;
  }

  set innerValue(v) {
    this.$emit('input', merge(this.value, v));
  }

  get innerSortMap() {
    const computedSortAs = (this.defaultSettings.columns || []).reduce((result, { value, sortAs }) => {
      if (sortAs && value) {
        return { ...result, [value]: sortAs };
      }

      return result;
    }, {});
    return { ...this.sortMap, ...computedSortAs };
  }

  created() {
    // Забираем настройки из localStorage, если есть, и применяем на внешний фильтр при маунте.
    this.initSettings();
    this.getListDraggable();
  }

  mounted() {
    this.setSettings();
    this.getListDraggable();
  }

  get list() {
    return (this.form?.columns as Array<TableHeaders>).filter(
      (item) => item.text && !collumnsNoSetting.includes(item.value)
    );
  }

  getListDraggableDirection(item) {
    if (this.form?.pageable?.sort?.some((itemSettings) => itemSettings.property === item.value)) {
      return this.form?.pageable?.sort?.filter((itemSettings) => itemSettings.property === item.value)[0].direction;
    }

    if (item.value === this.sortByDefault.property) {
      return this.sortByDefault.direction;
    }

    if (has(item, 'direction')) {
      return item.direction;
    }

    return null;
  }

  getListDraggableActiveDESC(item) {
    if (this.form?.pageable?.sort?.some((itemSettings) => itemSettings.property === item.value)) {
      return (
        this.form?.pageable?.sort?.filter((itemSettings) => itemSettings.property === item.value)[0].direction ===
        'DESC'
      );
    }

    return (item.value === this.sortByDefault.property && this.sortByDefault.direction) === 'DESC';
  }

  getListDraggableActiveASC(item) {
    if (this.form?.pageable?.sort?.some((itemSettings) => itemSettings.property === item.value)) {
      return (
        this.form?.pageable?.sort?.filter((itemSettings) => itemSettings.property === item.value)[0].direction === 'ASC'
      );
    }

    return (item.value === this.sortByDefault.property && this.sortByDefault.direction) === 'ASC';
  }

  getListDraggable() {
    this.listDraggable = this.listDraggable.map((item) => {
      return {
        ...item,
        sortable: has(item, 'sortable') ? item.sortable : true,
        isShow: this.form?.columns?.some((itemSettings) => itemSettings.value === item.value),
        direction: this.getListDraggableDirection(item),
        activeDESC: this.getListDraggableActiveDESC(item),
        activeASC: this.getListDraggableActiveASC(item),
      };
    });
  }

  /** Сохраненные настройки по всем таблицам. */
  get settings() {
    return JSON.parse(localStorage.getItem(SETTINGS_KEY) || '{}');
  }

  /** Хэш настроек. Используется для сброса сохраненного значения, если произошли изменеия в значении по умолчанию. */
  get hash() {
    return genHash(this.defaultSettings);
  }

  get sortByDefault() {
    return (
      this.defaultSorting || {
        property: this.list?.[0]?.value,
        direction: 'DESC',
      }
    );
  }

  get systemColumns() {
    return this.defaultSettings.columns?.reduce((result: any, item, index) => {
      if (collumnsNoSetting.includes(item.value as string)) {
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

  changeIsShow(value, i) {
    this.setListDraggable(i, { isShow: value });
  }

  /** Записать сохраненные настройки, если есть. */
  initSettings() {
    const { [this.id]: saved } = this.settings;
    const filteredValue = pick(this.value, 'columns', 'pageable.sort');
    const formValue = {
      ...filteredValue,
      columns: filteredValue.columns?.filter((item) => item.text && !collumnsNoSetting.includes(item.value as string)),
    };

    if (saved?.hash === this.hash) {
      this.setValue(merge(formValue, saved));
    }

    this.setValue(formValue);
  }

  copyForm() {
    const form = cloneDeep(this.form);

    const isSystemColumnsArray = this.systemColumns.filter((item) => {
      return form.columns?.some((element) => element.value === item.item.value);
    });

    if (!isSystemColumnsArray.length) {
      this.systemColumns.forEach(({ index, item }) => {
        form.columns?.splice(index, 0, item);
      });
    }

    return form;
  }

  mapSettings() {
    const form = this.copyForm();

    if (isEmpty(this.innerSortMap)) {
      return form;
    }

    return {
      ...form,
      pageable: {
        sort: form?.pageable?.sort?.reduce((result: any, item) => {
          const rule = this.innerSortMap[item.property];

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
        }, []),
      },
    };
  }

  mapSorting(list: { direction: 'ASC' | 'DESC'; property: string }[]) {
    if (!isEmpty(this.innerSortMap)) {
      return list.reduce((result: any, item) => {
        const rule = this.innerSortMap[item.property as string];

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

    return list;
  }

  mapListDraggable() {
    const visibleList = this.listDraggable.filter((item) => !!item.isShow);
    const sortableList = this.listDraggable.filter((item) => !!item.isShow && item.sortable && item.direction);
    const COLUMN_KEYS = ['text', 'value', 'sortable', 'direction', 'isShow', "sortAs"];
    const columns = visibleList.map((item) => pick(item, COLUMN_KEYS));
    const sort = sortableList.map((item) => ({ ...pick(item, 'direction'), property: item.value }));

    this.systemColumns.forEach(({ index, item }) => {
      columns?.splice(index, 0, item);
    });

    return {
      columns,
      pageable: { sort: this.mapSorting(sort) },
    };
  }

  setSettings() {
    const form = this.mapSettings();
    this.setForm(form);
  }

  /** Применить пользовательские настройки. */
  applySettings() {
    const form = this.mapListDraggable();
    this.setForm(form);
  }

  setForm(form) {
    // Принудительно выставляется первая страница.
    this.setValue({ ...form, pageable: { pageNumber: 0, ...form.pageable } });
    this.innerValue = this.form;
    localStorage.setItem(
      SETTINGS_KEY,
      JSON.stringify({
        ...this.settings,
        [this.id]: {
          ...this.form,
          hash: this.hash,
        },
      })
    );

    this.isShow = false;
    this.$emit('apply-settings');
  }

  changeDirection(direction: 'ASC' | 'DESC', i: number) {
    let payload: any = { direction };
    const opposite = direction === 'ASC' ? 'DESC' : 'ASC';

    if (!has(this.listDraggable[i], `active${direction}`)) {
      payload = {
        ...payload,
        [`active${direction}`]: true,
        [`active${opposite}`]: false,
      };
    } else if (this.listDraggable[i][`active${direction}`]) {
      payload = {
        ...payload,
        [`active${direction}`]: false,
        [`sortDisabled${direction}`]: true,
      };
    } else {
      payload = {
        ...payload,
        [`active${direction}`]: true,
        [`active${opposite}`]: false,
        [`sortDisabled${direction}`]: false,
      };
    }

    if (!payload.activeASC && !payload.activeDESC) {
      payload = { ...payload, direction: null };
    }

    this.setListDraggable(i, payload);
  }

  setListDraggable(i, payload) {
    const data = [...this.listDraggable];
    data.splice(i, 1, { ...data[i], ...payload });
    this.listDraggable = data;
  }

  /** Безопасная запись значения фильтра. */
  setValue(partial: Partial<TInnerFilter>) {
    this.form = merge(this.form, partial);
  }

  /** Сброс настроек до дефолтных. */
  resetSettings() {
    const value = cloneDeep(this.defaultSettings);
    const filterValue = {
      ...value,
      columns: value.columns?.filter((item) => item.text && !collumnsNoSetting.includes(item.value as string)),
    };

    this.setValue(filterValue);
    this.getListDraggable();
  }

  // Актуализирует данные при каждом открытии формы настроек.
  @Watch('isShow')
  $onChangeVisibility(v) {
    if (v) {
      this.initSettings();
    }
  }
}
</script>

<style lang="scss">
.settings_wrap {
  display: inline-block;
  width: 15px;
  margin: auto;
}

.ghost {
  opacity: 0;
}

.list-enter-active,
.list-leave-active {
  transition: all 0.9s ease-in-out;
}

.dialog_settings {
  .v-list-item__content {
    & > .v-list-item__title {
      flex: 1 1 0;
      white-space: normal;
    }
  }
}
</style>
