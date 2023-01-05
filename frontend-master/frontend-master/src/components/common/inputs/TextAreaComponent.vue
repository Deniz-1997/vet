<template>
  <div class="text-area">
    <div v-if="label">
      <label-component
        :label="label"
        :show-icon="required"
        :error="error"
      />
    </div>
    <v-textarea
      v-model="innerValue"
      :append-icon="appendIcon"
      :auto-grow="autoGrow"
      :color="color"
      :disabled="disabled"
      :error-messages="errorMessages"
      :error="error"
      :height="height"
      :hide-details="hideDetails"
      :no-resize="noResize"
      :placeholder="placeholder"
      :readonly="readonly"
      :required="required"
      :row-height="rowHeight"
      :rows="rows"
      :rules="rules"
      :type="type"
      :class="[additionalClass]"
      solo
    >
      <template #message="{ message }">
        <text-component class="base-micro text-error">
          {{ message }}
        </text-component>
      </template>
    </v-textarea>
  </div>
</template>

<script lang="ts">
import { Component, Model, Prop, Vue } from 'vue-property-decorator';
import LabelComponent from '@/components/common/Label/Label.vue';
import TextComponent from '@/components/common/Text/Text.vue';

type Value = string | number;

@Component({
  name: 'textarea-component',
  components: {
    LabelComponent,
    TextComponent,
  },
})
export default class TextAreaComponent extends Vue {
  @Model('input', { type: [String, Number] }) value!: Value;

  @Prop({ type: String, default: 'auto' }) height!: string;
  @Prop({ type: String, default: 'text' }) type!: string;
  @Prop({ type: String, default: '' }) label!: string;
  @Prop({ type: String, default: '' }) placeholder!: string;
  @Prop({ type: String, default: '' }) appendIcon!: string;
  @Prop({ type: String}) rows!: string;
  @Prop({ type: String, default: '' }) additionalClass!: string;
  @Prop({ type: String, default: '24' }) rowHeight!: string;
  @Prop({ type: String, default: '#828286' }) color!: string;
  @Prop({ type: Array, default: () => [] }) rules!: string;
  @Prop({ type: [String, Array] }) errorMessages!: string;
  @Prop({ type: Boolean, default: false }) error!: boolean;
  @Prop(Boolean) required!: boolean;
  @Prop(Boolean) requiredMark!: boolean;
  @Prop(Boolean) hideDetails!: boolean;
  @Prop(Boolean) autoGrow!: boolean;
  @Prop(Boolean) disabled!: boolean;
  @Prop(Boolean) noResize!: boolean;
  @Prop(Boolean) readonly!: boolean;

  get innerValue(): Value {
    return this.value;
  }

  set innerValue(value: Value) {
    this.$emit('input', value);
  }
}
</script>

<style scoped lang="scss">
@import '@/assets/styles/_variables.scss';

  //$secondary: map-get($theme-colors, "secondary");
  //$light: map-get($theme-colors, "light");
  //$error: map-get($theme-colors, "error");

  .v-textarea::v-deep {
    //font-size: 22px;

    .v-input__slot {
      border: 1px solid $input-border-color;
      border-radius: 4px;
      box-shadow: none !important;
      min-height: 0;
    }

    &.v-input--is-disabled .v-input__slot {
      background-color: $input-disable-background !important;
    }

    &.error--text .v-input__control .v-input__slot {
      border: 1px solid $error-color;
    }

    & .v-text-field .v-text-field__details {
      padding: 0;
    }
  }
</style>
