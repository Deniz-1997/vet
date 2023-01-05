<template>
  <div
    class="scroll-wrapper"
    ref="wrapper"
  >
    <div
      v-if="hasScroll && hideArrows"
    >
      <v-btn
        @click="scrollLeft"
        :style="{
          top: `${targetOffsetTop}px`
        }"
        class="scroll-btn scroll-btn_left"
        depressed
        fab
      >
        <v-icon
          class="chevron chevron_left"
          :size="35"
        >
          mdi-chevron-left
        </v-icon>
      </v-btn>
      <v-btn
        @click="scrollRight"
        :style="{
          top: `${targetOffsetTop}px`
        }"
        class="scroll-btn scroll-btn_right"
        depressed
        fab
      >
        <v-icon
          class="chevron chevron_right"
          :size="35"
        >
          mdi-chevron-right
        </v-icon>
      </v-btn>
    </div>
    <slot />
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator';

/**
 * @description target itself or target children need to have min-width
 * to activate scroll controls
 */
@Component({
  name: 'horizontal-scroll-new',
})
export default class HorizontalScrollNew extends Vue {
  @Prop({ type: Number, default: 200 }) readonly scrollStep!: number;
  @Prop(Boolean) readonly loading!: boolean;
  @Prop(Boolean) readonly hideArrows!: boolean;
  @Prop(String) readonly scrollTarget!: string;

  wrapper: HTMLElement | null = null;
  targets: HTMLElement[] = [];

  get targetHeight(): number {
    return this.currentTarget?.offsetHeight ?? 0;
  }

  get targetOffsetTop(): number {
    if (!this.currentTarget || !this.wrapper) {
      return 0;
    }

    const targetRects = this.getDOMRects(this.currentTarget);
    const wrapperRects = this.getDOMRects(this.wrapper);

    return (targetRects.y - wrapperRects.y) + this.targetHeight / 2;
  }

  get hasScroll(): boolean {
    const { currentTarget } = this;

    if (!currentTarget) {
      return false;
    }

    return currentTarget.scrollWidth > currentTarget.clientWidth;
  }

  get currentTarget(): HTMLElement | undefined {
    return this.targets.find((target) => this.wrapper?.contains(target));
  }

  scrollLeft(): void {
    const { scrollStep } = this;

    this.currentTarget && this.currentTarget.scrollBy({ left: -scrollStep, behavior: 'smooth' });
  }

  scrollRight(): void {
    const { scrollStep } = this;

    this.currentTarget && this.currentTarget.scrollBy({ left: scrollStep, behavior: 'smooth' });
  }

  getDOMRects(element: HTMLElement): DOMRect {
    return element.getBoundingClientRect();
  }

  updateScrollNodes(): void {
    this.targets = [];
    this.wrapper = null;

    this.$nextTick(() => {
      this.targets = Array.from(document.querySelectorAll(this.scrollTarget));
      this.wrapper = this.$refs.wrapper as HTMLElement;
    });
  }

  mounted(): void {
    this.updateScrollNodes();
    window.addEventListener('resize', this.updateScrollNodes);
  }

  destroy(): void {
    window.removeEventListener('resize', this.updateScrollNodes);
  }

  @Watch('loading')
  onLoadingChange(loading: boolean): void {
    !loading && this.updateScrollNodes();
  }
}
</script>

<style scoped lang="scss">

  @import "@/assets/styles/_variables.scss";
  /*ToDO найти решение для проблемы с таблицами. Эта обертка не ограничивает ширину таблицы без max-width **/
  .scroll-wrapper {
    overflow: hidden;
    position: relative;
    // max-width: 78vw;
  }

  .scroll-btn {
    background-color: $input-border-color !important;
    cursor: pointer;
    height: 5px;
    position: absolute;
    width: 5px;

    &_left {
      left: 0;
      transform: translate(-50%, -50%);
    }

    &_right {
      right: 0;
      transform: translate(50%, -50%);
    }
  }

  .chevron {
    color: $white-color !important;
    position: absolute;
    top: 50%;

    &_left {
      right: 20px / 4;
      transform: translate(50%, -50%);
    }

    &_right {
      left: 20px / 4;
      transform: translate(-50%, -50%);
    }
  }
</style>

<style lang="scss">
@import '@/assets/styles/_mixins';
@import "@/assets/styles/_variables.scss";

.v-data-table__wrapper {
  &::-webkit-scrollbar {
    width: 5px !important;
    height: 5px !important;

    @include respond-to('medium') {
      width: 5px !important;
      height: 5px !important;
    }
  }

  &::-webkit-scrollbar-track {
    background: transparent;
  }

  &::-webkit-scrollbar-thumb {
    background: $input-border-color !important;
    border-radius: 25px;
  }

  &::-webkit-scrollbar-thumb:hover {
    background: $input-border-color !important;
  }

  &::-webkit-scrollbar-corner {
    background: transparent;
  }
}
</style>
