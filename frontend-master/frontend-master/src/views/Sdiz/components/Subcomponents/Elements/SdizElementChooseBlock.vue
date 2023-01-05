<template>
  <v-row v-show="isChangeForm || model[elemModelNumber] !== null" class="mt-5" no-gutters>
    <v-col
      v-show="isChangeForm || isChecked"
      :class="['mb-0', !offCheckbox && isChangeForm ? 'cursor' : '']"
      cols="12"
      @click="onClick"
    >
      <text-component class="float-left" variant="h5">{{ label }}</text-component>
      <checkbox-component v-if="!offCheckbox && isChangeForm" :value="checkboxValue" class="float-left checkbox-v" />
    </v-col>

    <v-col
      v-show="isChecked && elemModelDate !== ''"
      :xl="isDetailPage ? '4' : exl"
      class="pr-10 pr-xl-5"
      cols="12"
      md="3"
      sm="6"
    >
      <UiDateInput
        v-model="model[elemModelDate]"
        :disabled="!isChangeForm"
        label="Дата"
        mask=""
        placeholder="Выберите дату"
      />
    </v-col>

    <v-col v-show="isChecked" :xl="isDetailPage ? '4' : exl" class="pr-10 pr-xl-15" cols="12" md="4" sm="6">
      <input-component
        v-model="model[elemModelNumber]"
        :disabled="!isChangeForm"
        label="Номер"
        placeholder="Введите номер"
      />
    </v-col>
    <v-col v-show="isChecked && isEsiz" :xl="isDetailPage ? '4' : exl" class="pr-10 pr-xl-5" cols="12" md="3" sm="6">
      <UiDateInput
        v-model="model[elemModelEsizDate]"
        :disabled="!isChangeForm"
        mask=""
        label="Дата государственного контракта"
        placeholder="Выберите дату"
      />
    </v-col>
    <v-col v-show="isChecked && isEsiz" :xl="isDetailPage ? '4' : exl" class="pr-10 pr-xl-15" cols="12" md="4" sm="6">
      <input-component
        v-model="model[elemModelEsizNumber]"
        :disabled="!isChangeForm"
        label="Номер государственного контракта"
        placeholder="Введите номер"
      />
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Component, Model, Prop, Vue } from 'vue-property-decorator';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';

@Component({
  name: 'sdiz-element-choose-block',
  components: { UiDateInput, LabelComponent, TextComponent, InputComponent, CheckboxComponent },
})
export default class SdizElementChooseBlock extends Vue {
  @Model('change', { type: Object, required: true }) readonly model!: SdizGpbVueModel | SdizVueModel;

  @Prop({ type: Boolean, default: false }) readonly isEdit!: boolean;
  @Prop({ type: Boolean, default: false }) readonly isCreate!: boolean;
  @Prop({ type: Boolean, default: false }) readonly isEsiz!: boolean;

  @Prop({ type: Boolean, default: false }) readonly offCheckbox!: boolean;
  @Prop({ type: Boolean, default: false }) readonly isCheckedBlock!: boolean;

  @Prop({ type: String, required: true }) readonly label!: string;
  @Prop({ type: String, default: 'DD.MM.YYYY' }) readonly dateFormat!: string;
  @Prop({ type: String, required: true }) readonly elemModelNumber!: string;
  @Prop({ type: String, default: '' }) readonly elemModelDate!: string;
  @Prop({ type: String, default: '' }) readonly elemModelEsizDate!: string;
  @Prop({ type: String, default: '' }) readonly elemModelEsizNumber!: string;
  @Prop({ type: String, default: '12' }) readonly cols!: string;
  @Prop({ type: String, default: '12' }) readonly xl!: string;
  @Prop({ type: String, default: '4' }) readonly exl!: string;

  checkboxValue: boolean = false;

  get isGKA() {
    return (
      (this.model.gka_date !== null || typeof this.model.gka_date !== 'undefined') &&
      (this.model.gka_number !== null || typeof this.model.gka_number !== 'undefined')
    );
  }

  get isDetailPage() {
    return !this.isChangeForm && this.isGKA;
  }

  get isChecked() {
    if (!this.isChangeForm) {
      return this.model[this.elemModelDate] !== null || this.model[this.elemModelNumber] !== null;
    }

    return this.isChangeForm && !this.offCheckbox ? this.checkboxValue : this.isCheckedBlock;
  }

  get isChangeForm() {
    return this.isEdit || this.isCreate;
  }

  onClick() {
    this.checkboxValue = !this.checkboxValue;

    if (!this.checkboxValue) {
      this.model.eisz_number_checkbox_init = null;
    } else {
      this.model.eisz_number_checkbox_init = true;
    }
  }
}
</script>

<style lang="scss" scoped>
.cursor {
  cursor: pointer;
}

.checkbox-v {
  padding: 4px 20px !important;
  margin-top: 20px;
}
</style>
