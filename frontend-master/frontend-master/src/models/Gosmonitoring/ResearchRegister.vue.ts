import { constructByInterface } from '@/utils/construct-by-interface';
import { QualityIndicatorsVueModel } from '@/models/Lot/QualityIndicators.vue';
import { LotTargetVueModel } from '@/models/Lot/LotTarget.vue';
import { AddressFiasVueModel } from '@/models/Gosmonitoring/AddressFias.vue';
import { FiltersVueInterface } from '@/models/Common/Filters.vue';
import { DefaultVueInterface } from '@/models/Common/Default.vue';
import { ManufacturerVueModel } from '@/models/Sdiz/Manufacturer.vue';
import { applyMask as applyDecimalMask } from '@/components/common/inputs/mask/decimalNumberMask';
import { applyMask as applyNumberThousandsMask } from '@/components/common/inputs/mask/numberThousandsMask';
import moment from 'moment';
import { EAction } from '@/models/roles';
import { Okpd2VueModel } from '@/models/Sdiz/Okpd2.vue';
import STATUS_NAMES from '@/utils/constants/statusNames';
import { HistoryEntryModel } from '@/models/Lot/HistoryEntry';
import { mergeQualityIndicators } from '@/utils/qualityIndicators';

export interface FishObjVueInterface {
  name: string;
}

export class FishObjVueModel implements FishObjVueInterface {
  name = '';

  constructor(o?: FishObjVueInterface) {
    constructByInterface(o, this);
  }
}

export interface ResearchRegisterVueInterface extends FiltersVueInterface, DefaultVueInterface {
  address: Array<any>;
  laboratory_monitor_id: number | null;
  laboratory_monitor_number: string | null;
  research_number: string | number | null;
  date_check: string;
  place_of_checking: AddressFiasVueModel;
  place_of_checking_id: number | null;
  status_name: string | null;
  date_of_akt_check: string;
  number_check: string | null;
  number_of_akt_check: string | null;
  number_of_akt_check_mask: string | null;
  number_grain_samples_mask: string | null;
  number_of_protocol_check_mask: string | null;
  number_grain_samples: string | null;
  date_of_protocol_check: string;
  number_of_protocol_check: string | null;
  checker_id: number | null;
  lots_numbers_from_subject: any;
  lots_numbers_from_subject_id: number | null;
  esp_id: number | null;
  date_check_from: string | null;
  date_check_to: string | null;
  target_id: number | null;
  status_id: number | null;
  checkbox_status: boolean;
  lot_target: LotTargetVueModel;
  okpd2_id: number | null;
  okpd2: Okpd2VueModel;
  quality_indicators: QualityIndicatorsVueModel[];
  versions: HistoryEntryModel[];
  owner: ManufacturerVueModel;
  status: FishObjVueModel;
  date_of_protocol_check_from: string | null;
  date_of_protocol_check_to: string | null;
  isSubscribed: boolean;
}

export class ResearchRegisterVueModel implements ResearchRegisterVueInterface {
  address: Array<any> = [];
  checkbox_status = false;
  date_check_from: string | null = null;
  date_check_to: string | null = null;
  date_of_protocol_check_from: string | null = null;
  date_of_protocol_check_to: string | null = null;
  status_name: string | null = null;

  id: number | null = null;
  laboratory_monitor_id: number | null = null;
  laboratory_monitor_number: string | null = null;
  research_number: string | number | null = null;

  place_of_checking: AddressFiasVueModel = new AddressFiasVueModel(); // с измененым форматом
  place_of_checking_id: number | null = null;

  okpd2_id: number | null = null;
  okpd2: Okpd2VueModel = new Okpd2VueModel();

  lot_target: LotTargetVueModel = new LotTargetVueModel();
  target_id: number | null = null;

  status: FishObjVueModel = new FishObjVueModel();
  status_id: number | null = null;

  number_check: string | null = null;
  number_of_akt_check: string | null = null;
  number_of_akt_check_mask: string | null = null;
  number_grain_samples: string | null = null;
  number_grain_samples_mask: string | null = null;
  number_of_protocol_check: string | null = null;
  number_of_protocol_check_mask: string | null = null;

