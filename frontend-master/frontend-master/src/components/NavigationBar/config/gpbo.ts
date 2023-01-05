import { TMenuItem } from '../models';
import { EAction } from '@/models/roles';

export default {
  label: 'Управление производствами, не подлежащими учету в системе',
  path: '/gpbo',
  pages: [
    {
      label: 'Реестр производств, не подлежащих учету в системе',
      path: '/gpbo/gpb-out/list',
      enable: EAction.READ_GRAIN_PROCESSING_BATCH_OUT_REGISTRY,
    },
    {
      label: 'Формирование производства продукции, не подлежащей учету.',
      path: '/gpbo/gpb-out/create',
      enable: EAction.CREATE_GRAIN_PROCESSING_BATCH_OUT,
    },
  ],
} as TMenuItem;
