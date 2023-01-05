<template>
  <v-col cols="12" lg="8" md="12" sm="12" xl="5">
    <sdiz-table
      v-model="innerValue"
      :is-create="isCreate"
      :is-edit="isEdit"
      :options-for-table="optionsForTable"
      :is-edit-action="isCreate || isEdit"
      :is-can-edit="isCreate || isEdit"
      :is-hide-action="false"
      :is-show-card-button="isCreate || isEdit"
      class_="edit-table-lots-docs"
      empty-text="Не добавлены документы"
      title="Документы, подтверждающие переход права собственности"
    />
  </v-col>
</template>

<script lang="ts">
import { Component, Mixins, Model, Prop, Watch } from 'vue-property-decorator';
import EditableTable from '@/components/common/Table/index.vue';
import { AdditionalMix } from '@/utils/mixins/additional';
import TextComponent from '@/components/common/TextComponent.vue';
import nsiList from '@/views/NSI/config';
import { DocsAktVueModel } from '@/models/Sdiz/DocsAkt.vue';
import SdizTable from '@/views/Sdiz/components/Subcomponents/Table/SdizTable.vue';
import { DictionariesMix } from '@/utils/mixins/dictionaries';

@Component({
  name: 'sdiz-docs-akt-tables',
  components: {
    SdizTable,
    TextComponent,
    EditableTable,
  },
})
export default class SdizDocsAktTables extends Mixins(AdditionalMix, DictionariesMix) {
  @Model('change', { type: Array, required: true }) value!: DocsAktVueModel[];

  @Prop({ type: Boolean, default: false }) isEdit!: boolean;

  @Prop({ type: Boolean, default: false }) isCreate!: boolean;

  typesSelectItems: any[] = [];

  date = false;
  number = false;
  type = false;

  get innerValue(): any[] {
    return this.value.map((e) => ({
      ...e,
      type: { label: e.type?.name, value: e.type?.id, code: e.type?.code },
    }));
  }

  set innerValue(value: any[]) {
    const isAnyField = (e) => e.date || e.number || e.type;

    if (value.length > 0) {
      this.$emit(
        'change',
        Object.keys(value[0]).length > 0
          ? value
              .filter((e) => isAnyField(e))
              .map(
                ({ date, number, type }) =>
                  new DocsAktVueModel({
                    date: date,
                    number: number,
                    type: this.convertToDictionaryRecord(type),
                    type_id: type?.value || null,
                  })
              )
          : []
      );
    } else {
      this.$emit('change', []);
    }
  }

  get optionsForTable(): Array<object> {
    return [
      {
        label: 'Тип',
        name: 'type',
        placeholder: 'Выберите тип документа',
        controlType: 'select',
        fontSize: '14px',
        restrictions: this.typesSelectItems,
        exclude: true,
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
      },
      {
        label: 'Номер',
        name: 'number',
        placeholder: 'Введите номер',
      },
    ];
  }

  async created() {
    const { content }: any = await this.$store.dispatch('nsi/getList', {
      url: nsiList['property-right-transfer-doc-type'].apiUrl,
      params: { actual: true },
    });

    this.typesSelectItems = content.map((e) => this.convertToSelectItem(e));
  }

  convertToSelectItem(item) {
    return { label: item.name, value: item.id, code: item.code };
  }

  convertToDictionaryRecord(item) {
    if (!item) return null;
    return { id: item.value, code: item.code, name: item.label };
  }

  async actualizeData() {
    return await Promise.all(
      this.value.map(async (item) => {
        item.type = item.type?.code
          ? await this.dictionaryRecordByCode('property-right-transfer-doc-type', item.type?.code || '')
          : null;
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
<style lang="scss">
.edit-table-lots-docs {
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
        margin: 0 auto;
      }

      > span {
        display: block;
        max-width: 230px !important;
      }
    }
  }
}
</style>
