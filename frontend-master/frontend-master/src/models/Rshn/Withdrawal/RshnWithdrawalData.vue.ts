import { applyMask as applyDecimalMask, validate } from '@/components/common/inputs/mask/decimalNumberMask';
import { applyMask as applyNumberThousandsMask } from '@/components/common/inputs/mask/numberThousandsMask';
import { RestrictionsData } from '@/models/Rshn/Withdrawal/RestrictionsData';
import { setTranslateStatus } from '@/utils/getTranslateStatus';
import { BatchTypeEnum, StatusEnum, WithdrawalTypeEnum } from '@/utils/enums/RshnEnums';
import { DataRshn, HeaderItem } from '@/models/Rshn/Extends/DataRshn.vue';
import { AvailableFilters } from '@/models/Common/Default.vue';
import { ShipperVueModel } from '@/models/Sdiz/Shipper.vue';
import { AddressFiasVueModel } from '@/models/Gosmonitoring/AddressFias.vue';
import { ManufacturerVueModel } from '@/models/Sdiz/Manufacturer.vue';
import { RshnPrescriptionData } from '@/models/Rshn/Prescription/RshnPrescriptionData.vue';
import { RshnExpertiseData } from '@/models/Rshn/Expertise/RshnExpertiseData.vue';
import { Okpd2VueModel } from '@/models/Sdiz/Okpd2.vue';
import { EAction } from '@/models/roles';
import { DictionaryRecordModel } from '@/models/Common/DictionaryRecord';

export interface RshnWithdrawalDataVueInterface {
  id: number | null;
  enter_date: string | null;
  sdiz_number: string | null;
  gw_type: WithdrawalTypeEnum | number | null;
  batch_type: BatchTypeEnum | null;
  lot_number: string | null;
  is_not_doc: boolean;
  owner_id: string | number | null;
  amount_kg: string | number | null;
  amount_kg_mask: string | null;
  okpd2_id: string | number | null;
  repository_id: string | number | null;
  current_location_id: string | number | null;
  current_check_location_id: string | number | null;
  gw_number: string | null;
  square: string | number | null;
  square_mask: string | null;
  status_id: StatusEnum | string | null;
  current_transit_location_id: string | number | null;
  departures_transit_location_id: string | number | null;
  transport_type: DictionaryRecordModel | null;
  transport_type_id: number | null;
  transport_number: string | null;
  container_number: string | null;
  shipper_id: string | number | null;
  operator_id: string | number | null;
  okpd2: Okpd2VueModel;
  shipper: ShipperVueModel;
  current_location: AddressFiasVueModel;
  departures_transit_location: AddressFiasVueModel;
  current_transit_location: AddressFiasVueModel;
  current_check_location: AddressFiasVueModel;
  owner: ManufacturerVueModel;
  operator: ManufacturerVueModel;
  repository: ShipperVueModel;
  restrictions: Array<RestrictionsData>;
  prescriptions: Array<RshnPrescriptionData>;
  expertises: Array<RshnExpertiseData>;
}

export class RshnWithdrawalData extends DataRshn implements RshnWithdrawalDataVueInterface {
  component_name = 'withdrawal';
  entity_name = 'withdrawal';
  list_apiendpoint = 'rshn/getListWithdrawal';
  create_apiendpoint = 'rshn/createWithdrawal';
  show_apiendpoint = 'rshn/showWithdrawal';
  update_apiendpoint = 'rshn/updateWithdrawal';
  delete_apiendpoint = 'rshn/deleteWithdrawal';
  export_apiendpoint = 'rshn/withdrawal/export';
  export_canceled_apiendpoint = 'rshn/withdrawal/export/canceled';

  subscribe_service = 'rshn/withdrawal/subscribe';
  cancel_service = 'rshn/withdrawal/cancel';

  view_data_privileges = EAction.CREATE_WITHDRAWAL;

  name_route_list = 'rshn_withdrawal_list';

