<template>
  <v-menu
    v-if="$config.device.desktop"
    v-model="isModalOpen.menu"
    v-bind="position"
    :close-on-content-click="false"
    offset-y
    allow-overflow
    eager
    :transition="false"
    min-width="auto"
  >
    <template #activator="{ on, attrs }">
      <InputComponent
        :id="id"
        ref="input"
        v-model="innerStringValue"
        :name="name"
        :label="label"
        :placeholder="placeholder"
        :prepend-icon="prependIcon"
        :append-icon="appendIcon"
        :readonly="readonly || disabled"
        :disabled="disabled"
        :mask="mask"
        :rules="ruleDate"
        input-class="datePicker"
        v-bind="attrs"
        v-on="on"
        @clear="$emit('change', '')"
        @click="clickIconAppend"
      />
    </template>
    <v-date-picker
      ref="calendar"
      v-model="innerDateValue"
      no-title
      locale="ru-RU"
      :class="['calendar', $style['ui-date-input__calendar']]"
      color="lime lighten-2"
      first-day-of-week="1"
      :allowed-dates="getAllowedDates"
      show-current
      show-adjacent-months
      :readonly="readonly || disabled"
      :disabled="disabled"
    />
  </v-menu>
  <v-dialog v-else ref="dialog" v-model="isModalOpen.dialog" persistent width="290px">
    <template #activator="{ on, attrs }">
      <InputComponent
        :id="id"
        ref="input"
        v-model="innerStringValue"
        :name="name"
        :label="label"
        :placeholder="placeholder"
        prepend-icon="mdi-calendar"
        :readonly="readonly || disabled"
        :disabled="disabled"
        :mask="mask || innerMask"
        v-bind="attrs"
        v-on="on"
        @clear="$emit('change', '')"
      />
    </template>
    <v-date-picker
      ref="calendar"
      v-model="innerDateValue"
      no-title
      locale="ru-RU"
      :class="['calendar', $style['ui-date-input__calendar']]"
      color="lime lighten-2"
      first-day-of-week="1"
      :allowed-dates="getAllowedDates"
      show-current
      show-adjacent-months
      :readonly="readonly || disabled"
      :disabled="disabled"
    />
  </v-dialog>
</template>

<script lang="ts">
import { Vue, Component, Model, Prop, Watch } from 'vue-property-decorator';
import isAfter from 'date-fns/isAfter';
import isBefore from 'date-fns/isBefore';
import format from 'date-fns/format';
import locale from 'date-fns/locale/ru';
import parse from 'date-fns/parse';
import startOfDay from 'date-fns/startOfDay';

import InputComponent from '@/components/common/inputs/InputComponent.vue';
import { Memoize } from '@/utils/global/decorators/method';

@Component({
  name: 'UiDateInput',
  components: { InputComponent },
})
export default class UiDateInput extends Vue {
  static dateFormat = {
    inner: 'yyyy-MM-dd',
    outer: 'dd.MM.YYYY',
  };

  @Model('change', { type: [String, Object], required: false }) readonly value!: string;
  @Prop({ type: String, default: '' }) readonly id?: string;
  @Prop({ type: String, default: '' }) readonly name?: string;
  @Prop({ type: String, default: '' }) readonly label?: string;
  @Prop({ type: String, default: '' }) readonly placeholder?: string;
  @Prop({ type: Boolean, default: false }) readonly isAppendIcon?: Boolean;
  @Prop({ type: [String, Function, Array, Object], default: null }) readonly mask?: any;
  @Prop({ type: [String, Date, Number], default: '' }) readonly limitFrom?: string | number | Date;
  @Prop({ type: [String, Date, Number], default: '' }) readonly limitTo?: string | number | Date;
  @Prop({ type: Boolean, default: false }) readonly readonly?: boolean;
  @Prop({ type: Boolean, default: false }) readonly disabled?: boolean;
  @Prop({ type: String, default: UiDateInput.dateFormat.outer }) readonly format!: string;
  @Prop({ type: String, default: UiDateInput.dateFormat.outer }) readonly outputFormat!: string;

  temp = 'initial';
  prevTemp = '';
  position = { top: false, bottom: true, left: true, right: false };
  isModalOpen = {
    menu: false,
    dialog: false,
  };

  prependIcon = 'mdi-calendar';
  appendIcon = 'mdi-calendar-blank-outline';

  maskDate = /^(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}$/;
  ruleDate = [(date) => !date || this.maskDate.test(date) || 'Недопустимый формат даты']

  mounted() {
    this.switchIconPosition();
  }

  get parent() {
    const elements = [...document.querySelectorAll('div[role="dialog"]')];
    const parent = elements.find((element) => element.contains(this.$el));

    if (parent) {
      return (parent.querySelector('.v-dialog') as HTMLElement) || document.documentElement;
    }

    return document.documentElement;
  }

