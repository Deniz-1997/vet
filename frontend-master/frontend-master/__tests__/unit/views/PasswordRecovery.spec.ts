import Vue from 'vue';
import UiControl from '@/components/global/UiForm/UiControl.vue';
import UiForm from '@/components/global/UiForm/UiForm.vue';
import PasswordRecovery from '@/views/Login/PasswordRecovery.vue';
import { shallowMount } from '@vue/test-utils';

const stubs = {
  UiForm,
  UiControl,
  Button: {
    template: '<button @click="$emit(\'click\')" type="submit">{{ title }}</button>',
    props: { title: String, variant: String },
  },
  Input: {
    template:
      '<input :value="value" @input="({ target }) => $emit(\'input\', target.value)" :type="type || \'text\'" />',
    props: { value: String, type: String, label: String },
  },
};

describe('PasswordRecovery page', () => {
  test('render', async () => {
    const mockNext = jest.fn();
    const page = shallowMount(PasswordRecovery);
    (page.vm as any).beforeRouteEnter.call(null, { query: {} }, null, mockNext);
    expect(mockNext).toBeCalledWith('/login?error=password-recovery');
  });

  test('recovery', async () => {
    const $router = { push: jest.fn() };
    const $service = { password: { reset: jest.fn() } };
    const page = shallowMount(PasswordRecovery, {
      mocks: {
        $route: { query: { uuid: 'trololo' } },
        $router,
        $service,
      },
      stubs,
    });

    await page.setData({
      form: {
        password: 'password',
        confirmPassword: 'password',
      },
    });
    page.find('form').trigger('submit');
    expect($service.password.reset).toBeCalledWith({
      password: 'password',
      confirmPassword: 'password',
      uuid: 'trololo',
    });
  });
});
