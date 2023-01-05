<template>
  <div :class="['table-row__wrapper', { 'table-row__wrapper_error': error || isError }]">
    <UiForm :rules="rules" :messages="messages" validate-on="input" class="tableListRow" @validation="onValidate">
      <span class="spanList">
        <img v-if="!isShowcase" src="/icons/cross.svg" class="iconTable" @click="$emit('reset')" />
      </span>
      <span
        v-for="col in options"
        :key="col.name"
        :style="typeof col.style === 'undefined' ? { width: col.width + 'px' } : col.style"
        class="spanList"
      >
        <UiControl :name="col.name" :value="getValidatingValue(value, col)" hide-message>
          <text-component
            v-if="['custom', 'text'].includes(col.controlType) || col.useRestrictions"
            v-html="getValue(value, col)"
          />
          <EditableCell
            v-else-if="col.name === '%LIST%'"
            :value="value"
            :options="col"
            :list="list"
            :full-value="value"
            :id-table="idTable"
            @input="(v) => Object.assign(value, v)"
          />
          <EditableCell
            v-else
            v-model="value[col.name]"
            :options="col"
            :list="list"
            :full-value="value"
            :id-table="idTable"
          />
        </UiControl>
      </span>
    </UiForm>
    <div v-if="error" class="validation-message">{{ error }}</div>
  </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from 'vue-property-decorator';
import get from 'lodash/get';
import EditableCell from './EditableCell.vue';
import TextComponent from '@/components/common/TextComponent.vue';
import { TFormValidationEvent } from '@/services/models/common';
import Validator from 'validatorjs';

type Options = {
  name: string;
  label: string;
  width: number;
  disabled: boolean;
  controlType?: 'input' | 'select' | 'date';
  mask?: string;
  validate?: (...args: any[]) => any;
};

type Props = {
  options: Options[];
  isShowcase?: boolean;
  value: any;
  isError: boolean;
  idTable?: string;
};

@Component({
  name: 'TableEditableRow',
  components: { TextComponent, EditableCell },
})
export default class TableEditableRow extends Vue {
  @Prop({
    type: Boolean,
    default: () => false,
  })
  readonly isShowcase: Props['isShowcase'] | undefined;
  @Prop({
    type: Boolean,
    default: () => false,
  })
  readonly isError: Props['isError'] | undefined;
  @Prop({
    type: Array,
    default: () => [],
  })
  readonly options: Props['options'] | undefined;
  @Prop({
    type: Object,
    default: () => ({}),
  })
  readonly value: Props['value'] | undefined;
  @Prop({ type: Array, default: () => [] }) readonly list!: any[];
  @Prop({ type: Object, default: () => ({}) }) readonly rules!: Validator.Rules;
  @Prop({ type: Object, default: () => ({}) }) readonly messages!: Validator.ErrorMessages;

  @Prop({
    type: String,
    default: () => null,
  })
  readonly idTable: Props['idTable'] | undefined;

  error = '';

  onValidate(event: TFormValidationEvent) {
    const { isValid, errors } = event;
    if (!isValid) {
      this.error = Object.values(errors)[0][0];
    } else {
      this.error = '';
    }
    this.$emit('validate', event);
  }

  getValidatingValue(value, col) {
    if (col.controlType === 'date') {
      return this.$moment(value[col.name], 'DD.MM.YYYY').toISOString();
    }

    if (col.controlType === 'address') {
      return value;
    }

    return this.getValue(value, col);
  }

  getValue(value, col) {
    if (col.useRestrictions) {
      return get(value, `${col.useRestrictions}.${col.name}`, '');
    }

    return col.controlType === 'custom' ? col.customRenderValue(value) : value[col.name];
  }

  created() {
    this.options?.forEach((col) => {
      const key = col.name === '%LIST%' ? 'value' : `value.${col.name}`;
      const event = `change.${col.name}`;
      this.$watch(key, (value) => this.$emit(event, value, this.value), { deep: true });
    });
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.table-row__wrapper {
  padding-top: 5px;
  min-height: 40px;
  box-sizing: border-box;
  padding-bottom: 5px;
  border-bottom: 1px solid $input-border-color;

  &_error {
    box-shadow: 0 0 4px $error-color;
  }

  .validation-message {
    height: 12px;
    margin: 8px 16px;
    font-size: 10px;
    line-height: 16px;
    color: red;
  }
}

.tableListRow {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-content: center;
  align-items: center;
  box-sizing: border-box;

  .spanList:first-child {
    text-align: left;
    width: 120px;
  }

  .spanList:last-child {
    text-align: right;
    width: calc(100% - 120px);
  }
}

.spanList {
  display: inline-table;
  table-layout: fixed;
  color: $footer-color;
  margin-right: 15px;
  font-size: 0.75rem;

  &:nth-child(2) {
    word-break: break-word;
  }
}

.iconTable {
  width: 16px;
  height: 16px;
  margin-left: 3px;
  cursor: pointer;
}

.tableList {
  overflow-y: auto;
  width: 100%;
  min-height: 250px;

  @include respond-to('small') {
    font-size: 12px;
  }
}

.tableInfo {
  display: flex;
  flex-direction: row;
  margin-top: 25px;
}
</style>
