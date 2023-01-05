import { ERole } from '@/models/roles';
import { EAction } from '@/utils';
import { TMenuItem } from '../models';

export default [
  {
    label: 'Управление партиями зерна',
    path: '/lots',
    enable() {
      const disallowed = [ERole.ROLE_ADMIN, ERole.ROLE_AUDITOR];
      const isDisallowed = this.$store.getters['auth/roles'].find((role) => disallowed.includes(role));

      return !isDisallowed;
    },
    pages: [
      {
        label: 'Реестр партий зерна',
        path: '/lots/list',
        enable: EAction.READ_GRAIN_LOT_REGISTER,
      },
      {
        label: 'Формирование партии зерна из других партий',
        path: '/lots/createFromAnotherBatch',
        enable: EAction.CREATE_OTHER_LOTS_GRAIN_LOT,
      },
      {
        label: 'Формирование партии зерна по результатам государственного мониторинга',
        path: '/lots/createFromField',
        enable: EAction.CREATE_GOSMONITORING_GRAIN_LOT,
      },
      {
        label: 'Формирование партии зерна из остатков',
        path: '/lots/CreateFromResidues',
        enable: EAction.CREATE_SURPLUS_GRAIN_LOT,
      },
      {
        label: 'Формирование партии зерна при ввозе',
        path: '/lots/createFromImported',
        enable: EAction.CREATE_IMPORT_GRAIN_LOT,
      },
      {
        label: 'Формирование партии зерна на основании СДИЗ на бумажном носителе',
        path: '/lots/createFromSdiz',
        enable: EAction.CREATE_SDIZ_GRAIN_LOT,
      },
    ],
  },
  {
    label: 'Управление партиями зерна на хранении',
    path: '/lots',
    enable() {
      const disallowed = [ERole.ROLE_ADMIN, ERole.ROLE_AUDITOR];
      const isDisallowed = this.$store.getters['auth/roles'].find((role) => disallowed.includes(role));

      return !isDisallowed;
    },
    pages: [
      {
        label: 'Реестр партий зерна на хранении',
        path: '/lots/elevator/list',
        enable: EAction.READ_GRAIN_LOT_STORAGE_REGISTER,
      },
      {
        label: 'Формирование партии зерна из других партий',
        path: '/lots/elevator/createFromAnotherBatch',
        enable: EAction.CREATE_GRAIN_PARTIES_OTHER_PARTIES_STORAGE,
      },
      {
        label: 'Формирование партии зерна по результатам государственного мониторинга',
        path: '/lots/elevator/createFromField',
        enable: EAction.READ_GRAIN_LOT_STORAGE_REGISTER,
      },
      {
        label: 'Формирование партии зерна из остатков',
        path: '/lots/elevator/createFromResidues',
        enable: EAction.CREATE_GRAIN_PARTIES_SURPLUS_STORAGE,
      },
      {
        label: 'Формирование партии зерна на основании СДИЗ на бумажном носителе',
        path: '/lots/elevator/createFromSdiz',
        enable: EAction.READ_GRAIN_LOT_STORAGE_REGISTER,
      },
    ],
  },
] as TMenuItem[];
