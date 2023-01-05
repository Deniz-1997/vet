<template>
  <div :style="{ width }">
    <div v-if="label">
      <label-component :label="label" :show-icon="isRequired" />
    </div>
    <v-select
      v-model="innerValue"
      :chips="chips"
      :class="[variant, { chips }, additionalClass]"
      :clearable="clearable"
      :color="color"
      :style="{ fontSize }"
      :deletable-chips="deletableChips"
      :disabled="isDisabled || disabled"
      :hide-details="hideDetails"
      :item-text="itemText"
      :item-value="itemValue"
      :items="listItem"
      :label="innerLabel"
      :loading="loading"
      :error="error"
      :error-messages="errorMessage"
      :messages="help"
      :multiple="isMultiple"
      :no-data-text="noDataText"
      :placeholder="placeholder"
      :readonly="readonly"
      :return-object="returnObject"
      :rules="rules"
      :menu-props="{
        contentClass,
        maxHeight: hideMenu ? 0 : 400,
        closeOnClick: true,
        ...selectMenuProps,
      }"
      solo
      @blur="handleBlur"
      @change="handleChange"
      @click="handleClick"
      @focus="handleFocus"
      :name="name"
    >
      <template v-for="(_, name) in $slots" #[name]="slotData">
        <slot :name="name" v-bind="slotData" />
      </template>
      <template #prepend-item>
        <slot v-if="isPaginationShown" name="pagination">
          <select-pagination
            :per-page="perPage"
            :items-length="itemsLength"
            class="pagination"
            @on-page-change="handlePageChange"
          />
        </slot>
        <slot v-if="isMultiple" name="all-selection">
          <all-selection v-model="innerValue" :items="items" @select-all-toggle="handleSelectAllToggle" />
        </slot>
      </template>
      <template #message="{ message }">
        <text-component class="base-micro text-error">
          {{ message }}
        </text-component>
      </template>
      <template v-if="isPaginationShown" #prepend-inner>
        <div>
          <v-chip
            v-for="value in innerValue"
            :key="value[itemValue]"
            close
            @click:close="deleteChip(value, innerValue)"
          >
            <span>{{ value[itemText] }}</span>
          </v-chip>
        </div>
      </template>
      <template v-if="isPaginationShown" #selection />
    </v-select>
  </div>
</template>

<script lang="ts">
/* eslint-disable max-len */
import { Component, Model, Prop, Vue } from 'vue-property-decorator';
import { PageChange } from '@/components/common/Pagination/Pagination.types';
import AllSelection from '@/components/common/inputs/AllSelection.vue';
import ClearIcon from '@/components/common/IconComponent/icons/ClearIcon.vue';
import IconComponent from '@/components/common/IconComponent/IconComponent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import SelectPagination from '@/components/common/Pagination/SelectPagination.vue';
import TextComponent from '@/components/common/Text/Text.vue';

export type SelectItem<T = string | number> = {
  label?: string | number;
  text?: string | number;
  name?: string | number;
  code?: T;
  value?: T;
};

export type SelectMenuProps = {
  [key: string]: number | string | boolean;
};

type Value = SelectItem[] | SelectItem | any;

type Variants = 'micro' | 'small';

@Component({
  name: 'select-component',
  components: {
    AllSelection,
    ClearIcon,
    IconComponent,
    LabelComponent,
    SelectPagination,
    TextComponent,
  },
})
export default class SelectComponent extends Vue {
  @Model('change', { type: [String, Number, Array, Object] })
  readonly value!: Value;

