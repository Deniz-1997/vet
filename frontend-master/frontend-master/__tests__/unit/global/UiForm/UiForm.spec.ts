import { mount, shallowMount } from '@vue/test-utils';
import Validator from 'validatorjs';
import UiForm from '@/components/global/UiForm/UiForm.vue';
import { delay } from '__tests__/unit/__utils__/helpers';

export interface IFormComponent extends Vue {
  isSubmitted: boolean;
  validator: Validator.Validator<any>;
  update(name: string, value: any): void;
}

/**
 * @jest-environment jsdom
 */
describe('UiForm.vue', () => {
  test('submits on button', () => {
    const form = mount<IFormComponent>(UiForm);
    expect(form.vm.isSubmitted).toBe(false);
    form.find('form').trigger('submit');
    expect(form.vm.isSubmitted).toBe(true);
    const { submit } = form.emitted();
    expect(submit).toHaveLength(1);
  });

  test('returns data on submit', () => {
    const data = { foo: 'bar', test: 200, test2: [1, 2, 3] };
    const form = mount<IFormComponent>(UiForm);
    Object.entries(data).forEach(([key, value]) => {
      form.vm.update(key, value);
    });
    form.trigger('submit');
    const { submit } = form.emitted();
    expect(submit).toContainEqual([data]);
  });

  test('validates on update when validateOn input', async () => {
    const rules = { foo: 'required', bar: 'required|integer' };
    const form = shallowMount<IFormComponent>(UiForm, {
      propsData: { rules, validateOn: 'input' },
    });

    form.vm.update('foo', 'test');
    const emitted1 = form.emitted();

    await delay(300);

    expect(emitted1.validation).toHaveLength(1);
    expect(emitted1.validation?.[0]).toMatchObject([{ isValid: false }]);

    form.vm.update('bar', 'test');
    const emitted2 = form.emitted();

    await delay(300);

    expect(emitted2.validation).toHaveLength(2);
    expect(emitted2.validation?.[1]).toMatchObject([{ isValid: false }]);

    form.vm.update('bar', 123);
    const emitted3 = form.emitted();

    await delay(300);

    expect(emitted3.validation).toHaveLength(3);
    expect(emitted3.validation?.[2]).toMatchObject([{ isValid: true }]);
  });

  test('validates on update when validateOn submit', async () => {
    const rules = { foo: 'required', bar: 'required|integer' };
    const form = mount<IFormComponent>(UiForm, {
      propsData: { rules, validateOn: 'submit' },
    });

    form.vm.update('foo', 'test');
    const emitted1 = form.emitted();

    await delay(300);

    expect(emitted1.validation).not.toBeDefined();

    form.trigger('submit');
    form.vm.update('bar', 'test');
    const emitted2 = form.emitted();

    await delay(300);

    expect(emitted2.validation).toHaveLength(1);
    expect(emitted2.validation?.[0]).toMatchObject([{ isValid: false }]);

    form.vm.update('bar', 123);
    const emitted3 = form.emitted();

    await delay(300);

    expect(emitted3.validation).toHaveLength(2);
    expect(emitted3.validation?.[1]).toMatchObject([{ isValid: true }]);
  });

  test('collapses updates', async () => {
    const form = shallowMount<IFormComponent>(UiForm, {
      propsData: {
        validateOn: 'input',
      },
    });

    form.vm.update('foo', 'test');
    form.vm.update('bar', 'test');
    form.vm.update('bar', 123);

    await delay(300);

    const emitted3 = form.emitted();

    expect(emitted3.validation).toHaveLength(1);
  });

  test('passes custom messages on validate', async () => {
    const rules = { foo: 'required', bar: 'required|integer' };
    const messages = { required: 'Required', 'required.foo': 'Required foo', 'integer.bar': 'Integer' };
    const form = shallowMount<IFormComponent>(UiForm, {
      propsData: { rules, messages, validateOn: 'input' },
    });

    form.vm.update('foo', '');

    await delay(300);
    const emitted1 = form.emitted();

    expect(emitted1.validation?.[0]).toMatchObject([
      { errors: { foo: [messages['required.foo']], bar: [messages.required] } },
    ]);

    form.vm.update('foo', 'test');
    form.vm.update('bar', 'test');

    await delay(300);
    const emitted2 = form.emitted();

    expect(emitted2.validation?.[1]).toMatchObject([{ errors: { bar: [messages['integer.bar']] } }]);

    form.vm.update('bar', 123);

    await delay(300);
    const emitted3 = form.emitted();

    expect(emitted3.validation?.[2]).toMatchObject([{ errors: {} }]);
  });

  test('prevents submit on validation error', async () => {
    const rules = { foo: 'required', bar: 'required|integer' };
    const form = mount<IFormComponent>(UiForm, { propsData: { rules } });
    form.trigger('submit');

    await delay(300);

    const { submit, validation } = form.emitted();
    expect(submit).not.toBeDefined();
    expect(validation).toHaveLength(1);
    expect(validation?.[0]).toMatchObject([{ isValid: false }]);
  });

  test('parses conditional rules', () => {
    const rules = {
      foo: true && 'required',
      bar: [true && 'required', false && 'integer'],
      test: ['required', true && 'required', false && 'integer'],
    };
    const form = shallowMount<IFormComponent>(UiForm, { propsData: { rules } });
    expect(form.vm.validator.rules.foo).toContainEqual({ name: 'required' });
    expect(form.vm.validator.rules.bar).toContainEqual({ name: 'required' });
    expect(form.vm.validator.rules.test).toHaveLength(2);
    expect(form.vm.validator.rules.test).toContainEqual({ name: 'required' });
    expect(form.vm.validator.rules.test).not.toContainEqual({ name: 'integer' });
  });
});
