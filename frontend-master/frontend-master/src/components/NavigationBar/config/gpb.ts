import { EAction, ERole } from '@/models/roles';
import { TMenuItem } from '../models';

export default {
  label: 'Управление партии продуктов переработки зерна',
  path: '/lots',
  enable() {
    const disallowed = [ERole.ROLE_ADMIN, ERole.ROLE_AUDITOR];
    const isDisallowed = this.$store.getters['auth/roles'].find((role) => disallowed.includes(role));

    return !isDisallowed;
  },
  pages: [
    {
      label: 'Реестр партии продуктов переработки зерна',
      path: '/lots/gpb/list',
      enable: EAction.READ_GRAIN_PRODUCT_LOT_REGISTER,
    },
    {
      label: 'Формирование партии продуктов переработки зерна из других партий',
      path: '/lots/gpb/createFromAnotherBatch',
      enable: EAction.CREATE_OTHER_LOT_GRAIN_PRODUCT_LOT,
    },
    {
      label: 'Формирование партии продуктов переработки зерна при производстве',
      path: '/lots/gpb/createFromInProduct',
      enable: EAction.CREATE_PRODUCTION_GRAIN_PRODUCT_LOT,
    },
    {
      label: 'Формирование партии продуктов переработки зерна из остатков',
      path: '/lots/gpb/createFromResidues',
      enable: EAction.CREATE_SURPLUS_GRAIN_PRODUCT_LOT,
    },
    {
      label: 'Формирование партии продуктов переработки зерна при ввозе',
      path: '/lots/gpb/createFromImported',
      enable: EAction.CREATE_IMPORT_GRAIN_PRODUCT_LOT,
    },
    {
      label: 'Формирование партии продуктов переработки зерна на основании СДИЗ на бумажном носителе',
      path: '/lots/gpb/createFromSdiz',
      enable: EAction.CREATE_SDIZ_GRAIN_PRODUCT_LOT,
    },
  ],
} as TMenuItem;
