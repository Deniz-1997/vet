<template>
  <v-col cols="12" md="12">
    <lot-table
      v-model="innerValue"
      :options-for-table="optionsForTable"
      :is-show-delete-button="false"
      :hide-action="isDetail"
      :is-edit-action="isChangeData"
      :max="1"
      :is-can-edit="isCreate || isEdit"
      empty-text="Не добавлены партии зерна"
      :title="titleTable"
      :is-edit="isEdit"
      :is-create="isCreate"
      @onInput="onChangeAmountLots"
    />
  </v-col>
</template>

<script lang="ts">
import { Component, Model, Prop, Vue } from 'vue-property-decorator';
import EditableTable from '@/components/common/Table/index.vue';
import LotTable from '@/views/Lot/components/Subcomponents/Tables/LotTable.vue';
import {
  applyMask,
  decimalNumberMask,
  decimalNumberUnmask,
  validate,
} from '@/components/common/inputs/mask/decimalNumberMask';
import { isEmpty, isNull, isNumber } from 'lodash';
import { add } from '@/utils/decimals';

interface SdizPaper {
  amount_kg: number;
  lot_sp_number: string;
}

@Component({
  name: 'lot-tables-paper-store',
  components: { LotTable, EditableTable },
})
export default class LotTablesPaperStore extends Vue {
  @Model('change', { type: Array, required: true }) value!: Array<any>;

  @Prop({ type: String, default: 'Предшествующие партии зерна' }) titleTable!: string;

  @Prop({ type: Boolean, default: false }) isEdit!: boolean;

  @Prop({ type: Boolean, default: false }) isCreate!: boolean;

  @Prop({ type: Boolean, default: false }) isDetail!: boolean;

  set = 0;

  get innerValue(): SdizPaper[] {
    return this.value;
  }

  set innerValue(value: SdizPaper[]) {
    this.$emit('change', value);
  }

  get optionsForTable(): Array<object> {
    return [
      {
        label: 'Номер',
        name: 'lot_sp_number',
        placeholder: '0000000000/00/00',
        style: { minWidth: '250px', width: '45%', textAlign: 'center' },
        customRenderValue: (model: any): string | number => {
          return model.lot_sp_number ? model.lot_sp_number : '0000000000/00/00';
        },
      },
      {
        label: 'Масса, кг',
        name: 'amount_kg_mask',
        placeholder: 'Введите массу партии',
        mask: decimalNumberMask,
        style: { minWidth: '250px', width: '45%', textAlign: 'center' },
        customRenderValue: (model: any): string | number => {
          model.amount_kg = decimalNumberUnmask(model.amount_kg_mask);

          return validate(model.amount_kg_mask)
            ? `<span class="text-caption ma-0">${
                model.amount_kg_mask ?? '0'
              }</span> <span class="text-caption text-center orange--text d-flex mb-2">Укажите граммы от 001 до 999</span>`
            : `${model.amount_kg_mask ?? '0'}`;
        },
      },
    ];
  }

  get isChangeData() {
    return this.isEdit || this.isCreate;
  }

  checkAndConvertArray(lot: any) {
    if (isNull(lot.amount_kg) || (isEmpty(lot.amount_kg) && !isNumber(lot.amount_kg))) {
      lot.amount_kg = 0;
    }

    if (typeof lot.amount_kg === 'string') {
      lot.amount_kg = decimalNumberUnmask(lot.amount_kg);
    }

    if (lot.amount_kg < 0) {
      lot.amount_kg = 0;
    }

    lot.amount_kg = parseFloat(lot.amount_kg);
    return lot;
  }

  onChangeAmountLots(row: any[]): void {
    let amountKg = 0;

    this.innerValue = row.map((lot) => {
      const element = this.checkAndConvertArray(lot);
      element.amount_kg = decimalNumberUnmask(element.amount_kg_mask);
      amountKg = add(amountKg, element.amount_kg);
      return element;
    });

    let maskAmountKr = applyMask(amountKg, true);

    this.$emit('onChangeAmountKg', {
      amountKg: {
        mask: maskAmountKr,
        value: amountKg,
      },
      list: this.innerValue,
    });
  }
}
</script>
