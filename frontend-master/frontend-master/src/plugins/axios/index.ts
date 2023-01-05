import Vue, { PluginObject } from 'vue';
import debounce from 'lodash/debounce';
import axios from 'axios';
import Utils from '@/plugins/axios/utils';
import requests from '@/plugins/axios/interceptors/request';
import responses from '@/plugins/axios/interceptors/response';
import errors from '@/plugins/axios/interceptors/error';
import { XHRLayout } from '@/core/utils/xhr';

const store = {
  request: new Map<string, (...args: any[]) => any>(),
  response: new Map<string, Set<(...args: any[]) => any>>(),
  reject: new Map<string, Set<(...args: any[]) => any>>(),
};

const getFileKey = (config) => {
  return JSON.stringify({
    ...config,
    data: [...(config.data as FormData).entries()].map(([key, value]: [string, any]) => ({
      [key]: value.name + value.size,
    })),
  });
};

const saveRequest = (resolve, reject, config) => {
  const key = config.data instanceof FormData ? getFileKey(config) : JSON.stringify(config);

  if (!store.response.has(key)) {
    store.response.set(key, new Set());
  }
  if (!store.reject.has(key)) {
    store.reject.set(key, new Set());
  }
  if (!store.request.has(key)) {
    store.request.set(
      key,
      debounce(
        async () => {
          try {
            const response = await axios.defaults.adapter?.(config);
            store.response.get(key)?.forEach((fn) => fn(response));
            store.response.get(key)?.clear();
          } catch (err) {
            store.reject.get(key)?.forEach((fn) => fn(err));
            store.reject.get(key)?.clear();
          }
        },
        config.url.includes('create') || config.url.includes('update') ? 1000 : 200
      )
    );
  }

  store.response.get(key)?.add(resolve);
  store.reject.get(key)?.add(reject);
  store.request.get(key)?.();
};

/** Регистрация axios и добавление в контекст приложения. */
export default (ctx: Vue): PluginObject<never> => ({
  install(constructor) {
    // Определение базового пути API из переменных окружения.
    const instance = axios.create({
      adapter: (config) => {
        return new Promise((resolve, reject) => {
          saveRequest(resolve, reject, config);
        });
      },
    });

    // Регистрация перехватчиков запроса.
    instance.interceptors.request.use(Utils.createInterceptor(requests, ctx));
    axios.interceptors.request.use(Utils.createInterceptor(requests, ctx));
    // Регистрация перехватчиков ответа.
    instance.interceptors.response.use(Utils.createInterceptor(responses, ctx), Utils.createInterceptor(errors, ctx));
    axios.interceptors.response.use(Utils.createInterceptor(responses, ctx), Utils.createInterceptor(errors, ctx));
    // Регистрация $axios в контекст.
    constructor.prototype.$axios = instance;

    // TODO: Dirtyhack, пока не переедем
    XHRLayout.axios = instance;
  },
});
