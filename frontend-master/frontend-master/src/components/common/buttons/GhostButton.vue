<template>
  <button-component
    @click="handleClick"
    :append-icon="appendIcon"
    :disabled="disabled"
    :prepend-icon="prependIcon"
    :size="size"
    :title="title"
    :type="type"
    :variant="variant"
    :width="width"
    class="button ghost-button"
  />
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import ButtonComponent from './DefaultButton.vue';

@Component({
  name: 'ghost-button',
  components: {
    ButtonComponent,
  },
})
export default class GhostButton extends ButtonComponent {
  handleClick(event: Event): void {
    this.$emit('click', event);
  }
}
</script>

<style scoped lang="scss">
  @import "@/assets/styles/_variables.scss";

  @import "./_buttons.scss";

  $medium: map-get($theme-colors, "medium");

  .ghost-button::v-deep {
    background-color: transparent !important;
    padding-left: 0 !important;
    padding-right: 0 !important;

    i {
      color: $medium !important;
      font-size: 18px !important;
    }
  }
</style>
