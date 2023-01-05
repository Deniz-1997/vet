<template>
  <v-col cols="12" lg="11" md="12" sm="12" xl="5">
    <sdiz-table
      v-model="innerValue"
      :is-create="isCreate"
      :is-edit="isEdit"
      :options-for-table="optionsForTable"
      :is-edit-action="isCreate || isEdit"
      :is-can-edit="isCreate || isEdit"
      :is-hide-action="false"
      :is-show-card-button="isCreate || isEdit"
      class_="edit-table-sdiz-docs-transport"
      empty-text="Не добавлены транспортные средства"
      title="Транспортное(-ые) средство(-а)"
    />
  </v-col>
</template>

<script lang="ts">
import { Component, Model, Prop, Mixins, Watch } from 'vue-property-decorator';
import EditableTable from '@/components/common/Table/index.vue';
import { AdditionalMix } from '@/utils/mixins/additional';
import TextComponent from '@/components/common/TextComponent.vue';
import nsiList from '@/views/NSI/config';
import SdizTable from '@/views/Sdiz/components/Subcomponents/Table/SdizTable.vue';
import { DocsTransportsVueModel } from '@/models/Sdiz/DocsTransports.vue';
import { DictionariesMix } from '@/utils/mixins/dictionaries';
import { DictionaryRecordModel } from '@/models/Common/DictionaryRecord';

@Component({
  name: 'sdiz-docs-transport-tables',
  components: {
    SdizTable,
    TextComponent,
    EditableTable,
  },
})
export default class SdizDocsTransportTables extends Mixins(AdditionalMix, DictionariesMix) {
  @Model('change', { type: Array, required: true }) value!: DocsTransportsVueModel[];

  @Prop({ type: Boolean, default: false }) isEdit!: boolean;

  @Prop({ type: Boolean, default: false }) isCreate!: boolean;

  types: DictionaryRecordModel[] = [];

  type_tc = false;
  number = false;
  number_tc = false;

  get innerValue(): any[] {
    return this.value.map((e) => ({
      ...e,
      type: { label: e.type?.name, value: e.type?.id, code: e.type?.code },
    }));
  }

  set innerValue(value: any[]) {
    const isAnyField = (e) => e.type || e.number_tc || e.number;
    if (value.length > 0) {
      this.$emit(
        'change',
        Object.keys(value[0]).length > 0
          ? value
              .filter((e) => isAnyField(e))
              .map((val) => {
                return new DocsTransportsVueModel({
                  date: val.date,
                  number: val.number,
                  number_tc: val.number_tc,
                  type: this.convertToDictionaryRecord(val.type),
                  type_id: val.type?.value || null,
                });
              })
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
        placeholder: 'Выберите тип ТС',
        controlType: 'select',
        restrictions: this.types,
        exclude: true,
        width: 500,
        customRenderValue: (el: any) => {
          const noDataRender = this.isEdit ? 'Выберите тип ТС' : '-';

          return `<span class="${!el.type?.value ? 'text--caption red--text text--lighten-1' : ''}">${
            el.type?.label || noDataRender
          }</span>`;
        },
      },
      {
        label: 'Номер ТС',
        name: 'number_tc',
        placeholder: 'Введите значение',
        width: 300,
      },
      {
        label: 'Номер контейнера',
        name: 'number',
        width: 300,
        placeholder: 'Введите номер',
      },
    ];
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
      url: nsiList['nsi-transport-type'].apiUrl,
      params: { actual: true },
    });
    this.types = content.map((e) => this.convertToSelectItem(e));
  }

  async actualizeData() {
    return await Promise.all(
      this.value.map(async (item) => {
        item.type = await this.dictionaryRecordByCode('nsi-transport-type', item.type?.code || '');
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
<style lang="scss" scoped></style>
