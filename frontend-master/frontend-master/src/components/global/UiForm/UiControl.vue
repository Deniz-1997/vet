<template>
  <component
    :is="tag"
    data-validator="control"
    :data-state="error ? 'error' : 'idle'"
    @change.once="isChanged = true"
    @submit="(evt) => $emit('submit', evt)"
    @keydown="(evt) => $emit('keydown', evt)"
  >
    <slot v-bind="{ name, errors }" />
    <slot name="error" :errors="errors">
      <div v-if="!hideMessage" class="validation-message">{{ error }}</div>
    </slot>
  </component>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator';
import UiFormVue from './UiForm.vue';

@Component({ name: 'UiControl' })
export default class extends Vue {
  @Prop({ type: [String, Number, Boolean, Object, Array, Date] }) readonly value!: any;
  @Prop({ type: String, default: 'label' }) readonly tag!: string;
  @Prop({ type: String }) readonly name!: string;
  /** Флаг для скрытия встроенного вывода текста ошибки. */
  @Prop({ type: Boolean, default: false }) readonly hideMessage!: boolean;

  /** Флаг первого взаимодействия с контролом. */
  protected isChanged = false;
  protected isFormSubmitted = false;
  protected errors: string[] = [];

  private get form() {
    return this.getForm(this.$parent as any);
  }

  protected get error() {
    return this.$showError ? this.errors[0] : '';
  }

  protected get $showError() {
    if (this.form) {
      if (this.form.validateOn === 'submit' && this.isFormSubmitted) {
        return true;
      }

      if (this.form.validateOn === 'input' && this.isChanged) {
        return true;
      }
    }

    return false;
  }

  created() {
    if (this.form) {
      this.form.$on('validation', () => {
        this.errors = this.form?.validator.errors.get(this.name) ?? [];
        this.isFormSubmitted = this.form?.isSubmitted ?? false;
      });

      this.form.update(this.name, this.value);
    }
  }

  beforeDestroy() {
    if (this.form) {
      this.form.destroy(this.name);
    }
  }

  @Watch('value', { deep: true })
  private _handleChange(value) {
    if (this.form) {
      this.form.update(this.name, value);
    }
  }

  private getForm(component: Vue): UiFormVue | null {
    if (component.$options.name !== 'UiForm' || (this.name || '').endsWith(component.$data.id)) {
      if (component.$parent) {
        return this.getForm(component.$parent);
      }

      return null;
    }

    return component as UiFormVue;
  }
}
</script>

<style lang="scss" scoped>
.validation-message {
  height: 12px;
  margin-bottom: 8px;
  margin-left: 2px;
  font-size: 10px;
  line-height: 16px;
  color: red;
}
</style>
