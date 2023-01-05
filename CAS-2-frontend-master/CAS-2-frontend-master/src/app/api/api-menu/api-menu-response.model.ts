import {constructByInterface} from '../api-connector/api-connector.utils';

export interface ApiMenuResponseResponseInterface<T> {
  items: Array<T>;
  totalCount: number;
  columns: number;
}

export class ApiMenuResponse<T> {
  status;
  errors;
  requestId: string;
  response: ApiMenuResponseResponseInterface<T>;

  constructor(o: ApiMenuResponse<T>) {
    constructByInterface(o, this);
  }
}
