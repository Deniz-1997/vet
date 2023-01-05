<template>
  <v-row>
    <v-col cols="12">
      <div class="title-h2">Аттестаты аккредитации</div>
    </v-col>

    <v-col cols="12">
      <EditableTable
        v-model="form.certificates"
        :options="headers"
        :max="999"
        :is-can-edit="isEditTable"
        is-custom-create
        :is-edit-action="isEditTable"
        is-custom-edit
        :is-show-delete-button="isEditTable"
        :is-showcase="!isEditTable"
      />
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Mixins, Component, Prop, Model } from 'vue-property-decorator';
import EditableTable from '@/components/common/Table/index.vue';
import Form from '@/utils/global/mixins/form';

@Component({
  name: 'CertificatesTable',
  components: {
    EditableTable,
  },
})
export default class extends Mixins(Form) {
  @Model('change', { type: Array, default: () => [] }) protected readonly value!: any[];
  @Prop({ type: Boolean, default: () => true }) readonly isShow!: boolean;
  @Prop({ type: Boolean, default: () => true }) readonly isEditTable!: boolean;

  get headers() {
    return [
      {
        label: 'Номер аттестата',
        name: 'doc_num',
        width: 400,
      },
      {
        label: 'Дата начала действия',
        name: 'start_date',
        controlType: 'date',
        limitTo: this.$moment().add(1, 'd').toDate(),
        width: 250,
      },
      {
        label: 'Дата окончания действия',
        name: 'end_date',
        controlType: 'date',
        width: 250,
      },
    ];
  }
}
</script>
