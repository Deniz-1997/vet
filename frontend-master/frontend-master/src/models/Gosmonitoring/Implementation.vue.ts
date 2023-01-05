import { constructByInterface } from '@/utils/construct-by-interface';
import { LotTargetVueModel } from '@/models/Lot/LotTarget.vue';
import { FishObjVueModel } from '@/models/Gosmonitoring/ResearchRegister.vue';
import { AddressFiasVueModel } from '@/models/Gosmonitoring/AddressFias.vue';
import { FiltersVueInterface } from '@/models/Common/Filters.vue';
import { DefaultVueInterface } from '@/models/Common/Default.vue';
import { ManufacturerVueModel } from '@/models/Sdiz/Manufacturer.vue';
import { applyMask as applyDecimalMask, validate } from '@/components/common/inputs/mask/decimalNumberMask';
import { applyMask as applyNumberThousandsMask } from '@/components/common/inputs/mask/numberThousandsMask';
import moment from 'moment';
import { EAction } from '@/models/roles';
import { Okpd2VueModel } from '@/models/Sdiz/Okpd2.vue';
import STATUS_NAMES from '@/utils/constants/statusNames';

export interface ImplementationVueInterface extends FiltersVueInterface, DefaultVueInterface {
  status_id: number | null;
  checkbox_status: boolean;
  prodact_monitor_id: number | null;
  prodact_monitor_number: string | null;
  prodact_monitor_number_mask: string | null;
  status_name: string | null;
  weight_mask: string | null;
  area_mask: string | null;
  area: number | null;
  ownership_details: string | null;
  place_of_cultivation: AddressFiasVueModel;
  place_of_cultivation_id: string | null;
  current_location: AddressFiasVueModel;
  ownership_details_id: number | null;
  weight: number | null;
  lots_numbers_from_subject: any;
  lots_numbers_from_subject_id: number | null;
  esp_id: number | null;
  lot_target: LotTargetVueModel;
  okpd2_id: number | null;
  okpd2: Okpd2VueModel;
  owner: ManufacturerVueModel;
  status: FishObjVueModel;
  isSubscribed: boolean;
}

export class ImplementationVueModel implements ImplementationVueInterface {
  checkbox_status = false;

  date_from: string | null = null;
  date_to: string | null = null;

  id: number | null = null;
  prodact_monitor_id: number | null = null;
  prodact_monitor_number: string | null = null;
  prodact_monitor_number_mask: string | null = null;

  status_name: string | null = null;
  date_enter: string | null = null;

  place_of_cultivation: AddressFiasVueModel = new AddressFiasVueModel(); // с измененым форматом
  place_of_cultivation_id: string | null = null;

  current_location: AddressFiasVueModel = new AddressFiasVueModel(); // с измененым форматом
  current_location_id: string | null = null;

  ownership_details: string | null = null; // с измененым форматом
  ownership_details_id: number | null = null;

  okpd2_id: number | null = null;
  okpd2: Okpd2VueModel = new Okpd2VueModel();

  weight: number | null = null;
  weight_mask: string | null = null;

  area: number | null = null;
  area_mask: string | null = null;

  lots_numbers_from_subject: any = null;
  lots_numbers_from_subject_id: number | null = null;

  lot_target: LotTargetVueModel = new LotTargetVueModel();

  owner: ManufacturerVueModel = new ManufacturerVueModel();
  owner_id: number | null = null;

  status: FishObjVueModel = new FishObjVueModel();
  status_id: number | null = null;
  esp_id: number | null = null;
  component_name = 'gosImplementation';
  cancel_link = 'gosmonitoring_register_implementation';
  update_apiendpoit = 'gosmonitoring_register_implementation_detail';
  create_apiendpoit = 'gosmonitoring_register_implementation_create';
  url = 'register/implementation';

  export_pdf_service = 'gosmonitoring/register/implementation/export';
  export_pdf_canceled_service = 'gosmonitoring/register/implementation/export/canceled';

  subscribe_service = 'gosmonitoring/register/implementation/subscribe';
  cancel_service = 'gosmonitoring/register/implementation/cancel';

  title = 'Реестр сведений о собранном урожае';
  name_route_detail = 'gosmonitoring_register_implementation_detail';
  name_route_list = 'gosmonitoring_register_implementation';

