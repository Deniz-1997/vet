<template>
  <div :style="{ width }">
    <slot name="label">
      <div v-if="label">
        <label-component :label="label" :show-icon="required" />
      </div>
    </slot>

    <v-autocomplete
      v-model="innerValue"
      :cache-items="cacheItems"
      :chips="chips"
      :class="variant"
      :clearable="clearable"
      :color="color"
      :dense="dense"
      :disable-lookup="disableLookup"
      :disabled="isDisabled || disabled"
      :error-messages="errorMessages"
      :error="error"
      :hide-details="hideDetails"
      :hide-no-data="hideNoDate"
      :hide-selected="hideSelected"
      :hint="hint"
      :item-text="itemText"
      :item-value="itemValue"
      :items="items"
      :filter="filter"
      :loading="loading"
      :menu-props="{ contentClass, closeOnClick: true }"
      :messages="messages"
      :multiple="isMultiple"
      :no-data-text="noDataText"
      :placeholder="placeholder"
      :readonly="readonly"
      :return-object="returnObject"
      :rules="rules"
      :small-chips="smallChips"
      :solo="solo"
      auto-grow
      :autocomplete="autocomplete"
      :deletable-chips="deletableChips"
      :name="name"
      @click="onClick"
      @change="onChange"
      @focus="onFocus"
      @blur="handleBlur"
      @update:search-input="onSearchInputUpdate"
    >
      <template v-for="(_, name) in $scopedSlots" #[name]="slotData">
        <slot :name="name" v-bind="slotData" />
      </template>
      <template #append-item>
        <slot v-if="isActionBlock" name="action-item" />
      </template>
      <template #prepend-item>
        <slot name="prepend-item" />
        <slot v-if="isPaginationShown" name="pagination">
          <select-pagination
            :per-page="perPage"
            :items-length="itemsLength"
            class="pagination"
            @onPageChange="handlePageChange"
          />
        </slot>
        <!-- <slot v-if="isMultiple" name="all-selection">
          <all-selection
            v-if="items.length > 0"
            v-model="innerValue"
            @selectAllToggle="handleSelectAllToggle"
            :items="items"
          />
        </slot> -->
      </template>
      <template v-if="chips || tooltip" #selection="{ attrs, item, parent }">
        <v-chip
          v-if="chips"
          active-class="chip"
          close-icon="mdi-close"
          :color="item.disabled ? '#828286' : '#D19B3F'"
          text-color="#FFFFFF"
          :dark="false"
          v-bind="attrs"
          :close="deletableChips && !item.disabled"
          large
          label
          @click:close="parent.onChipInput(item)"
        >
          {{ parseName(item) }}
        </v-chip>
        <v-tooltip top max-width="500" open-delay="500">
          <template #activator="{ on, activatorAttrs }">
            <span v-if="tooltip" v-bind="activatorAttrs" class="hint_span" v-on="on">
              {{ parseName(item) }}
            </span>
          </template>
          {{ parseName(item) }}
        </v-tooltip>
      </template>
      <template #message="{ message }">
        <text-component class="base-micro text-error">
          {{ message }}
        </text-component>
      </template>
    </v-autocomplete>
  </div>
</template>

<script lang="ts">
/* eslint-disable max-len */
import { Component, Model, Prop, Vue } from 'vue-property-decorator';
import { PageChange } from '@/components/common/Pagination/Pagination.types';
import AllSelection from '@/components/common/inputs/AllSelection.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import Pagination from '@/components/common/Pagination/Pagination.vue';
import SelectPagination from '@/components/common/Pagination/SelectPagination.vue';
import TextComponent from '@/components/common/Text/Text.vue';

export type AutocompleteItem<T = string> = {
  text?: string | number;
  value?: T;
};

type Value = AutocompleteItem[] | AutocompleteItem;

@Component({
  name: 'autocomplete-component',
  components: {
    AllSelection,
    LabelComponent,
    Pagination,
    SelectPagination,
    TextComponent,
  },
})
export default class AutocompleteComponent extends Vue {
  @Model('change', {
    type: [String, Array, Object, Number],
    default: '',
  })
  value!: Value;

