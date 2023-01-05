import { TMenuItem } from '../models';
import { EAction } from '@/utils';

export default {
  label: 'РОУ АПК',
  pages: [
    {
      label: 'Просмотр СДИЗ',
      path: '/regional-government/sdizs',
      enable: EAction.READ_SDIZ_ROU_APK,
    },
    {
      label: 'Просмотр партии зерна',
      path: '/regional-government/lots',
      enable: EAction.READ_LOT_ROU_APK,
    },
    {
      label: 'Просмотр сведений о собранном урожае',
      path: '/regional-government/implementations',
      enable: EAction.READ_PRODUCTMONITOR_ROU_APK,
    },
  ],
} as TMenuItem;
