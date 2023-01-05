import moment from 'moment';
import { LotsMovedVueModel } from '@/models/Lot/LotsMoved.vue';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import { constructByInterface } from '@/utils/construct-by-interface';
import { AddressFiasVueModel } from '@/models/Gosmonitoring/AddressFias.vue';
import { ManufacturerVueModel } from '@/models/Sdiz/Manufacturer.vue';
import { GpbOutDataVueModel } from '@/models/Lot/GpbOut/GpbOutData.vue';

export enum StatusEnum {
  CREATE = 'CREATE',
  SUBSCRIBED = 'SUBSCRIBED',
  CANCELED = 'CANCELED',
}

export type HeaderItem = {
  text: string;
  value: string;
  notExclude?: boolean | undefined;
};

export interface DataVueModelInterface {
  component_name: string;
  list_apiendpoit: string;
  create_apiendpoit: string;
  show_apiendpoit: string;
  update_apiendpoit: string;
  delete_apiendpoit: string;
  create_link: string;
  detail_link: string;
  cancel_link: string;
  date_from: string | null;
  date_to: string | null;
  status_translate: string | null;
  options: Array<{ lot: any; model: string; tableName: string }>;
  statusList: Array<{ id: number; name: string; code: StatusEnum }>;
  lots_moved: Array<LotsMovedVueModel>;
  gpb_moved: Array<LotsMovedVueModel>;
}

export class DataVueModel implements DataVueModelInterface {
  component_name = 'gpb_out';
  list_apiendpoit = 'gpbo/getListGpbOut';
  create_apiendpoit = 'gpbo/createGpbOut';
  show_apiendpoit = 'gpbo/showGpbOut';
  update_apiendpoit = 'gpbo/updateGpbOut';
  delete_apiendpoit = 'gpbo/deleteGpbOut';
  create_link = 'gpbo_gpb_out_create';
  detail_link = 'gpbo_gpb_out_detail';
  cancel_link = 'gpbo_gpb_out_list';
  date_from: string | null = null;
  date_to: string | null = null;
  status_translate: string | null = null;
  options = [
    {
      lot: new LotDataVueModel(),
      model: 'lotsMoved',
      isRepositoryFilter: true,
      tableName: 'Предшествующие партии зерна',
    },
    {
      lot: new LotGpbDataVueModel(),
      model: 'gpbLotsMoved',
      isRepositoryFilter: false,
      tableName: 'Предшествующие партии переработки зерна',
    },
  ];
  headers: HeaderItem[] = [
    { text: 'Действия', value: 'actions' },
    { text: 'ID', value: 'id' },
    { text: 'Номер', value: 'gpbo_number' },
    { text: 'Дата формирования', value: 'enter_date' },
    { text: 'Местоположение', value: 'current_location.address' },
    { text: 'Производитель', value: 'manufacturer.name' },
    { text: 'Статус', value: 'status_translate' },
  ];
  statusList = [
    { id: 1, name: 'Проект', code: StatusEnum.CREATE },
    { id: 2, name: 'Подписан', code: StatusEnum.SUBSCRIBED },
    { id: 3, name: 'Аннулирован', code: StatusEnum.CANCELED },
  ];

  available_filters: any[] = [
    { name: 'gpbo_number', operator: '%%', type: 'text' },
    { name: 'gpb_row_number', operator: '%%', type: 'text' },
    { name: 'manufacturer_id', type: 'number' },
    { name: 'current_location_id', type: 'number' },
    { name: 'status', type: 'string' },
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

  lots_moved: Array<LotsMovedVueModel> = [];
  gpb_moved: Array<LotsMovedVueModel> = [];

  constructor(o?: GpbOutDataVueModel) {
    this.init(o);

    this.lots_moved = this.lots_moved.map((e) => new LotsMovedVueModel(e));
    this.gpb_moved = this.gpb_moved.map((e) => new LotsMovedVueModel(e));
  }

  protected init(o) {
    if (o) {
      constructByInterface(
        o,
        this,
        {
          lot_moved: LotsMovedVueModel,
          gpb_moved: LotsMovedVueModel,
          current_location: AddressFiasVueModel,
          manufacturer: ManufacturerVueModel,
        },
        true
      );
    }
  }
}
