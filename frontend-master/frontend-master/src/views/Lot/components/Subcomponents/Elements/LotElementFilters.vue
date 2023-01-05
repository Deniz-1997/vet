<template>
  <v-row justify-md="start">
    <v-col cols="12" lg="4" md="6" sm="7" xl="3">
      <label-component label="Дата" />
      <v-row>
        <v-col class="pr-1" cols="12" data-app sm="6">
          <UiDateInput
            v-model="filters.date_from"
            :limit-to="today"
            :disabled="isNeedLockFilters"
            :format="'DD.MM.YYYY'"
            placeholder="от"
          />
        </v-col>
        <v-col class="pr-1" cols="12" data-app sm="6">
          <UiDateInput
            v-model="filters.date_to"
            :limit-to="today"
            :limit-from="fromDate(filters.date_from)"
            :format="'DD.MM.YYYY'"
            :disabled="isNeedLockFilters"
            placeholder="до"
          />
        </v-col>
      </v-row>
    </v-col>
    <v-col cols="12" lg="4" md="6" sm="7" xl="2">
      <label-component label="Масса, кг" />
      <v-row no-gutters>
        <v-col cols="12" data-app sm="6">
          <WeightInput
            v-model="filterAmountKgFromMask"
            label=""
            placeholder="От"
            :disabled="isNeedLockFilters"
            class="mr-3"
          />
        </v-col>
        <v-col cols="12" data-app sm="6">
          <WeightInput v-model="filterAmountKgToMask" label="" placeholder="До" :disabled="isNeedLockFilters" />
        </v-col>
      </v-row>
    </v-col>
    <v-col cols="12" lg="4" md="3" sm="4" xl="2">
      <input-component
        v-model="filters[filters.getNameNumber()]"
        :disabled="isNeedLockFilters"
        label="Номер партии"
        placeholder="Введите номер партии"
      />
    </v-col>
    <v-col cols="12" lg="4" md="4" sm="4" xl="2">
      <select-request-component
        v-model="lockFromFilterModalTarget"
        type="nsi-lots-target"
        :disabled="isNeedLockFilters || lockFromFilterModal('target_id')"
        label="Цель использования"
        placeholder="Выберите цель использования"
      />
    </v-col>
    <v-col cols="12" lg="4" md="4" sm="4" xl="3">
      <select-request-component
        :key="newKey"
        v-model="filters.objects.purpose"
        :purpose-type="purposeType"
        label="Назначение"
        type="nsi-lots-purpose"
        preserve-data
        is-return-object
        placeholder="Выберите назначение"
      />
    </v-col>
    <v-col cols="12" lg="4" md="4" sm="5" xl="4">
      <autocomplete-priority-address
        v-model="lockFromFilterModalLocation"
        :is-disabled="isNeedLockFilters || (lockFromFilterModal('current_location_id') && !isDisabledLocationLock)"
        label="Местоположение"
        placeholder="Выберите местоположение"
      />
    </v-col>
    <slot :filters="filters" name="sdiz-owner-filter" />
    <slot :filters="filters" name="repository-owner-filter" />
    <v-col
      v-if="
        isElevator ||
        $route.name === 'lot_elevator_create_from_another_batch' ||
        filters.component_name === 'lot_elevator'
      "
      cols="12"
      lg="4"
      md="6"
      sm="3"
      xl="4"
    >
      <ManufacturerAutocomplete
        v-model="lockFromFilterModalOwner"
        label="Владелец партии"
        placeholder="Выберите владельца партии"
        clereables
        show-name-in-tooltip
        :is-disabled="(filters.owner_id !== null && isNeedLockFilters) || lockFromFilterModal('owner_id')"
      />
    </v-col>
    <v-col v-if="!isModal" cols="12" lg="4" md="4" sm="4" xl="3">
      <select-request-component
        v-model="filters.status"
        :custom-items="
          filters.getStatus().map((v) => {
            return { id: v.code, name: v.name };
          })
        "
        label="Статус партии"
        placeholder="Выберите назначение"
      />
    </v-col>

    <slot
      :array-lock-filters="arrayLockFilters"
      :filters="filters"
      :is-need-lock-filters="isNeedLockFilters"
      :lock-from-filter-modal="lockFromFilterModal"
      name="sdiz-product-filter"
    >
      <v-col cols="12" lg="6" md="6" sm="4" xl="4">
        <select-request-component
          v-model="lockFromFilterModalOkpd2Code"
          :lot-type="filters.lotType"
          :disabled="isNeedLockFilters || (lockFromFilterModal('okpd2Code') && !isDisabledOkdp2Lock)"
          :label="filters.filter_name"
          :is-active="false"
          type="nsi-okpd2-codes"
          item-id="code"
          :placeholder="'Выберите' + ' ' + filters.filter_name"
        />
      </v-col>
    </slot>

    <slot
      :array-lock-filters="arrayLockFilters"
      :filters="filters"
      :is-need-lock-filters="isNeedLockFilters"
      :lock-from-filter-modal="lockFromFilterModal"
      name="manufacture-filter"
    />

    <slot
      :array-lock-filters="arrayLockFilters"
      :filters="filters"
      :is-need-lock-filters="isNeedLockFilters"
      :lock-from-filter-modal="lockFromFilterModal"
      name="create-date-filter"
    />

    <v-col cols="12" xl="3" lg="4" md="6">
      <select-request-component
        v-model="filters.docs_type"
        label="Тип документа"
        placeholder="Выберите тип документа"
        type="lot-document-type"
      />
    </v-col>
    <v-col v-show="filters.docs_type !== null" cols="12" xl="3" lg="4" md="6">
      <input-component
        v-model="filters.docs_number"
        required
        :disabled="isNeedLockFilters"
        label="Номер документа"
        placeholder="Введите номер"
      />
    </v-col>
    <v-col v-if="['lot', 'lot_elevator'].includes(filters.component_name)" cols="12" xl="4" lg="6" md="6" sm="12">
      <input-component
        v-model="filters.laboratory_monitor_number"
        :disabled="isNeedLockFilters"
        label="Номер документа государственного мониторинга"
        placeholder="Введите номер"
      />
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Component, Mixins, Model, Prop } from 'vue-property-decorator';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import { AdditionalMix } from '@/utils/mixins/additional';
import LabelComponent from '@/components/common/Label/Label.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import { decimalNumberMask, decimalNumberUnmask } from '@/components/common/inputs/mask/decimalNumberMask';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';
import { LotType } from '@/utils/enums/LotType';

