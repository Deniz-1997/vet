import { TMenuItem } from '../models';

export default {
  label: 'Инструкции',
  pages: [
    {
      label: 'Пользовательские инструкции',
      path: '/user-files/manual',
    },
    {
      label: 'Рабочая документация',
      path: '/user-files/documents',
    },
    {
      label: 'Видеоинструкции',
      path: '/user-files/video',
    },
    {
      label: 'RUTUBE',
      path: '/user-files/rutube',
    },
  ],
} as TMenuItem;