  @Prop({ type: String, default: 'mdi-chevron-down' })
  readonly appendIcon!: string;
  @Prop({ type: String, default: 'Данные отсутствуют' })
  readonly noDataText!: string;
  @Prop({ type: String, default: 'value' }) readonly itemValue!: string;
  @Prop({ type: [String, Function], default: 'text' })
  readonly itemText!: string;
  @Prop({ type: String, default: 'small' }) readonly variant!: Variants;
  @Prop({ type: Boolean, default: true }) readonly clearable!: boolean;
  @Prop({ type: String, default: 'auto' }) readonly width!: string;
  @Prop({ type: String, default: '14px' }) readonly fontSize!: string;
  @Prop({ type: String, default: '#d19b3f' }) color!: string;
  @Prop({ type: Number, default: 200 }) readonly perPage!: number;
  @Prop({ type: Object, default: () => ({}) })
  readonly selectMenuProps!: SelectMenuProps;
  @Prop({ type: Array, default: () => [] }) readonly items!: SelectItem[];
  @Prop({ type: Array, default: () => [] }) rules!: string;
  @Prop(String) readonly additionalClass!: string;
  @Prop(String) readonly placeholder!: string;
  @Prop(String) readonly label!: string;
  @Prop(String) readonly innerLabel!: string;
  @Prop(String) readonly help!: string;
  @Prop(Boolean) readonly returnObject!: boolean;
  @Prop(Boolean) readonly readonly!: boolean;
  @Prop(Boolean) readonly loading!: boolean;
  @Prop(String) readonly errorMessage!: string;
  @Prop(Boolean) readonly error!: boolean;
  @Prop(Boolean) readonly isRequired!: boolean;
  @Prop(Boolean) readonly isMultiple!: boolean;
  @Prop(Boolean) readonly isDisabled!: boolean;
  @Prop(Boolean) readonly disabled!: boolean;
  @Prop(Boolean) readonly hideMenu!: boolean;
  @Prop(Boolean) readonly hideDetails!: boolean;
  @Prop(Boolean) readonly deletableChips!: boolean;
  @Prop(Boolean) readonly chips!: boolean;
  @Prop(Number) readonly itemsLength!: number;
  @Prop(Boolean) readonly exclude!: boolean;
  @Prop({ type: String, default: '' }) name!: string;

  get innerValue(): any {
    if (this.exclude) {
      const selectItem = this.items
        .map((item) => {
          return {
            text: item.label,
            value: JSON.stringify({
              label: item.label,
              value: item.value,
            }),
          };
        })
        .find((item) => {
          if (this.value) {
            if (JSON.parse(item.value).value === this.value.value || JSON.parse(item.value).value === this.value) {
              return item;
            }
          }
        });
      return selectItem ? selectItem : '';
    } else {
      if (Array.isArray(this.value)) {
        return this.value.filter((item: any) => item.text && !['actions', 'check'].includes(item.value));
      }
      return this.value;
    }
  }

  set innerValue(value: any) {
    if (this.exclude) {
      if (!value) {
        this.$emit('change', value);
      } else {
        const selectItem = JSON.parse(value);
        const newValue = this.items.find((item) => {
          if (selectItem.value === item.value) {
            return item;
          }
        });
        this.$emit('change', newValue);
      }
    } else {
      this.$emit('change', value);
    }
  }

  get listItem(): SelectItem[] {
    if (this.exclude) {
      return this.items.map((item) => ({
        text: item.label,
        value: JSON.stringify({
          label: item.label,
          value: item.value,
        }),
      }));
    }
    return this.items;
  }

  get isPaginationShown(): boolean {
    return this.itemsLength > this.perPage;
  }

  get contentClass(): string {
    const { variant } = this;

    return `select-menu ${variant}`;
  }

  handleClick(): void {
    this.$emit('onClick');
  }

  handleFocus(value: Value): void {
    this.$emit('onFocus', value);
  }

  handleBlur(value: Value): void {
    this.$emit('onBlur', value);
  }

  handleChange(value: Value): void {
    this.$emit('onChange', value);
  }

  handleClearClick(): void {
    const emptyItem = { text: '', value: '' };

    this.$emit('change', this.isMultiple ? [] : emptyItem);
  }

  handlePageChange(pageChange: PageChange): void {
    const { page, perPage } = pageChange;

    this.$emit('onPageChange', { page, perPage });
  }

  handleSelectAllToggle(items: SelectItem[]): void {
    this.$emit('change', items);
    this.$emit('selectAllLogically', items.length === this.items.length);
  }

  deleteChip(itemNeedToRemove: SelectItem, array: Value): void {
    if (Array.isArray(array)) {
      for (let i = 0; i < array.length; i++) {
        if (array[i] === itemNeedToRemove) {
          array.splice(i, 1);
        }
      }
    }
  }
}
</script>

<style scoped lang="scss">
@import '@/assets/styles/_variables';

