import RoleCardModal from '@/components/Administration/Roles/RoleCardModal.vue';
import { RoleItem } from '@/services/mappers/roles';
import { shallowMount } from '@vue/test-utils';
import Vue from 'vue';
import { fixtures } from '__tests__/services/__fixtures__/roles';

let mocks;

beforeEach(() => {
  mocks = {
    $service: {
      roles: {
        findOne: jest.fn((role_id) => Promise.resolve({ data: new RoleItem({ ...fixtures[1], role_id }) })),
      },
    },
  };
});

describe('RoleCardModal.vue', () => {
  test('renders single info', async () => {
    const component = shallowMount(RoleCardModal, {
      mocks,
      propsData: {
        id: 2,
      },
    });
    const mockCache = jest.spyOn(component.vm as any, 'getFromCache');

    await component.setProps({ $isModalOpen: true });
    await Vue.nextTick();

    expect(mocks.$service.roles.findOne).toBeCalledTimes(1);
    expect(mockCache).toBeCalledTimes(2);
    expect(component.find('[data-qa="role-card__title"]').text()).toBe('Не администратор');
    expect(component.find('[data-qa="role-card__name"]').exists()).toBe(false);
    expect(component.find('[data-qa="role-card__description"]').text()).toBe('Администратор 1');
    expect(component.findAll('[data-qa="role-card__authority"]')).toHaveLength(1);
  });

  test('renders multiple info', async () => {
    const component = shallowMount(RoleCardModal, {
      mocks,
      propsData: {
        id: [2, 3, 100],
      },
    });
    const mockCache = jest.spyOn(component.vm as any, 'getFromCache');

    await component.setProps({ $isModalOpen: true });
    await Vue.nextTick();
    await Vue.nextTick();

    expect(mocks.$service.roles.findOne).toBeCalledTimes(3);
    expect(mockCache).toBeCalledTimes(2);
    expect(component.find('[data-qa="role-card__title"]').text()).toBe('Не администратор');
    expect(component.find('[data-qa="role-card__name"]').text()).toBe('Не администратор');
    expect(component.find('[data-qa="role-card__description"]').text()).toBe('Администратор 1');
    expect(component.findAll('[data-qa="role-card__name"]')).toHaveLength(3);
    expect(component.findAll('[data-qa="role-card__description"]')).toHaveLength(3);
    expect(component.findAll('[data-qa="role-card__authority"]')).toHaveLength(3);
  });

  test('uses passed title', async () => {
    const component = shallowMount(RoleCardModal, {
      mocks,
      propsData: {
        id: 2,
        title: 'Test title',
      },
    });

    await component.setProps({ $isModalOpen: true });
    await Vue.nextTick();

    expect(component.find('[data-qa="role-card__title"]').text()).toBe('Test title');
  });
});
