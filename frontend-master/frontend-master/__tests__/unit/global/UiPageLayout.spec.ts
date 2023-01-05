import Vue from 'vue';
import { shallowMount } from '@vue/test-utils';
import UiPageLayout from '@/components/global/UiPageLayout/UiPageLayout.vue';
import layouts from '@/layouts';

interface IPageComponent extends Vue {
  defaultTitle: string;
  title: string;
  layout: string;
  findLayout(): string;
}

describe('UiPageLayout.vue', () => {
  test('renders div if nothing matched', () => {
    const layout = shallowMount<IPageComponent>(UiPageLayout, {
      mocks: {
        $route: {
          matched: [],
        },
        $router: {
          afterEach: jest.fn(),
        },
      },
    });
    Vue.nextTick().then(() => {
      const layoutType = layout.vm.layout;
      expect(layoutType).toBe('');
      const component = layout.find('div');
      expect(component.exists()).toBe(true);
    });
  });

  test('renders default layout', () => {
    const layout = shallowMount<IPageComponent>(UiPageLayout, {
      mocks: {
        $route: {
          matched: [{ components: {} }],
        },
        $router: {
          afterEach: jest.fn(),
        },
      },
    });
    Vue.nextTick().then(() => {
      const layoutType = layout.vm.layout;
      expect(layoutType).toBe('default');

      Vue.nextTick().then(() => {
        const component = layout.findComponent(layouts[layoutType]);
        expect(component.exists()).toBe(true);
      });
    });
  });

  test('renders dedicated layout', () => {
    const layout = shallowMount<IPageComponent>(UiPageLayout, {
      mocks: {
        $router: {
          afterEach: jest.fn(),
        },
        $route: {
          matched: [
            {
              components: {
                default: {
                  options: {
                    layout: 'login',
                  },
                },
              },
            },
          ],
        },
      },
    });
    Vue.nextTick().then(() => {
      const layoutType = layout.vm.layout;
      expect(layoutType).toBe('login');

      Vue.nextTick().then(() => {
        const component = layout.findComponent(layouts[layoutType]);
        expect(component.exists()).toBe(true);
      });
    });
  });

  test('2. renders default layout if inner does not exist', () => {
    const layout = shallowMount<IPageComponent>(UiPageLayout, {
      mocks: {
        $router: {
          afterEach: jest.fn(),
        },
        $route: {
          matched: [
            {
              components: {
                default: {
                  options: {
                    layout: 'ololo',
                  },
                },
              },
            },
          ],
        },
      },
    });
    Vue.nextTick().then(() => {
      const layoutType = layout.vm.layout;
      expect(layoutType).toBe('default');

      Vue.nextTick().then(() => {
        const component = layout.findComponent(layouts.default);
        expect(component.exists()).toBe(true);
      });
    });
  });

  test('title', async () => {
    const getters = {
      'auth/title': { prefix: 'Prefix', name: 'Name' } as any,
    };
    const $route = {
      matched: [],
      meta: {
        breadcrumb: [],
      },
    } as any;
    const layout = shallowMount<IPageComponent>(UiPageLayout, {
      mocks: {
        $route,
        $router: {
          afterEach: jest.fn(),
        },
        $store: { getters },
      },
    });
    expect(layout.vm.defaultTitle).toBe('Prefix Name');
    expect(layout.vm.title).toBe('Prefix Name');
    getters['auth/title'] = { name: 'Only Name' };
    expect(layout.vm.defaultTitle).toBe('Only Name');
    expect(layout.vm.title).toBe('Only Name');

    $route.meta.breadcrumb.push({ name: 'Dedicated Name' });
    expect(layout.vm.defaultTitle).toBe('Only Name');
    expect(layout.vm.title).toBe('Dedicated Name');
  });
});
