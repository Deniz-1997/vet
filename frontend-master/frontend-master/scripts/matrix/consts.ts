export const RoleKey = Object.freeze({
  primary: 'Роль' as const,
  description: 'Описание' as const,
  status: 'Новая' as const,
});

export const AuthorityKey = Object.freeze({
  primary: 'Полномочие' as const,
  description: 'Описание' as const,
  status: 'Новое' as const,
});

export const MatrixKey = Object.freeze({
  primary: 'Код функциональности' as const,
  description: 'Роль, Полномочие / функциональность' as const,
});

export const TitleKey = Object.freeze({
  prefix: 'Префикс' as const,
  name: 'Наименование роли' as const,
});

export const Status = Object.freeze({
  DELETE: 'Удалено' as const,
});

export const disclaimer = `
  /*
  * This file was generated automatically.
  * Don't change it manually.
  */\n\n
`;

export const matrixUrl =
  'https://it.fors.ru/svn/GISZPP/3.%20%d0%a0%d0%b0%d0%b1%d0%be%d1%87%d0%b8%d0%b5%20%d0%bc%d0%b0%d1%82%d0%b5%d1%80%d0%b8%d0%b0%d0%bb%d1%8b/%d0%a0%d0%be%d0%bb%d0%b5%d0%b2%d0%b0%d1%8f%20%d0%bc%d0%be%d0%b4%d0%b5%d0%bb%d1%8c/%d0%9c%d0%b0%d1%82%d1%80%d0%b8%d1%86%d0%b0%20%d0%bf%d1%80%d0%b0%d0%b2%20%d0%b4%d0%be%d1%81%d1%82%d1%83%d0%bf%d0%b0_v3_1.xlsx';

export const sheets = ['accessMatrix', '', 'titles', 'roles', 'authorities'] as const;

export const headers = Object.freeze({
  accept:
    'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
  'cache-control': 'no-cache',
  pragma: 'no-cache',
  'upgrade-insecure-requests': '1',
  Referer:
    'https://it.fors.ru/svn/GISZPP/3.%20%d0%a0%d0%b0%d0%b1%d0%be%d1%87%d0%b8%d0%b5%20%d0%bc%d0%b0%d1%82%d0%b5%d1%80%d0%b8%d0%b0%d0%bb%d1%8b/%d0%a0%d0%be%d0%bb%d0%b5%d0%b2%d0%b0%d1%8f%20%d0%bc%d0%be%d0%b4%d0%b5%d0%bb%d1%8c/',
  'Referrer-Policy': 'strict-origin-when-cross-origin',
});
