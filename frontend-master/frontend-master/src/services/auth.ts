import { AxiosError, AxiosResponse } from 'axios';
import Cookie from 'js-cookie';
import { Service } from '@/plugins/service';
import { SubjectItem } from './mappers/auth';
import { ISubjectData } from './models/common';
import { Memoize } from '@/utils/global/decorators/method';
import { Polling } from '@/utils';

type TAuthCredentials = {
  login: string;
  password: string;
};

type TAuthTokens = {
  accessToken?: string;
  refreshToken?: string;
  ttl?: number;
  refreshTokenTtl?: number;
};

export default class Auth extends Service {
  private $refreshPromise: Promise<any> | null = null;

  get isLoggedIn(): boolean {
    return !!this.tokens.accessToken;
  }

  get tokens(): TAuthTokens {
    const accessToken = Cookie.get('access_token');
    const refreshToken = Cookie.get('refresh_token');

    return {
      accessToken,
      refreshToken,
    };
  }

  private setTokens({ accessToken, refreshToken, ttl, refreshTokenTtl }: TAuthTokens) {
    if (refreshToken) {
      const expires = !refreshTokenTtl ? 40 : new Date(Date.now() + refreshTokenTtl * 1000);
      Cookie.set('refresh_token', refreshToken, { expires, domain: location.hostname, path: '/' });
    }

    if (accessToken) {
      const expires = !ttl
        ? new Date(Date.now() + 30 * 60 * 1000)
        : new Date(Date.now() + (((refreshTokenTtl || ttl) + ttl) / 2) * 1000);
      Cookie.set('access_token', accessToken, {
        expires,
        domain: location.hostname,
        path: '/',
      });
    }
  }

  async getOrganizations() {
    try {
      const response = await this.$axios.post<ISubjectData[]>('/api/auth/user_organizations');
      return { ...response, data: response.data.map((item) => new SubjectItem(item).toJSON()) };
    } catch (_err) {
      return { data: [] };
    }
  }

  async setOrganization(id: number) {
    const response = await this.$axios.post('/api/auth/set_user_organization', { subject_id: id });
    this.setTokens(response.data);
    await this.restoreSession();
    return response;
  }

  saveSession(id: number) {
    const path = !this.$route.path.includes('login') ? this.$route.path : '/home';
    Cookie.set('@giszp/returnTo', `${id}:${path}`, {
      domain: location.hostname,
      path: '/',
      expires: new Date(Date.now() + 5 * 60 * 1000),
    });
  }

  async restoreSession() {
    const [id, path] = (Cookie.get('@giszp/returnTo') || '/home').split(':');
    Cookie.remove('@giszp/returnTo');

    const { data } = await this.$axios.post('/api/auth/userinfo');
    this.$store.commit('auth/setUserInfo', data);
    this.$router.push(id === String(data.id) ? path : '/home');
  }

  async login(credentials: TAuthCredentials) {
    const response = await this.$axios.post('/api/auth/login', credentials, { ignoreStatuses: [401, 403] });
    this.setTokens(response.data);
    return response;
  }

  async startEsiaLogin() {
    const response = await this.$axios.get('api/esia/authorize', { ignoreStatuses: [401, 403] });
    const url = new URL(response.data);
    if (process.env.NODE_ENV === 'development') {
      url.searchParams.set('redirect_uri', `${location.origin}/loginForm`);
    }
    window.open(String(url), '_self');
    return response;
  }

  async confirmEsiaLogin(params) {
    try {
      const response = await this.$axios.post('api/esia/callback', params, { ignoreStatuses: [401, 403] });
      this.setTokens(response.data);
      return response;
    } catch (err) {
      const { response } = err as unknown as AxiosError;

      if ([401, 403].includes(response?.status || -1)) {
        this.$router.push({
          path: '/login',
          query: { error: 'esia-confirm' } as any,
        });
      }

      throw err;
    }
  }

  async applyAgreement() {
    return this.$axios.post('/api/auth/personal_data_confirmation', {
      is_confirmed: true,
    });
  }

  async logout(): Promise<any> {
    const id = this.$store?.state?.auth?.user?.id;
    const response = await this.$axios('/api/auth/logout');
    Polling.stop();
    Cookie.remove('access_token', { domain: location.hostname, path: '/' });
    Cookie.remove('refresh_token', { domain: location.hostname, path: '/' });

    this.$store.commit('registryFilters/clearAllFilters');

    if (response.data) {
      const redirectUrl = new URL(response.data);

      if (process.env.NODE_ENV === 'development') {
        redirectUrl.searchParams.set('redirect_uri', `${location.origin}/login`);
        redirectUrl.searchParams.set('redirectUri', `${location.origin}/login`);
      }

      this.saveSession(id);
      window.open(redirectUrl, '_self');
    } else {
      await this.goLoginPage(id);
    }

    return response;
  }

  async refreshTokens(prevToken?: string) {
    const id = this.$store?.state?.auth?.user?.id;
    try {
      if (!this.tokens.refreshToken) {
        throw new Error();
      }

      if (this.$refreshPromise) {
        return this.$refreshPromise;
      }

      if (this.tokens.accessToken && (!prevToken || prevToken !== this.tokens.accessToken)) {
        return { data: this.tokens };
      }

      this.$refreshPromise = this.$axios.post('/api/auth/refresh', this.tokens);
      Cookie.remove('access_token', { domain: location.hostname, path: '/' });
      const response = await this.$refreshPromise;

      this.setTokens(response.data);
      this.$refreshPromise = null;

      return response;
    } catch (_) {
      this.goLoginPage(id);
      this.$refreshPromise = null;
    }
  }

  @Memoize()
  async updateRoleModel(canUpdateUser = this.isLoggedIn) {
    const isLoaded = this.$store.getters['auth/loaded'];

    if (isLoaded) return;

    const [matrix, titles, user] = await Promise.allSettled([
      this.$axios.get('/configs/role-model/accessMatrix.json'),
      this.$axios.get('/configs/role-model/titles.json'),
      canUpdateUser ? this.$axios.post('/api/auth/userinfo') : Promise.reject(),
    ]);

    if (matrix.status === 'fulfilled') {
      this.$store.commit('auth/setMatrix', matrix.value.data);
    }

    if (titles.status === 'fulfilled') {
      this.$store.commit('auth/setTitles', titles.value.data);
    }

    if (user.status === 'fulfilled') {
      this.$store.commit('auth/setUserInfo', user.value.data);
    }
  }

  async goLoginPage(id?: number) {
    if (id) {
      this.saveSession(id);
    }
    await this.$router.push({
      path: '/login',
    });
  }

  checkPageAccess(pathTo: string = this.$route.path, next?: any) {
    if (!this.$store.getters['auth/isPageAvailable'](pathTo)) {
      const callback = next || this.$router.push;
      callback('/home');
    } else if (next) {
      next();
    }
  }

  async getSmevVerification(id: number): Promise<AxiosResponse<any>> {
    const response = await this.$axios.get(`/api/smev/verification/${id}`);
    return response;
  }
}
