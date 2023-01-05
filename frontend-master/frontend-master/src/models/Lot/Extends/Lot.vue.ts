import { constructByInterface } from '@/utils/construct-by-interface';
import { FiltersVueInterface } from '@/models/Common/Filters.vue';
import { AvailableFilters, DefaultVueInterface, HeaderSdizItem } from '@/models/Common/Default.vue';
import { ObjectsLotVueModel } from '@/models/Lot/Objects';
import { LotInterface } from '@/models/Lot/interface/Lot.interface';
import { applyMask } from '@/components/common/inputs/mask/decimalNumberMask';
import moment from 'moment';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';
import { LotDataOgvVueModel } from '@/models/Lot/Ogv/LotDataOgv.vue';
import { LotOgvGpbDataVueModel } from '@/models/Lot/Ogv/LotOgvGpbData.vue';
import { LotOgvElevatorDataVueModel } from '@/models/Lot/Ogv/LotOgvElevatorData.vue';
import { RestrictionsEnum } from '@/utils/enums/RshnEnums';
import { HistoryEntryModel } from '@/models/Lot/HistoryEntry';
import { DocsVueModel } from '@/models/Lot/Docs.vue';
import { LotsMovedVueModel } from '@/models/Lot/LotsMoved.vue';
import { mergeQualityIndicators } from '@/utils/qualityIndicators';
import { LotsPurposeEnum } from '@/utils/enums/lotsPurpose.enum';
import { IndicatorPurposeEnum } from '@/utils/enums/indicatorPurpose.enum';
import { add } from '@/utils/decimals';

export class Lot implements LotInterface, FiltersVueInterface, DefaultVueInterface {
  id: number | null = null;
  objects: ObjectsLotVueModel = new ObjectsLotVueModel();
  component_name = '';

  date_from: string | null = null;
  date_to: string | null = null;
  type: string | null = null;
  filter_name = '';
  docs_type: string | null = null;
  docs_number: string | null = null;
  origin_location: object | null = null;

  amount_kg_mask: string | null = null;
  amount_kg_original_mask: string | null = null;
  target_id: number | null = null;
  esp_id: number | null = null;
  operator_id: number | null = null;
  current_location_id: number | null = null;
  origin_location_id: string | null = null;
  country_destination_id: number | null = null;
  number_type = '';
  filter_sdiz_lot_number = '';
  error_find_number_from_reserves = '';
  lot_year: string | number | null = null;

  purpose_id: number | null = null;
  amount_kg: number | null = null;
  amount_kg_available_mask: string | null = '';
  amount_kg_from: number | null = null;
  amount_kg_from_mask: string | null = null;
  amount_kg_to: number | null = null;
  amount_kg_to_mask: string | null = null;
  amount_kg_original: number | null = null;
  valid_paper_sdiz_number = true;

  status_translate: string | null = null;
  status: string | null = null;
  restriction_type: RestrictionsEnum | null = null;

  owner_id: number | null = null;

  date_enter: string | null = null;
  enter_date: string | null = null;
  available_filters: AvailableFilters[] = [];

  original_data: any = null;
  amount_kg_available: number | null = null;
  titleForFirstBlockSdiz = '';
  movedField = '';
  propertyNameForSdiz = '';

  lot_tables_paper_store_title = '';
  name_from_another_batch = '';
  name_from_imported = '';
  name_from_residues = '';
  name_from_sdiz = '';

  name_route_list = '';
  name_route_detail = '';
  create_from_another_batch = '';
  create_from_field = '';
  create_from_imported = '';
  create_from_residues = '';
  create_from_sdiz = '';
  create_from_in_product = '';

  list_sdiz_apiendpoint = '';
  list_apiendpoit = '';
  create_apiendpoit = '';
  show_apiendpoit = '';
  update_apiendpoit = '';
  delete_apiendpoit = '';
  reserve_number_apiendpoit = '';
  export_pdf_service = '';
  export_pdf_service_cancel = '';
  export_pdf_service_debit = '';
  export_pdf_service_debit_from_description = '';
  export_pdf_service_debit_cancel = '';

  export_pdf_service_ammend_from_description = '';
  create_ammend_api_endpoint = '';

  subscribe_service = '';
  cancel_service = '';
  debit_service = '';
  debit_cancel_service = '';

  lotType: Record<string, boolean> = {};

  register_grain_lot_product_read_privileges = '';
  filter_register_grain_lot_product_privileges = '';

  view_data_privileges = '';

  create_gosmonitoring_grain_lot_privileges = '';
  create_product_grain_lot_privileges = '';
  create_other_grain_lot_privileges = '';
  create_surples_grain_lot_privileges = '';
  create_import_grain_lot_privileges = '';
  create_sdiz_grain_lot_privileges = '';

  update_privileges = '';
  delete_privileges = '';
  cancel_privileges = '';
  cancel_lot_surples = '';
  ammend_priveleges = '';

  constructor(o?: any) {
    this.init(o);
  }