  amount_kg: number | null = 0;
  amount_kg_mask: string | null = null;
  amount_kg_available: number | null = 0;
  amount_kg_original: number | null = 0;
  amount_kg_available_mask: string | null = null;
  amount_kg_original_mask: string | null = null;

  owner: ManufacturerVueModel = new ManufacturerVueModel();
  owner_id: number | null = null;

  lots_numbers_from_subject: any = null;
  lots_numbers_from_subject_id: number | null = null;
  checker_id: number | null = null;
  operator_id: number | null = null;
  esp_id: number | null = null;

  date_check = '';
  date_of_protocol_check = '';
  date_of_akt_check = '';
  location_of_creation_formatted = '';

  quality_indicators: QualityIndicatorsVueModel[] = [];
  versions: HistoryEntryModel[] = [];

  isSubscribed = false;
  cancel_link = 'gosmonitoring_research_register';
  update_apiendpoit = 'gosmonitoring_research_register_detail';
  create_apiendpoit = 'gosmonitoring_research_register_create';

  export_pdf_service_ammend_from_description =
    'gosmonitoring/register/research-register/export/change/pdf/from/description';
  create_ammend_api_endpoint = 'gosmonitoring/createAmmend';

  export_pdf_service = 'gosmonitoring/register/research-register/export';
  export_pdf_canceled_service = 'gosmonitoring/register/research-register/export/canceled';

  subscribe_service = 'gosmonitoring/register/research-register/subscribe';
  cancel_service = 'gosmonitoring/register/research-register/cancel';

  url = 'register/research-register';
  title = 'Реестр проведенных исследований';
  component_name = 'gosResearch';
  name_route_detail = 'gosmonitoring_research_register_detail';
  name_route_list = 'gosmonitoring_research_register';

  list_privelegies = EAction.READ_RESEARCH_REGISTER;
  view_data_privileges = EAction.READ_RESEARCH_DATA;
  create_privileges = EAction.CREATE_RESEARCH_DATA;
  delete_privileges = EAction.DELETE_RESEARCH_DATA;
  sign_privileges = EAction.SIGN_RESEARCH_DATA;
  cancel_privileges = EAction.CANCEL_RESEARCH_DATA;
  update_privileges = EAction.UPDATE_RESEARCH_DATA;

  create_gosmonitoring_grain_lot_privileges = EAction.CREATE_GOSMONITORING_GRAIN_LOT;

  available_filters: any[] = [
    ...this.timeAvailableFilters(),
    { name: 'id', type: 'number' },
    { name: 'status_id', type: 'number' },
    { name: 'okpd2Code', key: 'okpd2.code' },
    { name: 'current_location_id', type: 'number' },
    { name: 'prodact_monitor_id', type: 'number' },
    { name: 'target_id', type: 'number' },
    { name: 'owner_id', type: 'number' },
    { name: 'operator_id', type: 'number' },
    { name: 'place_of_checking_id', type: 'number' },
    { name: 'laboratory_monitor_number', operator: '%%' },
    { name: 'number_grain_samples', operator: '%%' },
    { name: 'number_of_protocol_check', operator: '%%' },
  ];
  timeAvailableFilters(): Array<any> {
    return [
      {
        name: 'date_check_from',
        operator: '>=',
        key: 'date_check',
        value: (v) => moment(v, 'DD.MM.YYYY').startOf('day').format('DD.MM.YYYY HH:mm:ss'),
      },
      {
        name: 'date_check_to',
        operator: '<=',
        key: 'date_check',
        value: (v) => moment(v, 'DD.MM.YYYY').endOf('day').format('DD.MM.YYYY HH:mm:ss'),
      },

      {
        name: 'date_of_protocol_check_from',
        operator: '>=',
        key: 'date_of_protocol_check',
        value: (v) => moment(v, 'DD.MM.YYYY').startOf('day').format('DD.MM.YYYY HH:mm:ss'),
      },
      {
        name: 'date_of_protocol_check_to',
        operator: '<=',
        key: 'date_of_protocol_check',
        value: (v) => moment(v, 'DD.MM.YYYY').endOf('day').format('DD.MM.YYYY HH:mm:ss'),
      },
    ];
  }

