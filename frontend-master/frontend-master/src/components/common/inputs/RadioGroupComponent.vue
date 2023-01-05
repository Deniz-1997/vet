<template>
  <v-radio-group v-model="innerValue" :row="row" :column="column" :hide-details="hideDetails" :disabled="disabled">
    <v-radio :label="innerLabelYes" :value="valueYes">
      <template v-if="labelYes" #label>
        <label-component :label="labelYes" :show-icon="isRequired" />
      </template>
    </v-radio>
    <v-radio :label="innerLabelNo" :value="valueNo">
      <template v-if="labelNo" #label>
        <label-component :label="labelNo" :show-icon="isRequired" />
      </template>
    </v-radio>
  </v-radio-group>
</template>

<script lang="ts">
import { Component, Model, Prop, Vue } from 'vue-property-decorator';
import TextComponent from '@/components/common/Text/Text.vue';
import LabelComponent from '@/components/common/Label/Label.vue';

type Value = boolean | string | number;

@Component({
  name: 'radio-group-component',
  components: {
    LabelComponent,
    TextComponent,
  },
})
export default class RadioGroupComponent extends Vue {
  @Model('change', { type: [Boolean, String, Number], default: false }) readonly value!: Value;

  @Prop({ type: [Boolean, String, Number], default: true }) readonly valueYes!: boolean;
  @Prop({ type: [Boolean, String, Number], default: false }) readonly valueNo!: boolean;
  @Prop({ type: Boolean, default: true }) readonly row!: boolean;
  @Prop({ type: Boolean, default: false }) readonly column!: boolean;

  @Prop(Boolean) readonly disabled!: boolean;
  @Prop(Boolean) readonly isRequired!: boolean;
  @Prop(String) readonly innerLabelYes!: string;
  @Prop(String) readonly innerLabelNo!: string;
  @Prop(String) readonly labelYes!: string;
  @Prop(String) readonly labelNo!: string;
  @Prop(Boolean) readonly hideDetails!: boolean;
  @Prop(Function) readonly onClick!: () => void;

  get innerValue(): Value {
    return this.value;
  }

  set innerValue(value: Value) {
    this.$emit('change', value);
  }
}
</script>

<style lang="scss">
@import '@/assets/styles/_variables';

.v-input__control .v-radio {
  .v-label {
    font-size: 14px !important;
  }

  .v-input--selection-controls__input {
    color: transparent !important;
  }

  .v-input--selection-controls__input > .primary--text {
    caret-color: $gold-light-color !important;
    color: $gold-light-color !important;
  }
}
</style>
