import { Mapper } from '@/utils';
import { IFilterableList, TInnerFilter } from '@/services/models/common';

export class Filter extends Mapper<IFilterableList<any>> {
  @Mapper.catch()
  get count() {
    return this.get(({ numberOfElements }) => numberOfElements).required.value;
  }

  @Mapper.catch()
  get total() {
    return this.get(({ totalElements }) => totalElements).required.value;
  }

  toJSON() {
    return {
      count: this.count,
      total: this.total,
    };
  }
}

export class FilterOut<T extends TInnerFilter = TInnerFilter> extends Mapper<T> {
  @Mapper.catch()
  get filter() {
    return this.get(({ filter }) => filter).optional.value;
  }

  @Mapper.catch()
  get is_processor() {
    return this.get(({ is_processor }) => is_processor).optional.value;
  }

  @Mapper.catch()
  get pageable() {
    return this.get(({ pageable }) => pageable).optional.value;
  }

  @Mapper.catch()
  get actual() {
    return this.get(({ actual }) => actual).optional.value;
  }

  toJSON() {
    return {
      filter: this.filter,
      pageable: this.pageable,
      actual: this.actual,
      is_processor: this.is_processor,
    };
  }
}