  get locationOfCreationFormatted() {
    if (this.lots_numbers_from_subject) {
      const location = this.lots_numbers_from_subject;
      const lots_numbers_from_subject = location?.lots_numbers_from_subject;
      const okpd2Name = location?.okpd2?.name;
      const okpd2Code = location?.okpd2?.code;
      if (!lots_numbers_from_subject) return '-';
      if (lots_numbers_from_subject && (!okpd2Name || !okpd2Code)) {
        return lots_numbers_from_subject;
      }
      return `${lots_numbers_from_subject} (${okpd2Name}, ОКПД2: ${okpd2Code})`;
    }
    return '-';
  }

  constructor(o?: ResearchRegisterVueModel) {
    if (o) {
      constructByInterface(o, this, {
        okpd2: Okpd2VueModel,
        lot_target: LotTargetVueModel,
        quality_indicators: QualityIndicatorsVueModel,
        versions: HistoryEntryModel,
      });
      if (this.number_of_akt_check !== null)
        this.number_of_akt_check_mask = applyNumberThousandsMask(this.number_of_akt_check);
      if (this.number_grain_samples !== null)
        this.number_grain_samples_mask = applyNumberThousandsMask(this.number_grain_samples);

      if (this.amount_kg_original || this.amount_kg_original === 0) {
        this.amount_kg_original_mask = applyDecimalMask(this.amount_kg_original, true);
      }

      if (this.amount_kg_available || this.amount_kg_available === 0) {
        this.amount_kg_available_mask = applyDecimalMask(this.amount_kg_available, true);
      }

      if (this.amount_kg || this.amount_kg === 0) {
        this.amount_kg_mask = applyDecimalMask(this.amount_kg, true);
      }

      if (this.status_id) {
        this.status_name = STATUS_NAMES[this.status_id] || '';
      }

      if (this.number_of_protocol_check !== null)
        this.number_of_protocol_check_mask = applyNumberThousandsMask(this.number_of_protocol_check);
      this.research_number =
        this.status_name === 'Подписано' && this.laboratory_monitor_number ? this.laboratory_monitor_number : this.id;
    }
  }

  getHeaders() {
    return [
      { text: 'Действия', value: 'actions' },
      { text: 'Номер', value: 'id' },
      { text: 'Дата проведения отбора проб', value: 'date_check' },
      { text: 'Номер акта отбора проб', value: 'number_check' },
      { text: 'Товаропроизводитель', value: 'owner.name' },
      { text: 'Место формирования партии в целях отбора проб', value: 'place_of_checking.address' },
      { text: 'Номер (шифр) пробы зерна', value: 'number_grain_samples' },
      { text: 'Дата протокола исследований зерна', value: 'date_of_protocol_check' },
      { text: 'Номер протокола исследований зерна', value: 'number_of_protocol_check' },
      { text: 'Цель использования', value: 'lot_target.name' },
      { text: 'Вид с/х культуры', value: 'okpd2.product_name_convert' },
      { text: 'Место формирования партии зерна', value: 'locationOfCreationFormatted', exclude: true },
      { text: 'Номер исследования ГМ', value: 'laboratory_monitor_number' },
      { text: 'Масса, кг', value: 'amount_kg_original_mask' },
      { text: 'Текущая масса, кг', value: 'amount_kg_available_mask' },
      { text: 'Статус', value: 'status_name' },
    ];
  }

  clearObject(): object {
    const newModel = {};
    const model = {
      status_id: this.status_id,
      date_check: this.date_check,
      place_of_checking_id: this.place_of_checking_id,
      date_of_akt_check: this.date_of_akt_check,
      number_check: this.number_check,
      number_of_akt_check: this.number_of_akt_check,
      number_grain_samples: this.number_grain_samples,
      date_of_protocol_check: this.date_of_protocol_check,
      number_of_protocol_check: this.number_of_protocol_check,
      lots_numbers_from_subject_id: this.lots_numbers_from_subject_id,
      owner_id: this.owner_id,
      checker_id: this.checker_id,
      operator_id: this.operator_id,
      okpd2_id: this.okpd2_id,
      target_id: this.target_id,
      quality_indicators: this.quality_indicators,
    };
    for (const key of Object.getOwnPropertyNames(model)) {
      if (model[key]) newModel[key] = model[key];
    }
    return newModel;
  }