  @Prop({ type: String, default: 'Уточните текст запроса' })
  readonly noDataText!: string;
  @Prop({ type: Array, default: () => [] }) items!: AutocompleteComponent[];
  @Prop({ type: Array, default: () => [] }) rules!: string;
  @Prop({ type: String, default: '' }) label!: string;
  @Prop({ type: String, default: undefined }) autocomplete!: string;
  @Prop({ type: String, default: '' }) messages!: string;
  @Prop({ type: String, default: '' }) placeholder!: string;
  @Prop({ type: String, default: '' }) width!: string;
  @Prop({ type: String, default: '' }) hint!: string;
  @Prop({ type: String, default: 'micro' }) variant!: string;
  @Prop({ type: String, default: 'text' }) itemText!: keyof AutocompleteItem;
  @Prop({ type: String, default: 'value' }) itemValue!: keyof AutocompleteItem;
  @Prop({ type: String, default: '#d19b3f' }) color!: string;
  @Prop({ type: [String, Array] }) errorMessages!: string;
  @Prop({ type: Number, default: 200 }) readonly perPage!: number;
  @Prop(Boolean) cacheItems!: boolean;
  @Prop(Boolean) chips!: boolean;
  @Prop({ type: Boolean, default: true }) readonly clearable!: boolean;
  @Prop(Boolean) dense!: boolean;
  @Prop(Boolean) disableLookup!: boolean;
  @Prop(Boolean) error!: boolean;
  @Prop(Boolean) hideSelected!: boolean;
  @Prop({ type: Boolean, default: false }) hideDisabledItems!: boolean;
  @Prop(Boolean) isDisabled!: boolean;
  @Prop(Boolean) disabled!: boolean;
  @Prop(Boolean) isMultiple!: boolean;
  @Prop(Boolean) loading!: boolean;
  @Prop(Boolean) required!: boolean;
  @Prop(Boolean) returnObject!: boolean;
  @Prop(Boolean) smallChips!: boolean;
  @Prop(Boolean) solo!: boolean;
  @Prop(Boolean) tooltip!: boolean;
  @Prop({ type: Boolean, default: false }) isInputValue!: boolean;
  @Prop({ type: Boolean, default: true }) hideDetails!: boolean;
  @Prop({ type: Boolean, default: false }) isActionBlock!: boolean;
  @Prop(Boolean) hideNoDate!: boolean;
  @Prop(Number) readonly itemsLength!: number;
  @Prop(Boolean) readonly!: boolean;
  @Prop(Boolean) deletableChips!: boolean;
  @Prop({ type: String, default: '' }) name!: string;
  @Prop({
    type: Function,
    default: (item: unknown, queryText: string, itemText: string) => {
      return itemText.toLocaleLowerCase().indexOf(queryText.toLocaleLowerCase()) > -1;
    },
  })
  filter!: (item: unknown, queryText: string, itemText: string) => boolean;

  get innerValue(): Value {
    return this.value;
  }

  set innerValue(value: Value) {
    this.$emit('change', value);
  }

  get isPaginationShown(): boolean {
    return this.itemsLength > this.perPage;
  }

  get contentClass(): string {
    const { variant } = this;
    const classHideDisabledItems = this.hideDisabledItems ? 'autocomplete-menu--hide-disabled-items' : '';

    return `autocomplete-menu ${variant} ${classHideDisabledItems}`;
  }

  get complexItemsView(): string | number | undefined {
    const { innerValue } = this;

    if (Array.isArray(innerValue)) {
      const values = innerValue.map((value) => value[this.itemText as keyof AutocompleteItem] || value);

      return values.join(', ');
    }

    return innerValue[this.itemText as keyof AutocompleteItem];
  }

  onFocus(value: Value): void {
    this.$emit('onFocus', value);
  }

  handleBlur(value: Value): void {
    this.$emit('onBlur', value);
  }

  onSearchInputUpdate(value: Value): void {
    if (this.isInputValue) {
      this.$emit('getValueFromInput', value);
    }
    this.$emit('searchInputUpdate', value);
  }

  onChange(value: Value): void {
    this.$emit('onChange', value);
  }

  onClick(value: Value): void {
    this.$emit('onClick', value);
  }

  handlePageChange(pageChange: PageChange): void {
    const { page, perPage } = pageChange;

    this.$emit('onPageChange', { page, perPage });
  }

  /** ToDo: Исправить типизацию. */
  handleSelectAllToggle(items: AutocompleteItem[] | any[]): void {
    const filterArr = items.filter((item) => {
      return !item.disabled;
    });
    this.$emit('change', filterArr);
    this.$emit('selectAllLogically', filterArr.length === this.items.length);
  }

