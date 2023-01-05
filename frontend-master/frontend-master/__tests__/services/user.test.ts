import { UserRequestOut } from './../../src/services/mappers/user';
import User from '@/services/user';
import { ESubjectType } from '@/services/enums/subject';
import { ERole } from '@/models/roles';
const data = {
  user_id: 1231,
  first_name: 'First1',
  last_name: 'Last1',
  second_name: 'Father1',
  email: 'first@last.father1',
  login: 'first.father1',
  subject: [
    {
      subject: {
        subject_id: 2536,
        subject_type: ESubjectType.IR,
        name: 'BIC X',
        opf: undefined,
        opf_name: undefined,
        ogrn: undefined,
        inn: undefined,
        kpp: undefined,
        short_name: undefined,
        last_name: undefined,
        first_name: undefined,
        second_name: undefined,
        country: undefined,
        country_name: undefined,
        nza: undefined,
        identity_doc: undefined,
        identity_doc_type: undefined,
        address: undefined,
        address_text: '',
        foreign_address: undefined,
        start_date: '01.03.2022 07:46',
        end_date: undefined,
        last_verification_date: '02.05.2022 00:00',
        esia_id: undefined,
        subject_verification_status: undefined,
        oker: undefined,
        inn_kpp: '',
      },
      start_date: '08.04.2022 14:48',
      created: '08.04.2022 14:48',
      created_by: 'admin',
      role: [
        {
          role_id: 313,
          role: ERole.ROLE_SECURITY_ADMIN,
          description: 'Администратор безопасности',
          active_from: '18.01.2022 00:50',
        },
        {
          role_id: 314,
          role: ERole.ROLE_LABORATORY,
          description: 'Сотрудник, ответственный за ведение реестра лабораторий',
          active_from: '18.01.2022 00:52',
        },
      ],
    },
  ],
};
let service: User;
let ctx = {
  $axios: {
    get: jest.fn(() => Promise.resolve({ data })),
    post: jest.fn(() => Promise.resolve({ data })),
  },
};

beforeEach(() => {
  ctx = {
    $axios: {
      get: jest.fn(() => Promise.resolve({ data })),
      post: jest.fn(() => Promise.resolve({ data })),
    },
  };
  service = new User(ctx as any);
});

describe('password service', () => {
  test('findOne', async () => {
    await service.findOne(123);
    expect(ctx.$axios.post).toBeCalledWith('/api/security/user/show', { id: 123 });
  });

  test('find current', async () => {
    await service.findOne();
    expect(ctx.$axios.get).toBeCalledWith('/api/security/user/currentUser');
  });

  test('update', async () => {
    await service.update({
      id: 123,
      firstName: 'First',
      lastName: 'Last',
      fatherName: 'Father',
      email: 'first@last.father',
      login: 'first.father',
      subjects: [],
    });
    expect(ctx.$axios.post).toBeCalledWith('/api/security/user/update', expect.any(UserRequestOut));
  });
});
