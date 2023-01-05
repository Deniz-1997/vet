import { setTranslateExpertiseType, setTranslateStatus } from '@/utils/getTranslateStatus';
import { ConformityEnum, ExpertiseEnum, ExpertiseSdizType, StatusEnum } from '@/utils/enums/RshnEnums';
import { DataRshn, HeaderItem } from '@/models/Rshn/Extends/DataRshn.vue';
import { AvailableFilters } from '@/models/Common/Default.vue';
import { ManufacturerVueModel } from '@/models/Sdiz/Manufacturer.vue';
import { RshnWithdrawalData } from '@/models/Rshn/Withdrawal/RshnWithdrawalData.vue';
import moment from 'moment';
import { QualityIndicatorsVueModel } from '@/models/Lot/QualityIndicators.vue';
import { SdizShortVue } from '@/models/Rshn/ShortModel/SdizShort.vue';
import { Okpd2VueModel } from '@/models/Sdiz/Okpd2.vue';
import { EAction } from '@/utils';
import { getDateObject } from '@/utils/date';

export interface RshnExpertiseDataVueInterface {
  id: number | null;
  expertise_type: ExpertiseEnum | number | null;
  expertise_number: string | null;
  gw_id: number | null;
  sdiz_id: number | null;
  gpb_sdiz_id: number | null;

  decision_date: string | null;
  departure_date: string | null;
  expertise_date: string | null;
  selection_date: string | null;
  selection_number: string | null;
  conformity_sign: number | null;
  is_not_conducted: boolean | null;

  test_report: string | null;
  conclusion: string | null;
  status_id: number | null;
  operator_id: number | null;
  operator: ManufacturerVueModel;
  withdrawal: RshnWithdrawalData;
  quality_indicators: Array<QualityIndicatorsVueModel>;
}

export class RshnExpertiseData extends DataRshn implements RshnExpertiseDataVueInterface {
  component_name = 'expertise';
  entity_name = 'expertise';
  list_apiendpoint = 'rshn/getListExpertise';
  create_apiendpoint = 'rshn/createExpertise';
  show_apiendpoint = 'rshn/showExpertise';
  update_apiendpoint = 'rshn/updateExpertise';
  delete_apiendpoint = 'rshn/deleteExpertise';
  export_apiendpoint = 'rshn/withdrawal/expertise/export';
  export_canceled_apiendpoint = 'rshn/withdrawal/expertise/export/canceled';
  create_link = 'rshn_expertise_create';
  detail_link = 'rshn_expertise_detail';
  cancel_link = 'rshn_expertise_list';

  name_route_list = 'rshn_expertise_list';

  subscribe_service = 'rshn/withdrawal/expertise/subscribe';
  cancel_service = 'rshn/withdrawal/expertise/cancel';

  id: number | null = null;
  expertise_type: ExpertiseEnum | number | null = null;

  decision_date: string | null = null;
  departure_date: string | null = null;
  expertise_date: string | null = null;
  selection_date: string | null = null;
  selection_number: string | null = null;
  conformity_sign: number | ConformityEnum | null = null;
  is_not_conducted: boolean | null = null;

  expertise_number: string | null = null;
  gw_id: number | null = null;
  sdiz_id: number | null = null;
  gpb_sdiz_id: number | null = null;
  gw_number: number | null = null;
  sdiz_number: number | null = null;
  test_report: string | null = null;
  conclusion: string | null = null;

  view_data_privileges = EAction.CREATE_EXPERTISE;

