<template>
  <button
    v-bind="attrs"
    :class="['btn', `btn--${variant}`, `btn--${size}`]"
    :disabled="disabled || loading"
    :style="{ width: width }"
    :type="type"
    @click="handleClick"
    v-on="on"
  >
    <slot v-if="customIcon" name="icon-elem">
      <img :src="customIcon" class="custom-icon" :alt="alt" />
    </slot>
    <v-progress-circular
      v-else-if="loading"
      :class="['loader', `loader--${variant}`, `loader--${size}`]"
      indeterminate
    />
    <span v-else class="btn-container">
      <span v-if="prependIcon" :style="`padding-right: ${calculatedPadding}`">
        <v-icon class="icon">{{ prependIcon }}</v-icon>
      </span>
      <span>
        {{ size === 'large' ? uppercase : title }}
      </span>
      <span v-if="appendIcon" :style="`padding-left: ${calculatedPadding}`">
        <v-icon class="icon">{{ appendIcon }}</v-icon>
      </span>
    </span>
  </button>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';

type Variants = 'default' | 'primary' | 'success' | 'error';

type Sizes = 'large' | 'medium' | 'small' | 'micro' | 'pico';

type Types = 'submit' | 'rest' | 'button';

@Component({
  name: 'button-component-new',
})
export default class DefaultButtonNew extends Vue {
  @Prop({ type: String, default: 'medium' }) size!: Sizes;
  @Prop({ type: String, default: 'default' }) variant!: Variants;
  @Prop({ type: String, default: 'button' }) type!: Types;
  @Prop({ type: String, default: 'Button' }) title!: string;
  @Prop({}) attrs!: string;
  @Prop({}) on!: string;
  @Prop(String) width!: string;
  @Prop(String) prependIcon!: string;
  @Prop(String) appendIcon!: string;
  @Prop(Boolean) disabled!: boolean;
  @Prop(Boolean) depressed!: boolean;
  @Prop(Boolean) loading!: boolean;
  @Prop(String) customIcon!: string;
  @Prop(String) alt!: string;

  get uppercase(): string {
    return this.title.toUpperCase();
  }

  get calculatedPadding(): string {
    switch (this.size) {
      case 'large':
        return '24px';
      case 'small':
        return '22px';
      case 'micro':
        return '16px';
      default:
        return '5px';
    }
  }

  handleClick(event: MouseEvent): void {
    this.$emit('click', event);
  }
}
</script>

<style scoped lang="scss">
@import '@/assets/styles/_variables.scss';

//@import "./_buttons.scss";

.icon {
  color: inherit;
  font-size: inherit;
  font-style: inherit;
}

.custom-icon {
  width: 16px;
  height: 16px;
  filter: brightness(0) invert(1);
}

.btn {
  background-color: $white-color;
  color: $medium-grey-color;
  padding: 0 16px !important;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  border-radius: 4px;
  margin-right: 8px;
  border: 1px solid $input-border-color;
  height: 30px;
  line-height: normal;
  box-shadow: none;
  cursor: pointer;
  outline: none;

  &:last-child {
    margin-right: 0;
  }

  &:hover {
    box-shadow: 0 0 5px rgba($black-color, 0.5);
  }

  &--primary {
    border-color: $button-primary-background;
    background-color: $button-primary-background;
    color: $white-color;

    &:disabled {
      border-color: $input-border-color;
    }
  }
}
</style>
