import Cookie from 'js-cookie';
import Auth from '@/services/auth';
import { noop } from 'lodash';

let tokens: any = {};
let ctx: any;

beforeEach(() => {
  tokens = {};
  ctx = {
    $axios: jest.fn(() =>
      Promise.resolve({
        data: {
          accessToken: 'access_from_axios',
          refreshToken: 'access_from_axios',
        },
      })
    ),
    $route: { path: '/', query: { returnTo: '/home' } },
    $router: {
      push: jest.fn(),
    },
    $store: {
      commit: jest.fn(),
      getters: {
        'auth/loaded': false,
      },
    },
  };

  ctx.$axios.post = jest.fn(() =>
    Promise.resolve({
      data: {
        accessToken: 'access_from_axios',
        refreshToken: 'access_from_axios',
      },
    })
  );

  jest.spyOn(Cookie, 'get').mockImplementation(((name) => tokens[name]) as any);
  jest.spyOn(Cookie, 'set').mockImplementation(((name, value) => (tokens[name] = value)) as any);
  jest.spyOn(Cookie, 'remove').mockImplementation(((name) => (tokens[name] = null)) as any);
});

describe('auth service', () => {
  test('returns tokens', () => {
    const auth = new Auth(ctx as any);

    tokens = {
      access_token: 'access',
      refresh_token: 'refresh',
    };

    expect(auth.tokens.accessToken).toEqual(tokens.access_token);
    expect(auth.tokens.refreshToken).toEqual(tokens.refresh_token);

    tokens = {
      access_token: 'access1',
      refresh_token: 'refresh1',
    };

    expect(auth.tokens.accessToken).toEqual(tokens.access_token);
    expect(auth.tokens.refreshToken).toEqual(tokens.refresh_token);

    tokens = {
      access_token: 'access2',
      refresh_token: 'refresh2',
    };

    expect(auth.tokens.accessToken).toEqual(tokens.access_token);
    expect(auth.tokens.refreshToken).toEqual(tokens.refresh_token);
  });

  test('watches login state', () => {
    const auth = new Auth(ctx as any);

    expect(auth.isLoggedIn).toBe(false);

    tokens.access_token = 'token';
    expect(auth.isLoggedIn).toBe(true);

    tokens.access_token = '';
    expect(auth.isLoggedIn).toBe(false);
  });

  test('sets tokens', () => {
    const auth = new Auth(ctx as any);

    const set = (auth as any).setTokens;

    expect(auth.tokens).toEqual({});

    set({ refreshToken: 2 });
    expect(auth.tokens).toEqual({ refreshToken: 2 });

    set({ accessToken: null });
    expect(auth.tokens).toEqual({ accessToken: undefined, refreshToken: 2 });

    set({ refreshToken: 5 });
    expect(auth.tokens).toEqual({ accessToken: undefined, refreshToken: 5 });
  });

  test('login', async () => {
    const auth = new Auth(ctx as any);

    const actual = auth.login({ login: 'login1', password: 'password1' });
    expect(ctx.$axios.post).toBeCalledWith(
      '/api/auth/login',
      { login: 'login1', password: 'password1' },
      { ignoreStatuses: [401, 403] }
    );
    expect(actual).toBeInstanceOf(Promise);
    expect((await actual).data).toEqual(auth.tokens);
  });

  test('refresh tokens', async () => {
    const auth = new Auth(ctx as any);

    const mock = jest.spyOn(auth, 'goLoginPage').mockImplementationOnce(noop);
    auth.refreshTokens();

    expect(mock).toBeCalled();

    tokens.refresh_token = 'token';
    const actual = auth.refreshTokens();
    expect(ctx.$axios.post).toBeCalledWith('/api/auth/refresh', { refreshToken: 'token' });
    expect((await actual)?.data).toEqual(auth.tokens);
  });

  test('logout', async () => {
    const auth = new Auth(ctx as any);
    tokens = {
      access_token: 'ololo',
      refresh_token: 'trololo',
    };

    const mock = jest.spyOn(auth, 'goLoginPage').mockImplementationOnce(noop);
    await auth.logout();

    expect(ctx.$axios).toBeCalledWith('/api/auth/logout');
    expect(mock).toBeCalled();
    expect(auth.tokens.accessToken).toBeNull();
    expect(auth.tokens.refreshToken).toBeNull();
  });

  test('go login page', () => {
    const auth = new Auth(ctx as any);

    auth.goLoginPage();

    expect(ctx.$router.push).toBeCalledWith({ path: '/login' });
  });

  test('update role model', async () => {
    ctx.$axios.get = jest.fn((path) =>
      Promise.resolve({
        data: [path],
      })
    );

    const auth = new Auth(ctx as any);
    await auth.updateRoleModel();

    expect(ctx.$axios.get).toBeCalledTimes(2);
    expect(ctx.$axios.get).toBeCalledWith('/configs/role-model/accessMatrix.json');
    expect(ctx.$axios.get).toBeCalledWith('/configs/role-model/titles.json');
    expect(ctx.$store.commit).toBeCalledWith('auth/setMatrix', ['/configs/role-model/accessMatrix.json']);
    expect(ctx.$store.commit).toBeCalledWith('auth/setTitles', ['/configs/role-model/titles.json']);
  });

  test('apply agreement', async () => {
    ctx.$axios.post = jest.fn();

    const auth = new Auth(ctx as any);
    await auth.applyAgreement();

    expect(ctx.$axios.post).toBeCalledWith('/api/auth/personal_data_confirmation', {
      is_confirmed: true,
    });
  });

  test('get organizations', async () => {
    ctx.$axios.post = jest.fn(() => Promise.resolve({ data: [] }));

    const auth = new Auth(ctx as any);
    await auth.getOrganizations();

    expect(ctx.$axios.post).toBeCalledWith('/api/auth/user_organizations');
  });

  test('set organization', async () => {
    ctx.$axios.post = jest.fn(() =>
      Promise.resolve({
        data: {
          accessToken: 'access_from_axios',
          refreshToken: 'access_from_axios',
        },
      })
    );

    const auth = new Auth(ctx as any);
    await auth.setOrganization(100);

    expect(ctx.$axios.post).toBeCalledWith('/api/auth/set_user_organization', { subject_id: 100 });
    expect(tokens).toMatchObject({ access_token: 'access_from_axios', refresh_token: 'access_from_axios' });
  });

  test('restore session', async () => {
    ctx.$axios.post = jest.fn(() => Promise.resolve({ data: [] }));

    const auth = new Auth(ctx as any);

    await auth.restoreSession();
    expect(ctx.$router.push).toBeCalledWith('/home');

    ctx.$route.query.returnTo = null;
    await auth.restoreSession();
    expect(ctx.$router.push).toBeCalledWith('/home');
  });

  test('start esia login', async () => {
    const mockOpen = jest.spyOn(window, 'open').mockImplementation(noop as any);
    ctx.$axios.get = jest.fn(() => Promise.resolve({ data: 'http://test.test/test?test=test' }));

    const auth = new Auth(ctx as any);
    await auth.startEsiaLogin();
    expect(ctx.$axios.get).toBeCalledWith('api/esia/authorize', { ignoreStatuses: [401, 403] });
    expect(mockOpen).toBeCalledWith('http://test.test/test?test=test', '_self');

    mockOpen.mockClear();
    ctx.$axios.get = jest.fn(() => Promise.reject({ data: 'http://test.test/test?test=test' }));
    try {
      await auth.startEsiaLogin();
      expect(false).toBe(true);
    } catch (_) {
      expect(mockOpen).not.toBeCalled();
    }

    process.env.NODE_ENV = 'development';
    ctx.$axios.get = jest.fn(() => Promise.resolve({ data: 'http://test.test/test?test=test' }));
    await auth.startEsiaLogin();
    expect(mockOpen).toBeCalledWith(
      'http://test.test/test?test=test&redirect_uri=http%3A%2F%2Flocalhost%2FloginForm',
      '_self'
    );

    process.env.NODE_ENV = 'test';
  });

  test('confirm esia login', async () => {
    tokens = {
      '@giszp/returnTo': '/home',
    };

    const auth = new Auth(ctx as any);
    await auth.confirmEsiaLogin({ login: 'login', password: 'password' });
    expect(ctx.$axios.post).toBeCalledWith(
      'api/esia/callback',
      { login: 'login', password: 'password' },
      { ignoreStatuses: [401, 403] }
    );
    expect(tokens).toMatchObject({ access_token: 'access_from_axios', refresh_token: 'access_from_axios' });

    ctx.$axios.post = jest.fn(() => Promise.reject({ response: { status: 401 } }));
    try {
      await auth.confirmEsiaLogin({ login: 'login', password: 'password' });
      expect(false).toBe(true);
    } catch (_) {
      expect(ctx.$router.push).toBeCalledWith({
        path: '/login',
        query: { error: 'esia-confirm' },
      });
    }

    ctx.$axios.post = jest.fn(() => Promise.reject({ response: { status: 403 } }));
    try {
      await auth.confirmEsiaLogin({ login: 'login', password: 'password' });
      expect(false).toBe(true);
    } catch (_) {
      expect(ctx.$router.push).toBeCalledWith({
        path: '/login',
        query: { error: 'esia-confirm' },
      });
    }

    ctx.$axios.post = jest.fn(() => Promise.reject({ response: {} }));
    try {
      await auth.confirmEsiaLogin({ login: 'login', password: 'password' });
      expect(false).toBe(true);
    } catch (err) {
      expect(err).toEqual({ response: {} });
    }
  });
});
