<template>
  <div :class="['table-row__wrapper', error && 'table-row__wrapper_error']">
    <UiForm
      :rules="rules"
      :messages="messages"
      validate-on="input"
      class="tableListRow"
      :class="{
        'tableListRow--added': value.editCode === 'ADD',
        'tableListRow--delete': value.editCode === 'DEL',
        'tableListRow--edit': value.editCode === 'EDIT',
      }"
      @validation="onValidate"
    >
      <span v-if="hideActions" class="spanList d-flex justify-space-between align-center">
        <v-tooltip bottom>
          <template #activator="{ on, attrs }">
            <span v-bind="attrs" v-on="on">
              <img v-if="!isShowcase && isEditAction" src="/icons/edit.svg" class="iconTable" @click="$emit('edit')" />
            </span>
          </template>
          <span>Редактировать запись</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template #activator="{ on, attrs }">
            <span v-bind="attrs" v-on="on">
              <img
                v-if="!isShowcase && isShowDeleteButton"
                src="/icons/deleteBasket.svg"
                class="iconTable"
                @click="$emit('remove')"
              />
            </span>
          </template>
          <span>Удалить запись</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template #activator="{ on, attrs }">
            <span v-bind="attrs" v-on="on">
              <img
                v-if="isShowcase && isShowCardButton"
                src="/icons/show.svg"
                class="iconTable"
                @click="$emit('edit')"
              />
            </span>
          </template>
          <span>Просмотреть запись</span>
        </v-tooltip>
      </span>
      <span
        v-for="col in options"
        :key="col.name"
        class="spanList"
        :style="typeof col.style === 'undefined' ? { width: col.width + 'px' } : col.style"
        @click="onClickOnCustomerRow(col, value)"
      >
        <UiControl :name="col.name" :value="getValidatingValue(value, col)" hide-message>
          <text-component v-html="getValue(value, col)" />
        </UiControl>
      </span>
    </UiForm>
    <div v-if="error" class="validation-message">{{ error }}</div>
  </div>
</template>

<script lang="ts">
import get from 'lodash/get';
import isEmpty from 'lodash/isEmpty';
import moment from 'moment';
import { Component, Prop, Vue } from 'vue-property-decorator';
import TextComponent from '@/components/common/TextComponent.vue';
import { address } from '@/utils/global/filters';
import { TFormValidationEvent } from '@/services/models/common';
import Validator from 'validatorjs';

type Functions = (...args: any[]) => any;

type Options = {
  name: string;
  label: string;
  width: number;
  optional?: object;
  controlType?: 'input' | 'select' | 'date' | 'link';
  useRestrictions?: string;
  customRenderValue: Functions;
  onClick: Functions;
  isRowNeedEdit?: boolean;

  format?: string;
  titleFormat?: string;
};

type Props = {
  options: Options[];
  isShowcase?: boolean;
  isShowCardButton?: boolean;
  isShowDeleteButton?: boolean;
  value: any;
  hideActions?: boolean;
  isEditAction?: boolean;
};

@Component({
  name: 'TableRow',
  components: { TextComponent },
})
export default class TableRow extends Vue {
  @Prop({
    type: Boolean,
    default: false,
  })
  readonly isShowCardButton: Props['isShowCardButton'] | undefined;
  @Prop({
    type: Boolean,
    default: true,
  })
  readonly isShowDeleteButton: Props['isShowDeleteButton'] | undefined;
  @Prop({
    type: Boolean,
    default: false,
  })
  readonly hideActions: Props['hideActions'] | undefined;
  @Prop({
    type: Boolean,
    default: false,
  })
  readonly isEditAction: Props['isEditAction'] | undefined;
  @Prop({
    type: Boolean,
    default: true,
  })
  readonly isShowcase: Props['isShowcase'] | undefined;
  @Prop({
    type: Array,
    default: [],
  })
  readonly options: Props['options'] | undefined;
  @Prop({
    type: Object,
    default: {},
  })
  readonly value: Props['value'] | undefined;
  @Prop({ type: Object, default: () => ({}) }) readonly rules!: Validator.Rules;
  @Prop({ type: Object, default: () => ({}) }) readonly messages!: Validator.ErrorMessages;

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

  renderDateFormat(date, format, titleFormat) {
    if (typeof date === 'string' && (typeof format === 'undefined' || typeof titleFormat === 'undefined')) {
      return date;
    }
    if (!date || !format || !titleFormat) {
      return;
    } else {
      return moment(date, titleFormat).format(format);
    }
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

  getValue(v, col) {
    let value = v;
    let key = '';

    if (col.controlType === 'autoUserComplete') {
      return value?.[col.name]?.full_name || value?.user_full_name;
    }

    if (col.useRestrictions) {
      value = get(value, `["${col.useRestrictions}"]`);
    }

    if (typeof col.controlType === 'function') {
      key = col.controlType(this.value);
    } else {
      key = col.controlType || '';
    }

    switch (key.toLowerCase()) {
      case 'select':
      case 'autocomplete':
        if (col.customRenderValue !== undefined) {
          return typeof col.customRenderValue === 'function'
            ? col.customRenderValue(value)
            : this.renderValue(col.name === '%LIST%' ? value : get(value, col.name));
        }
        return this.renderValue(col.name === '%LIST%' ? value : get(value, col.name));
      case 'address':
        return address(value.country ? value : get(value, col.name));
      case 'date':
        return this.renderDateFormat(get(value, col.name), col.format, col.titleFormat);
      default:
        return typeof col.customRenderValue === 'function' ? col.customRenderValue(value) : get(value, col.name);
    }
  }

  renderValue(value) {
    return value?.label ?? (isEmpty(value) ? '' : value);
  }

  onClickOnCustomerRow(col, value) {
    if (typeof col.onClick !== 'undefined') return col.onClick(value, col);
    if (this.isEditAction) {
      return this.$emit('edit');
    } else {
      if (col.isRowNeedEdit !== undefined) {
        col.isRowNeedEdit(col, value, (isNeed) => {
          if (isNeed) this.$emit('edit');
        });
      }
    }
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

  &_error {
    box-shadow: 0 0 4px $error-color;

    .tableListRow {
      border-bottom: 0;
    }
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
  padding-top: 5px;
  min-height: 40px;
  box-sizing: border-box;
  padding-bottom: 5px;
  border-bottom: 1px solid $input-border-color;

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
  font-size: 0.75rem !important;

  &:nth-child(2) {
    word-break: break-word;
  }

  @include respond-to('medium') {
    font-size: 14px;
  }

  @include respond-to('small') {
    font-size: 12px;
  }
}

.iconTable {
  width: 20px;
  height: 20px;
  margin-left: 8px;
  cursor: pointer;
}

.tableList {
  overflow-y: auto;
  width: 100%;

  @include respond-to('small') {
    font-size: 12px;
  }
}

.tableInfo {
  display: flex;
  flex-direction: row;
  margin-top: 25px;
}

.tableListRow {
  &--edit {
    background: rgba($gold-light-color, 0.3);
    color: $medium-grey-color;
  }

  &--delete {
    background: rgba($del-color, 0.3);

    .spanList {
      color: $medium-grey-color;
    }
  }

  &--added {
    background: rgba($added-color, 0.3);

    .spanList {
      color: $medium-grey-color;
    }
  }
}
</style>
