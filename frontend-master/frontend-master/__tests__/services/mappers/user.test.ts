import { ERole } from './../../../src/models/roles';
import { UserItem, UserRequestOut } from '@/services/mappers/user';
import { checkMapper } from './__utils__/checkMapper';
import { ESubjectType } from '@/services/enums/subject';

describe('password mappers', () => {
  test('UserItem', () => {
    checkMapper(
      UserItem,
      {
        user_id: 123,
        first_name: 'First',
        last_name: 'Last',
        second_name: 'Father',
        email: 'first@last.father',
        login: 'first.father',
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
      },
      {
        id: 123,
        firstName: 'First',
        lastName: 'Last',
        fatherName: 'Father',
        email: 'first@last.father',
        login: 'first.father',
        subjects: expect.any(Array),
      }
    );
    checkMapper(
      UserItem,
      {
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
      },
      {
        id: 1231,
        firstName: 'First1',
        lastName: 'Last1',
        fatherName: 'Father1',
        email: 'first@last.father1',
        login: 'first.father1',
        subjects: expect.any(Array),
      }
    );
  });

  test('UserRequestOut', () => {
    checkMapper(
      UserRequestOut,
      {
        id: 1231,
        firstName: 'First1',
        lastName: 'Last1',
        fatherName: 'Father1',
        email: 'first@last.father1',
        login: 'first.father1',
        subjects: [
          {
            id: 123,
            roles: [{ id: 1 }, { id: 2 }],
          },
        ] as any,
      },
      {
        user_id: 1231,
        first_name: 'First1',
        last_name: 'Last1',
        second_name: 'Father1',
        email: 'first@last.father1',
        login: 'first.father1',
        subject: [
          {
            subject_id: 123,
            subject: { subject_id: 123 },
            role: [{ role_id: 1 }, { role_id: 2 }],
          },
        ],
      }
    );
    checkMapper(
      UserRequestOut,
      {
        id: 123,
        firstName: 'First',
        lastName: 'Last',
        fatherName: 'Father',
        email: 'first@last.father',
        login: 'first.father',
        subjects: [],
      },
      {
        user_id: 123,
        first_name: 'First',
        last_name: 'Last',
        second_name: 'Father',
        email: 'first@last.father',
        login: 'first.father',
        subject: [],
      }
    );
  });
});
