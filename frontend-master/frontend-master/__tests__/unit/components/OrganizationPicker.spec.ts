import OrganizationPicker from '@/components/OrganizationPicker.vue';
import { shallowMount } from '@vue/test-utils';

const createComponent = (options: any = {}) =>
  shallowMount<any>(OrganizationPicker, {
    ...options,
    propsData: {
      ...options.propsData,
      value: [],
      label: 'trololo',
      name: 'test',
      multi: true,
    },
  });

describe('OrganizationPicker.vue', () => {
  test('fetch', async () => {
    const options = {
      mocks: {
        $axios: {
          post: jest.fn(() => Promise.resolve({ data: [] })),
        },
      },
    };

    const component = createComponent(options);
    expect(options.mocks.$axios.post).toBeCalledTimes(1);
    expect(options.mocks.$axios.post).toBeCalledWith('/api/subject/subjects', {
      filter: '',
      pageable: {
        pageNumber: 0,
        pageSize: 15,
        sort: [{ property: 'subject.name', direction: 'ASC' }],
      },
      with_total_count: false,
      actual: true,
    });
    await component.vm.onInput('trolol0');
    await component.vm.onInput('trolol1');
    await component.vm.onInput('trolol2');
    component.vm.onInput.flush();
    expect(options.mocks.$axios.post).toBeCalledTimes(2);
    expect(options.mocks.$axios.post).toBeCalledWith('/api/subject/subjects', {
      filter: 'trolol2',
      pageable: {
        pageNumber: 0,
        pageSize: 15,
        sort: [{ property: 'subject.name', direction: 'ASC' }],
      },
      with_total_count: false,
      actual: true,
    });
  });
});
