<template>
  <v-container fluid>
    <editable-table
      v-model="innerValue"
      :hide-actions="isHideAction"
      :is-edit-action="(isEdit || isCreate) && isEditAction"
      :is-showcase="getIsShowCase"
      :is-show-card-button="isShowCardButton"
      :max="999"
      :options="optionsForTable"
      :title="title"
      enable-new-btn
      is-custom-create
      is-delete-row
      :is-can-edit="isCanEdit"
    />
    <v-row v-show="innerValue.length === 0 && !isCustomCreate" no-gutters>
      <v-col class="center-align" cols="12">
        <text-component class="text--secondary text-caption" variant="span">{{ emptyText }}</text-component>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { Component, Model, Prop, Mixins } from 'vue-property-decorator';
import { ActionsMix } from '@/utils/mixins/actions';
import TextComponent from '@/components/common/TextComponent.vue';
import EditableTable from '@/components/common/Table/index.vue';
import { RshnPrescriptionData } from '@/models/Rshn/Prescription/RshnPrescriptionData.vue';
import { RshnWithdrawalData } from '@/models/Rshn/Withdrawal/RshnWithdrawalData.vue';
import { RshnExpertiseData } from '@/models/Rshn/Expertise/RshnExpertiseData.vue';

@Component({
  components: { EditableTable, TextComponent },
})
export default class ActivitiesTable extends Mixins(ActionsMix) {
  @Model('change', { type: Array, required: true }) value!:
    | RshnPrescriptionData[]
    | RshnWithdrawalData[]
    | RshnExpertiseData[];
  @Prop({ required: true }) optionsForTable!: any;
  @Prop({ required: true }) title!: string;
  @Prop({ required: true }) emptyText!: string;
  @Prop({ type: Boolean, default: false }) isCanEdit!: boolean;
  @Prop({ type: Boolean, default: true }) isShowCardButton!: boolean;
  @Prop({ type: Boolean, default: true }) isEditAction!: boolean;
  @Prop({ type: Boolean, default: true }) isHideAction!: boolean;
  @Prop({ type: Boolean, default: false }) isEdit!: boolean;
  @Prop({ type: Boolean, default: false }) isCreate!: boolean;
  @Prop() isShowcase!: boolean;
  isCustomCreate = false;

  get getIsShowCase() {
    return typeof this.isShowcase === 'undefined' ? !this.isCreate && !this.isEdit : this.isShowcase;
  }

  get innerValue(): any {
    return this.value;
  }

  set innerValue(value: any) {
    this.$emit('change', value);
  }
}
</script>

<style lang="scss" scoped></style>
