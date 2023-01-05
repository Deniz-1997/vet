<template>
  <component :is="component" v-if="component" v-model="innerValue" v-bind="props" v-on="on" />
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import noop from 'lodash/noop';
import omit from 'lodash/omit';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import Year from '@/components/common/Year/Year.vue';
import _ from 'lodash';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import PriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import UserAutocomplete from '@/components/UserAutocomplete/UserAutocomplete.vue';

type SelectItem = {
  value: any;
  label: string;
};

type TCalculatedOption<T> = T | ((...args: any[]) => T);

type TCellControlOptions = {
  name: string;
  label: string;
  prefix?: string;
  placeholder?: string;
  controlType?: 'input' | 'select' | 'date' | 'autoComplete' | 'year' | 'priorityAddress' | 'autoUserComplete';
  clearable?: boolean;
  disabled?: boolean;
  useResctictions?: string;
  exclude?: boolean;
  reverse?: boolean;
  titleFormat?: string;
  dateFormat?: string;
  fontSize?: string;
  rules?: any;
  limitFrom?: string;
  limitTo?: string;
  itemValue?: string;
  itemText?: string;
  idElement?: string;
};

type TCellControlHandlers = {
  handleChange?: () => void;
  handleSearchAddress: () => void;
};

type TCellControlAddition = {
  mask?: string;
  restrictions?: SelectItem[] | (<T>(list: T[], value: T) => SelectItem[]);
  validate?: (...args: any[]) => Record<string, any>;
};

type Options = { [K in keyof TCellControlOptions]: TCalculatedOption<TCellControlOptions[K]> } & TCellControlHandlers &
  TCellControlAddition;

type Props = {
  options: Options;
  isShowcase?: boolean;
  value: any;
  fullValue: any;
  idTable?: string;
};

const Components = {
  input(options: Options, fullValue: Props['fullValue'] | undefined, idTable: Props['idTable'] | undefined) {
    const { name } = options;

    return {
      component: InputComponent,
      ...options,
      label: undefined,
      name: idTable + '.' + name,
      width: undefined,
      ...(options.validate ? options.validate(fullValue) : {}),
    };
  },
  date(options: Options, fullValue: Props['fullValue'] | undefined, idTable: Props['idTable'] | undefined) {
    const { disabled, dateFormat, titleFormat, placeholder, limitFrom, limitTo, name } = options;

    return {
      component: UiDateInput,
      disabled,
      placeholder: placeholder ?? 'Укажите дату',
      format: dateFormat ?? 'DD.MM.YYYY',
      outputFormat: titleFormat ?? 'DD.MM.YYYY',
      class: ['datePicker'],
      limitFrom,
      limitTo,
      name: idTable + '.' + name,
      ...(options.validate ? options.validate(fullValue) : {}),
    };
  },
  year(options: Options, fullValue: Props['fullValue'] | undefined, idTable: Props['idTable'] | undefined) {
    return {
      component: Year,
      isDisabled: options.disabled,
      limitTo: new Date(),
      name: idTable + '.' + options.name,
      ...(options.validate ? options.validate(fullValue) : {}),
    };
  },
  select(options: Options, fullValue: Props['fullValue'] | undefined, idTable: Props['idTable'] | undefined) {
    const { fontSize, placeholder, clearable, disabled, restrictions, exclude, name } = options;

    return {
      component: SelectComponent,
      clearable: clearable,
      isDisabled: disabled,
      items: restrictions,
      fontSize,
      placeholder,
      exclude,
      name: idTable + '.' + name,
      ...(options.validate ? options.validate(fullValue) : {}),
    };
  },
  autoComplete(
    { disabled, restrictions, handleSearchAddress = noop, idElement, ...props }: Options,
    fullValue: Props['fullValue'] | undefined,
    idTable: Props['idTable'] | undefined
  ) {
    return {
      component: AutocompleteComponent,
      isDisabled: disabled,
      items: restrictions,
      returnObject: true,
      itemValue: props.itemValue || 'code',
      itemText: props.itemText || 'name',
      on: {
        searchInputUpdate: handleSearchAddress,
      },
      name: idTable + '.' + idElement,
      ...(props.validate ? props.validate(fullValue) : {}),
    };
  },
  autoUserComplete(
    { disabled, restrictions, idElement, ...props }: Options,
    fullValue: Props['fullValue'] | undefined,
    idTable: Props['idTable'] | undefined
  ) {
    return {
      component: UserAutocomplete,
      isDisabled: disabled,
      items: restrictions,
      returnObject: true,
      itemValue: props.itemValue || 'user_id',
      itemText: props.itemText || 'name',
      name: idTable + '.' + idElement,
    };
  },
  priorityAddress(options: Options, fullValue: Props['fullValue'] | undefined) {
    const { disabled, restrictions, handleChange = noop, handleSearchAddress = noop } = options;

    return {
      component: PriorityAddress,
      isDisabled: disabled,
      items: restrictions,
      label: '',
      on: {
        updateValue: handleChange,
        searchInputUpdate: handleSearchAddress,
      },
      ...(options.validate ? options.validate(fullValue) : {}),
    };
  },
};

