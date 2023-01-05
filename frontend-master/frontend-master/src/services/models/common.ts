import { ESubjectVerificationStatus, ESubjectType } from '@/services/enums/subject';
import { TableHeaders } from '@/components/common/DataTable/DataTable.types';
import { SelectItem } from '@/components/common/inputs/SelectComponent.vue';
import Validator from 'validatorjs';
import { IDictionaryRegions } from '@/services/models/catalogs';

export type TMapperPlain<T extends { toJSON(): any }> = ReturnType<T['toJSON']>;

export type OneOf<T> = T extends ReadonlyArray<infer U> ? U : never;

export type TIdentityNode = {
  series: string;
  id_number: string;
  type: IDictionaryNode;
  doc_date: string;
};

export type TFormValidationEvent = {
  isValid: boolean;
  errors: Validator.ValidationErrors;
  data: Record<string, any>;
};

export type IDictionaryNode<T = string, E = Record<string, never>> = {
  id: number;
  code: T;
  name: string;
  startDate: string;
  endDate?: string;
} & E;

export interface ISortOption {
  property: string;
  direction: 'ASC' | 'DESC';
}

export interface IFilter {
  filter?: string;
  pageable?: {
    pageSize?: number;
    pageNumber?: number;
    sort?: ISortOption[];
  };
  sort?: ISortOption[];
  actual?: boolean;
  is_processor?: boolean;
}

export interface IFilters {
  text?: string;
  placeholder?: string;
  value?: string;
  type?: string;
  list?: SelectItem[] | IDictionaryNode[] | IDictionaryRegions[];
  columns?: IFilters[];
  title?: string;
  label?: string;
  range?: boolean;
  limitTo?: string | number | Date;
  limitFrom?: string | number | Date;
  searchCallback?: any;
}

export type TInnerFilter = IFilter & {
  columns?: Partial<TableHeaders>[];
};

export interface IFilterableList<T> {
  content?: T[];
  first: boolean;
  last: boolean;
  number: number;
  numberOfElements: number;
  size: number;
  sort: ISortOption[];
  totalElements: number;
  totalPages: number;
}

export interface IOkerItem {
  nameOkato?: string;
  codeOkato?: string;
  subject_code?: string;
  name_okato?: string;
}

export interface IOpfItem {
  code: string;
  id: number;
  name: string;
  start_date: string;
}

export interface IAddressCountryItem {
  code: string;
  code_alpha2: string;
  code_alpha3: string;
  country_id: number;
  global_id: number;
  name: string;
  name_full: string;
  startDate: string;
  startTime: string;
  start_date: string;
}

export interface IAddressItem {
  div_type: string;
  additional_info?: string;
  // actstatus: number;
  // actual_status_name: string;
  address: string;
  address_object_type_name: string;
  aoguid: string;
  aoid: string;
  aolevel: number;
  areacode: string;
  // autocode: string;
  // centstatus: number;
  citycode: string;
  code: string;
  country?: IAddressCountryItem;
  // ctarcode: string;
  current_status_name: string;
  // currstatus: number;
  // divtype: number;
  enddate: string;
  // extrcode: string;
  fias_date: string;
  fiastype: string;
  name: string;
  full_address: string;
  guid: string;
  // ifnsfl: string;
  // ifnsul: string;
  // livestatus: number;
  // offname: string;
  okato: string;
  oker: IOkerItem;
  oktmo: string;
  operation_status_name: string;
  operstatus: number;
  placecode: string;
  // plaincode: string;
  plancode: string;
  // postalcode: string;
  postcode: string;
  regioncode: string;
  // sextcode: string;
  shortname: string;
  startdate: string;
  streetcode: string;
  type: string;
  updatedate: string;
  address_id: number;
}

export interface ISubjectItem {
  subject_data?: ISubjectData;
  address?: IAddressItem;
  address_text: string;
  end_date?: string;
  esia_id?: string;
  foreign_address?: string;
  identity_doc?: TIdentityNode;
  identity_doc_type?: string;
  oker?: IOkerItem;
  opf?: IOpfItem;
  opf_name?: IOpfItem['name'];
  start_date: string;
  subject_id: number;
  subject_type: ESubjectType;
  subject_verification_status?: IDictionaryNode<ESubjectVerificationStatus>;
  last_verification_date?: string;
  verification_status?: IDictionaryNode<ESubjectVerificationStatus>;

  // Remove this is type, after fix model subject
  country?: string;
  country_name?: string;
  first_name?: string;
  inn?: string;
  inn_kpp: string;
  kpp?: string;
  last_name?: string;
  name?: string;
  nza?: string;
  ogrn?: string;
  second_name?: string;
  short_name?: string;
  subject_data_id?: number;
  email?: string;
  phone_number?: string;
  created_by?: string;
  propertyMap: {
    is_laboratory?: boolean;
    is_manufacturer?: boolean;
    is_ogv?: boolean;
  };
  processor: boolean;
  is_processor: boolean;
  created: string;
  //
}

export interface ISubjectData {
  country?: string;
  country_name?: string;
  end_date?: string;
  first_name?: string;
  foreign_address?: string;
  inn?: string;
  inn_kpp: string;
  kpp?: string;
  last_name?: string;
  name: string;
  nza?: string;
  ogrn?: string;
  oker?: IOkerItem;
  opf?: IOpfItem;
  opf_name?: IOpfItem['name'];
  second_name?: string;
  short_name?: string;
  start_date: string;
  subject_id: number;
  subject_data_id: number;
  email?: string;
  phone_number?: string;
  created_by?: string;
  propertyMap?: TPropertyMap;
}

type TPropertyMap = {
  is_manufacturer?: boolean;
  is_laboratory?: boolean;
  is_ogv?: boolean;
};
