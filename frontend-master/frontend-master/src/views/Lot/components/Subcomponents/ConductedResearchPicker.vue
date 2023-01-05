<template>
  <div>
    <v-tooltip top max-width="500" open-delay="500">
      <template #activator="{ on, activatorAttrs }">
        <span v-if="placeholder" v-bind="activatorAttrs" class="hint_span" v-on="on">
          <AutocompleteComponent
            v-model="innerValue"
            :items="options"
            :label="label"
            no-data-text="Если нужного документа о результатах госмониторинга нет в списке, попробуйте уточнить запрос"
            item-value="id"
            item-text="name"
            :is-disabled="isDisabled"
            :clearable="true"
            :placeholder="placeholder"
            return-object
            @onChange="searchString = ''"
            @searchInputUpdate="onInput"
          />
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
import { ConductedResearchShortModel } from '@/models/Gosmonitoring/ConductedResearchShort';
import { FilterModel } from '@/models/Request/Filter.vue';
import { PropType } from 'vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';
import { add, subtract } from '@/utils/decimals';
import { applyMask } from '@/components/common/inputs/mask/decimalNumberMask';

@Component({
  name: 'ConductedResearchPicker',
  components: { AutocompleteComponent },
})
export default class ConductedResearchPicker extends Vue {
  @Model('change', { type: [Object, Array, Number], default: null }) readonly value!: any;
  @Prop({ type: Object as PropType<LotDataVueModel | LotGpbDataVueModel | LotElevatorDataVueModel>, required: true })
  lotModel!: LotDataVueModel | LotGpbDataVueModel | LotElevatorDataVueModel;
  @Prop({ type: Object, default: null }) selectedNumberGos!: any;
  @Prop({ type: Number, default: null }) subjectIdFilter!: number | null;
  @Prop({ type: String, required: false, default: 'Документ о результатах госмониторинга' }) readonly label!: string;
  @Prop({ type: Boolean }) readonly readonly!: boolean;
  @Prop({ type: Boolean }) readonly multi!: boolean;
  @Prop({ type: String, required: false, default: 'Начните вводить номер документа о результатах госмониторинга' })
  readonly placeholder!: string;
  @Prop({ type: String, required: false, default: 'Для поиска используйте латинские буквы' })
  readonly tooltipText!: string;
  @Prop({ type: Boolean, required: true }) readonly isDisabled!: boolean;
  @Prop({ type: Boolean, required: true }) readonly loadPrivileges!: boolean;
  @Prop({ type: Boolean, required: true }) readonly isChange!: boolean;
  @Prop({ type: Number, required: false, default: null }) readonly storedAmountKg!: number | null;
  @Prop({ type: Number, required: false, default: null }) readonly storedResearchId!: number | null;

  items: ConductedResearchShortModel[] = [];
  searchString = '';
  isLoading = false;
  temporaryValue: ConductedResearchShortModel | null = null;

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

  get innerLotModel(): any {
    return this.lotModel;
  }

