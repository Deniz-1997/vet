<template>
  <v-col :class="[{ hideTabs: !detail, 'mt-2': !detail, 'col-12': !detail }]">
    <v-row>
      <v-col cols="12">
        <div class="containerTabs">
          <div :class="['tabs']">
            <div v-for="(value, index) in tabList" :key="index">
              <div
                :class="[{ active: model === value.type, 'tab--disabled': edit || value.isDisabled }, 'tab']"
                @click="onSelectTab(value)"
              >
                {{ value.name }}
              </div>
            </div>
          </div>
        </div>
      </v-col>
    </v-row>
  </v-col>
</template>

<script lang="ts">
import { Component, Model, Prop, Vue } from 'vue-property-decorator';

@Component({
  name: 'tab-Component',
  components: {},
})
export default class TabComponent extends Vue {
  @Model('change', { type: Number, required: true }) value!: string;
  @Prop({ type: Boolean, default: false }) edit!: boolean;
  @Prop({ type: Boolean, default: false }) detail!: boolean;
  @Prop({ type: Array, default: () => [] }) tabList!: [];

  get model(): any {
    return this.value;
  }

  set model(value: any) {
    this.$emit('change', value);
  }

  onSelectTab(tab) {
    if (!this.edit && !tab.isDisabled) {
      this.model = tab.type;
    }
  }
}
</script>

<style lang="scss" scoped>
@import './src/assets/styles/_variables';
@import './src/assets/styles/_mixins';

.tabs {
  border-bottom: 1px solid $light-grey-color;
  width: 100%;
  display: flex;
  flex-direction: row;
  text-transform: uppercase;
}

.tab {
  display: flex;
  font-weight: bold;
  font-size: 13px;
  color: $footer-color;
  cursor: pointer;
  padding-bottom: 8px;
  margin-right: 18px;

  &.active {
    color: $gold-light-color;
    border-bottom: 1px solid $gold-light-color;
  }

  &--disabled {
    cursor: not-allowed;
    pointer-events: none;
    opacity: 0.4;
    color: $button-disabled-color;

    &.active {
      border-bottom: 1px solid $button-disabled-color;
    }
  }
}
</style>
