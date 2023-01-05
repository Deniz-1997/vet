import Vue from 'vue';
import { shallowMount } from '@vue/test-utils';
import UiCheckbox from '@/components/global/UiForm/components/UiCheckbox.vue';

interface ICheckboxComponent extends Vue {
  innerChecked: boolean;
  innerValue: any;
}

describe('global UICheckbox.vue', () => {
  test('renders checkbox', () => {
    const checkbox = shallowMount<ICheckboxComponent>(UiCheckbox, {
      stubs: {
        UiControl: {
          template: '<div><slot /></div>',
        },
      },
      propsData: {
        id: 'test_id',
        name: 'test_name',
        label: 'test_label',
      },
    });

    const nativeCheckbox = checkbox.find('input[type="checkbox"]');
    expect(nativeCheckbox.exists()).toBe(true);
    expect(nativeCheckbox.attributes('id')).toBe('test_id');
    expect(nativeCheckbox.attributes('name')).toBe('test_name');

    const label = checkbox.find('span.label');
    expect(label.exists()).toBe(true);
    expect(label.text()).toBe('test_label');
  });

  test('emits input on click', async () => {
    const checkbox = shallowMount<ICheckboxComponent>(UiCheckbox, {
      stubs: {
        UiControl: {
          template: '<div><slot /></div>',
        },
      },
      propsData: {
        id: 'test_id',
        name: 'test_name',
        label: 'test_label',
      },
    });

    expect(checkbox.vm.innerChecked).toBe(false);

    const nativeCheckbox = checkbox.find('input[type="checkbox"]');
    await nativeCheckbox.trigger('click');
    const emitted1 = checkbox.emitted();
    expect(emitted1.input).toHaveLength(1);
    expect(emitted1.input).toContainEqual([true]);

    await nativeCheckbox.trigger('click');
    const emitted2 = checkbox.emitted();
    expect(emitted2.input).toHaveLength(2);
    expect(emitted2.input).toContainEqual([false]);
  });

  test('changes checked', async () => {
    const checkbox = shallowMount<ICheckboxComponent>(UiCheckbox, {
      stubs: {
        UiControl: {
          template: '<div><slot /></div>',
        },
      },
      propsData: {
        id: 'test_id',
        name: 'test_name',
        label: 'test_label',
        checked: false,
      },
    });

    checkbox.vm.$on('input', (checked) => checkbox.setProps({ checked }));

    expect(checkbox.vm.innerChecked).toBe(false);

    const nativeCheckbox = checkbox.find('input[type="checkbox"]');
    await nativeCheckbox.trigger('click');
    expect(checkbox.vm.innerChecked).toBe(true);

    await nativeCheckbox.trigger('click');
    expect(checkbox.vm.innerChecked).toBe(false);
  });

  test('defines value', async () => {
    const checkbox = shallowMount<ICheckboxComponent>(UiCheckbox, {
      stubs: {
        UiControl: {
          template: '<div><slot /></div>',
        },
      },
      propsData: {
        id: 'test_id',
        name: 'test_name',
        label: 'test_label',
        checked: false,
      },
    });

    checkbox.vm.$on('input', (checked) => checkbox.setProps({ checked }));

    expect(checkbox.vm.innerValue).toBe(false);

    const nativeCheckbox = checkbox.find('input[type="checkbox"]');
    await nativeCheckbox.trigger('click');
    expect(checkbox.vm.innerValue).toBe(true);

    await nativeCheckbox.trigger('click');
    expect(checkbox.vm.innerValue).toBe(false);

    await checkbox.setProps({ value: 1 });
    expect(checkbox.vm.innerValue).toBe(false);

    await nativeCheckbox.trigger('click');
    expect(checkbox.vm.innerValue).toBe(1);

    await nativeCheckbox.trigger('click');
    expect(checkbox.vm.innerValue).toBe(false);

    await checkbox.setProps({ value: ['foo'] });
    expect(checkbox.vm.innerValue).toBe(false);

    await nativeCheckbox.trigger('click');
    expect(checkbox.vm.innerValue).toEqual(['foo']);

    await nativeCheckbox.trigger('click');
    expect(checkbox.vm.innerValue).toBe(false);

    await checkbox.setProps({ value: ['foo', 'bar'] });
    expect(checkbox.vm.innerValue).toBe('bar');

    await nativeCheckbox.trigger('click');
    expect(checkbox.vm.innerValue).toBe('foo');

    await nativeCheckbox.trigger('click');
    expect(checkbox.vm.innerValue).toBe('bar');
  });

  test('disables control', async () => {
    const checkbox = shallowMount<ICheckboxComponent>(UiCheckbox, {
      stubs: {
        UiControl: {
          template: '<div><slot /></div>',
        },
      },
      propsData: {
        id: 'test_id',
        name: 'test_name',
        label: 'test_label',
        checked: false,
      },
    });

    checkbox.vm.$on('input', (checked) => checkbox.setProps({ checked }));
    const label = checkbox.find('span.checkbox');
    const input = checkbox.find('input[type="checkbox"]');
    expect(label.exists()).toBe(true);
    expect(label.classes()).not.toContain('checkbox_disabled');
    expect(input.attributes('disabled')).not.toBeDefined();

    await checkbox.setProps({ disabled: true });
    expect(label.classes()).toContain('checkbox_disabled');
    expect(input.attributes('disabled')).toBe('disabled');
    await input.trigger('click');
    const emitted = checkbox.emitted();
    expect(emitted.input).not.toBeDefined();
  });
});
