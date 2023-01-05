<template>
  <div>
    <v-row>
      <v-col cols="12">
        <InputComponent
          id="name"
          v-model="form.subject.subject_data.name"
          placeholder="Введите текст"
          label="Наименование"
          :disabled="isShow"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <InputComponent
          id="shotOrganizationName"
          v-model="form.subject.subject_data.short_name"
          placeholder="Введите текст"
          label="Краткое наименование"
          :disabled="isShow"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <AutocompleteComponent
          v-model="form.subject.subject_data.opf"
          return-object
          :items="opfList"
          label="Организационно-правовая форма"
          item-value="code"
          item-text="name"
          multiple
          :is-disabled="isShow"
          @searchInputUpdate="searchOpf"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="4">
        <InputComponent
          id="inn"
          v-model="form.subject.subject_data.inn"
          placeholder="Введите текст"
          :maxlength="12"
          label="ИНН"
          mask="##########"
          :disabled="isEdit"
        />
      </v-col>
      <v-col cols="12" md="4">
        <InputComponent
          id="kpp"
          v-model="form.subject.subject_data.kpp"
          placeholder="Введите текст"
          mask="#########"
          label="КПП"
          :maxlength="9"
          :disabled="isEdit"
        />
      </v-col>
      <v-col cols="12" md="4">
        <InputComponent
          id="ogrn"
          v-model="form.subject.subject_data.ogrn"
          placeholder="Введите текст"
          mask="#############"
          label="ОГРН"
          :disabled="isEdit"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col v-if="form.subject.address" cols="12">
        <UiControl name="subjectAddress" :value="form.subject.address">
          <InputComponent
            id="address"
            :value="address(form.subject.address)"
            disabled
            label="Адрес"
            placeholder="Введите текст"
          />
        </UiControl>
      </v-col>

      <v-col v-if="form.subject.address && form.subject.address.additional_info" cols="12">
        <InputComponent
          id="additional_info"
          v-model="form.subject.address.additional_info"
          disabled
          placeholder="Введите текст"
          label="Дополнительный адрес"
        />
      </v-col>
      <v-col v-if="!isShow" cols="12">
        <DefaultButton variant="primary" :title="(isEdit || form.subject.address) ? 'Изменить адрес' : 'Указать адрес'" @click="showModal" />
      </v-col>
    </v-row>

    <Dialog-component
      v-model="isShowModal"
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
        <Address
          v-model="form.subject.address"
          :subject-type="form.head_subject && form.head_subject.subject_type"
          @close="closeModal"
          @saveAction="addFields"
        />
      </template>
    </Dialog-component>
  </div>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import Address from '@/components/Address/Address.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import FormMixin from './FormMixin.vue';
import { address } from '@/utils/global/filters';

type opfItem = {
  code: string;
  id: number;
  name: string;
  startDate: string;
  startTime: string;
  start_date: string;
};

@Component({
  name: 'subject-info',
  components: {
    InputComponent,
    AutocompleteComponent,
    DefaultButton,
    Address,
    DialogComponent,
  },
  methods: { address },
})
export default class extends Mixins(FormMixin) {
  opfList: opfItem[] = [];
  isShowModal = false;

  async searchOpf(value) {
    const itemIndex = this.opfList.findIndex((item: opfItem) => item.code === value);
    if (itemIndex === -1) {
      const { content } = await this.$store.dispatch('sdiz/getOPF');
      this.opfList = content;
    }
  }

  addFields(data: any) {
    this.form.subject.address = { ...data };
    this.isShowModal = false;
  }

  showModal() {
    this.isShowModal = true;
  }

  closeModal() {
    this.isShowModal = false;
  }
}
</script>