  register_read_privileges = EAction.READ_GOSMONITORING_DATA_REGISTER;
  view_data_privileges = EAction.READ_GOSMONITORING_DATA;
  create_privileges = EAction.CREATE_GOSMONITORING_DATA;
  update_privileges = EAction.UPDATE_GOSMONITORING_DATA;
  delete_privileges = EAction.DELETE_GOSMONITORING_DATA;
  sign_privileges = EAction.SIGN_GOSMONITORING_DATA;
  cancel_privileges = EAction.CANCEL_GOSMONITORING_DATA;
  isSubscribed = false;
  get numbersFromSubjectFormatted() {
    const numbersFromSubject = this.lots_numbers_from_subject;
    return numbersFromSubject
      ? `${numbersFromSubject.lots_numbers_from_subject} (${numbersFromSubject.okpd2.name}, ОКПД2: ${numbersFromSubject.okpd2.code})`
      : '';
  }

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
    { name: 'status_id', type: 'number' },
    { name: 'okpd2Code', key: 'okpd2.code' },
    { name: 'current_location_id', type: 'number' },
    { name: 'prodact_monitor_id', type: 'number' },
    { name: 'owner_id', type: 'number' },
    { name: 'place_of_cultivation_id', type: 'number' },
    { name: 'prodact_monitor_number', operator: '%%' },
  ];

  constructor(o?: ImplementationVueModel) {
    if (o !== undefined) {
      if (o.weight !== null) this.weight_mask = applyDecimalMask(o.weight, true);
      if (o.area !== null) this.area_mask = applyNumberThousandsMask(o.area);
      if (this.status_id) {
        this.status_name = STATUS_NAMES[this.status_id] || '';
      }
      constructByInterface(
        o,
        this,
        {
          okpd2: Okpd2VueModel,
          lot_target: LotTargetVueModel,
        },
        true
      );
      if (this.status_id) {
        this.status_name = STATUS_NAMES[this.status_id] || '';
      }
    }
  }

  getHeaders() {
    return [
      { text: 'Действия', value: 'actions' },
      { text: 'Номер', value: 'id', notExclude: true },
      { text: 'Номер документа', value: 'prodact_monitor_number', notExclude: true },
      { text: 'Дата сбора урожая', value: 'date_enter' },
      { text: 'Место выращивания партии зерна', value: 'place_of_cultivation.address' },
      {
        text: 'Площадь земельного участка или его части (поля), с которого собран урожай зерна (га)',
        value: 'area_mask',
      },
      {
        text: 'Сведения о виде вещного права на земельный участок или его часть (поле), с которого собран урожай зерна',
        value: 'ownership_details.name',
      },
      { text: 'Вид сельскохозяйственной культуры зерна', value: 'okpd2.product_name_convert' },
      {
        text: 'Масса зерна (нетто в килограммах), произведенного в день уборки урожая',
        value: 'weight_mask',
        notExclude: true,
      },
      { text: 'Место хранения зерна', value: 'current_location.address' },
      { text: 'Место формирования партии зерна', value: 'numbersFromSubjectFormatted' },
      { text: 'Статус', value: 'status_name' },
    ];
  }

  getDataForCreate() {
    return {
      lots_numbers_from_subject_id: this.lots_numbers_from_subject_id,
      okpd2_id: this.okpd2_id,
      date_enter: this.date_enter,
      area: this.area,
      ownership_details_id: this.ownership_details_id,
      current_location_id: this.current_location_id,
      place_of_cultivation_id: this.place_of_cultivation_id,
      weight: this.weight,
      owner_id: this.owner_id,
    };
  }

  validationData(): Array<string> {
    const errors: Array<string> = [];
    if (this.date_enter === null) errors.push('Укажите дату');
    if (this.area === null) errors.push('Укажите площадь');
    if (this.weight === null) errors.push('Укажите массу');
    if (validate(this.weight)) errors.push('Граммы должны быть указаны от 001 до 999');
    if (this.ownership_details_id === null) errors.push('Выберите право собственности');
    if (this.place_of_cultivation_id === null) errors.push('Выберите место выращивания партии зерна');
    if (this.current_location_id === null) errors.push('Выберите место хранения');
    if (this.lots_numbers_from_subject_id === null) errors.push('Выберите место формирования партии зерна');
    if (this.okpd2_id === null) errors.push('Выберите C/Х культуру');
    return errors;
  }

  get okpd2Code(): string | null {
    return this.okpd2?.code ?? null;
  }

  set okpd2Code(v) {
    this.okpd2 = { ...this.okpd2, code: v };
  }
}