const functionalParams = [
  'name',
  'labe',
  'prefix',
  'placeholder',
  'controlType',
  'clearable',
  'disabled',
  'useResctictions',
  'exclude',
  'reverse',
  'titleFormat',
  'dateFormat',
  'fontSize',
  'rules',
  'limitFrom',
  'limitTo',
  'itemValue',
  'itemText',
];
const mapOptions = (options, params) =>
  Object.entries(options).reduce((result, [key, value]) => {
    if (functionalParams.includes(key) && typeof value === 'function') {
      return { ...result, [key]: value(params) };
    }

    return { ...result, [key]: value };
  }, {});

@Component({
  name: 'TableEditableCell',
})
export default class TableEditableCell extends Vue {
  @Prop({
    type: [String, Number, Date, Object, Array],
    default: () => null,
  })
  readonly fullValue: Props['fullValue'] | undefined;

  @Prop({
    type: Object,
    default: () => null,
  })
  readonly options!: Props['options'];
  @Prop({
    type: [String, Number, Date, Object, Array],
    default: () => null,
  })
  readonly value!: Props['value'];
  @Prop({ type: Array, default: () => [] }) readonly list!: any[];
  @Prop({
    type: String,
    default: () => null,
  })
  readonly idTable: Props['idTable'] | undefined;

  get control() {
    let type: any = this.options.controlType || 'input';

    if (typeof this.options.controlType === 'function') {
      type = this.options.controlType(this.fullValue);
    }

    const options = mapOptions(this.options, this.fullValue);

    return Components[type] ? Components[type](options, this.fullValue, this.idTable) : {};
  }

  get component() {
    return this.control.component;
  }

  get on() {
    return 'on' in this.control && this.control.on;
  }

  get props() {
    const props = omit(this.control, ['component', 'on']);

    if ('items' in props && typeof props.items === 'string') {
      props.items = this.fullValue[props.items];
    }

    if ('items' in props && typeof props.items === 'function') {
      props.items = props.items(this.list, this.value);
    }

    return props;
  }

  get innerValue() {
    if (this.options?.controlType === 'select' || this.options?.controlType === 'autoComplete') {
      return this.value ? this.value.value || this.value.code : null;
    }

    return this.value;
  }

  set innerValue(v) {
    this.$emit('input', v);
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.label {
  color: $input-border-color;
  font-size: 14px;
  line-height: 16px;
  margin-bottom: 5px;
  display: block;

  &--strong {
    color: $black-color;
    font-weight: 700;
  }
}

.input {
  border: 1px solid $input-border-color;
  border-radius: 3px;
  background: $white-color;
  outline: none;
  height: 40px;
  color: $black-color;
  font-size: 14px;
  line-height: 16px;
  margin: 0;
  padding: 0 10px;
  z-index: 5;
  width: 100%;

  &--disabled {
    background-color: $input-disable-background;
    color: $input-disabled-color;
  }

  &--small {
    flex: 1 1 150px;
    margin-right: 15px;
    max-width: 150px;
  }

  &--big {
    height: auto;
    padding: 10px;
  }

  &--large {
    flex: 1 1 100%;
  }
}
</style>
