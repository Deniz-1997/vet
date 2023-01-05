import moment from 'moment';
import { constructByInterface } from '@/utils/construct-by-interface';

export type HeaderItem = {
  text: string;
  value: string;
  notExclude?: boolean | undefined;
};

export interface DataRshnInterface {
  component_name: string | null;
  entity_name: string | null;
  list_apiendpoint: string | null;
  create_apiendpoint: string | null;
  show_apiendpoint: string | null;
  update_apiendpoint: string | null;
  delete_apiendpoint: string | null;
  export_apiendpoint: string | null;
  export_canceled_apiendpoint: string | null;
  dop_apiendpoint: string | null;
  dop_export_apiendpoint: string | null;
  dop_export_canceled_apiendpoint: string | null;
  create_link: string | null;
  detail_link: string | null;
  cancel_link: string | null;
  date_from: string | null;
  date_to: string | null;
  status_translate: string | null;
}

export class DataRshn implements DataRshnInterface {
  component_name: string | null = null;
  entity_name: string | null = null;
  list_apiendpoint: string | null = null;
  create_apiendpoint: string | null = null;
  show_apiendpoint: string | null = null;
  update_apiendpoint: string | null = null;
  delete_apiendpoint: string | null = null;
  export_apiendpoint: string | null = null;
  export_canceled_apiendpoint: string | null = null;
  dop_apiendpoint: string | null = null;
  dop_export_apiendpoint: string | null = null;
  dop_export_canceled_apiendpoint: string | null = null;
  create_link: string | null = null;
  detail_link: string | null = null;
  cancel_link: string | null = null;
  date_from: string | null = null;
  date_to: string | null = null;
  status_translate: string | null = null;

  headers: HeaderItem[] = [];

  getAvailableFilters(): any[] {
    return [
      {
        name: 'date_from',
        operator: '>=',
        key: 'enter_date',
        value: (v) => moment(v, 'DD.MM.YYYY').startOf('day').format('DD.MM.YYYY'),
      },
      {
        name: 'date_to',
        operator: '<=',
        key: 'enter_date',
        value: (v) => moment(v, 'DD.MM.YYYY').endOf('day').format('DD.MM.YYYY'),
      },
    ];
  }

  constructor(o) {
    this.init(o);
  }

  protected init(o, types = {}) {
    if (o) {
      constructByInterface(
        o,
        this,
        {
          ...types,
        },
        true
      );
    }
  }
}
