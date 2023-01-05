import { constructByInterface } from '@/utils/construct-by-interface';
import moment from 'moment';
import { Okpd2VueModel } from '@/models/Sdiz/Okpd2.vue';
import { ManufacturerVueModel } from '@/models/Sdiz/Manufacturer.vue';

export interface LotNumbersVueInterface {
  id: number;
  subject_id: number | null;
  okpd2_id: number | null;
  lots_numbers_from_subject: string;

  date: string;
  date_to: string;
  date_from: string;

  active: boolean;
  available_filters: any[];
  okpd2: Okpd2VueModel;
}

export class LotNumbersVueModel implements LotNumbersVueInterface {
  id!: number;
  subject_id!: number | null;
  lots_numbers_from_subject!: string;

  date!: string;
  date_to!: string;
  date_from!: string;

  active!: boolean;

  okpd2_id!: number | null;
  okpd2: Okpd2VueModel = new Okpd2VueModel();

  available_filters: any[] = [
    {
      name: 'date_from',
      operator: '>=',
      key: 'date_enter',
      value: (v) => moment(v, 'DD.MM.YYYY').startOf('day').format('DD.MM.YYYY HH:mm:ss'),
    },
    {
      name: 'date_to',
      operator: '<=',
      key: 'date_enter',
      value: (v) => moment(v, 'DD.MM.YYYY').endOf('day').format('DD.MM.YYYY HH:mm:ss'),
    },
    { name: 'lots_numbers_from_subject', type: 'text', operator: '%%' },
    { name: 'okpd2Code', key: 'okpd2.code' },
    { name: 'active_number', type: 'number' },
  ];

  constructor(o?: LotNumbersVueModel) {
    constructByInterface(o, this);
  }

  get okpd2Code() {
    return this.okpd2?.code || null;
  }

  set okpd2Code(v) {
    this.okpd2 = { ...this.okpd2, code: v };
  }
}

export class LotNumbersShortModel {
  id!: number;
  subject_id!: number | null;
  subject: ManufacturerVueModel = new ManufacturerVueModel();

  lots_numbers_from_subject!: string;

  date!: string;

  active!: boolean;

  okpd2_id!: number | null;
  okpd2: Okpd2VueModel = new Okpd2VueModel();

  constructor(o?: LotNumbersVueModel) {
    constructByInterface(o, this, { okpd2: Okpd2VueModel, subject: ManufacturerVueModel });
  }
}
