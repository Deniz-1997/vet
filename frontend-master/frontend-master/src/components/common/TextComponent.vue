<script lang="ts">
import Vue, { PropType } from 'vue';

type Variant = 'h2' | 'h3' | 'h4' | 'h5' | 'span' | 'p'

export default Vue.extend({
  name: 'TextComponent',
  props: {
    variant: {
      type: String as PropType<Variant>,
      default: 'span',
    },
  },
  render: function (createElement) {
    return createElement(
      this.variant,
      {
        attrs: this.$attrs,
        on: this.$listeners,
      },
      this.$slots.default
    );
  },
});
</script>

<style scoped lang="scss">
  // @import "@/assets/styles/_variables.scss";

  // h2,
  // h3,
  // h4,
  // h5,
  // span {
  //   color: map-get($theme-colors, "black");
  // }

  h2 {
    font-size: 46px;
    font-weight: normal;
    line-height: 56px;
  }

  h3 {
    font-size: 34px;
    font-weight: 500;
    line-height: 40px;
  }

  h4 {
    font-size: 24px;
    font-weight: 500;
    line-height: 32px;;
  }

  h5 {
    font-size: 16px;
    font-weight: normal;
    line-height: 24px;
  }

  .base-prime {
    font-size: 22px;
    font-weight: normal;
    line-height: 32px;
  }

  .base-prime-bold {
    font-size: 22px;
    font-weight: 600;
    line-height: 32px;
  }

  .base-small {
    font-size: 14px;
    font-weight: normal;
    line-height: 16px;
  }

  .base-medium {
    font-size: 16px;
    font-weight: normal;
    line-height: 24px;
  }

  .caps-small {
    font-size: 13px;
    font-weight: bold;
    line-height: 16px;
    text-transform: uppercase;
  }

  // .gray {
  //   color: map-get($theme-colors, "medium");
  // }

  // .gold {
  //   color: map-get($theme-colors, "anchor");
  // }
</style>
