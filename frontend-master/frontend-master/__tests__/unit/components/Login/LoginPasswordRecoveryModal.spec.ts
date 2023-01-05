import Modal from '@/components/Login/LoginPasswordRecoveryModal.vue';
import { mount } from '@vue/test-utils';
import Vue from 'vue';

const createComponent = (options?: Record<string, any>) => {
  return mount(Modal, {
    stubs: ['DialogComponent', 'InputComponent'],
    ...options,
    propsData: {
      value: true,
      ...(options?.propsData ?? {}),
    },
  });
};

describe('LoginPasswordRecoveryModal.vue', () => {
  test('renders form', () => {
    const component = createComponent();

    expect(component.isVisible()).toBe(true);
    expect(component.find('[data-qa="password-recovery-modal__alert"]').isVisible()).toBe(true);
    expect(component.find('[data-qa="password-recovery-modal__form"]').isVisible()).toBe(true);
    expect(component.find('[data-qa="password-recovery-modal__input"]').isVisible()).toBe(true);
    expect(component.find('[data-qa="password-recovery-modal__submit"]').isVisible()).toBe(true);
  });

  test('shows success message', async () => {
    const $service = {
      password: {
        applyReset: jest.fn(() => Promise.resolve({})),
      },
    };
    const component = createComponent({ mocks: { $service } });
    await component.setData({ form: { login: 'login' } });
    component.find('form').trigger('submit');
    expect($service.password.applyReset).toBeCalledWith('login');
    await Vue.nextTick();
    await Vue.nextTick();
    expect(component.isVisible()).toBe(true);
    expect(component.find('[data-qa="password-recovery-modal__alert"]').isVisible()).toBe(true);
    expect(component.find('[data-qa="password-recovery-modal__input"]').exists()).toBe(false);
    expect(component.find('[data-qa="password-recovery-modal__submit"]').exists()).toBe(false);
  });

  test.skip('resets state on close', async () => {
    const $service = {
      password: {
        applyReset: jest.fn(() => Promise.resolve({})),
      },
    };
    const component = createComponent({ mocks: { $service } });
    await component.setData({ form: { login: 'login' } });
    component.find('form').trigger('submit');
    expect($service.password.applyReset).toBeCalledWith('login');
    await Vue.nextTick();
    await component.setData({ innerValue: false });
    await component.setData({ innerValue: true });
    expect(component.isVisible()).toBe(true);
    expect(component.find('[data-qa="password-recovery-modal__alert"]').isVisible()).toBe(true);
    expect(component.find('[data-qa="password-recovery-modal__form"]').isVisible()).toBe(true);
    expect(component.find('[data-qa="password-recovery-modal__input"]').isVisible()).toBe(true);
    expect(component.find('[data-qa="password-recovery-modal__submit"]').isVisible()).toBe(true);
  });
});
