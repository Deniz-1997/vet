import { PasswordChangeOut, PasswordRecoveryOut } from '@/services/mappers/password';
import { checkMapper } from './__utils__/checkMapper';

describe('password mappers', () => {
  test('PasswordRecoveryOut', () => {
    checkMapper(
      PasswordRecoveryOut,
      {
        uuid: 'test 1',
        password: 'test 1',
        confirmPassword: 'test 1',
      },
      {
        uuid: 'test 1',
        new_password: 'test 1',
        new_password_repeat: 'test 1',
      }
    );
    checkMapper(
      PasswordRecoveryOut,
      {
        uuid: 'test 2',
        password: 'test 2',
        confirmPassword: 'test 2',
      },
      {
        uuid: 'test 2',
        new_password: 'test 2',
        new_password_repeat: 'test 2',
      }
    );
  });

  test('PasswordChangeOut', () => {
    checkMapper(
      PasswordChangeOut,
      {
        userId: 123,
        password: 'test 1',
        confirmPassword: 'test 1',
      },
      {
        user_id: 123,
        new_password: 'test 1',
        new_password_repeat: 'test 1',
      }
    );
    checkMapper(
      PasswordChangeOut,
      {
        userId: 321123,
        password: 'test 2',
        confirmPassword: 'test 2',
      },
      {
        user_id: 321123,
        new_password: 'test 2',
        new_password_repeat: 'test 2',
      }
    );
  });
});