  get innerDateValue(): string {
    const date = parse(this.value, this.cleanFormat(this.outputFormat), startOfDay(new Date()), {
      locale,
      weekStartsOn: 1,
    });

    return +date ? format(date, UiDateInput.dateFormat.inner, { locale, weekStartsOn: 1 }) : '';
  }

  set innerDateValue(v: string) {
    this.$emit(
      'change',
      format(
        parse(v, UiDateInput.dateFormat.inner, startOfDay(new Date()), { locale, weekStartsOn: 1 }),
        this.cleanFormat(this.outputFormat),
        { locale, weekStartsOn: 1 }
      )
    );
    this.prevTemp = this.temp;
    this.temp = 'initial';
    this.isModalOpen.menu = false;
    this.isModalOpen.dialog = false;
  }

  get innerStringValue() {
    if (this.temp !== 'initial') {
      return this.temp;
    }

    const date = parse(this.value, this.cleanFormat(this.outputFormat), startOfDay(new Date()), {
      locale,
      weekStartsOn: 1,
    });
    return +date ? format(date, this.cleanFormat(this.format), { locale, weekStartsOn: 1 }) : '';
  }

  set innerStringValue(v: string) {
    console.log(v)
    const date = parse(v, this.cleanFormat(this.format), startOfDay(new Date()), { locale, weekStartsOn: 1 });
    const result = +date ? format(date, this.cleanFormat(this.outputFormat), { locale, weekStartsOn: 1 }) : '';
    this.prevTemp = this.temp;
    this.temp = 'initial';
    this.$emit('change', result);
  }

  @Watch('isModalOpen.menu')
  recalculatePosition(value) {
    if (value) {
      const calendar = (this.$refs.calendar as Vue).$el.parentElement as Element;
      const input = (this.$refs.input as Vue).$el as Element;
      calendar['style'].opacity = 0;
      calendar['style'].display = 'inline-block';
      const { height, width } = calendar.getBoundingClientRect();
      const { bottom, left } = input.getBoundingClientRect();
      const { offsetHeight, scrollTop, offsetWidth, scrollLeft } = this.parent;
      calendar['style'].display = '';
      calendar['style'].opacity = 1;

      const isFitVertical = bottom + height + scrollTop < offsetHeight;
      const isFitHorizontal = left + width + scrollLeft < offsetWidth;

      this.position = {
        top: !isFitVertical,
        bottom: isFitVertical,
        left: !isFitHorizontal,
        right: isFitHorizontal,
      };
    }
  }

  cleanFormat(format: string) {
    return format.replace(/[DY]/g, (char) => char.toLowerCase());
  }

  getAllowedDates(v) {
    const date = parse(v, UiDateInput.dateFormat.inner, startOfDay(new Date()), { locale, weekStartsOn: 1 });
    const limitFrom = this.limitFrom && +new Date(this.limitFrom) ? new Date(this.limitFrom) : '';
    const limitTo = this.limitTo && +new Date(this.limitTo) ? new Date(this.limitTo) : '';

    return (!limitFrom || isAfter(date, limitFrom)) && (!limitTo || isBefore(date, limitTo));
  }

  innerMask() {
    const value = (this.temp === 'initial' ? this.value : this.temp) || '';

    let dayMaskValue: RegExp;
    if (value[0] === '0') {
      dayMaskValue = /[1-9]/;
    } else if (value[0] === '3') {
      dayMaskValue = /[0-1]/;
    } else {
      dayMaskValue = /\d/;
    }

    const day = [/[0-3]/, dayMaskValue];
    const month = [/[0-1]/, value[3] === '0' ? /[1-9]/ : /[0-2]/];
    const year = [/[1-3]/, /\d/, /\d/, /\d/];

    if (value.length === 6 && this.prevTemp.length >= 6) {
      return [...day, '.', ...month];
    }

    if (value.length === 3 && this.prevTemp.length >= 3) {
      return [...day];
    }

    return [...day, '.', ...month, '.', ...year];
  }

  switchIconPosition() {
    this.isAppendIcon ? this.prependIcon = '' : this.appendIcon = '';
  }

  clickIconAppend() {
    this.isModalOpen.menu = !this.isModalOpen.menu;
    this.isModalOpen.dialog = !this.isModalOpen.dialog;
  }
}
</script>

<style lang="scss" module>
@import '@/assets/styles/_variables';

.ui-date-input__calendar {
  :global(.v-btn__content) {
    font-family: Roboto, $font-stack !important;
  }

  :global(.lime.lighten-2) {
    background-color: #d19b3f !important;
    border-color: #d19b3f !important;
  }

  :global(.lime--text.text--lighten-2) {
    color: #d19b3f !important;
    caret-color: #d19b3f !important;
  }

  :global(.v-date-picker-table--date td) {
    padding: 8px 4px;
  }

  :global(.v-date-picker-table) {
    height: auto;
  }
}
</style>

<style lang="scss">
.datePicker {
  .mdi-calendar-blank-outline::before {
    font-size: 19px;
  }

  .v-text-field__details {
    padding: 0 !important;
    bottom: -23px;
  }
}
</style>
