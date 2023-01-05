import NotificationPageViolationCard from '@/components/NotificationPage/cards/NotificationPageViolationCard.vue';
import { shallowMount } from '@vue/test-utils';

describe('NotificationPageViolationCard.vue', () => {
  test('render', () => {
    const card = shallowMount(NotificationPageViolationCard, {
      propsData: {
        item: {
          difference: 'Test3',
          subject: {
            name: 'Test1',
          },
          type: {
            name: 'Test2',
          },
        },
      },
    });

    expect(card.findAll('span').at(0).text()).toBe('Организация: Test1');
    expect(card.findAll('span').at(1).text()).toBe('Test2: Test3');
  });
});
