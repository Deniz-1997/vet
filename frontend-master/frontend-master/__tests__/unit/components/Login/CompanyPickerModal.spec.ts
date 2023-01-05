import Modal from '@/views/Login/components/CompanyPickerModal.vue';
import { mount } from '@vue/test-utils';
import Vue from 'vue';

const createComponent = (options?: Record<string, any>) => {
  return mount(Modal, {
    stubs: ['DialogComponent', 'SelectComponent'],
    ...options,
    propsData: {
      value: true,
      ...(options?.propsData ?? {}),
    },
  });
};

describe('CompanyPickerModal.vue', () => {
  test('renders form', () => {
    const component = createComponent();

    expect(component.isVisible()).toBe(true);
    expect(component.find('[data-qa="company-picker-modal__input"]').isVisible()).toBe(true);
    expect(component.find('[data-qa="company-picker-modal__submit"]').isVisible()).toBe(true);
    expect(component.find('[data-qa="company-picker-modal__submit"]').attributes('disabled')).toBe('disabled');
  });

  test('set organization', async () => {
    const $service = {
      auth: {
        setOrganization: jest.fn(() => Promise.resolve({})),
        restoreSession: jest.fn(() => Promise.resolve({})),
      },
    };
    const component = createComponent({ mocks: { $service } });
    await component.setData({ pickedId: 100 });
    expect(component.find('[data-qa="company-picker-modal__submit"]').attributes('disabled')).toBeUndefined();
    component.find('[data-qa="company-picker-modal__submit"]').trigger('click');
    expect($service.auth.setOrganization).toBeCalledWith(100);
    await Vue.nextTick();
    expect($service.auth.restoreSession).toBeCalled();
  });
});
