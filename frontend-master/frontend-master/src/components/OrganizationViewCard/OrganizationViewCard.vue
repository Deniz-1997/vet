<template>
  <v-container>
    <div v-if="!loading && form" class="cardOrganization">
      <div class="card">
        <ViewCardHeader :params="form" :permit-edit="permitEdit" @edit="$emit('edit')" @close="$emit('close')">
          <template #title><slot name="title" /></template>
          <template #actions><slot name="actions" /></template>
        </ViewCardHeader>
        <ViewCardTabs :tabs="tabs" :form="form">
          <template v-for="item in tabs" #[item.value]="data">
            <slot :name="item.value" v-bind="data" />
          </template>
        </ViewCardTabs>
      </div>
    </div>

    <slot />

    <v-overlay :value="loading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';
import ViewCardHeader from '@/components/OrganizationViewCard/components/ViewCardHeader.vue';
import ViewCardTabs from '@/components/OrganizationViewCard/components/ViewCardTabs.vue';

type TabItem = {
  value: string;
  name?: string;
  component?: Vue.Component;
  class?: string;
};

@Component({
  name: 'OrganizationViewCard',
  components: {
    ViewCardHeader,
    ViewCardTabs,
  },
})
export default class OrganizationViewCard extends Vue {
  @Prop({ type: Array, default: () => [] }) readonly tabs!: TabItem[];
  @Prop({ type: Object, default: () => null }) readonly form!: any;
  @Prop({ type: Boolean, default: true }) readonly loading!: boolean;
  @Prop({ type: Boolean, default: () => null }) readonly permitEdit!: boolean | null;
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.checkbox-block {
  align-items: center;
  // height: 61px;
  display: flex;
  margin-bottom: 28px;

  .label {
    margin-bottom: 0;
    margin-left: 5px;
    color: #828286;
    font-size: 14px;
    font-weight: normal;
    line-height: 16px;
  }
}

.checkbox {
  cursor: default;
  width: 16px;
  height: 16px;
  position: relative;

  [type='checkbox'] {
    position: absolute;
    opacity: 0;
  }

  &__icon {
    align-items: center;
    justify-content: center;
    background: $check-bg;
    display: flex;
    height: 16px;
    width: 16px;
    border: 1px solid $input-border-color;
    border-radius: 4px;

    img {
      width: 9px;
      display: block;
      opacity: 0;
    }
  }

  [type='checkbox']:checked {
    & + .checkbox__icon {
      background: $gold-light-color;
      border-color: $gold-light-color;

      img {
        opacity: 1;
      }
    }
  }
}

.checkbox-block {
  margin-bottom: 0;
}

.manufacturers-btn {
  padding-right: 12px;
}

.btn-block {
  margin-top: 24px;
}

.card {
  padding-right: 20px;
}
</style>
