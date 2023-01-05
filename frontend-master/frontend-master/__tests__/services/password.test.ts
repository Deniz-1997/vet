import { PasswordChangeOut } from './../../src/services/mappers/password';
import Password from '@/services/password';

let service: Password;
let ctx = {
  $axios: {
    get: jest.fn(),
    post: jest.fn(),
  },
  $router: {
    push: jest.fn(),
  },
};

beforeEach(() => {
  ctx = {
    $axios: {
      get: jest.fn(),
      post: jest.fn(),
    },
    $router: {
      push: jest.fn(),
    },
  };
  service = new Password(ctx as any);
});

describe('password service', () => {
  test('change', async () => {
    const data = { userId: 123, password: 'test', confirmPassword: 'test' };
    await service.change(data);
    expect(ctx.$axios.post).toBeCalledWith('/api/security/user/changePassword', expect.any(PasswordChangeOut));
  });

  test('applyReset', async () => {
    await service.applyReset('login');
    expect(ctx.$axios.get).toBeCalledWith('api/auth/password/reset-request/login');
  });

  test('reset', async () => {
    const data = { uuid: 'test', password: 'test', confirmPassword: 'test' };
    await service.reset(data);

    expect(ctx.$axios.post).toBeCalled();
    expect(ctx.$router.push).toBeCalledWith('/login');
  });

  test('redirects to login on error', async () => {
    try {
      ctx.$axios.post.mockImplementationOnce(() => Promise.reject());
      const data = { uuid: 'test', password: 'test', confirmPassword: 'test' };
      await service.reset(data);
    } catch (_err) {
      expect(ctx.$axios.post).toBeCalled();
      expect(ctx.$router.push).toBeCalledWith('/login?error=password-recovery');
    }
  });
});
