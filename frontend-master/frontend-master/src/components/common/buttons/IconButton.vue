<template>
  <v-btn
    @click="$emit('click')"
    :class="`icon-${type}`"
    :disabled="disabled"
    text
  >
    <div
      v-if="hasPrependIconSlot"
      class="mr-1"
    >
      <slot name="prependIcon" />
    </div>
    <v-icon
      v-else-if="icon"
      :size="iconSize"
      left
      dark
    >
      {{ icon }}
    </v-icon>
    <span class="text">{{ text }}</span>
  </v-btn>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';

type IconSizes = 'x-small' | 'small' | 'medium' | 'large' | 'x-large';

type Types = 'btn' | 'text';

@Component({
  name: 'icon-button',
})
export default class IconButton extends Vue {
  @Prop(Boolean) disabled!: boolean;
  @Prop({ type: String, default: 'btn' }) type!: Types;
  @Prop({ type: String, default: 'medium' }) iconSize!: IconSizes;
  @Prop({ type: String, default: '' }) icon!: string;
  @Prop({ type: String, default: '' }) text!: string;

  get hasPrependIconSlot(): boolean {
    return !!this.$slots.prependIcon;
  }
}
</script>

<style scoped lang="scss">
  @import "@/assets/styles/_variables.scss";

  $medium: map-get($theme-colors, "medium");
  $primary: map-get($theme-colors, "primary");
  $success: map-get($theme-colors, "success");

  .icon-text::v-deep {
    height: auto !important;
    padding: 0 3px !important;

    .v-icon {
      color: $primary;
      margin-right: 4px;
      text-decoration: none;
    }

    .v-btn__content {
      align-items: start;
      justify-content: start;
    }

    .text {
      border-bottom: 1px solid $medium;
      color: $medium;
      text-transform: initial;
    }
  }

  .icon-btn::v-deep {

    &:before {
      background-color: transparent;
      opacity: 0;
    }

    .v-ripple__container {
      display: none;
    }

    .v-icon {
      color: $primary !important;
      margin-bottom: -3px;
      margin-left: 0;
      text-decoration: none;
    }

    .text {
      color: $medium;
      font-size: 14px;
      line-height: 16px;
      text-decoration: underline;
      text-transform: initial;
    }

    &.icon-btn--success .v-icon {
      color: map-get($theme-colors, "success");
    }
  }
</style>