  create_link = 'rshn_withdrawal_create';
  detail_link = 'rshn_withdrawal_detail';
  cancel_link = 'rshn_withdrawal_list';
  id: number | null = null;
  enter_date: string | null = null;
  sdiz_number: string | null = null;
  is_not_doc = false;
  gw_type: WithdrawalTypeEnum | number | null = null;
  batch_type: BatchTypeEnum | null = null;
  lot_number: string | null = null;
  owner_id: string | number | null = null;
  amount_kg: string | number | null = null;
  amount_kg_mask: string | null = null;
  okpd2_id: string | number | null = null;
  repository_id: string | number | null = null;
  current_location_id: string | number | null = null;
  current_check_location_id: string | number | null = null;
  gw_number: string | null = null;
  square: string | number | null = null;
  square_mask: string | null = null;
  status_id: StatusEnum | string | null = null;
  current_transit_location_id: string | number | null = null;
  departures_transit_location_id: string | number | null = null;
  transport_type: DictionaryRecordModel | null = new DictionaryRecordModel();
  transport_type_id: number | null = null;
  transport_number: string | null = null;
  container_number: string | null = null;
  shipper_id: string | number | null = null;
  operator_id: string | number | null = null;
  okpd2: Okpd2VueModel = new Okpd2VueModel();
  shipper: ShipperVueModel = new ShipperVueModel();
  current_location: AddressFiasVueModel = new AddressFiasVueModel();
  departures_transit_location: AddressFiasVueModel = new AddressFiasVueModel();
  current_transit_location: AddressFiasVueModel = new AddressFiasVueModel();
  current_check_location: AddressFiasVueModel = new AddressFiasVueModel();
  owner: ManufacturerVueModel = new ManufacturerVueModel();
  operator: ManufacturerVueModel = new ManufacturerVueModel();
  repository: ShipperVueModel = new ShipperVueModel();
  restrictions: Array<RestrictionsData> = [];
  prescriptions: Array<RshnPrescriptionData> = [];
  expertises: Array<RshnExpertiseData> = [];
  available_filters: AvailableFilters[] = [
    ...this.getAvailableFilters(),
    { name: 'sdiz_number', operator: '%%', type: 'text' },
    { name: 'lot_number', operator: '%%', type: 'text' },
    { name: 'gw_number', operator: '%%', type: 'text' },
    { name: 'status_id', type: 'string' },
    { name: 'gw_type', type: 'string' },
    { name: 'departures_transit_location_id', type: 'number' },
    { name: 'okpd2Code', key: 'okpd2.code' },
    { name: 'owner_id', type: 'number' },
    { name: 'current_location_id', type: 'number' },
    { name: 'square', type: 'number' },
    { name: 'lot_number', operator: '%%', type: 'text' },
    { name: 'current_transit_location_id', type: 'number' },
    { name: 'shipper_id', type: 'number' },
    { name: 'transport_number', operator: '%%', type: 'text' },
    { name: 'container_number', operator: '%%', type: 'text' },
    { name: 'amount_kg', type: 'number' },
  ];
  headers: HeaderItem[] = [
    { text: 'Действия', value: 'actions' },
    { text: 'ID', value: 'id' },
    { text: 'Статус', value: 'status_translate' },
    { text: 'Дата формирования', value: 'enter_date' },
    { text: 'Вид СХ культуры или продукта переработки зерна ', value: 'okpd2.product_name_convert' },
    { text: 'Собственник ', value: 'owner.name' },
    { text: 'Масса нетто, кг ', value: 'amount_kg_mask' },
    { text: 'Номер СДИЗ ', value: 'sdiz_number' },
    { text: 'Номер Партии', value: 'lot_number' },
    { text: 'Площадь земельного участка (га)', value: 'square' },
    { text: 'Пункт отправления', value: 'departures_transit_location_id' },
    { text: 'Пункт назначения', value: 'current_transit_location_id' },
    { text: 'Перевозчик', value: 'shipper_id' },
    { text: 'Номер транспортного средства', value: 'transport_number' },
    { text: 'Номер контейнера', value: 'container_number' },
    { text: 'Место изъятия', value: 'current_check_location.address' },
  ];

  constructor(o?: RshnWithdrawalData) {
    super(o);
    this.init(o, {
      okpd2: Okpd2VueModel,
      shipper: ShipperVueModel,
      current_location: AddressFiasVueModel,
      departures_transit_location: AddressFiasVueModel,
      current_transit_location: AddressFiasVueModel,
      current_check_location: AddressFiasVueModel,
      owner: ManufacturerVueModel,
      operator: ManufacturerVueModel,
      repository: ShipperVueModel,
      restrictions: RestrictionsData,
      prescriptions: RshnPrescriptionData,
      expertises: RshnExpertiseData,
      transport_type: DictionaryRecordModel,
    });
    this.status_translate = setTranslateStatus(this.status_id);
    this.amount_kg_mask = this.amount_kg ? applyDecimalMask(this.amount_kg, true) : '';
    this.square_mask = this.square ? applyNumberThousandsMask(this.square) : '';
  }

  public getDataForCreateOrUpdate(): any {
    return {
      gw_type: this.gw_type,
      owner_id: this.owner_id,
      operator_id: this.operator_id,
      enter_date: this.enter_date,
      amount_kg: this.amount_kg,
      okpd2_id: this.okpd2?.id,
      current_check_location_id: this.current_check_location_id,
      batch_type: this.isGrain ? BatchTypeEnum.LOT : BatchTypeEnum.GBP,
      ...this.getData(),
    };
  }

  public createNewModel(response: any) {
    return new RshnWithdrawalData(response);
  }

  public createNewModelDop(response: any) {
    return new RestrictionsData(response);
  }

  public getNumber() {
    return this.gw_number ?? this.id;
  }
  getArrayDop() {
    return this.restrictions;
  }