import { currentDay } from '@/utils';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import { dateFrom } from '@/utils/date';
import WeightInput from '@/views/Lot/components/Subcomponents/WeightInput.vue';

@Component({
  name: 'lot-element-filters',
  components: {
    WeightInput,
    ManufacturerAutocomplete,
    AutocompletePriorityAddress,
    InputComponent,
    AutocompleteComponent,
    SelectRequestComponent,
    LabelComponent,
    UiDateInput,
  },
})
export default class LotElementFilters extends Mixins(AdditionalMix) {
  @Model('change', { type: Object, required: false }) filters!:
    | LotDataVueModel
    | LotGpbDataVueModel
    | LotElevatorDataVueModel;
  @Prop({ type: Boolean, default: false }) isNeedLockFilters!: boolean;
  @Prop({ type: Boolean, default: false }) isModal!: boolean;
  @Prop({ type: String, default: '' }) typeLots!: string;
  /** Принудительные фильтры */
  @Prop({ default: () => [] }) arrayLockFilters!: any;

  get isAnotherBatchGrain() {
    return this.typeLots === LotType.ANOTHER_BATCH_GRAIN && (this.filters.getLotType() as any)?.is_grain;
  }

  get isDisabledLocationLock() {
    return this.typeLots === LotType.IN_PRODUCT || this.typeLots === LotType.ANOTHER_BATCH_GRAIN;
  }

  get isDisabledOkdp2Lock() {
    return this.isAnotherBatchGrain;
  }

  get filterAmountKgFromMask() {
    return this.filters.amount_kg_from_mask;
  }

  set filterAmountKgFromMask(v) {
    this.filters.amount_kg_from_mask = v;
    this.filters.amount_kg_from = decimalNumberUnmask(v);
  }

  get filterAmountKgToMask() {
    return this.filters.amount_kg_to_mask;
  }

  set filterAmountKgToMask(v) {
    this.filters.amount_kg_to_mask = v;
    this.filters.amount_kg_to = decimalNumberUnmask(v);
  }

  decimalNumberMask = decimalNumberMask;

  decimalNumberUnmask = decimalNumberUnmask;
  currentDay = currentDay();

  get purposeType(): string {
    return this.typeLots;
  }

  get newKey() {
    return this.purposeType + this.randNumber;
  }

  get randNumber() {
    return Math.floor(Math.random() * 1000 + 1);
  }

  /**
   * Присутствует ли поле в принудительных фильтрах
   * @param field
   */
  lockFromFilterModal(field) {
    return !!this.arrayLockFilters[field];
  }

  fromDate(date) {
    return dateFrom(date, -1);
  }

  get lockFromFilterModalOwner(): any {
    if (this.arrayLockFilters['owner_id']) return this.arrayLockFilters.owner_id;
    return this.filters.owner_id;
  }

  set lockFromFilterModalOwner(value) {
    this.filters.owner_id = value;
  }

  get lockFromFilterModalTarget(): any {
    if (this.arrayLockFilters['target_id']) return this.arrayLockFilters.target_id;
    return this.filters.target_id;
  }

  set lockFromFilterModalTarget(value) {
    this.filters.target_id = value;
  }

  get lockFromFilterModalLocation(): any {
    return this.arrayLockFilters['current_location_id']
      ? this.arrayLockFilters.current_location_id
      : this.filters.current_location_id;
  }

  set lockFromFilterModalLocation(value) {
    this.filters.current_location_id = value;
    if (this.isDisabledLocationLock) {
      this.arrayLockFilters.current_location_id = value;
    }
  }

  get lockFromFilterModalOkpd2Code(): any {
    return this.arrayLockFilters['okpd2Code'] ? this.arrayLockFilters.okpd2Code : this.filters.okpd2Code;
  }

  set lockFromFilterModalOkpd2Code(value) {
    this.filters.okpd2Code = value;
    if (this.isDisabledOkdp2Lock) {
      this.arrayLockFilters.okpd2Code = value;
    }
  }
}
</script>
<style scoped lang="scss">
.v-input__slot {
  z-index: 5 !important;
}
</style>
