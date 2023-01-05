<template>
  <button
    ref="button"
    v-bind="attrs"
    :class="['btn', `btn--${variant}`, `btn--${size}`]"
    :disabled="disabled || loading"
    :style="{ width: width }"
    :type="type"
    @click="handleClick"
    v-on="on"
  >
    <slot v-if="customIcon" name="icon-elem">
      <img v-if="!isSvg" :src="customIcon" class="custom-icon" :alt="alt" />
      <v-icon v-else class="icon">{{ customIcon }}</v-icon>
    </slot>
    <span :class="['btn-container', loading && '-hidden']">
      <span class="prepend-custom-icon">
        <slot name="prependCustomIcon" />
      </span>
      <span v-if="prependIcon" class="icon" :style="`padding-right: ${calculatedPadding}`">
        <v-icon class="icon">{{ prependIcon }}</v-icon>
      </span>
      <span v-if="title">
        {{ size === 'large' ? uppercase : title }}
      </span>
      <span v-if="appendIcon" :style="`padding-left: ${calculatedPadding}`">
        <v-icon class="icon">{{ appendIcon }}</v-icon>
      </span>
      <span class="append-custom-icon">
        <slot name="appendCustomIcon" />
      </span>
    </span>
    <v-progress-circular v-if="loading" :class="['loader', `loader--${variant}`, `loader--${size}`]" indeterminate />
  </button>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';

type Variants = 'default' | 'primary' | 'success' | 'error';

type Sizes = 'large' | 'medium' | 'small' | 'micro' | 'pico';

type Types = 'submit' | 'rest' | 'button';

@Component({
  name: 'button-component',
})
export default class DefaultButton extends Vue {
  @Prop({ type: String, default: 'medium' }) size!: Sizes;
  @Prop({ type: String, default: 'default' }) variant!: Variants;
  @Prop({ type: String, default: 'button' }) type!: Types;
  @Prop({ type: String, default: '' }) title!: string;
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
  @Prop({ type: Boolean, default: false }) isSvg!: boolean;

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
        return '14px';
    }
  }

  handleClick(event: MouseEvent): void {
    const button = this.$refs.button as HTMLElement;
    button.blur();
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

.prepend-custom-icon,
.append-custom-icon {
  display: flex;
}

.prepend-custom-icon .svg {
  margin-right: 10px;
}

.append-custom-icon .svg {
  margin-left: 10px;
}

.loader {
  position: absolute;
  top: 50%;
  left: 50%;
  margin-top: -15px;
  margin-left: -15px;
}

.btn {
  position: relative;
  background-color: $white-color;
  color: $medium-grey-color;
  padding: 0 16px !important;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  border-radius: 4px;
  margin-right: 15px;
  border: 1px solid $input-border-color;
  height: 30px;
  line-height: normal;
  box-shadow: none;
  cursor: pointer;
  outline: none;

  &:last-child {
    margin-right: 0;
  }

  &--active {
    color: $gold-dark-color;
    border-color: $gold-dark-color;

    .v-icon {
      color: $gold-dark-color;
    }
  }

  .btn-container {
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 1;

    &.-hidden {
      opacity: 0;
    }
  }

  .icon {
    display: flex;
    align-items: center;
  }

  &:hover {
    background-color: $button-primary-background;
    color: $white-color;
    box-shadow: 0 0 5px rgba($black-color, 0.5);
  }

  &:focus {
    background-color: $white-color;
    color: $medium-grey-color;
  }

  &--primary {
    border-color: $button-primary-background;
    background-color: $button-primary-background;
    color: $white-color;

    .icon {
      color: $white-color;
    }

    &:disabled {
      border-color: $input-border-color;
    }
  }
}
</style>
