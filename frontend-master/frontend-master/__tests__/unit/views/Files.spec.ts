import Files from '@/views/Files/Files.vue';
import Vue from 'vue';
import { shallowMount } from '@vue/test-utils';

interface IComponent extends Vue {
  config: any[];
  isLoading: boolean;
}

describe('Files page', () => {
  test('shows documents', () => {
    const $axios = jest.fn(() => Promise.resolve({ data: [1, 2, 3] }));
    const page = shallowMount<IComponent>(Files, {
      mocks: { $axios, $route: { meta: { breadcrumb: [] }, params: { type: 'test' } } },
    });

    Vue.nextTick().then(() => {
      expect($axios).toBeCalledWith('/configs/files/test.json');
      expect(page.vm.isLoading).toBe(false);
      expect(page.vm.config).toEqual([1, 2, 3]);
    });
  });
});
