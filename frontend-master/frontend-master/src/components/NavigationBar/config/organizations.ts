import { EAction } from '@/utils';
import { TMenuItem } from '../models';

export default {
  label: 'Сведения об организациях, осуществляющих в качестве предпринимательской деятельности хранение зерна',
  pages: [
    {
      label: 'Реестр организаций',
      path: '/register-organizations',
      enable: EAction.READ_ORGANIZATION_REGISTER,
    },
    {
      label: 'Заявления',
      path: '/requests',
      enable: EAction.READ_REQUEST_REGISTER,
    },
    {
      label: 'Рассмотрение',
      path: '/tasks-for-approval',
      enable() {
        return [EAction.READ_TASK_REGISTER, EAction.READ_TASK_REGISTER_DIVISION].some((action) =>
          this.$store.getters['auth/check'](action)
        );
      },
    },
  ],
} as TMenuItem;
