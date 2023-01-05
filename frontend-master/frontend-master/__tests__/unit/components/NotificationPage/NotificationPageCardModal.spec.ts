import Vue from 'vue';
import { injectVuetify } from '__tests__/unit/__utils__/helpers';
import { ENotificationObjectType } from '@/services/enums/notification';
import NotificationPageCardModal from '@/components/NotificationPage/NotificationPageCardModal.vue';
import NotificationPageViolationCard from '@/components/NotificationPage/cards/NotificationPageViolationCard.vue';
import { mount } from '@vue/test-utils';

describe('NotificationPageCardModal', () => {
  test('renders', () => {
    const mock = jest.fn(() => ({
      data: {
        difference: 'Test3',
        subject: {
          name: 'Test1',
        },
        type: {
          name: 'Test2',
        },
      },
    }));
    const modal = mount(NotificationPageCardModal, {
      ...injectVuetify(),
      mocks: {
        $service: {
          notification: {
            [ENotificationObjectType.VIOLATION]: {
              findOne: mock,
            },
          },
        },
      },
      propsData: {
        value: false,
      },
    });

    modal.setProps({
      value: true,
      item: { object: { name: 'Test1', id: 4, type: ENotificationObjectType.VIOLATION } },
    });

    Vue.nextTick().then(() => {
      Vue.nextTick().then(() => {
        expect(mock).toBeCalledWith(4);
        expect(modal.find('span').text()).toBe('Test1');
        expect(modal.findComponent(NotificationPageViolationCard).exists()).toBe(true);
      });
    });
  });
});