  init(o) {
    if (o !== undefined) {
      constructByInterface(o, this, {}, true);
      this.objects = new ObjectsLotVueModel(o);
      this.original_data = o;
      this.setTranslateStatus();
      this.amount_kg_mask = this.amount_kg ? applyMask(this.amount_kg, true) : '0';

      if (this.amount_kg_original) {
        this.amount_kg_original_mask = applyMask(this.amount_kg_original, true);
      }

      if (this.amount_kg_available) {
        this.amount_kg_available_mask = applyMask(this.amount_kg_available, true);
      }

      if (this.amount_kg_to) {
        this.amount_kg_to_mask = applyMask(this.amount_kg_to, true);
      }

      if (this.amount_kg_from) {
        this.amount_kg_from_mask = applyMask(this.amount_kg_from, true);
      }

      if (o.okpd2Code) this.okpd2Code = o.okpd2Code;
      if (o.purposeCode) this.purposeCode = o.okpd2Code;
    }
  }

  getLotModel(
    lot
  ):
    | LotGpbDataVueModel
    | LotDataVueModel
    | LotElevatorDataVueModel
    | LotDataOgvVueModel
    | LotOgvGpbDataVueModel
    | LotOgvElevatorDataVueModel {
    return new LotDataVueModel(lot);
  }

