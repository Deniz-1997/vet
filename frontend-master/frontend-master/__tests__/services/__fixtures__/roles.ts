import { EAuthority, ERole } from '@/models/roles';
import { TRoleResponseItem } from '@/services/models/roles';

export const fixtures: TRoleResponseItem[] = [
  {
    role_id: 1,
    role: ERole.ROLE_ADMIN,
    description: 'Администратор',
    active_from: '12.12.2012 12:12',
    authority: [],
  },
  {
    role_id: 2,
    role: ERole.ROLE_AGENT_USER,
    name: 'Не администратор',
    description: 'Администратор 1',
    active_from: '12.12.2012 12:12',
    authority: [{
      authority_id: 1,
      authority_name: 'полномочие тест',
      code: EAuthority.MANAGE_CONTRACT,
      description: 'полномочие описание',
      activeFrom: '12.12.2012 12:12',
      created: '12.12.2012 12:12',
    }],
  },
  {
    role_id: 3,
    role: ERole.ROLE_ADMIN,
    description: 'Администратор',
    active_from: '12.12.2012 12:12',
    authority: [],
    deleted_date: '12.12.2012 12:12',
  },
];