  public getErrors(): Array<string> {
    const errors: Array<string> = [];
    switch (this.gw_type) {
      case WithdrawalTypeEnum.PRODUCT:
        errors.push(...this.getErrorProduct());
        break;
      case WithdrawalTypeEnum.SHIPPING:
        errors.push(...this.getErrorShipping());
        break;
      case WithdrawalTypeEnum.STORAGE:
        errors.push(...this.getErrorStorage());
        break;
    }
    if (this.isShippingOrStorage) errors.push(...this.getErrorDocuments());
    errors.push(...this.getErrorBase());
    return errors;
  }

  private getErrorDocuments() {
    const error: Array<string> = [];

    if (!this.is_not_doc && !this.lot_number) error.push('Укажите номер партии');

    return error;
  }

  private getErrorBase(): Array<string> {
    const error: Array<string> = [];
    if (!this.owner_id) error.push('Укажите собственника');
    if (!this.enter_date) error.push('Укажите дату');
    if (this.amount_kg === null) error.push('Укажите массу');
    if (validate(this.amount_kg_mask)) error.push('Граммы должны быть указаны от 001 до 999');
    if (!this.okpd2?.id) error.push('Выберите вид с/х культуры');
    if (!this.current_check_location_id) error.push(`Укажите ${this.currentCheckLocationTitle.toLowerCase()}`);
    return error;
  }

  private getErrorProduct(): Array<string> {
    const error: Array<string> = [];
    if (!this.square && this.isSquareField) error.push('Введите  площадь');
    return error;
  }
  private getErrorShipping(): Array<string> {
    const error: Array<string> = [];
    if (!this.departures_transit_location_id) error.push('Выберите пункт отправления');
    if (!this.current_transit_location_id) error.push('Выберите пункт назначения');
    if (!this.transport_type?.id) error.push('Выберите тип средства перевозки');
    if (!this.transport_number) error.push('Введите номер ГРН/Номер вагона/Номер воздушного судна');
    if (!this.shipper_id) error.push('Выберите перевозчика');
    return error;
  }
  private getErrorStorage(): Array<string> {
    // const error: Array<string> = [];
    // if (!this.repository_id && this.isRepositoryIdField) error.push('Укажите организацию, осуществляющую хранение');
    // if (!this.current_location_id && this.isCurrentLocationIdField) error.push('Выберите место хранения');
    // return error;
    return [] as string[];
  }

  private getDataProduct() {
    return {
      square: this.isSquareField ? this.square : null,
    };
  }
  private getDataShipping() {
    return {
      is_not_doc: this.is_not_doc,
      sdiz_number: this.sdiz_number,
      lot_number: this.lot_number,
      departures_transit_location_id: this.departures_transit_location_id,
      current_transit_location_id: this.current_transit_location_id,
      transport_type_id: this.transport_type?.id || null,
      transport_number: this.transport_number,
      container_number: this.container_number,
      shipper_id: this.shipper_id,
    };
  }

  private getDataStorage() {
    return {
      is_not_doc: this.is_not_doc,
      sdiz_number: this.sdiz_number,
      lot_number: this.lot_number,
      repository_id: this.isRepositoryIdField ? this.repository_id : null,
      current_location_id: this.isCurrentLocationIdField ? this.current_location_id : null,
    };
  }

  private getData(): object | void {
    switch (this.gw_type) {
      case WithdrawalTypeEnum.PRODUCT:
        return this.getDataProduct();
      case WithdrawalTypeEnum.SHIPPING:
        return this.getDataShipping();
      case WithdrawalTypeEnum.STORAGE:
        return this.getDataStorage();
    }
  }

  get isShippingOrStorage() {
    return this.gw_type === WithdrawalTypeEnum.STORAGE || this.gw_type === WithdrawalTypeEnum.SHIPPING;
  }

  get isStorage() {
    return this.gw_type === WithdrawalTypeEnum.STORAGE;
  }

  get isGrain() {
    return this.okpd2?.is_grain;
  }

  get isProduct() {
    return this.okpd2?.is_product;
  }

  get currentCheckLocationTitle() {
    if (this.gw_type === WithdrawalTypeEnum.STORAGE) return 'Место изъятия (хранения)';
    if (this.gw_type === WithdrawalTypeEnum.SHIPPING) return 'Место изъятия';
    if (this.gw_type === WithdrawalTypeEnum.PRODUCT && this.isGrain) return 'Место выращивания (изъятия)';
    if (this.gw_type === WithdrawalTypeEnum.PRODUCT && this.isProduct) return 'Место производства (изъятия)';
    return 'Место изъятия';
  }

  get currentCheckLocationPlaceholder() {
    return `Выберите ${this.currentCheckLocationTitle.toLowerCase()}`;
  }

  get isSquareField() {
    return this.gw_type === WithdrawalTypeEnum.PRODUCT && this.isGrain;
  }

  get isCurrentLocationIdField() {
    return this.isStorage && this.isGrain;
  }

  get isRepositoryIdField() {
    return this.isStorage && this.isGrain;
  }

  get okpd2Code() {
    return this.okpd2?.code || null;
  }

  set okpd2Code(v) {
    this.okpd2 = { ...this.okpd2, code: v };
  }
}
