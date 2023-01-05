import { AxiosError } from 'axios';
import Utils from '@/plugins/axios/utils';
import { TErrorHandler } from '@/plugins/axios/models';

const unauthorized: TErrorHandler = {
  status: 401,
  async callback(error: AxiosError) {
    const { config } = error;
    const isRefreshRequest = config.url?.endsWith('/api/auth/refresh');
    const isAuthorizedPage = this.$route.meta?.auth !== false;

    if (isRefreshRequest) {
      this.$service.auth.logout();

      throw error;
    }

    if (!config.refreshTokens) {
      const token = (config.headers.Authorization || '').slice(7);
      await this.$service.auth.refreshTokens(token);

      return await this.$axios({ ...config, refreshTokens: true });
    }

    if (isAuthorizedPage) {
      this.$service.auth.logout();

      throw error;
    }
  },
};

const showNetworkErrorModal: TErrorHandler = function (error: AxiosError) {
  if (!error.response && error.isAxiosError && !Utils.isCancel(error)) {
    this.$service.notify.push('message', { text: 'Network Error', params: { type: 'network-error' } });
    throw error;
  }
};

const showPopup: TErrorHandler = function (error: AxiosError) {
  const { response } = error;

  const texts = {
    403: 'Недостаточно прав, обратитесь к администратору.',
    default: 'Что-то пошло не так. Мы уже знаем о проблеме и занимаемся её устранением.',
  };

  let text =
    texts[response?.status || ''] ||
    response?.data?.messages ||
    response?.data?.message ||
    (typeof response?.data === 'string' && response.data) ||
    texts.default;

  if (response?.status) {
    text = `(${response.status}) ${text}`;
  }

  error.notifyId = this.$service.notify.push('error', { text, params: { error } });
  throw error;
};

export default Utils.wrapHandlers([unauthorized, showNetworkErrorModal, showPopup], 'ERROR');
