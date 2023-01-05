<template>
  <div :class="class_">
    <editable-table v-model="innerValue"
                    :is-edit-action="isEditAction"
                    :is-show-delete-button="isShowDeleteButton"
                    :hide-actions="hideAction === null ? !isCreate && !isEdit : hideAction"
                    :is-showcase="showCase === null ? !isCreate && !isEdit : showCase"
                    :is-show-card-button="isShowCardButton"
                    :is-not-add-new-field="isNotAddNewField"
                    :max="max"
                    :title="title"
                    :options="optionsForTable"
                    enable-new-btn
                    is-custom-create
                    is-delete-row
                    :isCanEdit="isCanEdit"
                    @deleteItem="onDeleteItem"
                    @edit="onEdit"
                    @resetEdit="onResetEdit"
                    @editRowAddition="onEditLotsMoved"
                    @customCreate="onCustomCreate"
                    @input="onInput"/>
    <v-row v-show="innerValue.length === 0 && !showHintForTable" no-gutters>
      <v-col class="center-align" cols="12">
        <text-component class="text--secondary text-caption" variant="span">{{emptyText}}</text-component>
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import {Component, Model, Prop} from 'vue-property-decorator'
import EditableTable from "@/components/common/Table/index.vue";
import {mixins} from "vue-class-component";
import {AdditionalMix} from "@/utils/mixins/additional";
import {DocsAktVueModel} from "@/models/Sdiz/DocsAkt.vue";
import TextComponent from "@/components/common/TextComponent.vue";
import {QualityIndicatorsVueModel} from "@/models/Lot/QualityIndicators.vue";


@Component({
  name: 'lot-table',
  components: {TextComponent, EditableTable}
})
export default class LotTable extends mixins(AdditionalMix) {
  @Model('change', {type: Array, required: true}) value!: any;

  @Prop({required: true}) optionsForTable!: any;
  @Prop({required: true}) title!: string;
  @Prop({required: true}) emptyText!: string;

  @Prop() class_!: string;

  @Prop({type: Boolean, default: false}) showHintForTable!: boolean;

  @Prop({type: Boolean, default: false}) isNotAddNewField!: boolean;

  @Prop({type: Boolean, default: false}) isEdit!: boolean;

  @Prop({type: Boolean, default: false}) isCreate!: boolean;

  @Prop({type: Boolean, default: true}) isShowDeleteButton!: boolean;

  @Prop({type: Boolean, default: false}) isShowCardButton!: boolean;

  @Prop({type: Boolean, default: true}) isEditAction!: boolean;

  @Prop({type: Boolean, default: null}) hideAction!: boolean;

  @Prop({type: Boolean, default: null}) showCase!: boolean;

  @Prop({type: Boolean, default: false}) isCanEdit!: boolean;

  @Prop({type: Number, default: 999}) max!: number;

  @Prop({type: String, default: 'inner'}) editStrategy!: string;

  get innerValue(): any {
    return this.value;
  }

  set innerValue(value: any) {
    this.$emit('change', value);
  }

  onEdit(){
    this.$emit('onEdit');
  }

  onCustomCreate(){
    this.$emit('onShowHintForTable', !this.showHintForTable);
    this.$emit('onCustomCreate');
  }

  onInput(value){
    this.$emit('onShowHintForTable', !this.showHintForTable);
    this.$emit('onInput', value);
  }

  onResetEdit(){
    this.$emit('onShowHintForTable', !this.showHintForTable);
    this.$emit('onResetEdit');
  }

  onDeleteItem(){
    this.$emit('onShowHintForTable', !this.showHintForTable);
    this.$emit('onDeleteItem');
  }

  onEditLotsMoved(){
    this.$emit('onShowHintForTable', !this.showHintForTable);
    this.$emit('onEditLotsMoved');
  }
}
</script>
<style lang="scss">
.table{
  margin-bottom: 10px !important;
}
</style>
