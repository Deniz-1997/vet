import { EAuthority, ERole } from '@/models/roles';
import { RoleItem } from '@/services/mappers/roles';
import { fixtures } from '../__fixtures__/roles';
import { checkMapper } from './__utils__/checkMapper';

const expected: ReturnType<RoleItem['toJSON']>[] = [
  {
    id: 1,
    code: ERole.ROLE_ADMIN,
    name: 'Администратор',
    description: 'Администратор',
    authorities: [],
    endDate: undefined,
  },
  {
    id: 2,
    code: ERole.ROLE_AGENT_USER,
    name: 'Не администратор',
    description: 'Администратор 1',
    authorities: [{
      id: 1,
      name: 'полномочие тест',
      code: EAuthority.MANAGE_CONTRACT,
      description: 'полномочие описание',
    }],
    endDate: undefined,
  },
  {
    id: 3,
    code: ERole.ROLE_ADMIN,
    name: 'Администратор',
    description: 'Администратор',
    authorities: [],
    endDate: '2012-12-12T08:12:00.000Z',
  },
];

describe('roles mappers', () => {
  test('RoleItem', () => {
    fixtures.forEach((fixture, index) => checkMapper(RoleItem, fixture, expected[index]));
  });
});
