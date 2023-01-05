<template>
  <div class="mb-2">
    <text-component :class="['label', error && 'error', classnames]">
      {{ label }}
    </text-component>
    <fa-icon v-show="showIcon" class="icon" scale="1" :name="icon" />
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import TextComponent from '@/components/common/TextComponent.vue';

@Component({
  name: 'label-component',
  components: {
    TextComponent,
  },
})
export default class Label extends Vue {
  @Prop(Boolean) showIcon!: boolean;
  @Prop(String) label!: string;
  @Prop(Boolean) error!: boolean;
  @Prop({ type: String, default: '' }) classnames!: string;
  @Prop({ type: String, default: 'asterisk' }) icon!: string;
}
</script>

<style scoped lang="scss">
@import '@/assets/styles/_variables';

.label {
  color: $medium-grey-color;
  font-size: 14px;
  font-weight: normal;
  line-height: 16px;
  margin-bottom: 5px;

  &.error {
    background-color: transparent !important;
    border-color: transparent !important;
    color: $error-color;
  }
}

.icon {
  vertical-align: super;
  width: auto;
  height: 7px;
  max-width: 100%;
  max-height: 100%;
  fill: $error-color;
}
</style>
