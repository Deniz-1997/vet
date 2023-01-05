import EsiaLogin from '@/views/Login/EsiaLogin.vue';
import { shallowMount } from '@vue/test-utils';

describe('EsiaLogin page', () => {
  test('esia login process on valid enter', () => {
    const $service = { auth: { confirmEsiaLogin: jest.fn(), getOrganizations: jest.fn() } };
    shallowMount(EsiaLogin, {
      mocks: {
        $route: { query: { some: 'param' } },
        $service,
      },
    });

    expect($service.auth.confirmEsiaLogin).toBeCalledWith({ some: 'param' });
  });

  test('redirect to login page on empty param', () => {
    const $router = { push: jest.fn() };
    shallowMount(EsiaLogin, {
      mocks: {
        $route: { query: {} },
        $router,
      },
    });

    expect($router.push).toBeCalledWith('/login');
  });

  test('redirect to login page on no param', () => {
    const $router = { push: jest.fn() };
    shallowMount(EsiaLogin, {
      mocks: {
        $route: {},
        $router,
      },
    });

    expect($router.push).toBeCalledWith('/login');
  });
});
