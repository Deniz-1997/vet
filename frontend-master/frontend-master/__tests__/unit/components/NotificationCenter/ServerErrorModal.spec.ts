import ServerErrorModal from '@/components/NotificationCenter/ServerErrorModal.vue';
import { shallowMount } from '@vue/test-utils';

describe('ServerErrorModal.vue', () => {
  test('renders', () => {
    const modal = shallowMount(ServerErrorModal, {
      propsData: {
        value: true,
      },
      stubs: {
        DefaultButton: {
          template: '<button @click="$emit(\'click\')">{{ title }}</button>',
          props: { title: String },
        },
      },
      mocks: { $config: { isDev: true } },
    });

    expect(modal.find('span').text()).toBe('Что-то пошло не так');
    expect(modal.find('p').text()).toBe('Сервер сейчас недоступен.\n            Попробуйте повторить действие позже.');
    expect(modal.findAll('button').at(0).text()).toBe('На главную');
    expect(modal.findAll('button').at(1).text()).toBe('Обновить страницу');
  });

  test('refresh page', () => {
    const modal = shallowMount(ServerErrorModal, {
      propsData: {
        value: true,
      },
      stubs: {
        DefaultButton: {
          template: '<button @click="$emit(\'click\')">{{ title }}</button>',
          props: { title: String },
        },
      },
      mocks: { $config: { isDev: false } },
    });

    const { location } = window;
    Reflect.deleteProperty(window, 'location');
    window.location = { reload: jest.fn() } as any;
    modal.findAll('button').at(1).trigger('click');
    expect(window.location.reload).toBeCalled();
    window.location = location;
  });

  test('to home page', () => {
    const $router = {
      push: jest.fn(),
    };
    const modal = shallowMount(ServerErrorModal, {
      propsData: {
        value: true,
      },
      stubs: {
        DefaultButton: {
          template: '<button @click="$emit(\'click\')">{{ title }}</button>',
          props: { title: String },
        },
      },
      mocks: { $router, $config: { isDev: true } },
    });

    modal.findAll('button').at(0).trigger('click');
    expect($router.push).toBeCalledWith('/home');
  });
});
