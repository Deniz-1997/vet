import { TMenuItem } from '../models';
import { EAction } from '@/utils';

export default {
  label: 'РСХН',
  path: '/rshn',
  pages: [
    {
      label: 'Реестр со сведениями об изъятии',
      path: '/rshn/withdrawal/list',
      enable: EAction.CREATE_WITHDRAWAL,
    },
    {
      label: 'Реестр выданных предписаний',
      path: '/rshn/prescription/list',
      enable: EAction.CREATE_PRESCRIPTION,
    },
    {
      label: 'Реестр экспертиз',
      path: '/rshn/expertise/list',
      enable: EAction.CREATE_EXPERTISE,
    },
  ],
} as TMenuItem;
