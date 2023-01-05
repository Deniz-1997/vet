<script lang="ts">
import Vue, { CreateElement, PropType } from 'vue';

type Variant = 'h2' | 'h3' | 'h4' | 'h5' | 'span' | 'p' | 'a';

export default Vue.extend({
  name: 'TextComponent',
  props: {
    variant: {
      type: String as PropType<Variant>,
      default: 'span',
    },
    href: {
      type: String,
      default: null,
    },
  },
  render: function (createElement: CreateElement) {
    const { href } = this;

    return createElement(
      this.variant,
      {
        attrs: { href },
        class: 'text',
      },
      this.$slots.default
    );
  },
});
</script>

<style scoped lang="scss">
  // @import "@/assets/styles/_variables.scss";

  // $black: map-get($map: $theme-colors, $key: "black");
  // $bright: map-get($map: $theme-colors, $key: "bright");
  // $dark: map-get($map: $theme-colors, $key: "dark");
  // $error: map-get($map: $theme-colors, $key: "error");
  // $gold: map-get($map: $theme-colors, $key: "primary");
  // $medium: map-get($map: $theme-colors, $key: "medium");
  // $primary: map-get($map: $theme-colors, $key: "primary");
  // $white: map-get($map: $theme-colors, $key: "white");

  // $colors: (
  //   "black": $black,
  //   "dark": $dark,
  //   "error": $error,
  //   "gold": $gold,
  //   "medium": $medium,
  //   "primary": $primary,
  //   "white": $white,
  // );

  // .text {
  //   @each $name, $color in $colors {
  //     &-#{$name} {
  //       color: $color;
  //     }
  //   }
  // }

  .pointer {
    cursor: pointer
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

  .base-medium {
    font-size: 18px;
    font-weight: normal;
    line-height: 24px;
  }

  .base-medium-bold {
    font-size: 18px;
    font-weight: 600;
    line-height: 24px;
  }

  .base-micro {
    font-size: 12px;
    font-weight: normal;
    line-height: 14px;
  }

  .base-small {
    font-size: 14px;
    font-weight: normal;
    line-height: 16px;
  }

  .base-small-bold {
    font-size: 14px;
    font-weight: 600;
    line-height: 16px;
  }

  .caps-small {
    font-size: 13px;
    font-weight: bold;
    line-height: 16px;
    text-transform: uppercase;
  }

  .sub-title-small {
    font-size: 13px;
    font-weight: bold;
    text-transform: uppercase;
  }

  // h2,
  // h3,
  // h4,
  // h5,
  // span {
  //   color: $black;
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
    line-height: 32px;
  }

  h5 {
    font-size: 16px;
    font-weight: normal;
    line-height: 24px;
  }
</style>
