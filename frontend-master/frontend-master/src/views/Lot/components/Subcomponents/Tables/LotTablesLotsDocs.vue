<template>
  <v-col :class="{ 'mt-10 mt-xl-0': showLotMovedTable }" cols="12" md="12">
    <lot-table
      v-model="innerValue"
      class_="edit-table-lots-docs"
      :options-for-table="optionsForTable"
      :hide-action="isDetail"
      :is-create="isCreate"
      :is-edit="isEdit"
      :is-can-edit="isChangeData"
      :is-edit-action="isChangeData"
      empty-text="Не добавлены документы"
      title="Документы"
    />
  </v-col>
</template>

<script lang="ts">
import { Component, Mixins, Model, Prop, Watch } from 'vue-property-decorator';
import { AdditionalMix } from '@/utils/mixins/additional';
import { DocsVueModel } from '@/models/Lot/Docs.vue';
import nsiList from '@/views/NSI/config';
import LotTable from '@/views/Lot/components/Subcomponents/Tables/LotTable.vue';
import { DictionariesMix } from '@/utils/mixins/dictionaries';
import omit from 'lodash/omit';

type SelectItem = {
  label: string;
  value: number;
  code: string;
};

@Component({
  name: 'lot-tables-lots-docs',
  components: { LotTable },
})
export default class LotTablesLotsDocs extends Mixins(AdditionalMix, DictionariesMix) {
  @Model('change', { type: Array, required: true }) value!: DocsVueModel[];

  @Prop({ required: true }) showLotMovedTable!: boolean;

  @Prop({ type: Boolean, default: false }) isEdit!: boolean;

  @Prop({ type: Boolean, default: false }) isCreate!: boolean;

  @Prop({ type: Boolean, default: false }) isDetail!: boolean;

  omit = omit;

  selectItems: any[] = [];

  date = false;
  number = false;
  type = false;
  end_date = false;

  set = 0;

  get innerValue(): any[] {
    return this.value.map((e) => ({
      ...e,
      type: { label: e.type?.name, value: e.type?.id, code: e.type?.code },
    }));
  }

  set innerValue(value: any[]) {
    if (value.length > 0) {
      this.$emit(
        'change',
        Object.keys(value[0]).length > 0
          ? value.map(({ date, number, type, end_date }) => {
              return new DocsVueModel({
                date: date,
                number: number,
                type: this.convertToDictionaryRecord(type),
                type_id: type?.value || null,
                end_date: this.isConformityDeclaration(new DocsVueModel({ type })) ? end_date : null,
              });
            })
          : []
      );
    } else {
      this.$emit('change', []);
    }
  }

  get isChangeData() {
    return this.isEdit || this.isCreate;
  }

  // eslint-disable-next-line max-lines-per-function
  get optionsForTable(): Array<object> {
    return [
      {
        label: 'Тип',
        name: 'type',
        placeholder: 'Выберите тип документа',
        controlType: 'select',
        restrictions: this.selectItems,
        exclude: true,
        style: {
          width: '50%',
        },
        validate: (value) => this.validate(value, 'type'),
        customRenderValue: (el: any) => {
          const noDataRender = this.isEdit ? 'Выберите тип документа' : '-';

          return `<span class="${!el.type?.value ? 'text--caption red--text text--lighten-1' : ''}">${
            el.type?.label || noDataRender
          }</span>`;
        },
      },
      {
        label: 'Дата',
        name: 'date',
        controlType: 'date',
        style: {
          width: '25%',
        },
        validate: (value) => this.validate(value, 'date'),
      },
      {
        label: 'Номер',
        name: 'number',
        placeholder: 'Введите номер',
        validate: (value) => this.validate(value, 'number'),
      },
      {
        label: 'Срок действия',
        name: 'end_date',
        controlType: (row) => {
          return this.isConformityDeclaration(row) ? 'date' : 'custom';
        },
        style: {
          width: '25%',
        },
        customRenderValue: () => {
          return '<span class="text-caption">-</span>';
        },
        validate: (value) => this.validateEndDate(value),
        disabled: (value) => {
          return !value.date || !this.isConformityDeclaration(value);
        },
        placeholder: (value) => {
          const isDateSet = !!value.date;
          const placeholder = isDateSet ? 'Укажите срок действия' : 'Укажите дату';

          return this.isConformityDeclaration(value) ? placeholder : '-';
        },
        limitFrom: (value: DocsVueModel) => {
          return value.date;
        },
      },
    ];
  }

  isConformityDeclaration(row: DocsVueModel | undefined) {
    return row?.type?.code === '1';
  }

  validateDefault(value) {
    return typeof value !== 'undefined' && value !== null && value !== '' && value !== {};
  }

  validateEndDate(_e: any) {
    // todo: при click-outside из строки в валидацию передается значение ячейки как строка, нельзя использовать
    return true;
  }

  validate(value, field): boolean {
    const validate = this.validateDefault(value);

    if (!validate) {
      let notify!: string;

      switch (field) {
        case 'type':
          notify = 'Выберите тип документа';
          break;

        case 'date':
          notify = 'Выберите дату';
          break;

        case 'number':
          notify = 'Укажите номер';
          break;

        default:
          notify = field;
      }
      this.notify(notify, this[field]);
    }

    this[field] = !this[field];

    return validate;
  }

  convertToSelectItem(item) {
    return { label: item.name, value: item.id, code: item.code };
  }

  convertToDictionaryRecord(item) {
    if (!item) return null;
    return { id: item.value, code: item.code, name: item.label };
  }

  async created() {
    const { content }: any = await this.$store.dispatch('nsi/getList', {
      url: nsiList['lot-document-type'].apiUrl,
      params: { actual: true },
    });
    this.selectItems = content.map((e): SelectItem => this.convertToSelectItem(e));
  }

  async actualizeData() {
    return await Promise.all(
      this.value.map(async (item) => {
        item.type = await this.dictionaryRecordByCode('lot-document-type', item.type?.code || '');
        if (!item.type) item.type_id = null;
        return item;
      })
    );
  }

  @Watch('isEdit')
  async onIsEditChange(isEdit) {
    if (isEdit) this.$emit('change', await this.actualizeData());
  }
}
</script>
<style lang="scss" scoped>
.edit-table-lots-docs {
  .table {
    .tableHeader,
    .tableList {
      padding-left: 10px;
    }

    .spanList,
    .spanHeader {
      &:last-child {
        text-align: center !important;
      }

      > div {
        width: 100% !important;
      }
    }
  }
}
</style>
