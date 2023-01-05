<template>
  <div>
    <v-tooltip top max-width="500" open-delay="500">
      <template #activator="{ on, activatorAttrs }">
        <span v-if="placeholder" v-bind="activatorAttrs" class="hint_span" v-on="on">
          <AutocompleteComponent
            v-model="innerValue"
            :items="items"
            :label="label"
            :no-data-text="noDataText"
            :item-value="itemValue"
            :item-text="itemText"
            :is-disabled="isDisabled"
            :clearable="clereables"
            :placeholder="placeholder"
            :item-disabled="items"
            :return-object="returnObject"
            @onChange="searchString = ''"
          />
        </span>
      </template>
      {{ placeholder }}
    </v-tooltip>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Model } from 'vue-property-decorator';
import isEmpty from 'lodash/isEmpty';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';

@Component({
  name: 'QuallityDictionary',
  components: { AutocompleteComponent },
})
export default class AutocompleteTooltipComponent extends Vue {
  @Model('change', { type: [Object, Array, Number], required: true }) readonly value!: any;
  @Prop({ type: [Object, Array, Number], required: true }) readonly items!: any;
  @Prop({ type: String, required: true }) readonly label!: string;
  @Prop({ type: String, required: true }) readonly itemValue!: string;
  @Prop({ type: String, required: true }) readonly itemText!: string;
  @Prop({ type: String, required: false }) readonly noDataText!: string;
  @Prop({ type: Boolean, default: false }) readonly includingClosed!: boolean;
  @Prop({ type: Boolean }) readonly readonly!: boolean;
  @Prop({ type: Boolean }) readonly multi!: boolean;
  @Prop({ type: String, required: false, default: '' }) readonly placeholder!: string;

  @Prop({ type: Boolean, required: false, default: false }) readonly clereables!: boolean;

  @Prop({ type: Boolean, required: false, default: false }) readonly isDisabled!: boolean;
  @Prop({ type: Boolean, required: false, default: true }) readonly returnObject!: boolean;
  searchString = '';
  temporaryValue: any = null;

  get innerValue() {
    if (this.value) {
      this.temporaryValue = { ...this.value };
    }
    return this.value;
  }

  set innerValue(v: any) {
    this.temporaryValue = v;
    this.$emit('change', !isEmpty(v) ? v : null);
  }

  beforeMount() {
    if (this.value) {
      this.items.push(this.value);
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';

.organization-picker {
  &__caption {
    font-size: 11px;
    color: $medium-grey-color;
  }

  &__text {
    font-size: 13px;
  }
}
</style>
