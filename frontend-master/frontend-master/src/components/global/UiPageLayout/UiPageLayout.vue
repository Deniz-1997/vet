<template>
  <component :is="component">
    <slot />
  </component>
</template>

<script lang="ts">
import get from 'lodash/get';
import { Component, Vue } from 'vue-property-decorator';
import layouts, { TLayoutType } from '@/layouts';

@Component({
  name: 'UiPageLayout',
  metaInfo(this: UiPageLayout) {
    return { title: this.title };
  },
})
export default class UiPageLayout extends Vue {
  layout = '';

  get component() {
    return layouts[this.layout] || 'div';
  }

  private get title() {
    const list = this.$route.meta?.breadcrumb ?? [];
    return [...list].pop()?.name ?? this.defaultTitle;
  }

  private get defaultTitle(): string {
    const { prefix, name } = this.$store.getters['auth/title'];
    return prefix ? `${prefix} ${name}` : name;
  }

  created() {
    this.findLayout();
    this.$root.$router.afterEach(this.findLayout);
  }

  findLayout() {
    Vue.nextTick(() => {
      const [match] = this.$route.matched;
      if (match) {
        const layout = Object.keys(match.components).reduce<TLayoutType>((res: TLayoutType, key) => {
          return get(match, `components.${key}.options.layout`, res);
        }, 'default');

        this.layout = layouts[layout] ? layout : 'default';
      }
    });
  }
}

export { layouts };
</script>
