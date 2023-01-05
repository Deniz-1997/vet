<template>
  <v-container fluid>
    <v-row>
      <dialog-component
        v-model="openModal"
        :prompt="false"
        cancel-title="Отмена"
        :confirm-title="options['buttonName']"
        :confirm-disabled="!isValidValue"
        controls-justify="justify-end"
        width="400"
        with-close-icon
        @onSuccess="onSuccess"
        @onCancel="onCancel"
      >
        <template #title>{{ options['titleName'] }}</template>
        <template #content>
          <v-col cols="12">
            <v-row>
              <v-col cols="12">
                <UiDateInput
                  v-model="innerValue.enter_date"
                  :format="'DD.MM.YYYY'"
                  label="Дата"
                  disabled
                  placeholder="Выберите дату"
                />
              </v-col>
              <v-col v-if="options['showElement']" cols="12">
                <select-request-component
                  v-model="innerValue.restriction_type"
                  :custom-items="typeRestrictions"
                  label="Тип запрета"
                  placeholder="Выберите тип"
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
import { PropType } from 'vue';
import SignatureModal from '@/components/SignatureModal/SignatureModal.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import { currentDay } from '@/utils';
import { rshnConsts } from '@/utils/consts/rshnConsts';
import { RestrictionsData } from '@/models/Rshn/Withdrawal/RestrictionsData';

export enum RestrictionAction {
  CREATE = 1,
  EDIT = 2,
  TAKEOFF = 3,
}

@Component({
  name: 'restrictions-dialog',
  components: { DialogComponent, SelectRequestComponent, SignatureModal, UiDateInput },
})
export default class RestrictionsDialog extends Vue {
  @Prop({ type: Object as PropType<RestrictionsData>, default: () => ({}) }) value!: RestrictionsData;
  @Prop({ type: Boolean, default: false }) isOpen!: boolean;
  @Prop({ type: Number as PropType<RestrictionAction>, required: true }) action!: RestrictionAction;

  dialogOptions = {
    [RestrictionAction.CREATE]: {
      buttonName: 'Установить',
      titleName: 'Установить запрет',
      showElement: true,
      disabled: false,
    },
    [RestrictionAction.EDIT]: {
      buttonName: 'Редактировать',
      titleName: 'Редактировать запрет',
      showElement: true,
      disabled: false,
    },
    [RestrictionAction.TAKEOFF]: {
      buttonName: 'Снять',
      titleName: 'Снять запрет',
      showElement: false,
      disabled: true,
    },
  };

  get options() {
    return this.dialogOptions[this.action];
  }

  typeRestrictions = rshnConsts.typeRestrictions;
  innerValue: RestrictionsData = new RestrictionsData();

  get openModal() {
    return this.isOpen;
  }

  set openModal(value: any) {
    this.$emit('is-open', value);
  }

  @Watch('isOpen', { immediate: true })
  handleIsOpenChange(v) {
    if (v) {
      this.innerValue = new RestrictionsData(this.value);

      if (!this.innerValue.id) this.innerValue.enter_date = currentDay();
    } else {
      this.innerValue = new RestrictionsData();
    }
  }

  onCancel() {
    this.openModal = !this.openModal;
  }

  onSuccess() {
    return this.$emit('on-success', this.innerValue, this.action);
  }

  get isValidValue() {
    return this.action === RestrictionAction.TAKEOFF || !!this.innerValue.restriction_type;
  }
}
</script>

<style lang="scss" scoped></style>
