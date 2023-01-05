<template>
  <v-container fluid>
    <v-row>
      <dialog-component
        :key="openModal"
        v-model="openModal"
        :prompt="false"
        :confirm-disabled="!isValidValue"
        cancel-title="Отмена"
        :confirm-title="titleConfirmButton"
        controls-justify="justify-end"
        width="640"
        with-close-icon
        @onSuccess="onSuccess"
        @onCancel="onCancel"
      >
        <template #title>{{ titleDialog }}</template>
        <template #content>
          <v-col cols="12">
            <v-row>
              <v-col cols="12">
                <UiDateInput
                  v-model="innerValue.enter_date"
                  :disabled="disabledElement"
                  :format="'DD.MM.YYYY'"
                  label="Дата"
                  placeholder="Выберите дату"
                />
              </v-col>

              <v-col cols="12">
                <input-component
                  v-model="innerValue.gpd_number"
                  :disabled="disabledElement"
                  label="Номер"
                  placeholder="Введите номер документа"
                />
              </v-col>

              <v-col cols="12">
                <select-request-component
                  v-model="innerValue.gpd_type"
                  :custom-items="rshn.typeDocks"
                  :disabled="disabledElement"
                  label="Вид документа"
                  placeholder="Выберите вид"
                />
              </v-col>
              <v-col cols="12">
                <text-area-component
                  v-model="innerValue.content"
                  :disabled="disabledElement"
                  label="Содержание операции"
                  placeholder="Введите (Максимум 250 символов)"
                />
              </v-col>
            </v-row>
          </v-col>
        </template>
      </dialog-component>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator';
import SignatureModal from '@/components/SignatureModal/SignatureModal.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import { PrescriptionDocData } from '@/models/Rshn/Prescription/PrescriptionDocData';
import TextAreaComponent from '@/components/common/inputs/TextAreaComponent.vue';
import { rshnConsts } from '@/utils/consts/rshnConsts';
import InputComponent from '@/components/common/inputs/InputComponent.vue';

@Component({
  components: {
    InputComponent,
    TextAreaComponent,
    DialogComponent,
    SelectRequestComponent,
    SignatureModal,
    UiDateInput,
  },
})
export default class PrescriptionDialog extends Vue {
  @Prop({ type: Object, default: () => ({}) }) value!: PrescriptionDocData;
  @Prop({ type: Boolean, default: false }) isDetail!: boolean;
  @Prop({ type: Boolean, default: false }) isOpen!: boolean;
  rshn = rshnConsts;

  innerValue: PrescriptionDocData = new PrescriptionDocData();

  @Watch('isOpen', { immediate: true })
  handleIsOpenChange(v) {
    if (v) {
      this.innerValue = { ...this.value };
    } else {
      this.innerValue = new PrescriptionDocData();
    }
  }

  get openModal() {
    return this.isOpen;
  }

  set openModal(value: any) {
    this.$emit('isOpen', value);
  }

  get disabledElement() {
    return this.isDetail;
  }

  get titleDialog() {
    return this.value.id ? 'Редактирование документа' : 'Внести подтверждающие документы';
  }

  get titleConfirmButton() {
    return this.value.id ? 'Сохранить' : 'Внести';
  }

  get onSuccessAction() {
    return this.value.id ? 'updateDock' : 'createDock';
  }

  onCancel() {
    this.openModal = !this.openModal;
  }
  onSuccess() {
    this.$emit(this.onSuccessAction, this.innerValue);
    this.onCancel();
  }

  get isValidValue() {
    const { gpd_number, gpd_type, enter_date, content } = this.innerValue;
    return gpd_number && gpd_type && enter_date && content;
  }
}
</script>

<style lang="scss" scoped></style>
