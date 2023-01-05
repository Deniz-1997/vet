import { EAction } from '@/models/roles';
import { TMenuItem } from '../models';

export default {
  label: 'Административные регламенты',
  pages: [
    {
      label: 'Шаблоны рассмотрения заявлений',
      path: '/approval-templates',
      enable: EAction.READ_APPROVAL_TEMPLATE_REGISTER,
    },
  ],
} as TMenuItem;