  set innerLotModel(v: any) {
    this.onLotModelChange(v);
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

  get loadPrivelegesAndSubjectFilter() {
    return `${this.loadPrivileges} ${this.subjectIdFilter}`;
  }

  @Watch('loadPrivelegesAndSubjectFilter')
  onLoadPrivelegesChange(_v) {
    this.items = [];
  }

  @Debounce(2000)
  async onInput(searchString: string) {
    await this.handleInput(searchString);
  }

  async handleInput(searchString: string) {
    this.isLoading = true;
    this.searchString = searchString || '';
    let items: any[] = [...this.items];

    if (
      !(
        this.temporaryValue &&
        typeof this.temporaryValue.laboratory_monitor_number === 'string' &&
        searchString &&
        searchString.includes(this.temporaryValue.laboratory_monitor_number)
      ) &&
      this.searchString?.length > 0
    ) {
      items = await this.fetchList(this.searchString);
    }

    if (
      typeof this.temporaryValue?.laboratory_monitor_number === 'string' &&
      this.temporaryValue?.laboratory_monitor_number.includes(this.searchString) &&
      this.value === this.temporaryValue.id
    ) {
      items.push(this.temporaryValue);
    }

    this.items = items;

    this.isLoading = false;
  }

  getBaseFilters(options: FilterModel[] = []) {
    const filters: any[] = [];

    filters.push(...options);

    if (this.subjectIdFilter && this.isChange) {
      filters.push({
        field: 'owner_id',
        operator: '=',
        value: this.subjectIdFilter,
      });
    }

    if (this.isChange) {
      filters.push(
        { field: 'status_id', operator: '=', value: 2 },
        { field: 'amount_kg_available', operator: '!=', value: 0 }
      );
    }

    return filters;
  }

  async fetchGosmonitoring(options: FilterModel[] = [], pageSize?: number, baseFilters = true) {
    if (!this.loadPrivileges) return [];

    const payload: any = {
      url: 'register/conducted-research-manufacturers',
      data: {
        filter: {
          options: baseFilters ? this.getBaseFilters(options) : options,
        },
      },
    };

    if (pageSize) payload.data.page_size = pageSize;

    return await this.$store.dispatch('gosmonitoring/getList', payload);
  }

  async fetchList(searchString: string) {
    let items: any = [];

    const { response, status } = await this.fetchGosmonitoring(
      [{ field: 'laboratory_monitor_number', operator: '%%', value: searchString } as FilterModel],
      20
    );

    if (status) items = response.map((e) => new ConductedResearchShortModel(e));

    return items;
  }

  async fetchById(id: number) {
    const filters: any[] = [{ field: 'id', operator: '=', value: id } as FilterModel];

    if (this.subjectIdFilter) {
      filters.push({
        field: 'owner_id',
        operator: '=',
        value: this.subjectIdFilter,
      });
    }

    const { response, status } = await this.fetchGosmonitoring(filters, undefined, false);

    return status && Array.isArray(response) && response.length ? new ConductedResearchShortModel(response[0]) : null;
  }

  isGosmonitoringPreviouslySelected(e) {
    return e.id === this.storedResearchId;
  }

  calculateGosmonitoringAvailableMass(item) {
    const base = this.isGosmonitoringPreviouslySelected(item)
      ? this.massForPreviouslySelectedGosmonitoring(item)
      : item.amount_kg_available;
    const isSelected = item.id === this.innerLotModel.research_numbers_government_monitoring_id;
    const mass = isSelected ? subtract(base, this.isChange ? this.innerLotModel.amount_kg : 0) : base;

    return mass > 0 ? mass : 0;
  }

  massForPreviouslySelectedGosmonitoring(e) {
    return add(e.amount_kg_available, this.isChange ? this.storedAmountKg || 0 : 0);
  }

  get options() {
    const nameWithAvailableMass = (e: any) => {
      const name = `${e.laboratory_monitor_number || ''} ${
        e.lots_numbers_from_subject?.lots_numbers_from_subject || ''
      }`;

      const avalilableMassString = this.isChange
        ? ` (${applyMask(this.calculateGosmonitoringAvailableMass(e), true)} кг)`
        : '';

      return `${name}${avalilableMassString}`;
    };

    return this.items.map((e) => ({
      ...e,
      name: nameWithAvailableMass(e),
      amount_kg_available: this.isGosmonitoringPreviouslySelected(e)
        ? this.massForPreviouslySelectedGosmonitoring(e)
        : e.amount_kg_available,
    }));
  }

  get fieldAmountKgError() {
    const previouslyUsed =
      this.selectedNumberGos && this.isGosmonitoringPreviouslySelected(this.selectedNumberGos)
        ? this.storedAmountKg
        : 0;

    return (
      this.selectedNumberGos &&
      this.innerLotModel.amount_kg > add(this.selectedNumberGos?.amount_kg_available || 0, previouslyUsed || 0)
    );
  }

  @Watch('fieldAmountKgError')
  handleFieldAmountKgError(value: boolean) {
    this.innerLotModel.amountKgError = value;
  }

  @Emit('select')
  onSelect(v: ConductedResearchShortModel | null): ConductedResearchShortModel | null {
    return v;
  }

  @Emit('lotModelChange')
  onLotModelChange(v: any) {
    return v;
  }
}
</script>
