import { ObjectsVueModel } from '@/models/Sdiz/Objects';
import { currentDay } from '@/utils';
import { SdizAuthorizedPersonsInterface } from '@/models/Sdiz/interfaces/SdizAuthorizedPersons.interface';
import { SdizTypesInterface } from '@/models/Sdiz/interfaces/SdizTypes.interface';
import { constructByInterface } from '@/utils/construct-by-interface';
import moment from 'moment';
import { SdizInterface } from '@/models/Sdiz/interfaces/Sdiz.interface';
import { FiltersVueInterface } from '@/models/Common/Filters.vue';
import { DefaultVueInterface, HeaderSdizItem } from '@/models/Common/Default.vue';
import store from '@/store';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import { SdizExtinguishCreateVueModel } from '@/models/Sdiz/SdizExtinguishCreate';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';
import { LotOgvGpbDataVueModel } from '@/models/Lot/Ogv/LotOgvGpbData.vue';
import { LotDataOgvVueModel } from '@/models/Lot/Ogv/LotDataOgv.vue';
import { LotOgvElevatorDataVueModel } from '@/models/Lot/Ogv/LotOgvElevatorData.vue';
import { SdizCarrierInterface, SdizCarrierModel } from '@/models/Sdiz/SdizCarrier';
import { applyMask } from '@/components/common/inputs/mask/decimalNumberMask';
import { LotsPurposeEnum } from '@/utils/enums/lotsPurpose.enum';
import { PrototypeSdizEnum } from '@/models/Sdiz/Operations.vue';

export class Sdiz implements SdizInterface, FiltersVueInterface, DefaultVueInterface {
  id: number | null = null;
  objects: ObjectsVueModel = new ObjectsVueModel();
  component_name = '';
  date_from: string | null = null;
  date_to: string | null = null;
  laboratory_id: number | null = null;

  sdiz_gpb_number: string | null = null;
  amount_kg_mask: string | null = null;

  amount_kg_from: number | null = null;
  amount_kg_from_mask: string | null = null;
  amount_kg_to: number | null = null;
  amount_kg_to_mask: string | null = null;
  amount_kg_original: number | null = null;
  amount_kg_original_mask: string | null = null;

  eisz_contract_number: string | number | null = null;
  eisz_contract_date: string | null = null;

  eisz_number: string | null = null;
  eisz_number_checkbox_init: boolean | null = null;
  elevator_creator = false;

  moving_lot_checkbox_init: boolean | null = null;

  contract_number: string | null = null;
  authorized_person: string | null = null;
  gka_number: string | null = null;
  consignee_location_id: number | null = null;
  consignee_location: any = null;
  consignee_repository_id: number | null = null;
  shipper_location_id: number | null = null;
  shipper_location: any = null;

  shipper_registration_number: string | null = null;
  consignee_registration_number: string | null = null;

  carriers: SdizCarrierInterface[] = [];
  shipper_repository_id: number | null = null;
  prototype_sdiz: number | null = null;

  original_data: any = null;
  consignee_id: number | null = null;
  shipper_id: number | null = null;
  seller_id: number | null = null;
  buyer_id: number | null = null;
  sdiz_type: number | null = null;
  status_id: number | null = null;
  owner_id: number | null = null;

  ved_con_number: string | number | null = null;
  ved_con_date: string | null = null;
  ved_dop_number: string | number | null = null;
  ved_dop_date: string | null = null;

  customs_declaration_number: string | null = null;

  repository_contract: string | null = null;
  contract_date: string | null = null;
  gka_date: string | null = null;
  enter_date: string = currentDay();

  protocol_number: string | null = null;
  protocol_date: string | null = null;

  authorized_persons: SdizAuthorizedPersonsInterface[] = [];
  sdiz_types: SdizTypesInterface[] = [];

  excludedKeyInData: string[] = [];
  available_filters: any[] = [];

  lot_type_name = '';
  product_type = '';
  lot_type = '';

  name_route_list = '';
  name_route_detail = '';
  name_route_create = '';

