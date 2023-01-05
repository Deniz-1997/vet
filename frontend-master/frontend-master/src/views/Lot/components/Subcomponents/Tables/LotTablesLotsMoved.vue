<template>
  <v-col cols="12" md="12">
    <lot-table
      v-model="innerValue"
      class_="edit-table-lot-moved"
      empty-text="Не добавлены партии зерна"
      :title="titleTable"
      edit-strategy="semi-inner"
      :hide-action="false"
      :is-create="isCreate"
      :is-edit="isEdit"
      :is-edit-action="isCreate || isEdit"
      :is-can-edit="isCreate || isEdit"
      :is-not-add-new-field="true"
      :options-for-table="optionsForTable"
      @onCustomCreate="onShowModalFindLot"
      @onDeleteItem="onDeleteLotMoved"
      @onInput="onChangeAmountLots"
      @onShowHintForTable="onShowHintForTable"
    />

    <lot-modal-find-lots
      :key="isOpenFindLot"
      v-model="lot"
      :type-lot="typeLot"
      :is-not-repository-filter="isNotRepositoryFilter"
      :filter-by-first-selected-lots="filtersFromFirstSelectedLot"
      :is-open-find-lot="isOpenFindLot"
      :selected-lots="selectedLots"
      :get-list-link="getListLink"
      :is-lots-moved="true"
      :lot-selection-processed="lotSelectionProcessed"
      :is-elevator-filter="isElevatorFilter"
      @isOpenFindLot="onShowModalFindLot"
      @onSelectLot="handleLotSelect"
      @clear-filters="handleClearFilters"
    >
      <template #[`create-date-filter`]="{ lockFromFilterModal, arrayLockFilters, filters, isNeedLockFilters }">
        <slot
          :array-lock-filters="arrayLockFilters"
          :filters="filters"
          :is-need-lock-filters="isNeedLockFilters"
          :lock-from-filter-modal="lockFromFilterModal"
          name="create-date-filter"
        />
      </template>
      <template #[`manufacture-filter`]="{ lockFromFilterModal, arrayLockFilters, filters, isNeedLockFilters }">
        <slot
          :array-lock-filters="arrayLockFilters"
          :filters="filters"
          :is-need-lock-filters="isNeedLockFilters"
          :lock-from-filter-modal="lockFromFilterModal"
          name="manufacture-filter"
        />
      </template>
    </lot-modal-find-lots>
  </v-col>
</template>

<script lang="ts">
import { Component, Mixins, Model, Prop } from 'vue-property-decorator';
import EditableTable from '@/components/common/Table/index.vue';
import { AdditionalMix } from '@/utils/mixins/additional';
import TextComponent from '@/components/common/TextComponent.vue';
import { LotsMovedVueModel } from '@/models/Lot/LotsMoved.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import LotTable from '@/views/Lot/components/Subcomponents/Tables/LotTable.vue';
import LotModalFindLots from '@/views/Lot/components/Subcomponents/LotModalFindLots.vue';
import { isEmpty, isNull, isNumber, isUndefined } from 'lodash';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';

import {
  applyMask,
  decimalNumberMask,
  decimalNumberUnmask,
  validate,
} from '@/components/common/inputs/mask/decimalNumberMask';

import { addSelectedLot } from '@/utils/addSelectedLot';
import { LotType } from '@/utils/enums/LotType';
import { add, subtract } from '@/utils/decimals';

@Component({
  name: 'lot-tables-lots-moved',
  components: {
    LotModalFindLots,
    LotTable,
    TextComponent,
    EditableTable,
  },
})
export default class LotTablesLotsMoved extends Mixins(AdditionalMix) {
  @Model('change', { type: Array, required: true }) value!: LotsMovedVueModel[];

  @Prop({ type: Object, required: true }) lot!: LotDataVueModel | LotGpbDataVueModel | LotElevatorDataVueModel;

  @Prop({ type: String, default: 'Предшествующие партии зерна' }) titleTable!: string;

  @Prop({ type: Boolean, default: false }) isDetail!: boolean;

  @Prop({ type: Boolean, default: false }) isEdit!: boolean;

  @Prop({ type: Boolean, default: false }) isCreate!: boolean;

  @Prop({ type: Boolean, default: false }) isLockFilters!: boolean;

  @Prop({ type: Boolean, default: () => false }) readonly isNotRepositoryFilter!: boolean;

  @Prop({ type: Boolean, default: () => false }) readonly isElevatorFilter!: boolean;

  @Prop({ type: String, default: null }) typeLot!: LotType | null;

  isShowHintForTable = false;

  isOpenFindLot = false;

  lotSelectionProcessed = true;

  rowEdit: any = undefined;

  prevRowsLots: LotDataVueModel[] | LotGpbDataVueModel[] = [];

  getListLink: string = this.lot.list_apiendpoit;

  filtersFromFirstSelectedLot: any = {};
  lots: Array<any> = [];

