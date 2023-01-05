import { Filter, FilterOut } from '@/services/mappers/common';
import { checkMapper } from './__utils__/checkMapper';

describe('common mappers', () => {
  test('Filter', () => {
    checkMapper(Filter, { numberOfElements: 20, totalElements: 10 }, { count: 20, total: 10 });
    checkMapper(Filter, { numberOfElements: 120, totalElements: 1 }, { count: 120, total: 1 });
    checkMapper(Filter, { numberOfElements: 3, totalElements: 2 }, { count: 3, total: 2 });
    checkMapper(Filter, { numberOfElements: 6, totalElements: 4 }, { count: 6, total: 4 });
    checkMapper(Filter, { numberOfElements: 1, totalElements: 1 }, { count: 1, total: 1 });
  });

  test('FilterOut', () => {
    checkMapper(
      FilterOut,
      { actual: false, filter: 'trololo', pageable: { pageSize: 2, pageNumber: 3 } },
      { actual: false, filter: 'trololo', pageable: { pageSize: 2, pageNumber: 3 } } as any
    );
    checkMapper(
      FilterOut,
      { actual: false, filter: 'trololo', pageable: { pageSize: 2, pageNumber: 3 }, columns: [] },
      { actual: false, filter: 'trololo', pageable: { pageSize: 2, pageNumber: 3 } } as any
    );
    checkMapper(
      FilterOut,
      { actual: true, filter: '', pageable: { pageSize: 3, pageNumber: 2 }, columns: [] },
      { actual: true, filter: '', pageable: { pageSize: 3, pageNumber: 2 } } as any
    );
  });
});
