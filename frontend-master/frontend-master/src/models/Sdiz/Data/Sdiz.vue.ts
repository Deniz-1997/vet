import { HeaderSdizItem } from '@/models/Common/Default.vue';
import { Sdiz } from '@/models/Sdiz/Extends/Sdiz.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { SdizExtinguishCreateVueModel } from '@/models/Sdiz/SdizExtinguishCreate';
import { EAction } from '@/models/roles';
import omit from 'lodash/omit';

export interface SdizVueInterface {
  lot_number: string | null; // delete on front & back
  sdiz_number: string | number | null;
  elevator_creator: boolean;
}

export class SdizVueModel extends Sdiz implements SdizVueInterface {
  sdiz_number: string | number | null = null;
  lot_number: string | null = null;
  product_type = 'is_grain';
  lot_type = 'lot';
  to_lot_link = 'lot_detail';
  component_name = 'sdiz';
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
          { name: 'purposeCode', key: 'purpose.code' },
          { name: 'target_id', type: 'number' },
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
          {
            name: 'okpd2Code',
            key: 'okpd2.code',
          },
        ],
      },
    },
  ];
  lot_type_name = 'Вид с/х культуры';

  name_route_list = 'sdiz_list';
  name_route_detail = 'sdiz_detail';
  name_route_create = 'sdiz_create';

  list_apiendpoit = 'sdiz/getList';
  create_apiendpoit = 'sdiz/create';
  show_apiendpoit = 'sdiz/show';
  show_lot_apiendpoit = 'sdiz/showLot';
  update_apiendpoit = 'sdiz/update';
  delete_apiendpoit = 'sdiz/delete';
  confirm_endpoint = 'sdiz/confirm';
  extinguish_api_endpoint = 'sdiz/extinguish';
  extinguish_refusal_api_endpoint = 'sdiz/extinguishRefusal';
  extinguish_cancel_api_endpoint = 'sdiz/extinguishCancel';
  extinguish_refusal_cancel_api_endpoint = 'sdiz/extinguishRefusalCancel';
  link_find_items_in_modal = 'lot/getList';
  export_pdf_service = 'sdiz/export/pdf';

  subscribe_service = 'sdiz/subscribe';
  cancel_service = 'sdiz/cancel';

  extinguish_cancel_sign_service = 'sdiz/extinguish/cancel';
  extinguish_refusal_cancel_sign_service = 'sdiz/extinguish/refusal/cancel';

  register_sdiz_privileges = EAction.READ_SDIZ_REGISTER;
  filter_register_sdiz_privileges = EAction.FILTER_SDIZ_REGISTER;
  view_data_privileges = EAction.READ_SDIZ;
  view_print_sdiz_privileges = EAction.READ_SDIZ_PRINT_FORM;
  create_sdiz_privileges = EAction.CREATE_SDIZ;
  update_privileges = EAction.UPDATE_SDIZ;
  delete_privileges = EAction.DELETE_SDIZ;
  sign_privileges = EAction.SIGN_SDIZ;
  cancel_privileges = EAction.CANCEL_SDIZ;
  repayment_privileges = EAction.REPAYMENT_SDIZ;
  confirm_priveleges = EAction.CONFIRM_SDIZ;

  headerTable = [
    {
      text: 'Действие',
      value: 'actions',
    },
    {
      text: 'ID',
      value: 'id',
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
      value: 'status_translate',
    },
    {
      text: 'Уполномоченное лицо',
      value: 'objects.owner.formattedName',
      notExclude: true,
    },
    {
      text: 'Номер партии',
      value: 'objects.lot.lot_number',
    },
    {
      text: 'Вид с/х культуры',
      value: 'objects.lot.objects.okpd2.product_name_convert',
    },
    {
      text: 'Цель использования',
      value: 'objects.lot.objects.target.name',
    },
    {
      text: 'Назначение',
      value: 'objects.lot.objects.purpose.name',
    },
    {
      text: 'Масса, кг',
      value: 'amount_kg_original_mask',
    },
    {
      text: 'Операция',
      value: 'objects.operations.types',
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
      text: 'Грузополучатель',
      value: 'objects.consignee.name',
    },
    {
      text: 'Грузоотправитель',
      value: 'objects.shipper.name',
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

  getDataDocks(): object {
    return {
      docs_other: this.objects.docs_other,
      docs_transports_other: this.objects.docs_transports_other,
      docs_akt: this.objects.docs_akt.map((e) => omit(e, 'type')),
    };
  }
  getDataContract(): object {
    return {
      eisz_contract_number: this.eisz_contract_number,
      eisz_contract_date: this.eisz_contract_date,
      eisz_number: this.eisz_number,
      contract_number: this.contract_number,
      ved_con_number: this.ved_con_number,
      ved_con_date: this.ved_con_date,
      ved_dop_number: this.ved_dop_number,
      ved_dop_date: this.ved_dop_date,
    };
  }

  getDataDocuments(): object {
    return {
      protocol_number: this.protocol_number,
      protocol_date: this.protocol_date,
      customs_declaration_number: this.customs_declaration_number,
    };
  }

  getDataInfo(): object {
    return {
      seller_id: this.seller_id,
      buyer_id: this.buyer_id,
      shipper_id: this.shipper_id,
      consignee_id: this.consignee_id,
      consignee_repository_id: this.consignee_repository_id,
      shipper_repository_id: this.shipper_repository_id,
      shipper_location_id: this.shipper_location_id,
      consignee_location_id: this.consignee_location_id,
      carriers: this.carriers.map((carrier) => {
        return { ...carrier, doc_transports: carrier.doc_transports.map((e) => omit(e, ['type'])) };
      }),
    };
  }
  getAdditionalDataInfo(): object {
    return {
      enter_date: this.enter_date,
      laboratory_id: this.laboratory_id,
      contract_date: this.contract_date,
      prototype_sdiz: this.objects.operations.prototype_sdiz,
      authorized_person: this.authorized_person,
      sdiz_type: this.objects.operations.sdiz_type,
      lot_id: this.objects.lot.id,
      lot: this.objects.lot,
    };
  }

  getData(): object {
    const additional_data: any = {};
    if (this.elevator_creator && this.objects.operations.detail.acceptance) {
      const data = { ...this.objects.storage_agreement };
      data.type_id = data.type?.id;
      data.moving_type_id = data.moving_type?.id;

      data.service = data.service.map((e) => omit({ ...e, elevator_service_type_id: e.id }, 'id'));

      additional_data.storage_agreement = omit(data, ['type', 'moving_type']);
    }

    additional_data.elevator_creator = this.elevator_creator || false;
    return {
      ...additional_data,
      ...this.getDataDocks(),
      ...this.getDataContract(),
      ...this.getDataInfo(),
      ...this.getAdditionalDataInfo(),
      ...this.getDataDocuments(),
    };
  }

  setDataForExtinguishCreate(data): SdizExtinguishCreateVueModel {
    return new SdizExtinguishCreateVueModel(data);
  }

  getNameNumber() {
    return 'lot_number';
  }

  getSdizNumber(): string {
    return (this.sdiz_number || '№' + (this.id ?? '0')).toString();
  }

  getHeaders(): HeaderSdizItem[] {
    return [...this.headerTable];
  }

  getLotModel(lot): LotGpbDataVueModel | LotDataVueModel {
    return new LotDataVueModel(lot);
  }

  getObjectLot(): LotGpbDataVueModel | LotDataVueModel {
    return this.objects.lot;
  }

  setObjectLot(data): void {
    this.objects.lot = data;
  }
}