  get innerValue() {
    if (
      this.value.length > 0 &&
      Object.keys(this.filtersFromFirstSelectedLot).length === 0 &&
      this.typeLot !== LotType.IN_PRODUCT
    ) {
      this.filterFromFirstSelected(this.lot);
    }

    return this.value;
  }

  set innerValue(value) {
    this.$emit('change', value);
  }

  get isChangeData(): boolean {
    return this.isEdit || this.isCreate;
  }
  get isAnotherBatchGrain(): boolean {
    return this.isCreate && this.typeLot === LotType.ANOTHER_BATCH_GRAIN;
  }

  get selectedLots(): any[] {
    return this.value;
  }

  async created() {
    const id = this.$route.query.lot_id;

    if (!this.$route.name?.includes('sdiz')) {
      await this.loadAndSelectLot(id, false);
    }
  }

  async loadAndSelectLot(id, pushLot = true) {
    const data = {};
    if (id) {
      const { status, response } = await this.$store.dispatch(this.lot.show_apiendpoit, id);

      let dataLot;

      switch (this.lot.component_name) {
        case 'lot':
          dataLot = new LotDataVueModel(response);
          break;
        case 'lot_ppz':
          dataLot = new LotGpbDataVueModel(response);
          break;
        case 'lot_elevator':
          dataLot = new LotElevatorDataVueModel(response);
          break;
      }

      data['lot'] = dataLot;

      if (status) this.onSelectLot(data, pushLot);
    }
  }

  optionNumber(): object {
    return {
      label: 'Номер',
      name: this.lot.getNameNumber(),
      controlType: 'custom',
      disabled: true,
      customRenderValue: (model: any): string | number => {
        return `<a class="text-caption"
                     href="/lots/${model.lot_number !== undefined ? model.lot_id : 'gpb/' + model.gpb_id}"
                     target="_blank">${model.lot_number ?? model.gpb_number}</a>`;
      },
    };
  }
  optionReasonableWight(): object {
    return this.isChangeData
      ? {
          label: 'Доступная масса, кг',
          name: 'amount_kg_available',
          controlType: 'custom',
          disabled: true,
          customRenderValue: (model: any): string | number => {
            const value = decimalNumberUnmask(model.value_mask);
            const availableAmount = applyMask(
              subtract(model.amount_kg_available, value) <= 0 ? 0 : subtract(model.amount_kg_available, value),
              true
            );
            return `<span class="text-caption ma-0">Доступно: <b>${availableAmount}</b></span>`;
          },
        }
      : {
          name: 'empty_amount_kg',
          controlType: 'text',
        };
  }
  optionWieght(): object {
    return {
      label: 'Масса, кг',
      name: 'value_mask',
      placeholder: 'Введите массу',
      mask: decimalNumberMask,
      isRowNeedEdit: (val, col, callback) => {
        if (this.isCreate || this.isEdit) {
          callback(true);
        } else {
          this.$notify({
            group: 'lot',
            type: 'warning',
            title: 'Сохраненные записи редактировать невозможно',
            text: '',
          });
        }
      },
      customRenderValue: (model: any): string | number => {
        model.value = decimalNumberUnmask(model.value_mask);

        if (typeof model === 'undefined' || !model.value) {
          return '<span class="text-caption ma-0">Введите массу партии</span>';
        }

        return validate(model.value_mask)
          ? `<span class="text-caption ma-0">${
              model.value_mask ?? ''
            }</span> <span class="text-caption text-center orange--text d-flex mb-2">Укажите граммы от 001 до 999</span>`
          : `${model.value_mask ?? ''}`;
      },
      validate: (value, row) => this.validate(value, row),
    };
  }
  optionCopyProperty(): object {
    return this.isAnotherBatchGrain
      ? {
          label: 'Скопировать свойства',
          name: 'button',
          controlType: 'custom',
          onClick: (model) => {
            this.copyProperty(model.lot_id || model.gpb_id);
          },
          customRenderValue: () => {
            return `<a class="text-caption">Копировать</a>`;
          },
        }
      : {
          name: 'empty_button',
          controlType: 'text',
          disabled: true,
        };
  }

  get optionsForTable(): Array<object> {
    return [this.optionNumber(), this.optionReasonableWight(), this.optionWieght(), this.optionCopyProperty()];
  }

  copyProperty(id) {
    this.lots.forEach((val) => {
      if (val.id === id) {
        this.lot.objects.quality_indicators.map((value) => {
          val.objects.quality_indicators.map((n2) => {
            if (value.quality_indicator_id === n2.quality_indicator_id) {
              value.value = n2.value;
            }
          });
        });
      }
    });
  }

  checkAndConvertArray(lot: any) {
    if (isNull(lot.value) || (isEmpty(lot.value) && !isNumber(lot.value))) {
      lot.value = 0;
    }

    if (typeof lot.value === 'string') {
      lot.value = decimalNumberUnmask(lot.value);
    }

    if (lot.value < 0) {
      lot.value = 0;
    }

    lot.value = parseFloat(lot.value);
    return lot;
  }

