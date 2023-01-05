import { ESubjectType } from '@/services/enums/subject';
import { ISubjectItem, TInnerFilter } from '@/services/models/common';

/** Элемент списка организации. */
export interface ISubjectItemResponse {
  created?: string;
  created_by?: string;
  end_date?: string;
  first_name?: string;
  inn?: string;
  inn_kpp?: string;
  kpp?: string;
  last_name?: string;
  name?: string;
  ogrn?: string;
  second_name: null;
  short_name?: string;
  start_date?: string;
  subject_id?: number;
  subject_type?: ESubjectType;
  updated?: string;
  updated_by?: string;
  opf_name?: string;
  propertyMap?: any;
  region?: string;
}

export type TSubjectInnerFilter = TInnerFilter & {
  registry?: boolean;
  name?: string;
  subject_type?: string;
  opf?: string;
  inn?: string;
  kpp?: string;
  ogrn?: string;
  oker_id?: string;
  in_registry?: string;
  actual: boolean;
};

export interface ILabSubjectItemResponse {
  subject: ISubjectItem;
  lab_include_date?: string;
  exclusion_date?: string;
  reason_exclusion?: string;
  certificates_string?: string;
  location_concat?: string;
  lab_certificates?: string;
  lab_locations?: any[];
  lab_head_subject?: ISubjectItemResponse;
}
