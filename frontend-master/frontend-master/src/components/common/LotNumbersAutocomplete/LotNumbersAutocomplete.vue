<template>
  <div>
    <v-tooltip top max-width="500" open-delay="500" :disabled="!tooltipText">
      <template #activator="{ on, activatorAttrs }">
        <span v-if="placeholder" v-bind="activatorAttrs" class="hint_span" v-on="on">
          <AutocompleteComponent
            v-model="innerValue"
            :items="options"
            :label="label"
            no-data-text="Если места формирования партии зерна нет в списке, попробуйте уточнить запрос"
            item-value="id"
            item-text="label"
            :is-disabled="isDisabled"
            :clearable="clereables"
            :placeholder="placeholder"
            :filter="filter"
            return-object
            @onChange="searchString = ''"
            @searchInputUpdate="onInput"
          >
            <template #item="{ item, attrs }">
              <div v-bind="attrs" :key="item.id" class="d-flex">
                <div>
                  <UiHighlightedText
                    v-if="item.lots_numbers_from_subject"
                    class="lots-numbers-picker__text"
                    :text="item.lots_numbers_from_subject"
                    :search="searchString"
                    tag="div"
                  />
                  <span class="lots-numbers-picker__caption">
                    <span v-if="item.okpd2.name"> {{ item.okpd2.name }} </span>
                    <span v-if="item.okpd2.code"> (ОКПД2: {{ item.okpd2.code }}) </span>
                  </span>
                </div>
              </div>
            </template>
          </AutocompleteComponent>
        </span>
      </template>
      {{ tooltipText }}
    </v-tooltip>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Model, Emit, Watch } from 'vue-property-decorator';
import isEmpty from 'lodash/isEmpty';
import { Debounce } from '@/utils/global/decorators/method';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import { FilterModel } from '@/models/Request/Filter.vue';
import { LotNumbersShortModel, LotNumbersVueModel } from '@/models/Lot/LotNumbers.vue';

@Component({
  name: 'ElevatorAutocomplete',
  components: { AutocompleteComponent },
})
export default class LotNumbersAutocomplete extends Vue {
  @Model('change', { type: [Object, Array, Number], default: null }) readonly value!: any;
  @Prop({ type: String, required: false, default: 'Место формирования партии зерна' }) readonly label!: string;
  @Prop({ type: Boolean }) readonly readonly!: boolean;
  @Prop({ type: Boolean }) readonly multi!: boolean;
  @Prop({ type: String, required: false, default: 'Выберите значение' }) readonly placeholder!: string;
  @Prop({ type: String, required: false, default: '' }) readonly tooltipText!: string;
  @Prop({ type: Boolean, required: false, default: true }) readonly clereables!: boolean;
  @Prop({ type: Boolean, required: false, default: false }) readonly isDisabled!: boolean;

  /** Фильтр по владельцу  */
  @Prop({ type: Number, required: false, default: null }) readonly subjectIdFilter!: number | null;

  /** Только активные записи */
  @Prop({ type: Boolean, required: false, default: false }) readonly activeFilter!: boolean;

  items: LotNumbersShortModel[] = [];
  searchString = '';
  isLoading = false;
  temporaryValue: LotNumbersShortModel | null = null;

  @Emit('select')
  onSelect(v: LotNumbersVueModel | null) {
    return v;
  }

  get options() {
    const label = (e: LotNumbersShortModel) => {
      return `${e.lots_numbers_from_subject || ''} (${e.okpd2.name || ''}, ОКПД2: ${e.okpd2.code || ''})`;
    };

    return this.items.map((e) => ({ ...e, label: label(e) }));
  }

  get innerValue() {
    if (typeof this.value === 'number') {
      return {
        id: this.value,
      };
    }
    return this.value;
  }

  set innerValue(v: any) {
    this.temporaryValue = v;

    if (!isEmpty(v)) {
      this.$emit('change', v.id);
      this.onSelect(v);
    } else {
      this.$emit('change', null);
      this.onSelect(null);
    }
  }

  get requestFilterOptions(): FilterModel[] {
    const filters: FilterModel[] = [];

    if (this.subjectIdFilter) {
      filters.push({
        field: 'subject_id',
        operator: '=',
        value: this.subjectIdFilter,
      } as FilterModel);
    } else {
      filters.push({ field: 'subject_id', operator: '!=', value: 0 } as FilterModel);
    }

    if (this.activeFilter) {
      filters.push({
        field: 'active',
        operator: '=',
        value: true,
      } as FilterModel);
    }

    return filters;
  }

  async loadById() {
    const selected = await this.fetchById(this.value);

    if (selected) {
      this.temporaryValue = selected;

      this.items = [...this.items, selected];
    }
  }

  async created() {
    if (this.value) {
      await this.loadById();
    }
  }

  @Watch('value')
  async onValueChange(value) {
    if (value) {
      const item = this.items.find((e) => e.id === value);

      if (!item) await this.loadById();
    }
  }

  @Debounce(1000)
  async onInput(searchString: string) {
    await this.handleInput(searchString);
  }

  async handleInput(searchString: string) {
    this.isLoading = true;
    this.searchString = searchString || '';
    let items: any[] = [...this.items];

    if (
      !(this.temporaryValue && searchString && searchString.includes(this.temporaryValue.lots_numbers_from_subject)) &&
      this.searchString?.length > 0
    ) {
      items = await this.fetchList(this.searchString);
    }

    this.items = items;
    this.isLoading = false;
  }

  async fetchList(searchString: string) {
    let items: any = [];

    const { response, status } = await this.$store.dispatch('lot/numbers', {
      data: {
        page_size: 25,
        filter: {
          options: [
            { field: 'lots_numbers_from_subject', operator: '%%', value: searchString } as FilterModel,
            ...this.requestFilterOptions,
          ] as FilterModel[],
        },
      },
    });

    if (status) items = response.map((e) => new LotNumbersShortModel(e));

    return items;
  }

  async fetchById(id: number) {
    const { response, status } = await this.$store.dispatch('lot/numbers', {
      data: {
        page_size: 20,
        filter: {
          options: [
            { field: 'id', operator: '=', value: id },
            { field: 'subject_id', operator: '!=', value: 0 },
          ],
        },
      },
    });

    return status && Array.isArray(response) && response.length ? new LotNumbersShortModel(response[0]) : null;
  }

  @Watch('subjectIdFilter')
  async onSubjectIdFilterChange() {
    await this.handleInput('');
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';

.lots-numbers-picker {
  &__caption {
    font-size: 11px;
    color: $medium-grey-color;
  }

  &__text {
    font-size: 13px;
  }
}
</style>
