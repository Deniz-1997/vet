<template>
  <UiControl
    tag="form"
    :name="`@form/${id}`"
    :value="validator.passes()"
    hide-message
    @submit.prevent="handleSubmit"
    @keydown.enter="handleSubmit"
  >
    <slot />
  </UiControl>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator';
import debounce from 'lodash/debounce';
import isNull from 'lodash/isNull';
import isString from 'lodash/isString';
import isUndefined from 'lodash/isUndefined';
import omitBy from 'lodash/omitBy';
import Validator from 'validatorjs';
import './config';
import { Debounce } from '@/utils/global/decorators/method';

@Component({ name: 'UiForm' })
export default class UiForm extends Vue {
  /** Список правил валидации. */
  @Prop({ type: Object, default: () => ({}) }) readonly rules!: Validator.Rules;
  /** Переопределения текстов ошибок. */
  @Prop({ type: Object }) readonly messages!: Validator.ErrorMessages;
  /** Тип поведения при отправке формы. */
  @Prop({
    type: String,
    default: 'submit',
    validator: (v: string) => ['submit', 'input'].includes(v),
  })
  readonly validateOn!: 'submit' | 'input';
  /** Форма с шагами */
  @Prop({ type: Boolean, default: false }) readonly step!: boolean;

  /** Данные формы. */
  private data: any = {};
  /** Флаг первой попытки отправки формы. */
  isSubmitted = false;
  /** Флаг первой попытки отправки шага формы (для форм с пропом step). */
  isSubmittedStep = false;
  innerRules = {};
  status = {
    isValid: true,
    errors: {},
    data: this.safeData,
  };
  id = Math.random().toString(36).slice(2);

  private get parentForm() {
    return this.getForm(this.$parent as any);
  }

  get parent() {
    const deprecatedModals = [...document.querySelectorAll('div.overlay+div')]
      .map((elem) => elem.parentElement)
      .filter((v) => !!v) as HTMLElement[];
    const elements = [...document.querySelectorAll('div[role="dialog"]'), ...deprecatedModals];
    const parent = elements.find((element) => element.contains(this.$el) || element === this.$el);

    if (parent) {
      return (
        (parent.querySelector('.v-dialog') as HTMLElement) ||
        (parent.querySelector('.container') as HTMLElement) ||
        document.documentElement
      );
    }

    return document.documentElement;
  }

  get safeData() {
    return omitBy(this.data, (data) => isUndefined(data) || isNull(data));
  }

  get validator() {
    const isValidRule = (rule: any) => {
      if (isString(rule) && rule.includes(':')) {
        return !!this.safeData[rule.split(':')[1]];
      }

      return true;
    };

    const rules = Object.entries({ ...this.rules, ...this.innerRules }).reduce((result, [key, rules]) => {
      const value: any[] = (Array.isArray(rules) ? rules : [rules]).reduce((result, item) => {
        if (item && isString(item)) {
          return [...result, ...item.split('|').filter((v) => v && isValidRule(v))];
        }

        if (item) {
          return [...result, item];
        }

        return result;
      }, []);

      if (value.length) {
        return {
          ...result,
          [key]: value,
        };
      }

      return result;
    }, {});

    return new Validator(this.safeData, rules, this.messages);
  }

  mounted() {
    if (this.parentForm) {
      this.parentForm.$on('validation', this.handleSubmit);
    }
  }

  @Watch('validator', { deep: true, immediate: true })
  onValidatorChange() {
    this.pushEventsDebounced();
  }

  update(name: string, value: any) {
    this.data = {
      ...this.data,
      [name]: value,
    };

    if (name.startsWith('@form')) {
      this.innerRules = {
        ...this.innerRules,
        [name]: 'accepted',
      };
    }
  }

  destroy(name: string) {
    if (name.startsWith('@form')) {
      const { [name]: _, ...rules } = this.innerRules as any;
      this.innerRules = { ...rules };
    }
  }

  @Debounce()
  scrollToInvalid() {
    const [control] = [...this.parent.querySelectorAll('[data-validator="control"]')].filter((elem: any) => {
      return elem.dataset.state === 'error';
    });
    const { scrollTop } = this.parent;
    if (control) {
      const { top } = control.getBoundingClientRect();
      this.parent.scrollTo({ top: scrollTop + top, behavior: 'smooth' });
    }
  }

  /** Обработчик отправки формы. */
  handleSubmit() {
    this.isSubmitted = true;
    if (this.validator.passes()) {
      this.$emit('submit', this.data);
      this.isSubmittedStep = false;
    } else {
      this.pushEventsDebounced();
      this.$nextTick(this.scrollToInvalid);
      this.isSubmittedStep = true;
    }
  }

  /** Уведомление контролов и консьюмера об изменениях в форме. */
  pushEvents() {
    if (this.validateOn !== 'submit' || (!this.step && this.isSubmitted) || (this.step && this.isSubmittedStep)) {
      const isValid = this.validator.passes() || false;
      this.status = {
        isValid,
        errors: this.validator.errors.all(),
        data: this.data,
      };
      this.$emit('validation', this.status);
    }
  }

  pushEventsDebounced = debounce(this.pushEvents, 200);

  private getForm(component: Vue): UiForm | null {
    if (component.$options.name !== 'UiForm') {
      if (component.$parent) {
        return this.getForm(component.$parent);
      }

      return null;
    }

    return component as UiForm;
  }
}
</script>