  validate(value, lot: any): boolean {
    const validate = !!value;

    if (validate) {
      if (typeof value === 'string') value = decimalNumberUnmask(value);

      if (lot !== undefined && parseFloat(value) > lot.amount_kg_available) {
        this.notify('Указанная сумма больше доступной ' + lot.amount_kg_available, undefined);
        lot.value = 0;
      }
    }
    if (typeof value === 'string' && value === '') {
      lot.value = null;
      return true;
    }
    return validate;
  }

  filterFromFirstSelected(lot): void {
    this.filtersFromFirstSelectedLot = {
      target_id: lot.target_id,
      okpd2Code: lot.objects.okpd2?.code,
      current_location_id: lot.current_location_id,
      owner_id: lot.owner_id,
    };
  }

  setGpbLotField(lot): void {
    this.lot['manufacturer_id'] = lot.manufacturer_id;
    this.lot['create_date'] = lot.create_date;
  }

  async handleLotSelect(data) {
    this.lotSelectionProcessed = false;
    await this.loadAndSelectLot(data.lot?.id);
    this.lotSelectionProcessed = true;
  }

  setLotYearIfAbsent(selectedLot) {
    this.lot.lot_year = this.lot.lot_year || selectedLot.lot_year;
  }

  onSelectLot(data, pushLot = true) {
    if (pushLot) this.lots.push(data.lot);
    this.isShowHintForTable = false;
    this.prevRowsLots = data.rows;
    if (!this.lot.tnved_id) this.lot.tnved_id = data.lot?.tnved_id;
    if (data.lot && this.typeLot !== LotType.IN_PRODUCT) {
      if (data.lot instanceof LotGpbDataVueModel) this.setGpbLotField(data.lot);
      if (this.innerValue.length === 0) this.filterFromFirstSelected(data.lot);
    } else if (data.lot) {
      this.filtersFromFirstSelectedLot = {
        current_location_id: data?.lot?.current_location_id,
      };
    }

    if (this.lot.lotType?.is_grain && this.typeLot === LotType.ANOTHER_BATCH_GRAIN) {
      this.setLotYearIfAbsent(data.lot);
    }

    if (pushLot) this.innerValue = [...this.innerValue, addSelectedLot(data.lot)];
    this.$emit('onFirstLotGrainIsSelect', data);
  }

  onShowHintForTable() {
    this.isShowHintForTable = !this.isShowHintForTable;
  }

  onShowModalFindLot(value) {
    this.isOpenFindLot = isUndefined(value) ? !this.isOpenFindLot : value;
  }

  onDeleteLotMoved(row): void {
    if (this.innerValue.length <= 1) {
      this.deleteLotField();
      this.filtersFromFirstSelectedLot = {};
    }
    this.$emit('onDeleteLotMoved', { row: row });
  }

  deleteLotField(): void {
    this.lot.current_location_id = null;
    this.lot.okpd2_id = null;
    this.lot.target_id = null;
    if (this.lot instanceof LotGpbDataVueModel) {
      this.lot.manufacturer_id = null;
      this.lot.create_date = null;
    }
  }

  onChangeAmountLots(row: any[]): void {
    let amountKg = 0;

    this.innerValue = row.map((lot) => {
      const element = this.checkAndConvertArray(lot);
      element.value = decimalNumberUnmask(element.value_mask);
      amountKg = add(amountKg, element.value);
      return element;
    });

    let maskAmountKr = applyMask(amountKg, true);

    this.$emit('onChangeAmountKg', {
      amountKg: {
        mask: maskAmountKr,
        value: amountKg,
      },
      rowEdit: this.rowEdit,
      list: this.innerValue,
    });
  }

  handleClearFilters() {
    if (this.typeLot === LotType.ANOTHER_BATCH_GRAIN && this.lot.getLotType().is_grain)
      this.filtersFromFirstSelectedLot.okpd2Code = null;
    if (this.typeLot && [LotType.ANOTHER_BATCH_GRAIN, LotType.IN_PRODUCT].includes(this.typeLot))
      this.filtersFromFirstSelectedLot.current_location_id = null;
  }
}
</script>
<style scoped lang="scss">
.edit-table-lot-moved::v-deep {
  .tableHeader,
  .tableListRow {
    display: flex;
    flex-direction: row;
    align-content: center;
    justify-content: space-between;
  }

  .tableHeader {
    .theader {
      width: 33.33% !important;
      text-align: center !important;

      &:first-child {
        flex: 1 0 70px;
      }

      &:nth-child(2) {
        flex: 1 1 auto;
      }

      &:nth-child(3),
      &:last-child {
        flex: 2 1 auto;
      }
    }
  }

  .tableListRow {
    .spanList {
      line-height: 40px;
      height: 40px;
      width: 33.33% !important;
      text-align: center !important;

      &:first-child {
        flex: 1 0 70px;
      }

      &:nth-child(2) {
        flex: 2 1 auto;
      }

      &:nth-child(3),
      &:last-child {
        flex: 1 1 auto;
      }

      > div {
        max-width: 225px !important;
      }

      > span {
        display: block;
        max-width: 230px !important;
      }
    }
  }
}
</style>
