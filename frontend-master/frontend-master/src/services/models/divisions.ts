import { IFilterableList, ISubjectItem } from './common';

/** Элемент списка огв. */
export interface IDivisionsItemResponse {
  parent_division?: any;
  root_division_name?: string;
  name?: string;
  division_staff?: any[];
  subject_division_id: number;
  division_staff_user_full_names?: string;
  subject: ISubjectItem;
}
export type TDivisionsListResponse = IFilterableList<IDivisionsItemResponse>;
