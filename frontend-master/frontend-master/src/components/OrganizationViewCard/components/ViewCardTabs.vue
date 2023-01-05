<template>
  <div>
    <v-row>
      <v-col cols="12">
        <div class="containerTabs">
          <div class="tabs">
            <div
              v-for="item in innerTabs"
              :key="item.value"
              :class="[{ active: tab === item.value }, 'tab']"
              @click="clickTab(item.value)"
            >
              <span>{{ item.name }}</span>
            </div>
          </div>
        </div>
      </v-col>
    </v-row>

    <slot v-for="item in tabs" :name="item.value" v-bind="item">
      <div v-if="item.component && tab === item.value" :key="item.value" :class="item.class">
        <component :is="item.component" :form="item.mapper ? item.mapper(form) : form" />
      </div>
    </slot>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';

type TabItem = {
  value: string;
  name?: string;
  component?: Vue.Component;
  class?: string;
  mapper?: (payload: any) => any;
  enable?: boolean | ((payload: any) => boolean);
};

@Component({
  name: 'ViewCardTabs',
})
export default class ViewCardTabs extends Vue {
  @Prop({ type: Array, default: () => [] }) readonly tabs!: TabItem[];
  @Prop({ type: Object, default: () => null }) readonly form!: any;

  tab = this.tabs[0]?.value;

  get innerTabs() {
    return this.tabs.filter((item) => {
      if ('enable' in item) {
        return this.getEnable(item.enable as any);
      }

      return true;
    });
  }

  clickTab(tab: string) {
    this.tab = tab;
  }

  getEnable(enable: boolean | ((form: any) => boolean)) {
    return typeof enable === 'function' ? enable(this.form) : enable;
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.containerTabs {
  margin-top: 30px;
  padding-bottom: 20px;
}

.tabs {
  border-bottom: 1px solid $light-grey-color;
  width: 100%;
  display: flex;
  flex-direction: row;
}

.tab {
  display: flex;
  justify-content: center;
  font-weight: 400;
  line-height: 16px;
  font-size: 16px;
  color: $footer-color;
  cursor: pointer;
  padding-bottom: 8px;
  margin-right: 18px;

  &.active {
    color: $gold-light-color;
    border-bottom: 1px solid $gold-light-color;
  }
}
</style>
