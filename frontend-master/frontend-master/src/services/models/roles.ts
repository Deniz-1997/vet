import { EAuthority, ERole } from '@/models/roles';

export type TAuthorityResponseItem = {
  authority_id: number;
  authority_name: string;
  description: string;
  code: EAuthority;
  created: string;
  activeFrom: string;
};

export type TRoleResponseItem = {
  role_id: number;
  role: ERole;
  name?: string;
  description: string;
  active_from: string;
  deleted_date?: string;
  authority?: TAuthorityResponseItem[];
};
