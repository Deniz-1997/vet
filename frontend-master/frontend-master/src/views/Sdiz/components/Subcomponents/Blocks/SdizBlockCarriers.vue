<template>
  <v-row no-gutters class="mt-5">
    <text-component variant="h6">Сведения о перевозчиках</text-component>
    <v-row no-gutters :class="{ 'mb-10': !model.carriers.length }">
      <SdizElementCarrier
        v-for="(_carrier, idx) in model.carriers"
        v-model="model.carriers[idx]"
        :key="idx"
        :is-edit="isEdit"
        :is-create="isCreate"
        :is-change="isChange"
        :carrier-idx="idx"
        @delete="handleDelete"
      />
    </v-row>

    <DefaultButton
      v-if="isChange"
      :disabled="!isChange"
      title="Добавить перевозчика"
      size="small"
      variant="primary"
      @click="addCarrier"
    />
  </v-row>
</template>

<script lang="ts">
import LabelComponent from '@/components/common/Label/Label.vue';
import SdizElementCarrier from '@/views/Sdiz/components/Subcomponents/Elements/SdizElementCarrier.vue';
import { Component, Model, Prop } from 'vue-property-decorator';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import Vue from 'vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import { SdizCarrierModel } from '@/models/Sdiz/SdizCarrier';
import TextComponent from '@/components/common/TextComponent.vue';

@Component({
  name: 'sdiz-block-carriers',
  components: { TextComponent, DefaultButton, LabelComponent, SdizElementCarrier },
})
export default class SdizBlockCarriers extends Vue {
  @Model('change', { type: Object, required: true }) readonly model!: SdizGpbVueModel | SdizVueModel;
  @Prop({ required: true }) isEdit!: boolean;
  @Prop({ required: true }) isCreate!: boolean;
  @Prop({ required: true }) isChange!: boolean;

  get innerValue() {
    return this.model;
  }

  set innerValue(val) {
    this.$emit('change', val);
  }

  addCarrier() {
    this.innerValue.carriers.push(new SdizCarrierModel());
  }

  handleDelete(idx) {
    this.innerValue.carriers.splice(idx, 1);
  }
}
</script>
