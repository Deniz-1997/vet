import { AxiosInstance } from 'axios';
import moment from 'moment';
import { TLayoutType } from '@/layouts';
import { TConfig } from '@/plugins/config';
import { TService } from '@/services';
import { Filter } from '@/services/mappers/common';

declare module 'vue/types/vue' {
  interface Vue {
    $service: TService;
    $axios: AxiosInstance;
    $config: TConfig;
    $moment: typeof moment;
  }
}

declare module 'vue/types/options' {
  interface ComponentOptions {
    layout?: TLayoutType;
  }
}

declare module 'axios' {
  interface AxiosRequestConfig {
    ignoreStatuses?: number[];
    refreshTokens?: boolean;
  }

  interface AxiosError {
    notifyId?: string;
  }

  interface AxiosResponse {
    filter?: Filter;
  }
}
