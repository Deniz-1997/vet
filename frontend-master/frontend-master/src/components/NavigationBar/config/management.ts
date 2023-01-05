import { EAction } from '@/models/roles';
import { TMenuItem } from '../models';

export default {
  label: 'Администрирование',
  pages: [
    {
      label: 'Реестр пользователей',
      path: '/administration/users',
      enable: EAction.VIEW_USER_REGISTRY,
    },
    {
      label: 'Журнал действий пользователя',
      path: '/administration/activity-log',
      enable: EAction.READ_DIGITAL_ACCOUNTING_LOG,
    },
    {
      label: 'Реестр ролей',
      path: '/administration/roles',
      enable: EAction.VIEW_ROLE,
    },
    {
      label: 'Журнал согласования заявлений',
      path: '/administration/approval-request-log',
      enable: EAction.READ_APPROVAL_REQUEST_LOG_REGISTRY,
    },
    {
      label: 'Загрузка данных об организациях и пользователях',
      path: '/administration/import',
      enable: EAction.READ_IMPORT_LOG_REGISTRY,
    },
    {
      label: 'Управление сертификатами',
      path: '/administration/system-certificates',
      enable: EAction.READ_SYSTEM_CERTIFICATES_REGISTRY,
    },
    {
      label: 'Журнал информационного взаимодействия',
      path: '/administration/interaction-log',
      enable: EAction.READ_INTERACTION_LOG_REGISTER,
    },
    {
      label: 'Реестр всех организаций',
      path: '/subjects',
      enable: EAction.READ_FULL_ORGANIZATION_REGISTER,
    },
    {
      label: 'Мониторинг загрузки ФИАС',
      path: '/load-fias',
      enable: EAction.READ_FULL_ORGANIZATION_REGISTER, //TODO указать корректную привилегию
    },
  ],
} as TMenuItem;
