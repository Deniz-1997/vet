<template>
  <UiControl :name="name" :value="innerValue" class="elementsInput checkbox-block">
    <span :class="['checkbox', disabled && 'checkbox_disabled']">
      <input
        :id="`${id}`"
        v-model="innerChecked"
        type="checkbox"
        :name="name"
        :checked="innerChecked"
        :value="innerValue"
        :disabled="disabled"
      />
      <span class="checkbox__icon">
        <img src="/icons/checkbox.svg" />
      </span>
    </span>
    <span class="label">{{ label }}</span>
  </UiControl>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';

@Component({
  name: 'UiCheckbox',
  model: {
    prop: 'checked',
    event: 'input',
  },
})
export default class extends Vue {
  @Prop({ type: Boolean, default: () => false }) readonly checked!: boolean;
  @Prop({ type: [String, Number, Boolean, Object, Array, Date, Function] }) readonly value!: any;
  @Prop({ type: [String, Number], default: () => '' }) readonly id!: string | number;
  @Prop({ type: String, default: () => '' }) readonly name!: string;
  @Prop({ type: String, default: () => '' }) readonly label!: string;
  @Prop({ type: Boolean, default: () => false }) readonly disabled!: boolean;

  get innerValue() {
    const isBothAnswersArray = Array.isArray(this.value) && this.value.length === 2;
    const value = isBothAnswersArray ? this.value : [this.value];

    if (this.checked) {
      return value[0] || true;
    }

    return value[1] || false;
  }

  get innerChecked() {
    return this.checked;
  }

  set innerChecked(v) {
    this.$emit('input', v);
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.label {
  color: $medium-grey-color;
  font-size: 13px;
  line-height: 16px;
  margin-bottom: 5px;
  display: block;

  &--strong {
    color: $black-color;
    font-weight: 700;
  }
}

.checkbox-block {
  align-items: center;
  // height: 61px;
  display: flex;

  .label {
    margin-bottom: 0;
    margin-left: 5px;
  }
}

.checkbox {
  cursor: pointer;
  width: 16px;
  height: 16px;
  position: relative;

  [type='checkbox'] {
    position: absolute;
    opacity: 0;
  }

  &__icon {
    align-items: center;
    justify-content: center;
    background: $check-bg;
    display: flex;
    height: 16px;
    width: 16px;
    border: 1px solid $input-border-color;
    border-radius: 4px;

    img {
      width: 9px;
      display: block;
      opacity: 0;
    }
  }

  [type='checkbox']:checked {
    & + .checkbox__icon {
      background: $gold-light-color;
      border-color: $gold-light-color;

      img {
        opacity: 1;
      }
    }
  }

  &_disabled {
    cursor: initial;
  }
}

.elementsInput {
  position: relative;
}
</style>
