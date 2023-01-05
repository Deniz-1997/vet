import { ViolationItem } from '@/services/mappers/violation';
import { checkMapper } from './__utils__/checkMapper';

describe('notifications mappers', () => {
  test('ViolationItem', () => {
    checkMapper(
      ViolationItem,
      {
        id: 5,
        created: '12.12.2022 22:12',
        difference: 'test',
        violation_type: {
          id: 12,
          code: 'TEST_TYPE',
          name: 'test_type',
        } as any,
        subject: {
          subject_id: 56,
          name: 'some_name',
          last_verification_date: '12.12.2012',
        } as any,
      },
      {
        id: 5,
        created: '2022-12-12T19:12:00.000Z',
        difference: 'test',
        type: {
          id: 12,
          code: 'TEST_TYPE' as any,
          name: 'test_type',
        },
        subject: {
          id: 56,
          name: 'some_name',
          lastVerificationDate: '2012-12-11T20:00:00.000Z',
        },
      }
    );
    checkMapper(
      ViolationItem,
      {
        id: 53442,
        created: '22.12.2022 22:12',
        difference: '1test3',
        violation_type: {
          id: 123,
          code: '1TEST_TYPE',
          name: 'test_type1',
        } as any,
        subject: {
          subject_id: 516,
          short_name: 'some_name1',
          last_verification_date: '12.12.2012',
        } as any,
      },
      {
        id: 53442,
        created: '2022-12-22T19:12:00.000Z',
        difference: '1test3',
        type: {
          id: 123,
          code: '1TEST_TYPE' as any,
          name: 'test_type1',
        },
        subject: {
          id: 516,
          name: 'some_name1',
          lastVerificationDate: '2012-12-11T20:00:00.000Z',
        },
      }
    );
  });
});
