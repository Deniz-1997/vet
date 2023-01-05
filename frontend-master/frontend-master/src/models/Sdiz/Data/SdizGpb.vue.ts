import { AvailableFilters, HeaderSdizItem } from '@/models/Common/Default.vue';
import { Sdiz } from '@/models/Sdiz/Extends/Sdiz.vue';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import { EAction } from '@/utils';
import { SdizExtinguishCreateVueModel } from '@/models/Sdiz/SdizExtinguishCreate';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';
import { LotsPurposeEnum } from '@/utils/enums/lotsPurpose.enum';
import omit from 'lodash/omit';

export interface SdizGpbVueInterface {
  country_id: number | null;
  sdiz_gpb_number: string | number | null;
  sdiz_number: string | number | null;
  seller_text: string | null;
  buyer_text: string | null;
}

export class SdizGpbVueModel extends Sdiz implements SdizGpbVueInterface {
  sdiz_number: string | number | null = null;
  excludedKeyInData: string[] = ['enter_date', 'authorized_person'];
  country_id: number | null = null;
  product_type = 'is_product';
  lot_type = 'gpb';
  component_name = 'sdiz_gpb';
  to_lot_link = 'lots_gpb_detail';
  seller_text: string | null = null;
  buyer_text: string | null = null;
  available_filters: AvailableFilters[] = [
    ...this.getAvailableFilters(),
    { name: 'owner_id', type: 'number' },
    { name: 'sdiz_gpb_number', type: 'text', operator: '%%' },
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
        gpb: [
          { name: 'purposeCode', key: 'purpose.code', type: 'text' },
          { name: 'target_id', type: 'number' },
          {
            name: 'okpd2Code',
            key: 'okpd2.code',
          },
          { name: 'gpb_number', type: 'text', operator: '%%' },
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
  lot_type_name = 'Вид продуктов переработки';

  name_route_list = 'sdiz_gpb_list';
  name_route_detail = 'sdiz_gpb_detail';
  name_route_create = 'sdiz_gpb_create';

  list_apiendpoit = 'sdiz/getListForGpb';
  create_apiendpoit = 'sdiz/createForGpb';
  show_apiendpoit = 'sdiz/showForGpb';
  show_lot_apiendpoit = 'sdiz/showLotGpb';
  update_apiendpoit = 'sdiz/updateForGpb';
  delete_apiendpoit = 'sdiz/deleteForGpb';
  confirm_endpoint = 'sdiz/confirmGpb';
  extinguish_api_endpoint = 'sdiz/extinguishGpb';
  extinguish_refusal_api_endpoint = 'sdiz/extinguishRefusalGpb';
  extinguish_cancel_api_endpoint = 'sdiz/extinguishCancelGpb';
  extinguish_refusal_cancel_api_endpoint = 'sdiz/extinguishRefusalCancelGpb';
  export_pdf_service = 'sdiz/gpb/export/pdf';
  subscribe_service = 'sdiz/gpb/subscribe';
  cancel_service = 'sdiz/gpb/cancel';

  extinguish_cancel_sign_service = 'sdiz/gpb/extinguish/cancel';
  extinguish_refusal_cancel_sign_service = 'sdiz/gpb/extinguish/refusal/cancel';

  link_find_items_in_modal = 'lot/getListGpb';
  register_sdiz_privileges = EAction.READ_SDIZ_ON_PPZ_REGISTER;
  filter_register_sdiz_privileges = EAction.FILTER_SDIZ_ON_PPZ_REGISTER;
  view_data_privileges = EAction.READ_SDIZ_ON_PPZ;
  view_print_sdiz_privileges = EAction.READ_SDIZ_ON_PPZ_PRINT_FORM;
  create_sdiz_privileges = EAction.CREATE_SDIZ_ON_PPZ;
  update_privileges = EAction.UPDATE_SDIZ_ON_PPZ;
  delete_privileges = EAction.DELETE_SDIZ_ON_PPZ;
  sign_privileges = EAction.SEND_SDIZ_ON_PPZ;
  cancel_privileges = EAction.CANCEL_SDIZ_ON_PPZ;
  repayment_privileges = EAction.REPAYMENTS_SDIZ_ON_PPZ;
  confirm_priveleges = EAction.CONFIRM_SDIZ;
  headerList = [
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
      text: 'Номер партии ППЗ',
      value: 'objects.gpb.gpb_number',
    },
    {
      text: 'Вид продуктов переработки',
      value: 'objects.gpb.objects.okpd2.product_name_convert',
    },
    {
      text: 'Цель использования',
      value: 'objects.gpb.objects.target.name',
    },
    {
      text: 'Назначение',
      value: 'objects.gpb.objects.purpose.name',
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
  options: any = [
    {
      autocomplete: {
        condition: ['shipment', 'shipping'],
        model: 'shipper_id',
        arrayName: 'shippers',
        label: 'Грузоотправитель',
        placeholder: 'Начните вводить наименование, ИНН, КПП или ОГРН',
      },
      info: {
        condition: !!this.shipper_id,
        labelLocation: 'Пункт отправления',
        placeholderLocation: 'Определяется текущим местоположением партии',
        locationIdName: 'shipper_location_id',
        locationName: 'shipper_location',
        manufactureIdName: 'shipper_id',
      },
    },
    {
      autocomplete: {
        condition: ['shipping', 'acceptance'],
        model: 'consignee_id',
        arrayName: 'consignees',
        label: 'Грузополучатель',
        placeholder: 'Начните вводить наименование, ИНН, КПП или ОГРН',
      },
      info: {
        condition: !!this.consignee_id,
        labelLocation: 'Пункт назначения',
        placeholderLocation: 'Пункт назначения',
        locationIdName: 'consignee_location_id',
        locationName: 'consignee_location',
        manufactureIdName: 'consignee_id',
      },
    },
  ];

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
      ved_con_number: this.ved_con_number,
      ved_con_date: this.ved_con_date,
      ved_dop_number: this.ved_dop_number,
      ved_dop_date: this.ved_dop_date,
      eisz_number: this.eisz_number,
      contract_date: this.contract_date,
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
      shipper_repository_id: this.shipper_repository_id,
      shipper_location_id: this.shipper_location_id,
      consignee_location_id: this.consignee_location_id,
      contract_number: this.contract_number,
      consignee_repository_id: this.consignee_repository_id,
      carriers: this.carriers.map((carrier) => {
        return { ...carrier, doc_transports: carrier.doc_transports.map((e) => omit(e, ['type'])) };
      }),
    };
  }

  getAdditionalDataInfo(): object {
    return {
      enter_date: this.enter_date,
      laboratory_id: this.laboratory_id,
      prototype_sdiz: this.objects.operations.prototype_sdiz,
      authorized_person: this.authorized_person,
      sdiz_type: this.objects.operations.sdiz_type,
      gpb_id: this.objects.gpb.id,
      gpb: this.objects.gpb,
    };
  }

  constructor(o?: any) {
    super(o);
    this.init(o);
  }

  getData(): object {
    return {
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

  getSdizNumber(): string {
    return (this.sdiz_gpb_number || '№' + this.id).toString();
  }

  getErrorPrototypeSdiz(): Array<string> {
    const errors: Array<string> = [];
    if (this.objects.operations.prototype_sdiz === 0) errors.push('Выберите операцию (Внутренний рынок, ввоз, вывоз)');
    if (
      this.objects.operations.prototype_sdiz !== 2 &&
      this.getObjectLot().purposeCode === LotsPurposeEnum.IMPORT_TO_RUSSIA
    )
      errors.push('Выберите партию переработки с причиной (Вывоз, Переработка, Хранение и обработка)');

    if (this.objects.operations.prototype_sdiz !== 1) {
      if (!this.laboratory_id) errors.push('Укажите аккредитованное лицо, проводившее лабораторные исследования');
      if (this.ved_con_number && !this.ved_con_date) errors.push('Укажите дату ВЭД соглашения');
      if (this.ved_dop_number && !this.ved_dop_date) errors.push('Укажите дату доп ВЭД соглашения');
      if (this.ved_con_date && !this.ved_con_number) errors.push('Укажите номер ВЭД соглашения');
      if (this.ved_dop_date && !this.ved_dop_number) errors.push('Укажите номер доп ВЭД соглашения');
      if (this.protocol_number && !this.protocol_date) errors.push('Укажите дату протокола испытаний');
      if (this.protocol_date && !this.protocol_number) errors.push('Укажите номер протокола испытаний');
    }
    return errors;
  }

  getErrorOperationDetail(): Array<any> {
    const errors: Array<string> = [];
    if (this.objects.operations.sdiz_type === 0) errors.push('Выберите операцию');
    if (!this.objects.gpb.id) errors.push('Выберите партию');
    if (this.objects.operations.detail.shipment || this.objects.operations.detail.shipping) {
      if (!this.shipper_id) errors.push('Укажите грузоотправителя');
      if (!this.shipper_location_id) errors.push('Укажите пункт отправления');
    }
    if (this.objects.operations.detail.acceptance || this.objects.operations.detail.shipping) {
      if (!this.consignee_id) errors.push('Укажите грузополучателя');
      if (!this.consignee_location_id) errors.push('Укажите пункт назначения');
    }

    if (this.objects.operations.detail.realization) {
      if (!this.seller_id) errors.push('Укажите продавца');
      if (!this.buyer_id) errors.push('Укажите покупателя');
      if (!this.contract_date) errors.push('Укажите дату в сведениях о гражданско-правовом договоре');
      if (!this.contract_number) errors.push('Укажите номер в сведениях о гражданско-правовом договоре');
    }
    return errors;
  }

  getErrorDock(): Array<any> {
    const errors: Array<string> = [];

    if (this.objects.docs_akt.length && this.getDocsAktError()) {
      errors.push(this.getDocsAktError());
    }

    const docsOtherError = this.getOtherDocsError(this.objects.docs_other);
    if (this.objects.docs_other.length && docsOtherError) {
      errors.push(docsOtherError);
    }

    const docsOtherTransportsError = this.getOtherDocsTransportsError(this.objects.docs_transports_other);
    if (this.objects.docs_transports_other.length && docsOtherTransportsError) {
      errors.push(docsOtherTransportsError);
    }

    return errors;
  }

  getErrors(): Array<string> {
    const errors: Array<string> = [];
    errors.push(...this.getErrorPrototypeSdiz());
    errors.push(...this.getErrorOperationDetail());
    errors.push(...this.getErrorDock());
    errors.push(...this.getErrorCarriers());
    return errors;
  }

  getObjectLot(): LotGpbDataVueModel | LotDataVueModel | LotElevatorDataVueModel {
    return this.objects.gpb;
  }

  setObjectLot(data): void {
    this.objects.gpb = data;
  }

  getLotModel(lot): LotGpbDataVueModel | LotDataVueModel {
    return new LotGpbDataVueModel(lot);
  }

  getNameNumber() {
    return 'gpb_number';
  }

  getListManufactures(): any[] {
    return [...this.options];
  }

  getHeaders(): HeaderSdizItem[] {
    return [...this.headerList];
  }
}
