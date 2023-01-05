<template>
  <div class="wrapper">
    <v-progress-linear
      v-if="loading"
      color="#d19b3f"
      indeterminate
    />
    <v-tabs
      v-else-if="tabs && tabs.length > 0"
      @change="handleTabChange"
      :value="currentTabIndex"
      class="tabs"
    >
      <v-tab
        v-for="(tab, index) in tabs"
        :disabled="tabLoading || tab.disabled"
        :value="tab.value"
        :key="index"
      >
        <text-component class="caps-small">
          {{ tab.name }}
        </text-component>
      </v-tab>
    </v-tabs>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator';
import { CustomTab, BaseTab } from './Tabs.types';
import TextComponent from '@/components/common/Text/Text.vue';

@Component({
  name: 'tabs',
  components: {
    TextComponent,
  },
})
export default class Tabs extends Vue {
  @Prop({ type: Number, default: 0 }) currentTabIndex!: number;
  @Prop({ type: Function, default: () => undefined }) onChange!: (value: unknown, currentTabIndex: number) => void;
  @Prop(Array) tabs!: CustomTab[] | BaseTab[];
  @Prop(Boolean) loading!: boolean;
  @Prop(Boolean) tabLoading!: boolean;

  handleTabChange(currentTabIndex: number): void {
    this.onChange(this.tabs[currentTabIndex], currentTabIndex);
  }

  @Watch('currentTabIndex')
  handleCurrentTabIndexChange(currentTabIndex: number): void {
    this.onChange(this.tabs[currentTabIndex], currentTabIndex);
  }
}
</script>

<style scoped lang="scss">
  @import "@/assets/styles/_variables.scss";

  .wrapper {
    align-items: center;
    display: flex;
    min-height: 35px;
  }

  .tabs::v-deep {

    .v-tab {
      font-size: 13px;
      font-weight: bold;
      line-height: 16px;
      margin-right: 25px;
      min-width: auto;
      padding: 0;

      &:before {
        background-color: unset;
      }
    }

    &:not(.v-tabs--vertical) .v-tab {
      white-space: nowrap;
    }

    .v-tab--active span {
      color: $medium-grey-color !important;
    }

    .v-tab span {
      color: $footer-color;
    }

    .v-slide-group__content {
      border-bottom: 1px solid $light-grey-color;
    }

    .v-tabs-slider-wrapper {
      bottom: -1px;
    }

    .v-tabs-slider {
      background-color: $medium-grey-color;
    }

    .v-slide-group__prev {
      display: none !important;
    }
  }
</style>
