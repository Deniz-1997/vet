import ServerErrorModal from '@/components/NotificationCenter/ServerErrorModal.vue';
import HttpErrorModal from '@/components/NotificationCenter/HttpErrorModal.vue';
import NotificationCenter from '@/components/NotificationCenter/NotificationCenter.vue';
import { shallowMount } from '@vue/test-utils';
import { MapperError } from '@/utils/global/mapper/errors';

let $service: any;
const callbacks: any = {};

beforeEach(() => {
  $service = {
    notify: {
      on: jest.fn((type, callback) => (callbacks[type] = callback)),
      off: jest.fn(),
    },
  };
});

describe.skip('NotificationCenter.vue', () => {
  test('render', () => {
    const component = shallowMount(NotificationCenter, {
      mocks: { $service },
    });

    expect($service.notify.on).toBeCalledTimes(3);
    expect(component.findComponent(HttpErrorModal).exists()).toBe(true);
    expect(component.findComponent(ServerErrorModal).exists()).toBe(true);
    expect(component.html()).toContain('v-snackbar');
  });

  test('show error', () => {
    const component = shallowMount(NotificationCenter, {
      mocks: { $service },
    });

    expect((component.vm as any).isShow).toBe(false);
    expect((component.vm as any).errorText).toBe(undefined);
    callbacks.error({ text: 'test', params: {} });
    expect((component.vm as any).isShow).toBe(true);
    expect((component.vm as any).errorText).toBe('test');
  });

  test('hide error', () => {
    const component = shallowMount(NotificationCenter, {
      mocks: { $service },
    });

    expect((component.vm as any).isShow).toBe(false);
    expect((component.vm as any).errorText).toBe(undefined);
    callbacks.error({ text: 'test', params: {} });
    callbacks.flush();
    expect((component.vm as any).isShow).toBe(false);
    expect((component.vm as any).errorText).toBe(undefined);
  });

  test('show http error modal', () => {
    const component = shallowMount(NotificationCenter, {
      mocks: { $service },
    });

    expect((component.vm as any).isServerErrorModalShow).toBe(false);
    expect((component.vm as any).serverError).toBeNull();
    callbacks.error({ text: 'test', params: { error: new MapperError({ code: 'required' } as any) } });
    expect((component.vm as any).isServerErrorModalShow).toBe(true);
    expect((component.vm as any).serverError).toBeInstanceOf(MapperError);

    component.setData({ isServerErrorModalShow: false, serverError: null });
    callbacks.error({ text: 'test', params: { error: { response: { status: 500 } } } });
    expect((component.vm as any).isServerErrorModalShow).toBe(true);
    expect((component.vm as any).serverError).toMatchObject({ response: { status: 500 } });

    component.setData({ isServerErrorModalShow: false, serverError: null });
    callbacks.error({ text: 'test', params: { error: { response: { status: 504 } } } });
    expect((component.vm as any).isServerErrorModalShow).toBe(true);
    expect((component.vm as any).serverError).toMatchObject({ response: { status: 504 } });
  });

  test('show server error modal', () => {
    const component = shallowMount(NotificationCenter, {
      mocks: { $service },
    });

    expect((component.vm as any).isNetworkErrorModalShow).toBe(false);
    callbacks.message({ text: 'test', params: { type: 'network-error' } });
    expect((component.vm as any).isNetworkErrorModalShow).toBe(true);
  });
});