  status_id: number | StatusEnum | null = null;
  operator_id: number | null = null;
  status_translate: string | null = null;
  expertise_type_translate: string | null = null;
  operator: ManufacturerVueModel = new ManufacturerVueModel();
  withdrawal: RshnWithdrawalData = new RshnWithdrawalData();
  quality_indicators: Array<QualityIndicatorsVueModel> = [];
  okpd2: Okpd2VueModel = new Okpd2VueModel();
  sdiz: SdizShortVue = new SdizShortVue();
  sdiz_type: ExpertiseSdizType = ExpertiseSdizType.SDIZ;
  decision_date_to: string | null = null;
  decision_date_from: string | null = null;
  departure_date_to: string | null = null;
  departure_date_from: string | null = null;
  expertise_date_to: string | null = null;
  expertise_date_from: string | null = null;
  selection_date_to: string | null = null;
  selection_date_from: string | null = null;
  time_filter = [
    {
      name: 'decision_date_from',
      operator: '>=',
      key: 'decision_date',
      value: (v) => moment(v, 'DD.MM.YYYY').startOf('day').format('DD.MM.YYYY'),
    },
    {
      name: 'decision_date_to',
      operator: '<=',
      key: 'decision_date',
      value: (v) => moment(v, 'DD.MM.YYYY').endOf('day').format('DD.MM.YYYY'),
    },
    {
      name: 'departure_date_from',
      operator: '>=',
      key: 'departure_date',
      value: (v) => moment(v, 'DD.MM.YYYY').startOf('day').format('DD.MM.YYYY'),
    },
    {
      name: 'departure_date_to',
      operator: '<=',
      key: 'departure_date',
      value: (v) => moment(v, 'DD.MM.YYYY').endOf('day').format('DD.MM.YYYY'),
    },
    {
      name: 'expertise_date_from',
      operator: '>=',
      key: 'expertise_date',
      value: (v) => moment(v, 'DD.MM.YYYY').startOf('day').format('DD.MM.YYYY'),
    },
    {
      name: 'expertise_date_to',
      operator: '<=',
      key: 'expertise_date',
      value: (v) => moment(v, 'DD.MM.YYYY').endOf('day').format('DD.MM.YYYY'),
    },
    {
      name: 'selection_date_from',
      operator: '>=',
      key: 'selection_date',
      value: (v) => moment(v, 'DD.MM.YYYY').startOf('day').format('DD.MM.YYYY'),
    },
    {
      name: 'selection_date_to',
      operator: '<=',
      key: 'selection_date',
      value: (v) => moment(v, 'DD.MM.YYYY').endOf('day').format('DD.MM.YYYY'),
    },
  ];
  available_filters: AvailableFilters[] = [
    ...this.getAvailableFilters(),
    { name: 'sdiz_number', operator: '%%', type: 'text' },
    { name: 'gw_number', operator: '%%', type: 'text' },
    { name: 'selection_number', operator: '%%', type: 'text' },
    { name: 'expertise_number', operator: '%%', type: 'text' },
    { name: 'status_id', type: 'string' },
    { name: 'expertise_type', type: 'string' },
  ];
  headers: HeaderItem[] = [
    { text: 'Действия', value: 'actions' },
    { text: 'ID', value: 'id' },
    { text: 'Номер решения о проведении экспертизы', value: 'expertise_number' },
    { text: 'Дата решения о проведении экспертизы', value: 'decision_date' },
    { text: 'Дата проведения экспертизы ', value: 'expertise_date' },
    { text: 'Номер пробы', value: 'selection_number' },
    { text: 'Тип экспертизы', value: 'expertise_type_translate' },
    { text: 'Дата направления образца', value: 'departure_date' },
    { text: 'Дата отбора образца', value: 'selection_date' },
    { text: 'Номер изъятия', value: 'gw_id' },
    { text: 'Номер СДИЗ ', value: 'sdiz_number' },
    { text: 'Статус', value: 'status_translate' },
  ];

  constructor(o?: RshnExpertiseData) {
    super(o);
    this.init(o, {
      operator: ManufacturerVueModel,
      withdrawal: RshnWithdrawalData,
      quality_indicators: QualityIndicatorsVueModel,
      okpd2: Okpd2VueModel,
      sdiz: SdizShortVue,
    });
    this.status_translate = setTranslateStatus(this.status_id);
    this.expertise_type_translate = setTranslateExpertiseType(this.expertise_type);
  }
  getAvailableFilters(): any[] {
    return this.time_filter;
  }

  getDataForCreateOrUpdate(): any {
    return this.is_not_conducted
      ? {
          ...this.getData(),
          quality_indicators: this.quality_indicators,
          expertise_type: this.expertise_type,
          is_not_conducted: this.is_not_conducted || false,
        }
      : {
          ...this.getData(),
          decision_date: this.decision_date,
          selection_date: this.selection_date,
          selection_number: this.selection_number,
          departure_date: this.departure_date,
          expertise_date: this.expertise_date,
          expertise_number: this.expertise_number,
          test_report: this.test_report,
          conclusion: this.conclusion,
          quality_indicators: this.quality_indicators,
          expertise_type: this.expertise_type,
          is_not_conducted: this.is_not_conducted || false,
        };
  }

  createNewModel(response: any) {
    return new RshnExpertiseData(response);
  }

  getNumber() {
    return this.gw_number ?? this.id;
  }

  get attachedDocumentEnterDate(): Date | null {
    let dateString = '';

    switch (this.expertise_type) {
      case ExpertiseEnum.WITHDRAWAL:
        dateString = this.withdrawal?.enter_date || dateString;
        break;
      default:
        dateString = this.sdiz?.enter_date;
    }

    return dateString ? getDateObject(dateString) : null;
  }

