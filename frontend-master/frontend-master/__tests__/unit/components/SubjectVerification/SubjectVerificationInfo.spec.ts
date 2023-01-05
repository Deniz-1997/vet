import Vue from 'vue';
import SubjectVerificationInfo from '@/components/SubjectVerification/SubjectVerificationInfo.vue';
import { mount } from '@vue/test-utils';
import cases from './__fixtures__/cases';

interface IComponent extends Vue {
  isLoading: boolean;
}

const mockAxios = (data) => ({
  $axios: { get: jest.fn(() => ({ data })) },
});

const checkCase = async (data) => {
  const component = mount<IComponent>(SubjectVerificationInfo, {
    mocks: mockAxios({ violation: data }),
  });

  const date = () => component.find('div[data-qa="subject-verification-info__date"]');
  const type = () => component.find('div[data-qa="subject-verification-info__type"]');
  const difference = () => component.find('div[data-qa="subject-verification-info__difference"]');

  expect(date().exists()).toBe(false);
  expect(type().exists()).toBe(false);
  expect(difference().exists()).toBe(false);

  await Vue.nextTick();

  expect(date().text()).toBe(`Дата последней проверки: ${data.subject.last_verification_date.slice(0, 10)}`);
  expect(type().text()).toBe(`Тип выявленного нарушения: ${data.violation_type.name}`);
  expect(difference().text()).toBe(`Выявленные расхождения:\n        ${data.difference}`);
};

describe('SubjectVerificationInfo.vue', () => {
  test('render', async () => {
    await checkCase(cases[0]);
    await checkCase(cases[1]);
    await checkCase(cases[2]);
    await checkCase(cases[3]);
    await checkCase(cases[4]);
  });

  test('use fallback date', async () => {
    const component = mount<IComponent>(SubjectVerificationInfo, {
      mocks: mockAxios({
        violation: {
          created: '11.04.2012 12:12',
          id: 14,
          difference: 'TEST 5 difference between us',
          subject: {
            subject_id: 34,
            short_name: 'TEST 5 ООО "Моя оборона"',
          },
          violation_type: {
            id: 24,
            description: 'TEST 5 the dead weather',
            name: 'TEST 5 VIOLATION',
            code: 'TEST 5 VIOLATION',
            startDate: '13.04.2012 12:12',
            endDate: '14.04.2012 12:12',
          },
        },
      }),
    });

    await Vue.nextTick();

    expect(component.find('div[data-qa="subject-verification-info__date"]').text()).toBe(
      'Дата последней проверки: 11.04.2012'
    );
  });
});
