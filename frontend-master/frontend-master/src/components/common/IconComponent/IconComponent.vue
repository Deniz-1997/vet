<template>
  <svg
    v-bind="$attrs"
    :height="height"
    :width="width"
    :viewBox="viewbox"
    role="presentation"
    fill-rule="evenodd"
    clip-rule="evenodd"
    ref="svg"
    class="svg"
    xmlns="http://www.w3.org/2000/svg"
    v-on="$listeners"
  >
    <title :id="iconName" lang="en" />
    <g :fill="iconColor">
      <slot />
    </g>
  </svg>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';

@Component({
  name: 'icon-component',
})
export default class IconComponent extends Vue {
  @Prop({ default: 18, type: [Number, String] }) readonly width!: number | string;
  @Prop({ default: 18, type: [Number, String] }) readonly height!: number | string;
  @Prop({ default: 'box', type: String }) readonly iconName!: string;
  @Prop({ default: '#d19b3f', type: String }) readonly iconColor!: string;

  viewbox = '0 0 15 15';

  mounted(): void {
    const getViewbox = () => {
      if (this.$refs.svg) {
        const { x, y, width, height } = (this.$refs.svg as SVGGraphicsElement).getBBox();

        if (width || height) {
          this.viewbox = `${x} ${y} ${width} ${height}`;

          clearTimeout(timeoutId);
        } else {
          timeoutId = setTimeout(getViewbox, 500);
        }
      }
    };
    let timeoutId = setTimeout(getViewbox);
  }
}
</script>

<style lang="scss">
.svg {
  cursor: pointer;
  display: inline-block;
  vertical-align: baseline;
}
</style>
