<template>
  <div :class="class_">
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
      @customCreate="onCustomCreate"
      @deleteItem="onDeleteItem"
      @input="onInput"
      @resetEdit="onResetEdit"
    />
    <v-row v-show="innerValue.length === 0 && !isCustomCreate" no-gutters>
      <v-col class="center-align" cols="12">
        <text-component class="text--secondary text-caption" variant="span">{{ emptyText }}</text-component>
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import { Component, Model, Prop } from 'vue-property-decorator';
import EditableTable from '@/components/common/Table/index.vue';
import { mixins } from 'vue-class-component';
import { AdditionalMix } from '@/utils/mixins/additional';
import TextComponent from '@/components/common/TextComponent.vue';

@Component({
  name: 'sdiz-table',
  components: { TextComponent, EditableTable },
})
export default class SdizTable extends mixins(AdditionalMix) {
  @Model('change', { type: Array, required: true }) value!: any;

  @Prop({ required: true }) optionsForTable!: any;
  @Prop({ type: String, default: '' }) title!: string;
  @Prop({ required: true }) emptyText!: string;
  @Prop({ type: Boolean, default: false }) isCanEdit!: boolean;
  @Prop() class_!: string;

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

  onCustomCreate() {
    this.isCustomCreate = !this.isCustomCreate;
    this.$emit('onCustomCreate');
  }

  onInput() {
    this.isCustomCreate = !this.isCustomCreate;
    this.$emit('onInput');
  }

  onResetEdit() {
    this.isCustomCreate = !this.isCustomCreate;
    this.$emit('onResetEdit');
  }

  onDeleteItem() {
    this.isCustomCreate = !this.isCustomCreate;
    this.$emit('onDeleteItem');
  }
}
</script>
<style lang="scss">
.table {
  margin-bottom: 10px !important;
}
</style>
