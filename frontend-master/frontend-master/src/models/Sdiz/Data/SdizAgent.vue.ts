import { Sdiz } from '@/models/Sdiz/Extends/Sdiz.vue';
import { HeaderSdizItem } from '@/models/Common/Default.vue';

export interface SdizAgentInterface {
  lot_number: string | null; // delete on front & back
  sdiz_number: string | number | null;
  elevator_creator: boolean;
}

export class SdizAgentVue extends Sdiz implements SdizAgentInterface {
  sdiz_number: string | number | null = null;
  lot_number: string | null = null;
  product_type = 'is_grain';
  lot_type = 'lot';
  component_name = 'sdizAgent';
  available_filters: any[] = [
    ...this.getAvailableFilters(),
    { name: 'sdiz_number', type: 'text', operator: '%%' },
    { name: 'eisz_number', type: 'text', operator: '=' },
    { name: 'shipper_location_id', type: 'number', operator: '=' },
    { name: 'consignee_location_id', type: 'number', operator: '=' },
    {
      name: 'eisz_number_checkbox_init',
      key: 'eisz_number',
      type: 'text',
      operator: '!=',
      value: () => {
        return null;
      },
    },
    {
      name: 'objects',
      type: 'objects',
      child: {
        lot: [
          { name: 'purposeCode', key: 'purpose.code', type: 'text' },
          { name: 'target_id', type: 'number' },
          { name: 'okpd2_id', type: 'number' },
          { name: 'lot_number', type: 'text', operator: '%%' },
          { name: 'current_location_id', type: 'number' },
          {
            name: 'amount_kg_from',
            operator: '>=',
            key: 'amount_kg',
            type: 'number',
          },
          {
            name: 'amount_kg_to',
            operator: '<=',
            key: 'amount_kg',
            type: 'number',
          },
        ],
      },
    },
  ];
  lot_type_name = 'Вид с/х культуры';

  name_route_list = 'sdiz_agent_list';
  name_route_detail = 'sdiz_detail';
  name_route_create = 'sdiz_create';

  list_apiendpoit = 'sdiz/getList';
  create_apiendpoit = 'sdiz/create';
  show_apiendpoit = 'sdiz/show';
  update_apiendpoit = 'sdiz/update';
  delete_apiendpoit = 'sdiz/delete';
  export_pdf_service = 'sdiz/export/pdf';
  link_find_items_in_modal = 'lot/getList';
  headerList = [
    {
      text: 'Действие',
      value: 'actions',
    },
    {
      text: 'Номер',
      value: 'sdiz_number',
      notExclude: true,
    },
    {
      text: 'Дата',
      value: 'enter_date',
      notExclude: true,
    },
    {
      text: 'Статус СДИЗ',
      value: 'objects.sdiz_status_translate',
    },
    {
      text: 'Статус партии',
      value: 'objects.gpb.status_translate',
      notExclude: false,
    },
    {
      text: 'Вид с/х культуры',
      value: 'objects.lot.objects.okpd2.product_name_convert',
      notExclude: false,
    },
    {
      text: 'Цель использования',
      value: 'objects.lot.objects.target.name',
      notExclude: false,
    },
    {
      text: 'Назначение',
      value: 'objects.lot.objects.purpose.name',
    },
    {
      text: 'Масса, кг',
      value: 'objects.lot.amount_kg_original_mask',
    },
    {
      text: 'Продавец',
      value: 'objects.seller.name',
    },
    {
      text: 'Покупатель',
      value: 'objects.buyer.name',
    },
    {
      text: 'Номер договора',
      value: 'contract_number',
    },
    {
      text: 'Номер контракта ВЭД',
      value: 'ved_con_number',
    },
  ];

  constructor(o?: any) {
    super(o);
    this.init(o);
  }

  getHeaders(): HeaderSdizItem[] {
    return [...this.headerList];
  }
}
