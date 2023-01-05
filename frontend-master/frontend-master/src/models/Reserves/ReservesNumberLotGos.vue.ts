import { constructByInterface } from '@/utils/construct-by-interface';
import moment from 'moment';
import { EAction } from '@/models/roles';
import { Okpd2VueModel } from '@/models/Sdiz/Okpd2.vue';

export interface ReservesNumberLotGosVueInterface {
  id: number;
  operator_id: number | null;
  owner_id: number | null;
  owner_short_name: string | null;
  lot_number: string;
  enter_date: string;
  date_to: string;
  date_from: string;
  available_filters: any[];

  okpd2_id: number | null;
  okpd2: Okpd2VueModel;
}

export class ReservesNumberLotGosVueModel implements ReservesNumberLotGosVueInterface {
  id!: number;
  operator_id!: number | null;
  owner_id!: number | null;
  lot_number!: string;

  okpd2_id!: number | null;
  okpd2: Okpd2VueModel = new Okpd2VueModel();

  enter_date!: string;
  date_to!: string;
  date_from!: string;
  owner_short_name!: string;
  register_number_privileges = EAction.READ_GRAIN_NUMBER_REGISTER;
  create_number_privileges = EAction.CREATE_GRAIN_NUMBER;
  component_name = 'reservesLot';
  pdf_from_description_service = 'lot/numbers/export/pdf/from/description';

  available_filters: any[] = [
    {
      name: 'date_from',
      operator: '>=',
      key: 'enter_date',
      value: (v) => moment(v, 'DD.MM.YYYY').startOf('day').format('DD.MM.YYYY HH:mm:ss'),
    },
    {
      name: 'date_to',
      operator: '<=',
      key: 'enter_date',
      value: (v) => moment(v, 'DD.MM.YYYY').endOf('day').format('DD.MM.YYYY HH:mm:ss'),
    },
    { name: 'lot_number', type: 'text', operator: '%%' },
    { name: 'okpd2Code', key: 'okpd2.code', type: 'text' },
    { name: 'operator_id', type: 'number' },
    { name: 'owner_id', type: 'number' },
  ];

  constructor(o?: ReservesNumberLotGosVueModel) {
    constructByInterface(o, this, { okpd2: Okpd2VueModel });
  }

  getHeaders() {
    return [
      { text: 'ID', value: 'id' },
      { text: 'Номер партии', value: 'lot_number', sortable: false },
      { text: 'Дата заведения', value: 'enter_date', sortable: false },
      { text: 'Вид с/х культуры', value: 'okpd2.product_name_convert', sortable: false },
      { text: 'Получатель номера', value: 'operator.full_name', sortable: false },
      { text: 'Компания', value: 'owner_short_name', sortable: false },
    ];
  }

  get okpd2Code(): string | null {
    return this.okpd2?.code ?? null;
  }

  set okpd2Code(v) {
    this.okpd2 = { ...this.okpd2, code: v };
  }
}
