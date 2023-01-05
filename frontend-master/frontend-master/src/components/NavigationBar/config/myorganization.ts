import { EAction } from '@/utils';
import { TMenuItem } from '../models';

export default {
  label: 'Моя организация',
  pages: [
    {
      label: 'Сведения об организации',
      path: '/organization/information',
      enable: EAction.READ_ORGANIZATION_CARD,
    },
    {
      label: 'Информационная безопасность',
      path: '/organization/certificates',
      enable: EAction.READ_ORGANIZATION_CERTIFICATES_REGISTRY,
    },
    {
      label: 'Уведомления',
      path: '/notifications',
      enable() {
        return [EAction.VIEW_NOTIFICATION_REGISTRY, EAction.VIEW_NOTIFICATION_USER_REGISTRY].some((action) =>
          this.$store.getters['auth/check'](action)
        );
      },
    },
    {
      label: 'Жалобы',
      path: '/complaint',
      enable: EAction.READ_COMPLAINT_REGISTER,
    },
    {
      label: 'Запросы',
      path: '/requestRegister',
      enable: EAction.READ_FREE_FORM_REGISTER,
    },
  ],
} as TMenuItem;
