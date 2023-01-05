import { constructByInterface } from '@/utils/construct-by-interface';
import moment from 'moment';
import { EAction } from '@/models/roles';

export interface ReservesNumberSdizGosVueInterface {
  id: number;
  operator_id: number | null;
  owner_id: number | null;
  sdiz_number: string;

  enter_date: string;
  date_to: string;
  date_from: string;

  available_filters: any[];
}

export class ReservesNumberSdizGosVueModel implements ReservesNumberSdizGosVueInterface {
  id!: number;
  operator_id!: number | null;
  owner_id!: number | null;
  sdiz_number!: string;

  enter_date!: string;
  date_to!: string;
  date_from!: string;
  register_number_privileges = EAction.READ_SDIZ_NUMBER_REGISTER;
  create_number_privileges = EAction.CREATE_SDIZ_NUMBER;
  component_name = 'reservesSdiz';
  pdf_from_description_service = 'sdiz/export/number/pdf/from/description';

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
    { name: 'sdiz_number', type: 'text', operator: '%%' },
    { name: 'operator_id', type: 'number' },
    { name: 'owner_id', type: 'number' },
  ];

  constructor(o?: ReservesNumberSdizGosVueModel) {
    constructByInterface(o, this);
  }

  getHeaders() {
    return [
      { text: 'ID', value: 'id' },
      { text: 'Номер СДИЗ', value: 'sdiz_number', sortable: false },
      { text: 'Дата заведения', value: 'enter_date', sortable: false },
      { text: 'Получатель номера', value: 'operator.full_name', sortable: false },
    ];
  }
}