  set attachedDocumentEnterDate(v) {
    switch (this.expertise_type) {
      case ExpertiseEnum.WITHDRAWAL:
        this.withdrawal.enter_date = v && String(v);
        break;
      default:
        this.sdiz.enter_date = v ? String(v) : '';
    }
  }

  get attachedDocumentTitle() {
    switch (this.expertise_type) {
      case ExpertiseEnum.WITHDRAWAL:
        return 'изъятия';
      default:
        return 'СДИЗ';
    }
  }

  getDateErrors(): Array<string> {
    const errors: Array<string> = [];

    if (!this.is_not_conducted) {
      const decisionDate = this.decision_date ? getDateObject(this.decision_date) : null;
      const selectionDate = this.selection_date ? getDateObject(this.selection_date) : null;
      const departureDate = this.departure_date ? getDateObject(this.departure_date) : null;
      const expertiseDate = this.expertise_date ? getDateObject(this.expertise_date) : null;

      const checkDates = (a, b) => {
        return a && b && a < b;
      };

      if (checkDates(selectionDate, decisionDate))
        errors.push('Дата отбора образца не может предшествовать дате решения о проведении экспертизы');
      if (checkDates(departureDate, selectionDate))
        errors.push('Дата направления образца не может предшествовать дате отбора образца');
      if (checkDates(expertiseDate, departureDate))
        errors.push('Дата проведения экспертизы не может предшествовать дате направления образца');
      if (checkDates(decisionDate, this.attachedDocumentEnterDate))
        errors.push(
          `Дата решения о проведении экспертизы не может предшествовать дате формирования ${this.attachedDocumentTitle}`
        );
    }

    return errors;
  }

  getErrors(): Array<string> {
    const error: Array<string> = [];
    if (!this.is_not_conducted) {
      if (!this.decision_date) error.push('Укажите дату решения о проведении экспертизы');
      if (!this.expertise_number) error.push('Укажите номер решения о проведении экспертизы');
      if (!this.selection_date) error.push('Укажите дату отбора образца');
      if (!this.departure_date) error.push('Укажите дату направления образца');
      if (!this.expertise_date) error.push('Укажите дату проведения экспертизы');
      if (!this.test_report) error.push('Заполните протокол испытаний отобранного образца партии зерна');
      if (!this.conclusion) error.push('Заполните заключение по результатам экспертизы');
      if (this.quality_indicators.length > 0) {
        this.quality_indicators.map((qia) => {
          if (typeof qia.value === 'undefined') {
            error.push('Все потребительские свойства должны быть указаны');
          }
        });
      }
    }

    error.push(...this.getDateErrors());
    return error;
  }

  private getData(): object | void {
    switch (this.expertise_type) {
      case ExpertiseEnum.WITHDRAWAL:
        return {
          gw_id: this.gw_id,
        };
      case ExpertiseEnum.IMPORT:
        return {
          sdiz_number: this.sdiz_number,
          sdiz_id: this.sdiz_id,
          gpb_sdiz_id: this.gpb_sdiz_id,
          conformity_sign: this.conformity_sign ? 1 : 0,
        };
      case ExpertiseEnum.EXPORT:
        return {
          sdiz_id: this.sdiz_id,
          gpb_sdiz_id: this.gpb_sdiz_id,
          sdiz_number: this.sdiz_number,
          conformity_sign: this.conformity_sign ? 1 : 0,
        };
      default:
        return {};
    }
  }

  get attachedSdizType(): ExpertiseSdizType {
    if (this.sdiz_id) return ExpertiseSdizType.SDIZ;
    if (this.gpb_sdiz_id) return ExpertiseSdizType.GPB_SDIZ;
    return ExpertiseSdizType.NONE;
  }

  get attachedSdizId(): number | null {
    return this.sdiz_id || this.gpb_sdiz_id || null;
  }

  getSdizShowService(): string | null {
    let service: string | null = null;

    switch (this.attachedSdizType) {
      case ExpertiseSdizType.SDIZ:
        service = 'sdiz/show';
        break;
      case ExpertiseSdizType.GPB_SDIZ:
        service = 'sdiz/showForGpb';
        break;
    }

    return service;
  }

  get isNotConducted(): boolean {
    return this.is_not_conducted;
  }

  set isNotConducted(v) {
    this.is_not_conducted = v;

    if (v) {
      this.decision_date = null;
      this.selection_date = null;
      this.selection_number = null;
      this.departure_date = null;
      this.expertise_date = null;
      this.expertise_number = null;
      this.test_report = null;
      this.conclusion = null;
    }
  }
}
