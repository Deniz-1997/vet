import { EAction, ERole } from '@/models/roles';
import { TMenuItem } from '../models';

const disallowed = [ERole.ROLE_ADMIN, ERole.ROLE_RSHN];

export default [
  // {
  //   label: 'Поиск СДИЗ',
  //   path: '/sdizs-search',
  //   pages: [
  //     {
  //       label: 'Поиск СДИЗ',
  //       path: '/sdizs-search',
  //       enable: EAction.READ_SEARCH_SDIZ_REGISTRY,
  //     }
  //   ]
  // },
  {
    label: 'Сведения предоставляемые агентом',
    enable() {
      const isDisallowed = this.$store.getters['auth/roles'].find((role) => disallowed.includes(role));

      return !isDisallowed;
    },
    pages: [
      {
        label: 'Реестр сведений предоставляемых агентом',
        path: '/sdizs/agent/list',
        enable: EAction.READ_AGENT_DATA_REGISTER,
      },
      {
        label: 'Формирование сведений предоставляемых агентом',
        path: '/sdizs/agent/create',
        enable: EAction.CREATE_AGENT_DATA,
      },
    ],
  },
  {
    label: 'Управление СДИЗ',
    enable() {
      const disallowed = [ERole.ROLE_ADMIN, ERole.ROLE_AUDITOR, ERole.ROLE_RSHN];
      const isDisallowed = this.$store.getters['auth/roles'].find((role) => disallowed.includes(role));

      return !isDisallowed;
    },
    pages: [
      {
        label: 'Реестр СДИЗ',
        path: '/sdizs/list',
        enable: EAction.READ_SDIZ_REGISTER,
      },
      {
        label: 'Оформление СДИЗ',
        path: '/sdizs/create',
        enable: EAction.CREATE_SDIZ,
      },
      {
        label: 'Реестр деклараций ФТС',
        path: '/sdizs/declaration',
        enable: EAction.READ_DECLARATION_REGISTER_SDIZ,
      },
    ],
  },
  {
    label: 'Управление СДИЗ при хранении',
    enable() {
      const disallowed = [ERole.ROLE_ADMIN, ERole.ROLE_AUDITOR, ERole.ROLE_RSHN];
      const isDisallowed = this.$store.getters['auth/roles'].find((role) => disallowed.includes(role));

      return !isDisallowed;
    },
    pages: [
      {
        label: 'Реестр СДИЗ при хранении',
        path: '/sdizs-elevator/list',
        enable: EAction.READ_SDIZ_STORAGE_REGISTER,
      },
      {
        label: 'Оформление СДИЗ',
        path: '/sdizs-elevator/create',
        enable: EAction.CREATE_SDIZ_STORAGE,
      },
    ],
  },
  {
    label: 'Управление СДИЗ продуктов переработки',
    enable() {
      const disallowed = [ERole.ROLE_ADMIN, ERole.ROLE_AUDITOR, ERole.ROLE_RSHN];
      const isDisallowed = this.$store.getters['auth/roles'].find((role) => disallowed.includes(role));

      return !isDisallowed;
    },
    pages: [
      {
        label: 'Реестр СДИЗ продуктов переработки',
        path: '/sdizs-gpb/list',
        enable: EAction.READ_SDIZ_ON_PPZ_REGISTER,
      },
      {
        label: 'Оформление СДИЗ',
        path: '/sdizs-gpb/create',
        enable: EAction.CREATE_SDIZ_ON_PPZ,
      },
    ],
  },
] as TMenuItem[];
