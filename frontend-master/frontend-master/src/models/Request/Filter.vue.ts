import { constructByInterface } from '@/utils/construct-by-interface';

export interface FilterInterface {
  relation: string;
  field: string;
  operator: string;
  options: Array<FilterModel>;
  value: string | number | boolean | null;
}

export interface FilterRequestInterface {
  options: FilterInterface[];
}

export class FilterModel implements FilterInterface {
  relation!: string;
  field!: string;
  operator!: string;
  options!: Array<FilterModel>;
  value!: string | number | boolean | null;

  constructor(o?: FilterInterface) {
    constructByInterface(o, this);
  }
}
