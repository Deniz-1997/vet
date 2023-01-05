import { ISubjectItem, IFilterableList, TInnerFilter } from './common';

/** Элемент списка огв. */
export interface IStateAuthorityItemResponse {
  created?: string;
  id?: number;
  subject?: ISubjectItem;
  subject_id?: number;

}

export interface IStateAuthorityItemRequestOut {
  subject: Partial<ISubjectItem>;
  subject_id?: number;
  id?: number;
  head_subject?: Partial<ISubjectItem>;
}

export type TStateAuthorityListResponse = IFilterableList<IStateAuthorityItemResponse>;

export type TStateAuthorityInnerFilter = TInnerFilter & {
  name?: string;
  subject_type?: string;
  opf?: string;
  inn?: string;
  kpp?: string;
  ogrn?: string;
  oker_id?: string;
  include_date_start?: string;
  include_date_end?: string;
  exclusion_date_start?: string;
  exclusion_date_end?: string;
  reason_exclusion?: string;
  actual?: boolean;
};