<template>
  <v-row v-show="isChangeForm || model.objects.storage_agreement.moving_date !== null" class="mt-5" no-gutters>
    <v-col
      v-show="isChangeForm || isChecked"
      :class="['mb-0', 'd-flex', !offCheckbox && isChangeForm ? 'cursor' : '']"
      cols="12"
      @click="onClick"
    >
      <text-component class="float-left nowrap" variant="h5">{{ label }}</text-component>
      <checkbox-component v-if="!offCheckbox && isChangeForm" :value="checkboxValue" class="checkbox-v" />
    </v-col>
    <v-col v-show="isChecked" cols="12">
      <v-row>
        <v-col :xl="isDetailPage ? '4' : exl" class="pr-10 pr-xl-15" cols="12" md="4" sm="6">
          <UiDateInput
            v-model="model.objects.storage_agreement.moving_date"
            :limit-to="today"
            :disabled="!isChangeForm"
            :format="'DD.MM.YYYY'"
            label="Дата"
            placeholder="Выберите дату"
          />
        </v-col>
        <v-col :xl="isDetailPage ? '4' : exl" class="pr-10 pr-xl-15" cols="12" md="4" sm="6">
          <autocomplete-priority-address
            v-model="model.objects.storage_agreement.moving_place_id"
            :is-disabled="!isChangeForm"
            label="Место хранения"
            placeholder="Выберите место хранения"
          />
        </v-col>
      </v-row>
      <v-row>
        <v-col :xl="isDetailPage ? '4' : exl" class="pr-10 pr-xl-15" cols="12" md="4" sm="6">
          <input-component
            v-if="!isChangeForm"
            disabled
            label="Тип хранения"
            :value="model.objects.storage_agreement?.moving_type?.name || ''"
          />
          <select-request-component
            v-else
            v-model="model.objects.storage_agreement.moving_type"
            label="Тип хранения"
            placeholder="Выберите тип хранения"
            type="storage-type"
            item-text-to-display="name"
            is-return-object
            preserve-data
          />
        </v-col>
        <v-col :xl="isDetailPage ? '4' : exl" class="pr-10 pr-xl-15" cols="12" md="4" sm="6">
          <input-component
            v-model="model.objects.storage_agreement.moving_conditions"
            :disabled="!isChangeForm"
            label="Условия хранения"
            placeholder="Условия хранения"
          />
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Component, Model, Prop, Vue } from 'vue-property-decorator';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';

@Component({
  name: 'sdiz-element-moving-lot',
  components: {
    LabelComponent,
    TextComponent,
    InputComponent,
    CheckboxComponent,
    UiDateInput,
    AutocompletePriorityAddress,
    SelectRequestComponent,
  },
})
export default class SdizElementMovingLot extends Vue {
  @Model('change', { type: Object, required: true }) readonly model!: SdizGpbVueModel | SdizVueModel;

  @Prop({ type: Boolean, default: false }) readonly isEdit!: boolean;
  @Prop({ type: Boolean, default: false }) readonly isCreate!: boolean;
  @Prop({ type: Boolean, default: false }) readonly offCheckbox!: boolean;
  @Prop({ type: Boolean, default: false }) readonly isCheckedBlock!: boolean;
  @Prop({ type: String, required: true }) readonly label!: string;
  @Prop({ type: String, default: '4' }) readonly exl!: string;
  checkboxValue = false;
  today = new Date();

  get isDetailPage() {
    return !this.isChangeForm;
  }

  get isChecked() {
    if (!this.isChangeForm) {
      return (
        !!this.model.objects.storage_agreement.moving_date || !!this.model.objects.storage_agreement.moving_place_id
      );
    }
    return this.isChangeForm && !this.offCheckbox ? this.checkboxValue : this.isCheckedBlock;
  }

  get isChangeForm() {
    if (this.isEdit && this.model.objects.storage_agreement.moving_date) {
      this.model.moving_lot_checkbox_init = true;
      return (this.checkboxValue = true);
    }
    return this.isEdit || this.isCreate;
  }

  onClick() {
    if (this.isChangeForm) {
      this.checkboxValue = !this.checkboxValue;
      if (!this.checkboxValue) {
        this.clearModel();
      } else {
        this.model.moving_lot_checkbox_init = true;
      }
    }
  }

  clearModel(): void {
    this.model.moving_lot_checkbox_init = null;
    this.model.objects.storage_agreement.moving_place_id = undefined;
    this.model.objects.storage_agreement.moving_type = null;
    this.model.objects.storage_agreement.moving_date = undefined;
    this.model.objects.storage_agreement.moving_conditions = undefined;
  }
}
</script>

<style lang="scss" scoped>
.nowrap {
  white-space: nowrap;
}

.cursor {
  cursor: pointer;
}

.checkbox-v {
  padding: 4px 20px !important;
  margin-top: 20px;
}
</style>
