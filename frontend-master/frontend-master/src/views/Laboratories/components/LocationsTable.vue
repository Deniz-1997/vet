<template>
  <v-row>
    <v-col cols="12">
      <div class="title-h2">Адреса осуществления деятельности</div>
    </v-col>

    <v-col cols="12">
      <EditableTable
        v-model="innerValue"
        :options="headers"
        :max="999"
        :rules="rules"
        :is-showcase="isShow"
        :is-can-edit="!isShow"
        is-custom-create
        is-edit-action
        is-not-add-new-field
        is-custom-edit
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
        <Address v-model="form.address" :subject-type="subjectType" @close="closeModal" @saveAction="addFields" />
      </template>
    </Dialog-component>
  </v-row>
</template>

<script lang="ts">
import { Vue, Component, Prop } from 'vue-property-decorator';
import EditableTable from '@/components/common/Table/index.vue';
import Address from '@/components/Address/Address.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';

@Component({
  name: 'LocationsTable',
  components: {
    EditableTable,
    Address,
    DialogComponent,
  },
})
export default class extends Vue {
  @Prop({ type: Array, default: () => [] }) readonly value!: any[];
  @Prop({ type: Boolean, default: () => true }) readonly isShow!: boolean;
  @Prop({ type: String }) readonly subjectType!: string;

  isOpenModal = false;
  isEdit = false;
  rowOnEdit = 0;
  form: any = {
    address: {},
  };

  get headers() {
    return [
      {
        label: 'Адрес',
        name: 'address',
        controlType: 'address',
        width: 400,
        restrictions: this.innerValue,
      },
      {
        label: 'Дополнительный адрес',
        name: 'additional_info',
        width: 400,
      },
    ];
  }

  get rules() {
    return {
      address: [{ subject_country: this.subjectType }],
    };
  }

  get innerValue() {
    return this.value;
  }

  set innerValue(v) {
    this.$emit('input', v);
  }

  addFields(data: any) {
    if (this.isEdit) {
      this.$set(this.innerValue, this.rowOnEdit, data);
    } else {
      this.innerValue.push(data);
    }
    this.isOpenModal = false;
  }

  showModal() {
    this.form = {};
    this.isEdit = false;
    this.isOpenModal = true;
  }

  showModalEdit(index: number) {
    this.isOpenModal = true;
    this.rowOnEdit = index;
    this.isEdit = true;
    this.form.address = this.innerValue[index];
  }

  closeModal() {
    this.isOpenModal = false;
    this.isEdit = false;
  }
}
</script>