.v-input.v-select::v-deep {
  min-height: 40px;
  color: $light-grey-color;

  .v-select__selection--disabled {
    color: $input-disabled-color !important;
  }

  .v-text-field__details {
    display: none !important;
  }

  &.v-text-field--solo .v-input__control {
    min-height: auto;
  }

  &.v-input--is-focused .v-input__slot {
    border-color: $input-border-color;
  }

  .v-label {
    color: $light-grey-color;
  }

  .v-input__slot {
    border: 1px solid $input-border-color;
    border-radius: 3px;
    box-shadow: none !important;
    min-height: 40px;
    margin: 0 !important;
    overflow: hidden;
    z-index: 7;
  }

  &--is-menu-active .v-input__slot {
    border-color: $input-border-color;
  }

  &.v-input--is-disabled .v-input__slot {
    background-color: $input-disable-background !important;
  }

  &.v-input--is-disabled .v-input__slot .v-input__append-inner {
    display: none;
  }

  &.v-input--is-disabled .v-input__slot span.select-item {
    color: $input-disabled-color !important;
  }

  &:hover {
    color: $light-grey-color !important;
  }

  &.small {
    font-size: 22px;
  }

  &.small .v-select__selections {
    line-height: 32px;
  }

  &.micro {
    font-size: 14px;
  }

  &.micro .v-input__slot {
    min-height: 40px;
    padding: 0 12px 0 16px !important;
  }

  &.micro .v-select__selections {
    min-height: inherit;
  }

  &.pico {
    font-size: 11px;
  }

  &.pico .v-input__slot {
    height: 34px;
    padding: 0 10px 0 14px !important;
  }

  &.chips .v-input__slot {
    padding: 2px 14px 2px 2px !important;
  }

  &.chips .v-label {
    padding: 0 14px;
  }

  .v-select__slot {
    height: inherit;
  }

  .v-select__selections input {
    position: absolute;
  }

  .v-select__selection--comma {
    box-shadow: none !important;
    outline: none;
    color: $black-color;
    font-size: 14px;
    line-height: 16px;
    margin-bottom: 0;
    padding: 0 !important;
    white-space: initial;
    overflow: initial;
    text-overflow: initial;
    max-width: initial;
    word-break: break-word;
    margin: 5px 0 !important;
  }

  .v-chip {
    background-color: $input-border-color;
    border-radius: 4px;
    color: $white-color;
    font-size: 14px;
    height: auto;
    line-height: 16px;
    margin: 2px;
    min-height: 32px;
    padding: 8px;
    white-space: pre-line;
  }

  .v-chip .v-icon {
    color: $white-color;
  }

  .v-text-field__details .v-messages__message {
    align-items: center;
    display: flex;
    justify-content: flex-end;
  }

  .v-icon.mdi-menu-down.primary--text {
    color: $gold-light-color !important;
  }

  .v-messages__message {
    justify-content: flex-start !important;
  }

  &.show-hint-v-label {
    &.error--text .v-label {
      color: $error-color;
    }

    .v-label {
      color: #be8a32;
    }
  }
}

.v-application--is-ltr::v-deep {
  .v-text-field .v-input__append-inner {
    padding-left: 10px;
  }
}

.pagination {
  padding: 8px 16px;
}

.v-input.v-select {
  .v-text-field__details {
    padding: 0 !important;
  }

  &.error--text .v-input__control .v-input__slot {
    border: 1px solid $error-color;
  }
}

.select-menu {
  background: $white-color;
  border: 1px solid $input-border-color;
  border-radius: 4px;
  box-shadow: 0 16px 32px rgb(35 39 51 / 10%);
  padding: 0;

  &.small .v-list-item__title {
    font-size: 22px;
    line-height: 32px;
  }

  &.micro .v-list-item__title {
    font-size: 14px;
    line-height: 16px;
  }

  &.pico .v-list-item__title {
    font-size: 11px;
    line-height: 16px;
  }

  .v-list {
    padding: 0;
  }

  .v-list-item .v-list-item__content {
    padding: 16px 0;
  }

  .v-list-item--active {
    color: $gold-light-color !important;

    &::before {
      opacity: 0 !important;
    }
  }

  .v-list-item .v-simple-checkbox .v-icon .mdi-checkbox-blank-outline {
    color: $light-grey-color !important;
  }

  .v-list-item .v-simple-checkbox .v-icon .mdi-checkbox-marked {
    color: $gold-light-color !important;
  }

  .v-list-item {
    padding: 0 24px 0 16px;

    &:not(:last-child) {
      border-bottom: 1px solid $gold-light-color;
    }

    &:hover {
      background-color: $white-color;
    }

    &:hover .v-list-item__content .v-list-item__title {
      color: $gold-light-color !important;
    }

    &:hover::before {
      opacity: 0;
    }
  }
}
</style>
