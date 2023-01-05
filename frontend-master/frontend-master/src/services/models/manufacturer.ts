import { ESubjectType } from '../enums/subject';
import { ISubjectItem, IFilterableList, IDictionaryNode, TInnerFilter } from './common';

/** Элемент списка товаропроизводителей. */
export interface IManufacturerItemResponse {
  created?: string;
  id?: number;
  isProcessorText?: string;
  is_processor?: boolean;
  processor?: boolean;
  registry_inclusion_date?: string;
  subject?: ISubjectItem;
  subject_id?: number;
  exclusion_reason?: IDictionaryNode;
  registry_exclusion_date?: string;
  inn?: string;
  kpp?: string;
  ogrn?: string;
  opf?: string;
  name?: string;
  short_name?: string;
  address_text?: string;
  subject_type?: ESubjectType;
  region?: string;
  nza?: string;
  inn_kpp?: string;
}

export interface IManufacturerItemRequestOut {
  is_processor?: boolean;
  subject: Partial<ISubjectItem>;
  subject_id?: number;
  id?: number;
}

export type TExcludeItem = {
  exclusion_reason: Partial<IDictionaryNode>;
  id: number;
  registry_exclusion_date: string;
};

/** Модель ответа /api/subject/manufacturer/manufacturers */
export type TManufactureListResponse = IFilterableList<IManufacturerItemResponse>;

export type TManufacturerInnerFilter = TInnerFilter & {
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
  is_processor?: boolean;
};