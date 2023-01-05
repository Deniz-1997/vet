<template>
  <Dialog-component
    v-model="isModalOpen"
    :prompt="false"
    cancel-title=""
    confirm-title=""
    width="800"
    with-close-icon
    controls-justify="justify-end"
  >
    <template #title>
      <span v-if="isEdit">Данные контракта</span>
      <span v-else>Добавление контракта</span>
    </template>

    <template #content>
      <v-row>
        <v-col cols="12">
          <ManufacturerAutocomplete
            v-if="!isEdit"
            v-model="form.subject.subject_id"
            label="Агент"
            :is-disabled="isLoading"
            placeholder="Начните вводить наименование организации"
            :is-action-block="false"
            id-element="opf"
          />

          <InputComponent
            v-else
            v-model="form.subject.subject_data.name"
            label="Агент"
            :disabled="isEdit || readonly || isLoading"
            name="subject_data.name"
          />
        </v-col>

        <v-col cols="12">
          <InputComponent
            id="doc_num"
            v-model="form.document.doc_num"
            placeholder="Введите текст"
            label="Номер контракта"
            :disabled="readonly || isLoading"
            name="document.doc_num"
          />
        </v-col>

        <v-col cols="6">
          <UiDateInput
            v-model="form.contract_date_start"
            class="datePicker"
            label="Дата начала"
            :format="'DD.MM.YYYY'"
            title-format="MMMM YYYY"
            :disabled="readonly || isLoading"
            name="contract_date_start"
          />
        </v-col>

        <v-col cols="6">
          <UiDateInput
            v-model="form.contract_date_end"
            class="datePicker"
            label="Дата окончания"
            :format="'DD.MM.YYYY'"
            title-format="MMMM YYYY"
            :disabled="readonly || isLoading"
            name="contract_date_end"
          />
        </v-col>
      </v-row>

      <v-row justify="end">
        <v-col cols="12" class="col-exclude">
          <DefaultButton :title="readonly ? 'Закрыть' : 'Отмена'" @click="isModalOpen = false" />
          <DefaultButton
            v-if="!readonly"
            variant="primary"
            :disabled="isLoading"
            title="Сохранить"
            @click="saveContract"
          />
        </v-col>
      </v-row>

      <v-overlay :value="isLoading" :absolute="true">
        <v-progress-circular indeterminate size="64"></v-progress-circular>
      </v-overlay>
    </template>
  </Dialog-component>
</template>

<script lang="ts">
import { Component, Prop, Mixins } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import Modal from '@/utils/global/mixins/modal';

type Props = {
  idCard: number;
};

@Component({
  name: 'AgentContractCard',
  components: {
    InputComponent,
    DefaultButton,
    AutocompleteComponent,
    SelectComponent,
    ManufacturerAutocomplete,
    DialogComponent,
  },
})
export default class ContractCard extends Mixins(Modal) {
  @Prop({ type: [Number], default: () => ({}) })
  readonly idCard: Props['idCard'] | undefined;
  @Prop({ type: Boolean, default: false }) readonly readonly!: boolean;

  static get initialForm() {
    return {
      subject: {
        subject_data: {},
      },
      document: {
        doc_num: '',
      },
      contract_date_start: null,
      contract_date_finish: null,
      doc_num: '',
    };
  }

  isEdit = false;
  isLoading = true;
  form = ContractCard.initialForm;

  onModalOpen() {
    if (this.idCard) {
      this.isEdit = true;
      this.getCardInfoById();
    } else {
      this.isLoading = false;
    }
  }

  onModalClose() {
    this.isEdit = false;
    this.form = ContractCard.initialForm;
  }

  async getCardInfoById() {
    this.isLoading = true;
    const data: any = await this.$store.dispatch('contracts/showInfo', this.idCard);
    this.form = { ...data };
    this.isLoading = false;
  }

  async saveContract() {
    this.isLoading = true;
    try {
      if (this.isEdit) {
        await this.$store.dispatch('contracts/updateContracts', this.form);
        this.isModalOpen = false;
        this.isEdit = false;
      } else {
        await this.$store.dispatch('contracts/createContracts', this.form);
        this.isModalOpen = false;
      }
      this.isLoading = false;
    } catch (_err) {
      this.isLoading = false;
    }
  }
}
</script>

<style lang="scss" scoped>
.subtitle {
  font-weight: 700;
}
</style>
