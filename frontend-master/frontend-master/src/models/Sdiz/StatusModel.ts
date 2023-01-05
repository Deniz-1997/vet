import { constructByInterface } from '@/utils/construct-by-interface';

export const SDIZ_STATUSES = {
  CREATED: 1,
  SUBSCRIBED: 2,
  FULL_REDEEMED: 3,
  CANCELED: 4,
  CONFIRM: 5,
};

export const SDIZ_STATUSES_TRANSLATE = [
  { id: 1, name: 'Проект' },
  { id: 2, name: 'Оформлен' },
  { id: 5, name: 'Оформлен и подтвержден' },
  { id: 3, name: 'Погашен' },
  { id: 4, name: 'Аннулирован' },
];

export interface StatusModelVueInterface {
  code: string;
  id: number;
  name: string;
}

export class StatusModelVue implements StatusModelVueInterface {
  code!: string;
  id!: number;
  name!: string;

  constructor(o?) {
    constructByInterface(o, this);
  }

  getStatus(type: string): number {
    return SDIZ_STATUSES[type.toUpperCase()];
  }

  getStatuses() {
    return SDIZ_STATUSES_TRANSLATE;
  }

  getStatusTranslate(id: number | null): string {
    return id ? SDIZ_STATUSES_TRANSLATE.find((e) => e.id === id)?.name || '' : '';
  }
}
