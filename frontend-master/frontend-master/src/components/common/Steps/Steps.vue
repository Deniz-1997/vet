<template>
  <div class="step">
    <div
      v-for="(item, index) in steps"
      :key="index"
      class="step__item"
      :class="[{ 'step__item--active': item.isActive }, { 'step__item--complete': item.isComplete }]"
      @click="$emit('selectStep', index)"
    >
      <div
        class="step__index"
        :class="[{ 'step__index--complete': item.isComplete }, { 'step__index--active': item.isActive }]"
      >
        {{ index + 1 }}
      </div>
      <div class="step__name">{{ item.name }}</div>
      <div v-if="index !== steps.length - 1" class="icon">
        <svg width="24" height="8" viewBox="0 0 24 8" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M19.8293 0.123719C19.6214 -0.0581225 19.3056 -0.0370638 19.1237 0.170755C18.9419 0.378573 18.9629 0.694454 19.1707 0.876295L22.1693 3.50001H0V4.50001H22.1693L19.1707 7.12372C18.9629 7.30556 18.9419 7.62144 19.1237 7.82926C19.3056 8.03708 19.6214 8.05814 19.8293 7.8763L23.8293 4.3763C23.9378 4.28135 24 4.14419 24 4.00001C24 3.85583 23.9378 3.71866 23.8293 3.62372L19.8293 0.123719Z"
            fill="#828286"
          />
        </svg>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';

@Component({
  name: 'Step',
  components: {},
})
export default class Step extends Vue {
  @Prop({ type: Array }) readonly steps;
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';
.step {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  margin: 10px 0 0;

  &__item {
    margin-bottom: 10px;
    font-weight: 400;
    font-size: 13px;
    line-height: 16px;
    color: $input-border-color;
    display: flex;
    align-items: center;
    cursor: default;

    &--complete {
      color: $gold-dark-color;
    }

    &--active {
      color: $black-color;
    }

    &--complete:not(.step__item--active),
    &--complete + .step__item:not(.step__item--active) {
      cursor: pointer;
    }
  }

  &__index {
    font-weight: 500;
    font-size: 11px;
    line-height: 16px;
    height: 18px;
    width: 18px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: $white-color;
    background: $input-border-color;
    margin-right: 8px;

    &--complete {
      background: $gold-dark-color;
    }

    &--active {
      background: $black-color;
    }
  }

  .icon {
    margin: 0 14px;
  }
}
</style>
