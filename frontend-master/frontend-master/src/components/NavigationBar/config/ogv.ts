import { EAction, ERole } from '@/models/roles';
import { TMenuItem } from '../models';

export default {
  label: 'Реестры партий и СДИЗ',
  path: '/ogv',
  enable() {
    const disallowed = [
      ERole.ROLE_GOVERMENT_USER,
      ERole.ROLE_SUBJECT_USER,
      ERole.ROLE_ELEVATOR_USER,
      ERole.ROLE_AGENT_USER,
    ];
    const isDisallowed = this.$store.getters['auth/roles'].find((role) => disallowed.includes(role));

    return !isDisallowed;
  },
  pages: [
    {
      label: 'Реестр партий зерна',
      path: '/ogv/list',
      enable: EAction.READ_GRAIN_LOT_REGISTER,
    },
    {
      label: 'Реестр партий зерна на хранении',
      path: '/ogv/list-elevator',
      enable: EAction.READ_GRAIN_LOT_STORAGE_REGISTER,
    },
    {
      label: 'Реестр партий продуктов переработки зерна',
      path: '/ogv/list-gpb',
      enable: EAction.READ_GRAIN_PRODUCT_LOT_REGISTER,
    },
    {
      label: 'Реестр производств, не подлежащих учету в системе',
      path: '/ogv/list-gpb-out',
      enable: EAction.READ_GRAIN_PROCESSING_BATCH_OUT_REGISTRY,
    },
    {
      label: 'Реестр СДИЗ на зерно',
      path: '/ogv/list-sdiz',
      enable: EAction.READ_SDIZ_REGISTER,
    },
    {
      label: 'Реестр СДИЗ на продукты переработки зерна',
      path: '/ogv/list-sdiz-gpb',
      enable: EAction.READ_SDIZ_ON_PPZ_REGISTER,
    },
    {
      label: 'Реестр сведений предоставляемых агентом',
      path: '/ogv/list-agent-sdiz',
      enable: EAction.READ_AGENT_DATA_REGISTER,
    },
    {
      label: 'Реестр деклараций ФТС',
      path: '/ogv/declaration',
      enable: EAction.READ_DECLARATION_REGISTER,
    },
  ],
} as TMenuItem;
