<template>
  <div>
    <InputComponent
      v-model="innerValue"
      :label="label"
      :placeholder="placeholder"
      :mask="disableMask ? null : decimalNumberMask"
      :maxlength="15"
      :disabled="disabled"
      autocomplete="off"
      @input="handleInput"
    />
    <TextComponent
      v-if="validateInput"
      :class="[
        'weight-input__error-msg',
        'text-caption',
        'text-center',
        'orange--text',
        'mt-1',
        { 'weight-input__error-msg--active': error },
      ]"
      variant="span"
    >
      {{ error_message }}
    </TextComponent>
  </div>
</template>

<script lang="ts">
import { Vue, Component, Prop, Model } from 'vue-property-decorator';
import { decimalNumberMask, decimalNumberUnmask, validate } from '@/components/common/inputs/mask/decimalNumberMask';

import InputComponent from '@/components/common/inputs/InputComponent.vue';
import TextComponent from '@/components/common/TextComponent.vue';

@Component({ name: 'WeightInput', components: { TextComponent, InputComponent } })
export default class WeightInput extends Vue {
  @Model('change', { type: String }) value!: string;
  @Prop({ type: String, default: 'Масса, кг' }) label!: string;
  @Prop({ type: String, default: 'Укажите массу' }) placeholder!: string;
  @Prop({ type: Boolean, default: false }) disabled!: boolean;
  @Prop({ type: Boolean, default: false }) disableMask!: boolean;
  @Prop({ type: Boolean, default: true }) validateInput!: boolean;

  error_message = 'Введите граммы от 001 до 999';

  get innerValue() {
    return this.value;
  }

  set innerValue(v) {
    this.$emit('change', v);
  }

  decimalNumberMask = decimalNumberMask;
  decimalNumberUnmask = decimalNumberUnmask;

  handleInput(v) {
    this.$emit('input', decimalNumberUnmask(v));
  }

  get error(): boolean {
    return validate(this.value);
  }
}
</script>

<style lang="scss">
.weight-input {
  &__error-msg {
    opacity: 0;
    transition: opacity 0.1s ease-in-out;
    position: absolute;
  }

  &__error-msg--active {
    opacity: 1;
  }
}
</style>