  getError(): string {
    const errors = this.getErrors();
    return errors.length ? errors[0] : '';
  }
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
      { name: 'amount_kg_from', operator: '>=', key: 'amount_kg', type: 'number' },
      { name: 'amount_kg_to', operator: '<=', key: 'amount_kg', type: 'number' },
      { name: 'current_location_id', type: 'number' },
      { name: 'purposeCode', key: 'objects.purpose.code', type: 'text' },
      { name: 'okpd2Code', key: 'okpd2.code', type: 'text' },
      { name: 'target_id', type: 'number' },
      { name: 'status', type: 'string' },
      {
        name: 'docs_type',
        key: 'docs.type.code',
        operator: '=',
        value: (v) => {
          return typeof v === 'number' ? String(v) : null;
        },
      },
      { name: 'docs_number', key: 'docs.number', operator: '=' },
    ];
  }

  getTypeModel(): string {
    return 'Lot';
  }

  getTnvedFieldName(): string {
    return 'tnved_id';
  }

  getHeaders(): HeaderSdizItem[] {
    return [];
  }

  getNumber(): string | null | number {
    return '-';
  }
  getLotType(): Record<string, boolean> {
    return {};
  }
  getStoreLotType(): string {
    return '';
  }

  getPk(): object {
    return {};
  }

  getLots(): any[] {
    return [];
  }

  getNameNumber(): string {
    return 'lot_number';
  }

  getStatus(): { id: number; name: string; code: string }[] {
    return [
      { id: 1, name: 'Создано', code: 'CREATE' },
      { id: 3, name: 'Подписано', code: 'SUBSCRIBED' },
      { id: 4, name: 'Аннулировано', code: 'CANCELED' },
      { id: 5, name: 'В архиве', code: 'IN_ARCHIVE' },
      { id: 8, name: 'Заблокирована', code: 'BLOCKED' },
    ];
  }

  isNew(): boolean {
    return this.status === 'CREATE' || this.status === 'IMPORTED' || this.status === 'CREATED_IMPORTED';
  }

  isSubscribed(): boolean {
    return this.status === 'SUBSCRIBED';
  }

  setTranslateStatus() {
    switch (this.status) {
      case 'CREATE':
        this.status_translate = 'Создано';
        break;
      case 'IMPORTED':
        this.status_translate = 'На ввоз';
        break;
      case 'SUBSCRIBED':
        this.status_translate = 'Подписано';
        break;
      case 'CANCELED':
        this.status_translate = 'Аннулировано';
        break;
      case 'IN_ARCHIVE':
        this.status_translate = 'В архиве';
        break;
      case 'IN_STORAGE':
        this.status_translate = 'На хранении';
        break;
      case 'CREATED_IMPORTED':
        this.status_translate = 'Создано на ввоз';
        break;
      case 'BLOCKED':
        this.status_translate = 'Заблокирована';
        break;
      default:
        this.status_translate = this.status;
        break;
    }
  }

  getLotNumber(): string {
    return '-';
  }

  getDataForCreate(): any {
    return {};
  }

  getDataForUpdate(): any {
    return {};
  }

  getErrors(typeLot?: string): Array<string> {
    if (typeLot) return [];
    return [];
  }

  checkDate(datestring) {
    if (!datestring) return true;

    const dateTimestamp = moment(datestring, 'DD.MM.YYYY').unix();
    const todayTimestamp = moment().unix();

    return dateTimestamp <= todayTimestamp;
  }

  getConformityDeclarationDateError(e: DocsVueModel): string {
    const timestamp = (dateString: any) => {
      if (!dateString || typeof dateString !== 'string') return null;
      const dateParts = dateString.split('.');
      const dateObject = new Date(+dateParts[2], +dateParts[1] - 1, +dateParts[0]);
      return dateObject.getTime();
    };

    const dateTimestamp = timestamp(e.date);
    const endDateTimestamp = timestamp(e.end_date);

    if (!e.date) return 'Укажите дату декларации соответствия';
    if (!e.end_date) return 'Укажите срок действия декларации соответствия';

    return endDateTimestamp < dateTimestamp ? 'Дата начала действия должна быть меньше даты окончания' : '';
  }

  getConformityDeclarationDateErrors(data: DocsVueModel[]) {
    const errors: string[] = [];

    data.forEach((e) => {
      const error = this.getConformityDeclarationDateError(e);
      if (error) errors.push(error);
    });

    return errors;
  }

  amountKgError = false;

  getTranslateRestriction() {
    switch (this.restriction_type) {
      case RestrictionsEnum.FULL:
        return 'Полный запрет';
      case RestrictionsEnum.PARTIAL:
        return 'Частичный запрет';
      default:
        return '-';
    }
  }

  ammendReason = '';

  selectedHistoryVersionId: number | null = null;

  selectLatestHistoryVersion() {
    this.selectedHistoryVersionId = this.objects.versions.length
      ? this.objects.versions.sort((a, b) => b.id - a.id)[0].id
      : null;
  }

  /** Если есть история версий, то подставить в последнюю запись текущие потребсвойства из партии */
  get historyVersions() {
    if (!this.objects.versions.length) return [];

    const data = this.objects.versions
      .map((e: HistoryEntryModel) => ({
        text: `№${e.version}, Дата: ${e.create_date}`,
        ...e,
      }))
      .sort((a, b) => b.id - a.id);

    data[0].quality_indicators = this.objects.quality_indicators;

    return data;
  }

  get selectedHistoryVersion(): HistoryEntryModel | null {
    if (!this.historyVersions.length || !this.selectedHistoryVersionId) return null;

    const data = this.historyVersions.find((e) => e.id === this.selectedHistoryVersionId) || null;

    if (data !== null && Array.isArray(data?.quality_indicators)) {
      data.quality_indicators = mergeQualityIndicators(data.quality_indicators, this.objects.quality_indicators, [
        'valueTo',
        'valueFrom',
        'type',
        'measure',
      ]);
    }

    return data;
  }

  getDataForVersionCreation(): any {
    return {};
  }

  getDataForVersionPdf(): any {
    return {};
  }

  getVersionCreationErrors(): Array<string> {
    const errors: Array<string> = [];

    if (!this.ammendReason) errors.push('Укажите основание для внесения исправлений');
    if (this.objects.quality_indicators.filter((v) => !v.value && v.value !== 0).length > 0)
      errors.push('Заполните значения потребительских свойств');

    return errors;
  }

  get lotsMoved() {
    return this.objects.lots_moved.map((e: any) => {
      const previous = this.objects.previous_lots_moved.find((n2) => n2.id === e.id);

      if (previous && !e.isAmountKgAvailableProcessed) {
        e.amount_kg_available = add(e.amount_kg_available, previous.value);
        e.isAmountKgAvailableProcessed = true;
      }

      return e;
    });
  }

  set lotsMoved(v) {
    this.objects.lots_moved = v;
  }

  get gpbLotsMoved() {
    return this.objects.gpb_moved.map((e: any) => {
      const previous = this.objects.previous_gpb_moved.find((n2) => n2.id === e.id);

      if (previous && !e.isAmountKgAvailableProcessed) {
        e.amount_kg_available = add(e.amount_kg_available, previous.value);
        e.isAmountKgAvailableProcessed = true;
      }

      return e;
    });
  }

  set gpbLotsMoved(v) {
    this.objects.gpb_moved = v;
  }

  processLotsMoved(data: LotsMovedVueModel[], previousStateField: 'previous_lots_moved' | 'previous_gpb_moved'): any[] {
    return data.map((e: any) => {
      const previous = this.objects[previousStateField].find((n2) => n2.id === e.id);
      e.id = previous && previous.value === e.value ? e.id : null;

      return { id: e.id, lot_id: e.lot_id, gpb_id: e.gpb_id, value: e.value };
    });
  }

  get purposeId(): number | null {
    return this.objects.purpose?.id || null;
  }

  get purposeCode(): LotsPurposeEnum | null {
    return this.objects.purpose?.code ?? null;
  }

  set purposeCode(v) {
    this.objects.purpose.code = v;
  }

  get okpd2Code(): string | null {
    return this.objects.okpd2?.code ?? null;
  }

  set okpd2Code(v) {
    this.objects.okpd2 = { ...this.objects.okpd2, code: v };
  }

  get isImportOrExport() {
    return (
      this.purposeCode &&
      [LotsPurposeEnum.IMPORT_TO_RUSSIA, LotsPurposeEnum.EXPORT_FROM_RUSSIA].includes(this.purposeCode)
    );
  }

  get qualityIndicatorPurposes(): IndicatorPurposeEnum[] {
    return this.isImportOrExport ? [IndicatorPurposeEnum.IMPORT_OR_EXPORT] : [IndicatorPurposeEnum.LOT_CREATION];
  }
}