  parseName(item: number | string | AutocompleteItem): unknown {
    if (typeof item === 'object') {
      return item[this.itemText];
    }
    return item;
  }
}
</script>

<style scoped lang="scss">
@import '@/assets/styles/_variables';

.v-input.v-select::v-deep {
  color: $input-border-color;

  &.v-text-field {
    margin: 0 !important;
    padding: 0 !important;
  }

  &.v-text-field--solo .v-input__control {
    min-height: auto;
  }

  .v-label {
    color: $input-border-color;
  }

  .v-input__slot {
    border: 1px solid $input-border-color;
    border-radius: 4px;
    box-shadow: none !important;
    min-height: 0;
    overflow: hidden;
    max-width: 100%;
  }

  &--is-menu-active .v-input__slot {
    border-color: $input-border-color;
  }

  &.v-input--is-disabled .v-input__slot {
    background-color: $input-disable-background !important;
    border: 1px solid $input-disabled-color;
  }

  &.v-input--is-disabled .v-input__slot span.select_item {
    color: $medium-grey-color;
  }

  &:hover {
    color: $light-grey-color !important;
  }

  &.small {
    font-size: 22px;
  }

  &.small .v-select__selections {
    line-height: 24px;
  }

  &.micro {
    font-size: 14px;
  }

  &.micro .v-input__slot {
    min-height: 40px;
    padding: 0 12px 0 16px !important;
  }

  &.pico {
    font-size: 11px;
  }

  &.pico .v-input__slot {
    height: 32px;
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

  .theme--light.v-chip:not(.v-chip--active) {
    opacity: 0.7;
  }

  .v-select__selections input {
    .v-select__selections input {
      position: absolute;
    }

    .select_chip {
      background-color: $input-border-color;
      border-radius: 4px;
      color: $white-color;
      font-size: 14px;
      line-height: 16px;
      margin: 2px;
      padding: 12px;
    }

    .select_chip .v-icon {
      color: $white-color;
    }

    .chip-after {
      color: $light-grey-color;
      margin-left: 10px;
    }

    .select_item {
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;

      &:not(:first-child) {
        display: none;
      }
    }

    .v-text-field__details {
      padding: 0 !important;
    }

    .v-text-field__details .v-messages__message {
      align-items: center;
      display: flex;
      justify-content: flex-end;
    }

    .v-icon.mdi-menu-down.primary--text {
      color: $input-border-color !important;
    }

    &.error--text .v-input__control .v-input__slot {
      border: 1px solid $error-color;
    }

    &.error--text .v-text-field__details .v-messages__message {
      justify-content: flex-start;
    }

    &.error--text .v-text-field__details .v-messages__message .select_helper {
      color: $error-color;
      font-size: 12px;
      line-height: 12px;
    }

    &.error--text .v-text-field__details .v-messages__message .select_helper .v-icon {
      display: none;
    }
  }

  .pagination {
    padding: 8px 16px;
  }

  .autocomplete-menu {
    background: $white-color;
    border: 1px solid $input-border-color;
    border-radius: 4px;
    box-shadow: none !important;
    padding: 0;

    &.small .v-list-item__title {
      font-size: 14px;
      line-height: 22px;
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
      color: $input-border-color !important;

      &::before {
        opacity: 0 !important;
      }
    }

    .v-list-item .v-simple-checkbox .v-icon .mdi-checkbox-blank-outline {
      color: $light-grey-color !important;
    }

    .v-list-item .v-simple-checkbox .v-icon .mdi-checkbox-marked {
      color: $input-border-color !important;
    }

    .v-list-item {
      padding: 0 24px 0 16px;

      &:not(:last-child) {
        border-bottom: 1px solid $input-border-color;
      }

      &:hover {
        background-color: $white-color;
      }

      &:hover .v-list-item__content .v-list-item__title {
        color: $input-border-color !important;
      }

      &:hover::before {
        opacity: 0;
      }
    }
  }

  .v-chip {
    &.v-size--large {
      height: 24px;
      margin-left: 0;
      font-size: 14px;
    }
  }

  button {
    &.v-icon {
      width: 10px;
      height: 10px;
      background-color: rgb(0 0 0 / 30%);
      clip-path: polygon(
        20% 0%,
        0% 20%,
        30% 50%,
        0% 80%,
        20% 100%,
        50% 70%,
        80% 100%,
        100% 80%,
        70% 50%,
        100% 20%,
        80% 0%,
        50% 30%
      );
    }
  }
}
</style>