  getDataForCreate() {
    return { ...this.clearObject() };
  }

  baseError(): Array<string> {
    const errors: Array<string> = [];
    if (!this.number_check) errors.push('Укажите номер акта отбора проб');
    if (!this.number_of_protocol_check) errors.push('Укажите номер протокола исследований');
    if (!this.number_grain_samples) errors.push('Укажите номер (шифр) пробы зерна');
    if (this.target_id === null) errors.push('Выберите цель использования');
    if (this.place_of_checking_id === null) errors.push('Выберите место формирования партии в целях отбора проб');
    if (this.owner_id === null) errors.push('Выберите товаропроизводителя');
    if (this.lots_numbers_from_subject_id === null) errors.push('Выберите место формирования партии зерна');
    if (this.okpd2_id === null) errors.push('Выберите C/Х культуру');
    if (this.quality_indicators.filter((v) => !v.value && v.value !== 0).length > 0) {
      errors.push('Все потребительские свойства должны быть указаны');
    }
    return errors;
  }

  validationData(limitTo): Array<string> {
    const errors: Array<string> = [];
    const format = 'DD.MM.YYYY';
    const limitDate = limitTo ? moment(limitTo, format) : null;
    if (limitDate) {
      if (!this.date_check) errors.push('Укажите дату проведения отбора проб');
      const date_check = moment(this.date_check, format);
      if (limitDate.diff(date_check, 'days') < 1) errors.push('Дата проведения отбора должна быть меньше ' + limitTo);
      if (this.date_of_protocol_check === '') errors.push('Укажите дату протокола исследований');
      const date_of_protocol_check = moment(this.date_of_protocol_check, format);
      if (limitDate.diff(date_of_protocol_check, 'days') < 1)
        errors.push('Дата протокола исследований должна быть меньше ' + limitTo);
    }
    errors.push(...this.baseError());

    return errors;
  }

  ammendReason = '';

  selectedHistoryVersionId: number | null = null;

  selectLatestHistoryVersion() {
    this.selectedHistoryVersionId = this.versions.length ? this.versions.sort((a, b) => b.id - a.id)[0].id : null;
  }

  /** Если есть история версий, то подставить в последнюю запись текущие потребсвойства из сущности */
  get historyVersions() {
    if (!this.versions.length) return [];

    const data = this.versions
      .map((e: HistoryEntryModel) => ({
        text: `№${e.version}, Дата: ${e.create_date}`,
        ...e,
      }))
      .sort((a, b) => b.id - a.id);

    data[0].quality_indicators = this.quality_indicators;

    return data;
  }

  get selectedHistoryVersion(): HistoryEntryModel | null {
    if (!this.historyVersions.length || !this.selectedHistoryVersionId) return null;

    const data = this.historyVersions.find((e) => e.id === this.selectedHistoryVersionId) || null;

    if (data !== null && Array.isArray(data?.quality_indicators)) {
      data.quality_indicators = mergeQualityIndicators(data.quality_indicators, this.quality_indicators, [
        'valueTo',
        'valueFrom',
        'type',
        'measure',
      ]);
    }

    return data;
  }

  getDataForVersionCreation(): any {
    return {
      laboratory_monitor_id: this.id,
      quality_indicators: this.quality_indicators,
      reason: this.ammendReason,
    };
  }

  getDataForVersionPdf(): any {
    return {
      id: this.id,
      quality_indicators: this.quality_indicators,
      reason: this.ammendReason,
    };
  }

  getVersionCreationErrors(): Array<string> {
    const errors: Array<string> = [];

    if (!this.ammendReason) errors.push('Укажите основание для внесения исправлений');
    if (this.quality_indicators.filter((v) => !v.value && v.value !== 0).length > 0)
      errors.push('Заполните значения потребительских свойств');

    return errors;
  }

  get okpd2Code(): string | null {
    return this.okpd2?.code ?? null;
  }

  set okpd2Code(v) {
    this.okpd2 = { ...this.okpd2, code: v };
  }
}