  list_apiendpoit = '';
  create_apiendpoit = '';
  show_apiendpoit = '';
  show_lot_apiendpoit = '';
  update_apiendpoit = '';
  delete_apiendpoit = '';
  confirm_endpoint = '';
  extinguish_api_endpoint = '';
  extinguish_refusal_api_endpoint = '';
  extinguish_cancel_api_endpoint = '';
  export_pdf_service = '';
  subscribe_service = '';
  extinguish_cancel_sign_service = '';
  extinguish_refusal_cancel_sign_service = '';
  cancel_service = '';
  link_find_items_in_modal = '';
  register_sdiz_privileges = '';
  filter_register_sdiz_privileges = '';
  view_data_privileges = '';
  view_print_sdiz_privileges = '';

  create_sdiz_privileges = '';
  update_privileges = '';
  delete_privileges = '';
  sign_privileges = '';
  cancel_privileges = '';
  repayment_privileges = '';
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
        labelRepository: 'Реестровый номер организации, осуществляющей хранение (отправления)',
        placeholderLocation: 'Определяется текущим местоположением партии',
        locationIdName: 'shipper_location_id',
        locationName: 'shipper_location',
        manufactureIdName: 'shipper_id',
        repositoryIdName: 'shipper_repository_id',
        repositoryRegistrationNumberName: 'shipper_registration_number',
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
        labelRepository: 'Реестровый номер организации, осуществляющей хранение (назначения)',
        placeholderLocation: 'Пункт назначения',
        locationIdName: 'consignee_location_id',
        locationName: 'consignee_location',
        manufactureIdName: 'consignee_id',
        repositoryIdName: 'consignee_repository_id',
        repositoryRegistrationNumberName: 'consignee_registration_number',
      },
    },
  ];

  get disabledProperties() {
    const properties: string[] = [];

    if (
      (this.objects.operations.detail.shipping || this.objects.operations.detail.shipment) &&
      this.objects.operations.prototype_sdiz !== 2
    )
      properties.push('shipper_location_id');

    return properties;
  }

  constructor(o?: any) {
    this.init(o);
  }

  init(o) {
    if (o !== undefined) {
      constructByInterface(o, this, {}, true);
      this.objects = new ObjectsVueModel(o);
      this.original_data = o;

      this.amount_kg_original_mask = applyMask(this.amount_kg_original, true);
    }
  }

  /**
   * Отчищаем массивы по выбранной операции
   * @private
   */
  clearVarsByType(): void {
    if (!this.objects.operations.detail.shipping) {
      this.carriers = [];
    }

    if (this.eisz_contract_date === '') {
      this.eisz_contract_date = null;
    }

    if (!this.objects.operations.detail.shipment && !this.objects.operations.detail.shipping) {
      this.shipper_id = null;
    }

    if (!this.objects.operations.detail.realization) {
      this.seller_id = null;
      this.buyer_id = null;
      this.eisz_number = null;
      this.contract_number = null;
      this.gka_date = null;
      this.gka_number = null;
      this.objects.docs_other = [];
    }
  }

  clearVarsByPrototype() {
    if (this.objects.operations.prototype_sdiz === PrototypeSdizEnum.IN_RUSSIA) {
      this.protocol_number = null;
      this.protocol_date = null;
      this.customs_declaration_number = null;

      this.ved_con_date = null;
      this.ved_con_number = null;
      this.ved_dop_date = null;
      this.ved_dop_number = null;
      this.laboratory_id = null;
    }
  }

  getData(): object {
    return {};
  }

  getDataForCreate(): object {
    this.clearVarsByType();
    this.clearVarsByPrototype();
    return this.clearData(this.getData());
  }

  setDataForExtinguishCreate(data): SdizExtinguishCreateVueModel {
    return new SdizExtinguishCreateVueModel(data);
  }

  getDataForUpdate(): object {
    this.clearVarsByType();
    this.clearVarsByPrototype();
    return this.clearData(this.getData(), this.excludedKeyInData);
  }

  /**
   * Отчищаем объект от некорректных значений (null, undefined, object)
   * Так же исключаем элементы указанные в массиве excludedKey
   *
   * @param data
   * @param excludedKey
   */
  clearData(data, excludedKey: string[] = []): object {
    return Object.keys(data)
      .filter((key) => {
        if (Array.isArray(data[key])) return data[key].length;
        if (typeof data[key] === 'object') return key === 'storage_agreement';
        if (excludedKey.length > 0 && excludedKey.includes(key)) return false;
        return data[key] !== undefined && typeof data[key] !== 'object';
      })
      .reduce((obj, key) => {
        obj[key] = data[key];
        return obj;
      }, {});
  }

  getError(): string {
    const errors = this.getErrors();
    return errors.length ? errors[0] : '';
  }

  getErrorPrototypeSdiz(): Array<string> {
    const errors: Array<string> = [];
    if (this.objects.operations.prototype_sdiz === 0) errors.push('Выберите операцию (Внутрени рынок, ввоз, вывоз)');
    if (
      this.objects.operations.prototype_sdiz !== 2 &&
      this.getObjectLot().purposeCode === LotsPurposeEnum.IMPORT_TO_RUSSIA
    )
      errors.push('Выберите партию с причиной (Вывоз, Переработка, Хранение и обработка)');
    if (this.objects.operations.prototype_sdiz !== 1) {
      if (!this.laboratory_id) errors.push('Укажите аккредитованное лицо, проводившее лабораторные исследования');
      if (this.ved_con_number && !this.ved_con_date) errors.push('Укажите дату ВЭД соглашения');
      if (this.ved_dop_number && !this.ved_dop_date) errors.push('Укажите дату доп ВЭД соглашения');
      if (this.ved_con_date && !this.ved_con_number) errors.push('Укажите номер ВЭД соглашения');
      if (this.ved_dop_date && !this.ved_dop_number) errors.push('Укажите номер доп ВЭД соглашения');
      if (this.protocol_number && !this.protocol_date) errors.push('Укажите дату протокола испытаний');
      if (this.protocol_date && !this.protocol_number) errors.push('Укажите номер протокола испытаний');
    }
    if (this.objects.operations.sdiz_type === 0) errors.push('Выберите операцию');
    return errors;
  }

  getErrorOperationDetail(): Array<any> {
    const errors: Array<string> = [];
    if (this.objects.operations.detail.shipment || this.objects.operations.detail.shipping) {
      if (!this.shipper_id) errors.push('Укажите грузоотправителя');
      if (!this.elevator_creator && !this.shipper_location_id) errors.push('Укажите пункт отправления');
    }
    if (this.objects.operations.detail.acceptance || this.objects.operations.detail.shipping) {
      if (!this.consignee_id) errors.push('Укажите грузополучателя');
      if (!this.elevator_creator && !this.consignee_location_id) errors.push('Укажите пункт назначения');
    }
    if (this.objects.operations.detail.realization) {
      if (!this.seller_id) errors.push('Укажите продавца');
      if (!this.buyer_id) errors.push('Укажите покупателя');
      if (!this.contract_date) errors.push('Укажите дату в сведениях о гражданско-правовом договоре');
      if (!this.contract_number) errors.push('Укажите номер в сведениях о гражданско-правовом договоре');
    }
    return errors;
  }

  getDocsAktError(): string {
    let error = '';
    for (let i = 0; i < this.objects.docs_akt.length; i++) {
      const rowError = this.getDocsAktRowError(this.objects.docs_akt[i]);
      if (rowError) error = rowError;
    }
    return error;
  }

  getDocsAktRowError(value): string {
    if (!value.type_id) return 'Укажите тип документа, подтверждающего переход права собственности ';
    if (!value.date) return 'Укажите дату документа, подтверждающего переход права собственности ';
    if (!value.number) return 'Укажите номер документа, подтверждающего переход права собственности ';
    return '';
  }

  getOtherDocsError(value): string {
    let error = '';
    if (!value.length) return error;

    value.forEach((value) => {
      if (!value.type || !value.date || !value.number) {
        if (!value.type) error = 'Укажите тип иного товаросопроводительного документа';
        if (!value.date) error = 'Укажите дату иного товаросопроводительного документа';
        if (!value.number) error = 'Укажите номер иного товаросопроводительного документа';
      }
    });

    return error;
  }

  getOtherDocsTransportsError(value): string {
    let error = '';
    if (!value.length) return error;

    value.forEach((value) => {
      if (!value.type || !value.date || !value.number) {
        if (!value.type) error = 'Укажите тип иного товаросопроводительного документа';
        if (!value.date) error = 'Укажите дату иного товаросопроводительного документа';
        if (!value.number) error = 'Укажите номер иного товаросопроводительного документа';
      }
    });

    return error;
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

  getDocsTransportsError(value): string {
    let error = '';
    if (!value.length) return error;

    value.forEach((value) => {
      if (!value.type || !value.date || !value.number) {
        if (!value.type_id) error = 'Укажите тип транспортного средства';
        if (!value.number_tc) error = 'Укажите номер транспортного средства';
      }
    });

    return error;
  }

  getCarrierLocationsError(carriers: SdizCarrierModel[]) {
    return carriers.filter((e) => e.locations.find((e) => !e.location_id)).length ? 'Укажите место перегрузки' : '';
  }

  getErrorCarriers(): string[] {
    const errors: string[] = [];

    if (!this.objects.operations.detail.shipping) {
      return errors;
    }

    const carriers: SdizCarrierModel[] = this.carriers;

    if (!carriers.length) errors.push('Добавьте хотя бы одного перевозчика');

    const noCarrierId = carriers.filter((e) => !e.carrier_id).length;
    if (noCarrierId) errors.push('Укажите наименование перевозчика');
    if (!noCarrierId && carriers.length !== Array.from(new Set(carriers.map((e) => e.carrier_id))).length) {
      errors.push('Наименования перевозчиков должны быть уникальными');
    }
    if (carriers.filter((e) => !e.doc_transports.length).length) {
      errors.push('Укажите транспортное средство в блоке информации о перевозчике');
    }
    const transportDocErrors = carriers.map((e) => this.getDocsTransportsError(e.doc_transports)).filter((e) => !!e);

    errors.push(...transportDocErrors);
    if (this.getCarrierLocationsError(carriers)) errors.push(this.getCarrierLocationsError(carriers));

    return errors;
  }

  /**
   * Возвращаем 1 оишбку из массива
   *
   * @return string
   */
  getErrors(): Array<string> {
    const errors: Array<string> = [];
    errors.push(...this.getErrorPrototypeSdiz());
    errors.push(...this.getErrorOperationDetail());
    errors.push(...this.getErrorDock());
    errors.push(...this.getErrorCarriers());
    if (!this.objects.lot.id) errors.push('Выберите партию');
    return errors;
  }

  getSdizTypeFilter(): Array<any> {
    return [
      {
        name: 'sdiz_type',
        type: 'number',
        value: (value) => {
          const array = value as unknown as Array<any>;
          let code = 0;
          array.forEach((val) => {
            const sdiz_types = store.getters['sdiz/getTypes'].filter((v) => v.id === val);
            if (sdiz_types.length > 0) {
              const code_ = parseInt(sdiz_types[0].code) as number | undefined;
              if (code_ !== undefined) {
                code += code_;
              }
            }
          });
          return code.toString();
        },
      },
    ];
  }

  getAutorizedFilter(): Array<any> {
    return [
      {
        name: 'authorized_person',
        operator: '=',
        type: 'number',
        key: 'owner_id',
      },
    ];
  }

  getAvailableFilters(): any[] {
    return [
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
      ...this.getAutorizedFilter(),
      ...this.getSdizTypeFilter(),
      { name: 'contract_number', type: 'text', operator: '%%' },
      { name: 'seller_id', type: 'number' },
      { name: 'status_id', type: 'number' },
      { name: 'buyer_id', type: 'number' },
      { name: 'shipper_id', type: 'number' },
      { name: 'consignee_id', type: 'number' },
    ];
  }

  getObjectLot():
    | LotGpbDataVueModel
    | LotDataVueModel
    | LotElevatorDataVueModel
    | LotOgvGpbDataVueModel
    | LotDataOgvVueModel
    | LotOgvElevatorDataVueModel {
    return this.objects.lot ?? this.objects.gpb;
  }

  getNameNumber(): string {
    return 'sdiz';
  }

  getListManufactures(): any[] {
    return [...this.options];
  }

  getLotModel(data?: any): LotGpbDataVueModel | LotDataVueModel | LotOgvGpbDataVueModel | LotDataOgvVueModel {
    return new LotDataVueModel(data);
  }

  getHeaders(): HeaderSdizItem[] {
    return [];
  }

  getSdizNumber(): string {
    return '-';
  }

  get status_translate(): string {
    return this.objects.sdiz_status.getStatusTranslate(this.status_id);
  }
}
