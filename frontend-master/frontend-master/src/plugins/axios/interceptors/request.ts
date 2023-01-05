import Utils from '@/plugins/axios/utils';
import { TRequestHandler } from '@/plugins/axios/models';

const setToken: TRequestHandler = function (config) {
  const { accessToken } = this.$service.auth.tokens;

  if (accessToken) {
    config.headers.Authorization = `Bearer ${accessToken}`;
  }

  return config;
};

export default Utils.wrapHandlers([setToken], 'REQUEST');
