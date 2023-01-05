<template>
  <v-row>
    <v-col cols="12">
      <div class="title-h2">Адреса осуществления деятельности</div>
    </v-col>

    <v-col cols="12">
      <EditableTable
        v-model="form"
        :options="headers"
        :max="999"
        is-can-edit
        is-custom-create
        is-edit-action
        is-custom-edit
        is-show-delete-button
        :is-showcase="!isEditTable"
        edit-strategy="outer"
        @customCreate="showModal"
        @customEdit="showModalEdit"
      />
    </v-col>

    <Dialog-component
      v-model="isOpenModal"
      :prompt="false"
      cancel-title=""
      confirm-title=""
      width="800"
      with-close-icon
      controls-justify="justify-end"
      custom-class="dialog_address"
    >
      <template #title> Адрес </template>
      <template #content>
        <Address v-model="baseForm.address" :subject-type="subjectType" @close="closeModal" @saveAction="addFields" />
      </template>
    </Dialog-component>
  </v-row>
</template>

<script lang="ts">
import { Mixins, Component, Prop, Model } from 'vue-property-decorator';
import EditableTable from '@/components/common/Table/index.vue';
import Address from '@/components/Address/Address.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import Form from '@/utils/global/mixins/form';

@Component({
  name: 'LocationsTable',
  components: {
    EditableTable,
    Address,
    DialogComponent,
  },
})
export default class extends Mixins(Form) {
  @Model('change', { type: Array, default: () => [] }) protected readonly value!: any[];
  @Prop({ type: Boolean, default: () => true }) readonly isShow!: boolean;
  @Prop({ type: String }) readonly subjectType!: string;
  @Prop({ type: Boolean, default: () => true }) readonly isEditTable!: boolean;

  isOpenModal = false;
  isEdit = false;
  rowOnEdit = 0;
  baseForm: any = {
    address: {},
  };

  get headers() {
    return [
      {
        label: 'Адрес',
        name: 'address',
        controlType: 'address',
        width: 400,
        restrictions: this.form,
      },
      {
        label: 'Дополнительный адрес',
        name: 'additional_info',
        width: 400,
      },
    ];
  }

  addFields(data: any) {
    if (this.isEdit) {
      this.$set(this.form, this.rowOnEdit, data);
    } else {
      this.form.push(data);
    }
    this.closeModal();
  }

  showModal() {
    this.baseForm = {};
    this.isEdit = false;
    this.isOpenModal = true;
  }

  showModalEdit(index: number) {
    this.isOpenModal = true;
    this.rowOnEdit = index;
    this.isEdit = true;
    this.baseForm.address = this.form[index];
  }

  closeModal() {
    this.isOpenModal = false;
    this.isEdit = false;
  }
}
</script>
