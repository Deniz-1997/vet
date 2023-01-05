<template>
  <div :class="inputClass">
    <div v-if="label" class="d-flex align-center">
      <slot name="label">
        <label-component
          :label="label"
          :show-icon="required"
          :error="isError"
          :class="{ 'mr-2': !!$slots['label-icon'] }"
        />
        <slot name="label-icon" />
      </slot>
    </div>
    <v-text-field
      ref="input"
      v-model="innerValue"
      v-bind="$attrs"
      :class="{
        'edit-elem': edit,
        'delete-elem': del,
        'add-elem': add,
        'input_with-message': message,
      }"
      :color="color"
      :disabled="disabled"
      :error="isError"
      :error-count="errorCount"
      :error-messages="errorMessages"
      :hide-details="hideDetails"
      :maxlength="maxlength"
      :min="min"
      :hint="message"
      :placeholder="placeholder"
      :readonly="readonly"
      :rules="rules"
      :type="showPassword ? 'text' : type"
      :prefix="prefix"
      :reverse="reverse"
      :autofocus="autofocus"
      :append-icon="appendingIcon"
      class="input"
      solo
      :autocomplete="autocomplete"
      v-on="{ ...$listeners, input: (v) => (innerValue = v) }"
      @blur="handleBlur"
      @focus="handleFocus"
      @click:append="handleClickAppend"
      :title="tooltip"
    >
      <template #append>
        <slot name="append" />

        <img
          v-if="!hideClearable && value && !disabled"
          src="/icons/cross.svg"
          class="iconTable"
          @click="handleClearClick"
        />
      </template>
      <template #message="{ message }">
        <text-component class="base-micro text-error">
          {{ message }}
        </text-component>
      </template>
    </v-text-field>
  </div>
</template>

<script lang="ts">
import { Component, Model, Prop, Vue } from 'vue-property-decorator';
import set from 'lodash/set';
import LabelComponent from '@/components/common/Label/Label.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import { VueMaskFilter } from 'v-mask';

type Sizes = {
  [key in 'micro']: number;
};

type Variants = keyof Sizes;

type Value = string | number | unknown[];

@Component({
  name: 'InputComponent',
  components: {
    LabelComponent,
    TextComponent,
  },
})
export default class InputComponent extends Vue {
  @Model('input', { type: [String, Number, Array] }) value!: Value;

  @Prop({ type: String, default: '' }) appendIcon!: string;
  @Prop({ type: String, default: '' }) placeholder!: string;
  @Prop({ type: String, default: '' }) label!: string;
  @Prop({ type: String, default: '' }) message!: string;
  @Prop({ type: String, default: 'noop' }) autocomplete!: string;
  @Prop({ type: String, default: '#828286' }) color!: string;
  @Prop({ type: String, default: 'text' }) type!: string;
  @Prop({ type: String, default: '100%' }) width!: string;
  @Prop({ type: String, default: 'micro' }) variant!: Variants;
  @Prop({ type: Number, default: 0 }) min!: number;
  @Prop({ type: Array, default: () => [] }) rules!: string;
  @Prop({ type: Boolean, default: false }) isError!: boolean;
  @Prop({ type: Number, default: null }) maxlength!: number;
  @Prop({ type: Number, default: 1 }) errorCount!: number;
  @Prop(String) readonly errorMessages!: string | string[];
  @Prop(Boolean) hideClearable!: boolean;
  @Prop(Boolean) disabled!: boolean;
  @Prop(Boolean) hideDetails!: boolean;
  @Prop(Boolean) required!: boolean;
  @Prop(Boolean) readonly!: boolean;
  @Prop(Boolean) reverse!: boolean;
  @Prop(Boolean) autofocus!: boolean;
  @Prop(String) prefix!: string;
  @Prop({ type: Boolean, default: false }) edit!: boolean;
  @Prop({ type: Boolean, default: false }) del!: boolean;
  @Prop({ type: Boolean, default: false }) add!: boolean;
  @Prop({ type: [String, Array, Function], default: null }) mask!: any;
  @Prop(String) readonly inputClass!: string;
  @Prop({ type: String, default: '' }) tooltip!: string;

  sizeVariants: Sizes = {
    micro: 40,
  };
  showPassword = false;

  get appendingIcon(): string | undefined {
    if (this.type === 'password') {
      return !this.showPassword ? 'mdi-eye' : 'mdi-eye-off';
    }

    return this.appendIcon;
  }

  get innerValue(): Value {
    return this.mask ? VueMaskFilter(this.value, this.mask) : this.value;
  }

  set innerValue(value: Value) {
    const masked = this.mask ? VueMaskFilter(value, this.mask) : value;
    this.$emit('input', masked);
    set(this.$refs, 'input.lazyValue', masked);
  }

  handleBlur($event): void {
    this.$emit('onBlur', { $event: $event, value: this.innerValue });
  }

  handleFocus($event): void {
    this.$emit('onFocus', { $event: $event, value: this.innerValue });
  }

  handleClearClick(): void {
    this.innerValue = '';
    this.$emit('clear');
  }

  handleClickAppend(v): void {
    if (this.type === 'password') {
      this.showPassword = !this.showPassword;
    }
    this.$emit('click', v);
  }
}
</script>

<style scoped lang="scss">
@import '@/assets/styles/_variables';

.input::v-deep {
  .v-input {
    outline: none !important;

    &__control {
      min-height: 40px !important;
    }

    &__slot {
      border: 1px solid $input-border-color;
      border-radius: 3px;
      background: $white-color;
      box-shadow: none !important;
      outline: none;
      height: 40px;
      color: $black-color;
      font-size: 14px;
      line-height: 16px;
      margin-bottom: 0;
      padding: 0 10px !important;
      z-index: 7;
    }
  }

  .v-text-field {
    &__details {
      margin-top: 4px;
    }
  }

  &.v-input--is-disabled .v-input__slot {
    background-color: $input-disable-background !important;

    &.input {
      color: $input-disabled-color;
    }
  }

  .v-input.v-select {
    outline: none !important;
    color: inherit !important;

    .v-input__slot {
      border: 0 !important;
      box-shadow: none !important;
      outline: none !important;
    }
  }

  .v-input.v-input--is-focused {
    border: 0 !important;
    box-shadow: none !important;
    outline: none !important;
    color: inherit !important;
    caret-color: transparent !important;
  }
}

.input.edit-elem::v-deep {
  .v-input__control .v-input__slot {
    background: rgba($gold-light-color, 0.3) !important;

    input {
      color: $medium-grey-color !important;
    }
  }
}

.input.delete-elem {
  .v-input__slot {
    background: rgba($del-color, 0.3) !important;
    color: $medium-grey-color !important;
  }
}

.input.add-elem {
  .v-input__slot {
    background: rgba($added-color, 0.3) !important;
    color: $medium-grey-color !important;
  }
}

.v-input.input_with-message {
  padding-bottom: 20px;
}
</style>
