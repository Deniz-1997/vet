<template>
  <div
    :style="{ width }"
  >
    <div v-if="label">
      <label-component
        :label="label"
        :show-icon="isRequired"
      />
    </div>
    <v-file-input
      v-model="innerValue"
      @click:clear="handleClear"
      :clearable="clearable"
      :class="variant"
      :placeholder="placeholder"
      :multiple="isMultiple"
      :prepend-icon="prependIcon"
      :hide-details="hideDetails"
      :disabled="disabled"
      :error-messages="errorMessage"
      :error="isError"
    />
  </div>
</template>

<script lang="ts">
import { Component, Model, Prop, Vue } from 'vue-property-decorator';
import LabelComponent from '@/components/common/Label/Label.vue';

type Variants = 'micro' | 'small';

@Component({
  name: 'upload-file-component',
  components: {
    LabelComponent,
  },
})

export default class UploadFileComponent extends Vue {
  @Model('change', { type: Array }) readonly value!: File[];

  @Prop({ type: String, default: 'Данные отсутствуют' }) readonly noDataText!: string;
  @Prop({ type: String, default: 'auto' }) readonly width!: string;
  @Prop({ type: Boolean, default: false }) readonly isMultiple!: boolean;
  @Prop({ type: Boolean, default: true }) readonly clearable!: boolean;
  @Prop({ type: String, default: 'small' }) readonly variant!: Variants;
  @Prop({ type: String, default: 'mdi-arrow-collapse-up' }) readonly prependIcon!: string;
  @Prop({ type: [String, Array], default: '' }) errorMessage!: string | string[];
  @Prop(Boolean) readonly disabled!: boolean;
  @Prop(Boolean) readonly isRequired!: boolean;
  @Prop(Boolean) readonly isError!: boolean;
  @Prop(Boolean) readonly hideDetails!: boolean;
  @Prop(String) readonly placeholder!: string;
  @Prop(String) readonly label!: string;

  get innerValue(): File[] {
    return this.value;
  }

  set innerValue(files: File[]) {
    this.$emit('change', files);
  }

  handleClear(): void {
    this.$emit('change', []);
  }
}
</script>

<style lang="scss">
  @import "@/assets/styles/_variables.scss";

  .v-file-input {
    margin: 0;
    padding: 0;

    &.small {
      font-size: 22px;
    }

    &.micro {
      font-size: 14px;
    }

    .v-input__slot {
      border: 0;

      &:before,
      &:after {
        display: none;
      }

      .v-input__prepend-outer {
        align-self: center;
        display: flex;
        flex-direction: column;
      }

      .v-file-input__text {
        color: map-get($theme-colors, "medium");
        cursor: pointer;
        text-decoration: underline;
      }
    }
  }
</style>
