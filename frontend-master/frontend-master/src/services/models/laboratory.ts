import { ISubjectItem, IFilterableList, IDictionaryNode, TInnerFilter } from './common';

/** Элемент списка лабораторий. */
export interface ILaboratoryItemResponse {
  created?: string;
  id?: number;
  subject?: ISubjectItem;
  subject_id?: number;
  head_subject_id?: number;
  reason_exclusion?: IDictionaryNode;
  registry_exclusion_date?: string;
  head_subject?: ISubjectItem;
  location_concat?: string;
  certificates_string?: string;
  include_date?: string;
  termination_date?: string;
  exclusion_date?: string;
  certificates?: any[];
  locations?: any[];
}

export interface ILaboratoryItemRequestOut {
  subject: Partial<ISubjectItem>;
  subject_id?: number;
  id?: number;
  head_subject?: Partial<ISubjectItem>;
}

export type TExcludeItem = {
  exclusion_reason: Partial<IDictionaryNode>;
  id: number;
  registry_exclusion_date: string;
};

/** Модель ответа /api/subject/manufacturer/manufacturers */
export type TLaboratoryListResponse = IFilterableList<ILaboratoryItemResponse>;

export type TLaboratoryInnerFilter = TInnerFilter & {
  name?: string;
  subject_type?: string;
  opf?: string;
  inn?: string;
  kpp?: string;
  ogrn?: string;
  locations?: string;
  certificates?: string;
  include_date_start?: string;
  include_date_end?: string;
  exclusion_date_start?: string;
  exclusion_date_end?: string;
  reason_exclusion?: string;
  actual?: boolean;
};