import { SubjectItem } from '@/services/mappers/auth';
import { checkMapper } from './__utils__/checkMapper';

describe('notifications mappers', () => {
  test('SubjectItem', () => {
    checkMapper(
      SubjectItem,
      {
        subject_id: 56,
        name: 'some_name',
        inn: 'test',
      },
      {
        id: 56,
        name: 'some_name  (test)',
        inn: 'test',
        ogrn: undefined,
        kpp: undefined,
      }
    );
    checkMapper(
      SubjectItem,
      {
        subject_id: 516,
        short_name: 'some_name1',
        ogrn: 'test2',
      },
      {
        id: 516,
        name: 'some_name1',
        inn: undefined,
        ogrn: 'test2',
        kpp: undefined,
      }
    );
  });
});
