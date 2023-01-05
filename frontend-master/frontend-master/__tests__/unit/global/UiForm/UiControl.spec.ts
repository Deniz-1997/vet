import { mount, shallowMount } from '@vue/test-utils';
import UiControl from '@/components/global/UiForm/UiControl.vue';
import { noop } from 'lodash';

interface IControlComponent extends Vue {
  value: any;
  errors: any[];
  isChanged: boolean;
  isFormSubmitted: boolean;
  error?: string;
  $showError: boolean;
  form: any;
  onValidate(): void;
  _handleChange(value: any): void;
}

let parentComponent;

beforeEach(() => {
  parentComponent = {
    template: '<div><slot></slot></div>',
    name: 'UiForm',
    props: {
      validateOn: {
        type: String,
        default: 'submit',
      },
    },
    data: () => ({
      isSubmitted: true,
      validator: {
        errors: {
          get() {
            return ['ololo', 'trololo'];
          },
        },
      },
    }),
    methods: {
      update: noop,
    },
  };
});

describe('UiControl.vue', () => {
  /**
   * @jest-environment jsdom
   */
  test('watch changes', () => {
    const control = shallowMount<IControlComponent>(UiControl, {
      propsData: {
        name: 'control',
        value: 1,
      },
    });

    expect(control.vm.isChanged).toBe(false);
    control.trigger('change');
    expect(control.vm.isChanged).toBe(true);
  });

  /**
   * @jest-environment jsdom
   */
  test("don't find parent form if does not exist", () => {
    const control = shallowMount<IControlComponent>(UiControl, {
      propsData: {
        name: 'control',
        value: 1,
      },
    });

    expect(control.vm.form).toBeNull();
  });

  /**
   * @jest-environment jsdom
   */
  test('finds parent form if exist (straight child)', () => {
    const control = shallowMount<IControlComponent>(UiControl, {
      parentComponent,
      propsData: {
        name: 'control',
        value: 1,
      },
    });

    expect(control.vm.form).not.toBeNull();
  });

  /**
   * @jest-environment jsdom
   */
  test('finds parent form if exist (nested child)', () => {
    const Middlearth = {
      template: '<div><slot></slot></div>',
    };
    const wrapper = mount({
      components: { UiForm: parentComponent, UiControl, Middlearth },
      template: '<UiForm><Middlearth><UiControl></UiControl></Middlearth></UiForm>',
    });

    const control = wrapper.findComponent<IControlComponent>(UiControl);

    expect(control.vm.form).not.toBeNull();
  });

  /**
   * @jest-environment jsdom
   */
  test('updates form on value change', async () => {
    const control = shallowMount<IControlComponent>(UiControl, {
      parentComponent,
      propsData: {
        name: 'control',
        value: 1,
      },
    });
    const mock = jest.spyOn(control.vm.$parent, 'update' as any);
    await control.setProps({ value: 2 });
    expect(mock).toBeCalledWith('control', 2);

    await control.setProps({ value: 'test' });
    expect(mock).toBeCalledWith('control', 'test');
  });

  /**
   * @jest-environment jsdom
   */
  test('updates on validation form', async () => {
    const control = shallowMount<IControlComponent>(UiControl, {
      parentComponent,
      propsData: {
        name: 'control',
        value: 1,
      },
    });

    expect(control.vm.isFormSubmitted).toBe(false);
    expect(control.vm.errors).toHaveLength(0);

    const form = control.vm.$parent;
    form.$emit('validation');

    expect(control.vm.isFormSubmitted).toBe(true);
    expect(control.vm.errors).toHaveLength(2);
  });

  /**
   * @jest-environment jsdom
   */
  test('shows error when validate on submit', async () => {
    const control = shallowMount<IControlComponent>(UiControl, {
      parentComponent,
      propsData: {
        name: 'control',
        value: 1,
      },
    });

    expect(control.vm.$showError).toBe(false);
    expect(control.vm.error).toBe('');

    const form = control.vm.$parent;
    form.$emit('validation');

    expect(control.vm.$showError).toBe(true);
    expect(control.vm.error).toBe('ololo');
  });

  /**
   * @jest-environment jsdom
   */
  test('shows error when validate on input', async () => {
    const control = shallowMount<IControlComponent>(UiControl, {
      parentComponent,
      propsData: {
        name: 'control',
        value: 1,
      },
    });

    expect(control.vm.$showError).toBe(false);
    expect(control.vm.error).toBe('');

    const form = control.vm.$parent;
    (form as any).validateOn = 'input';
    control.trigger('change');
    form.$emit('validation');

    expect(control.vm.$showError).toBe(true);
    expect(control.vm.error).toBe('ololo');
  });

  /**
   * @jest-environment jsdom
   */
  test("don't shows error when hideMessage and validate on submit", async () => {
    const control = shallowMount<IControlComponent>(UiControl, {
      parentComponent,
      propsData: {
        name: 'control',
        value: 1,
        hideMessage: true,
      },
    });

    const form = control.vm.$parent;
    form.$emit('validation');
    expect(control.find('.validation-message').exists()).toBe(false);
  });

  /**
   * @jest-environment jsdom
   */
  test("don't shows error when hideMessage and validate on input", async () => {
    const control = shallowMount<IControlComponent>(UiControl, {
      parentComponent,
      propsData: {
        name: 'control',
        value: 1,
        hideMessage: true,
      },
    });

    const form = control.vm.$parent;
    (form as any).validateOn = 'input';
    control.trigger('change');
    form.$emit('validation');

    expect(control.find('.validation-message').exists()).toBe(false);
  });
});
