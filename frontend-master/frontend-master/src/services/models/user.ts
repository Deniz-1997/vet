import { ISubjectItem } from '@/services/models/common';
import { TRoleResponseItem } from '@/services/models/roles';

export interface IRoleSubjectItemResponse {
  subject: ISubjectItem;
  created: string;
  created_by: string;
  role: TRoleResponseItem[];
  start_date: string;
}

export interface IRoleSubjectItemRequest {
  subject: Pick<ISubjectItem, 'subject_id'>;
  subject_id: number;
  created?: string;
  created_by?: string;
  role: Pick<TRoleResponseItem, 'role_id'>[];
  start_date?: string;
}

export interface IUserItemResponse {
  active_from: string;
  created: string;
  created_by: string;
  first_name: string;
  last_name: string;
  second_name?: string;
  full_name?: string;
  login: string;
  email?: string;
  snils?: string;
  status: number;
  statusName: string;
  subject: IRoleSubjectItemResponse[];
  subject_names: string;
  user_id: number;
}

export interface IUserItemRequest {
  first_name: string;
  last_name: string;
  second_name?: string;
  full_name?: string;
  login: string;
  email?: string;
  snils?: string;
  subject?: IRoleSubjectItemRequest[];
  user_id: number;
}
