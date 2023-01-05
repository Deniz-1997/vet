import HttpErrorModal from '@/components/NotificationCenter/HttpErrorModal.vue';
import { shallowMount } from '@vue/test-utils';

describe('HttpErrorModal.vue', () => {
  test('renders', () => {
    const modal = shallowMount(HttpErrorModal, {
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

    expect(modal.find('span').text()).toBe('Ошибка интернет-соединения');
    expect(modal.find('p').text()).toBe(
      'Не удаётся установить связь с сервером.\n            Пожалуйста, проверьте своё интернет-соединение, после чего перезагрузите страницу.'
    );
    expect(modal.find('button').text()).toBe('Перезагрузить');
  });

  test('refresh page', () => {
    const modal = shallowMount(HttpErrorModal, {
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
    modal.find('button').trigger('click');
    expect(window.location.reload).toBeCalled();
    window.location = location;
  });
});
