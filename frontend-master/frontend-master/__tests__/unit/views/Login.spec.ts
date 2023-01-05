import Vue from 'vue';
import UiControl from '@/components/global/UiForm/UiControl.vue';
import UiForm from '@/components/global/UiForm/UiForm.vue';
import LoginPage from '@/views/Login/Login.vue';
import { shallowMount } from '@vue/test-utils';

describe('Login page', () => {
  test('render', () => {
    const $service = {
      auth: {
        startEsiaLogin: jest.fn(),
        login: jest.fn(),
      },
    };
    const page = shallowMount(LoginPage, {
      mocks: {
        $service,
        $route: { query: {} },
      },
      stubs: {
        UiForm,
        UiControl,
        Button: {
          template: '<button @click="$emit(\'click\')" type="submit">{{ title }}</button>',
          props: { title: String },
        },
        Input: {
          template:
            '<input :value="value" @input="({ target }) => $emit(\'input\', target.value)" :type="type || \'text\'" />',
          props: { value: String, type: String },
        },
      },
    });
    expect(page.find('.error').exists()).toBe(false);
    expect(page.find('.title').text()).toBe('Пожалуйста, авторизуйтесь');
    expect(page.find('form input[type="text"]').isVisible()).toBe(true);
    expect(page.find('form input[type="password"]').isVisible()).toBe(true);
    expect(page.find('form button[type="submit"]').isVisible()).toBe(true);
    expect(page.find('.additional__buttons .additional__item').isVisible()).toBe(true);
  });

  test('login', async () => {
    const $service = {
      auth: {
        startEsiaLogin: jest.fn(),
        login: jest.fn(),
      },
    };
    const page = shallowMount(LoginPage, {
      mocks: {
        $service,
        $route: { query: {} },
      },
      stubs: {
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
      },
    });

    page.find('form').trigger('submit');
    expect($service.auth.login).not.toBeCalled();
    page.setData({ form: { login: 'admin', password: 'admin' } });

    await Vue.nextTick();
    await page.find('form').trigger('submit');
    await Vue.nextTick();
    expect($service.auth.login).toBeCalledWith({ login: 'admin', password: 'admin' });
  });

  test('start esia login', () => {
    const $service = {
      auth: {
        startEsiaLogin: jest.fn(),
        login: jest.fn(),
      },
    };
    const page = shallowMount(LoginPage, {
      mocks: {
        $service,
        $route: { query: {} },
      },
      stubs: {
        UiForm,
        UiControl,
        Button: {
          template: '<button @click="$emit(\'click\')" type="submit">{{ title }}</button>',
          props: { title: String },
        },
        Input: {
          template:
            '<input :value="value" @input="({ target }) => $emit(\'input\', target.value)" :type="type || \'text\'" />',
          props: { value: String, type: String },
        },
      },
    });

    page.find('.additional__buttons .additional__item').trigger('click');
    expect($service.auth.startEsiaLogin).toBeCalled();
  });

  test('show errors', async () => {
    const $service = {
      auth: {
        startEsiaLogin: jest.fn(),
        login: jest.fn(),
      },
    };
    const page = shallowMount(LoginPage, {
      mocks: {
        $route: {
          query: { error: 'esia-confirm' },
        },
        $service,
      },
      stubs: {
        UiForm,
        UiControl,
        Button: {
          template: '<button @click="$emit(\'click\')" type="submit">{{ title }}</button>',
          props: { title: String },
        },
        Input: {
          template:
            '<input :value="value" @input="({ target }) => $emit(\'input\', target.value)" :type="type || \'text\'" />',
          props: { value: String, type: String },
        },
      },
    });

    expect(page.find('.error').text()).toBe('Не удалось авторизоваться под учётной записью ЕСИА');
    await page.setData({ error: 'esia-start' });
    expect(page.find('.error').text()).toBe('Не удалось подключиться к ЕСИА');
    await page.setData({ error: 'logpass' });
    expect(page.find('.error').text()).toBe('Неверный логин или пароль');
  });
});
