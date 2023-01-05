import { EAction } from '@/models/roles';
import { TMenuItem } from "../models";

export default {
  label: 'Реестр номеров',
  path: '/reserves',
  pages: [
    {
      label: 'Реестр выданных номеров СДИЗ',
      path: '/reserves/number/sdiz',
      enable: EAction.READ_SDIZ_NUMBER_REGISTER,
    },
    {
      label: 'Реестр выданных номеров партий зерна',
      path: '/reserves/number/lots',
      enable: EAction.READ_GRAIN_NUMBER_REGISTER,
    },
    {
      label: 'Реестр выданных номеров партий продуктов переработки зерна',
      path: '/reserves/number/products',
      enable: EAction.READ_GRAIN_PRODUCT_NUMBER_REGISTER,
    },
  ]
} as TMenuItem;
