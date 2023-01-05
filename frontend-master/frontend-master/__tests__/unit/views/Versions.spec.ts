import Versions from '@/views/Versions/Versions.vue';
import Vue from 'vue';
import { mount } from '@vue/test-utils';
import { injectVuetify } from '../__utils__/helpers';

interface IComponent extends Vue {
  list: any[];
  isLoading: boolean;
}

describe('Versions page', () => {
  test('render', async () => {
    const $service = {
      versions: {
        getVersionList: jest.fn(() => [
          {
            version: 'Test',
            name: 'Test',
            id: 'Test',
            active: true,
          },
          {
            version: 'Test2',
            name: 'Test2',
            id: 'giszp-ui',
            active: false,
          },
          {
            version: 'Test3',
            name: 'Test3',
            id: 'Test3',
            active: true,
          },
        ]),
        getVersionItem: jest.fn(() => ({
          version: 'Test2',
          name: 'Test2',
          id: 'giszp-ui',
          active: true,
        })),
      },
    };
    const page = mount<IComponent>(Versions, {
      ...injectVuetify(),
      mocks: { $service },
    });

    expect($service.versions.getVersionList).toBeCalled();
    await Vue.nextTick();
    expect(page.vm.isLoading).toBe(false);
    expect(page.vm.list).toHaveLength(3);
    expect(page.findAll('table tr')).toHaveLength(4);
    expect(page.findAll('.status-badge')).toHaveLength(3);
    expect(page.findAll('.status-badge_error')).toHaveLength(1);
    expect(page.find('.iconTable').isVisible()).toBe(true);

    page.find('.iconTable').trigger('click');
    expect($service.versions.getVersionItem).toBeCalled();
    await Vue.nextTick();
    expect(page.find('.iconTable').exists()).toBe(false);
  });
});
