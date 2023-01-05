export type TStateAuthorityItem = {
  created: string;
  created_by: string;
  id: string;
  'inn/kpp': string;
  start_date: string;
  subject: TSubject;
  subject_id: number;
};

// ToDo: Вынести subjectType в общую типизацаю.
export type TSubject = {
  address?: any;
  address_text: string;
  country?: any;
  country_name: string;
  divisions: any[];
  endDate: string;
  endTime: string;
  end_date: string;
  esia_id?: any;
  first_name?: string;
  foreign_address: any;
  identity_doc: any;
  identity_doc_type: any;
  inn: string;
  inn_kpp: string;
  kpp: string;
  last_name: string;
  name: string;
  nza: string;
  ogrn?: any;
  opf_name: string;
  propertyMap?: any;
  second_name?: string;
  short_name?: string;
  startDate: string;
  startTime: string;
  start_date: string;
  subject_id: number;
  subject_type: string;
};

export type TPageable = {
  pageSize: number;
  pageNumber: number;
};
