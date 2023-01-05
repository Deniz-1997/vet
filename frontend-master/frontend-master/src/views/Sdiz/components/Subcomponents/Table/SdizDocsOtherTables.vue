<template>
  <v-col cols="12" lg="11" md="12" sm="12" xl="7">
    <sdiz-table
      v-model="innerValue"
      :is-create="isCreate"
      :is-edit="isEdit"
      :options-for-table="optionsForTable"
      :is-edit-action="isCreate || isEdit"
      :is-can-edit="isCreate || isEdit"
      :is-hide-action="false"
      :is-show-card-button="isCreate || isEdit"
      class_="edit-table-sdiz-docs-other"
      empty-text="Не добавлены документы"
      title="Реквизиты иных товаросопроводительных документов на партию зерна или партию продуктов переработки зерна"
    />
  </v-col>
</template>

<script lang="ts">
import { Component, Model, Prop } from 'vue-property-decorator';
import { mixins } from 'vue-class-component';
import { AdditionalMix } from '@/utils/mixins/additional';
import { DocsOtherVueModel } from '@/models/Sdiz/DocsOther.vue';
import SdizTable from '@/views/Sdiz/components/Subcomponents/Table/SdizTable.vue';

@Component({
  name: 'sdiz-docs-other-tables',
  components: { SdizTable },
})
export default class SdizDocsOtherTables extends mixins(AdditionalMix) {
  @Model('change', { type: Array, required: true }) value!: DocsOtherVueModel[];

  @Prop({ type: Boolean, default: false }) isEdit!: boolean;

  @Prop({ type: Boolean, default: false }) isCreate!: boolean;

  date = false;
  number = false;
  type = false;

  get innerValue(): DocsOtherVueModel[] {
    return this.value;
  }

  set innerValue(value: DocsOtherVueModel[]) {
    const isAnyField = (e) => e.date || e.number || e.type;

    if (value.length > 0) {
      this.$emit(
        'change',
        Object.keys(value[0]).length > 0
          ? value
              .filter((e) => isAnyField(e))
              .map(({ date, number, type }) => new DocsOtherVueModel({ date: date, number: number, type: type }))
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
        placeholder: 'Введите тип документа',
        width: 400,
      },
      {
        label: 'Дата',
        name: 'date',
        controlType: 'date',
        width: 400,
      },
      {
        label: 'Номер',
        name: 'number',
        placeholder: 'Введите номер',
        width: 300,
      },
    ];
  }
}
</script>
<style lang="scss" scoped></style>
