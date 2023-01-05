import { currentDay, EAction } from '@/utils';
import { isNull, isNumber } from 'lodash';
import { Lot } from '@/models/Lot/Extends/Lot.vue';
import { HeaderSdizItem } from '@/models/Common/Default.vue';
import { dateArray } from '@/utils/date';
import { LotType } from '@/utils/enums/LotType';
import { DocsVueModel } from '@/models/Lot/Docs.vue';
import { LotsPurposeEnum } from '@/utils/enums/lotsPurpose.enum';
import { validate } from '@/components/common/inputs/mask/decimalNumberMask';
import omit from 'lodash/omit';

export interface LotDataInterface {
  okpd2_id: number | null;
  tnved_id: number | null;
  repository_id: number | null;
  research_numbers_government_monitoring_id: number | null;
  country_destination_id: number | null;
  lot_year: string | number | null;
  lot_number: string | number | null;
  origin_location_id: string | null;
}

export class LotDataVueModel extends Lot implements LotDataInterface {
  titleForFirstBlockSdiz = 'Сведения о партии зерна';
  component_name = 'lot';

  origin_location_id: string | null = null;
  okpd2_id: number | null = null;
  tnved_id: number | null = null;
  repository_id: number | null = null;
  country_destination_id: number | null = null;

  research_numbers_government_monitoring_id: number | null = null;
  filter_name = 'Вид с/х культуры';

  enter_date: string | null = null;

  valid_paper_sdiz_number = true;

  lot_year: string | number | null = null;

  lot_number: string | number | null = null;
  number_type = 'lot_number';
  filter_sdiz_lot_number = 'lot_number';
  laboratory_monitor_number: string | null = null;
  error_find_number_from_reserves = 'Номер партии не найден';
  available_filters: any[] = [
    ...this.getAvailableFilters(),
    { name: 'lot_number', operator: '%%' },
    { name: 'laboratory_monitor_number', key: 'laboratory_monitor.laboratory_monitor_number', operator: '%%' },
  ];
  lot_tables_paper_store_title = 'Предшествующие партии зерна';
  name_from_another_batch = 'Формирование партии  зерна из других партий';
  name_from_imported = 'Формирование партии  зерна  при ввозе';
  name_from_residues = 'Формирование партии  зерна из остатков';
  name_from_sdiz = 'Формирование партии   на основании СДИЗ на бумажном носителе';

  name_route_list = 'lot_list';
  name_route_detail = 'lot_detail';
  name_route_create = 'lot_detail';

  create_from_another_batch = 'lot_create_from_another_batch';
  create_from_field = 'lot_create_from_field';
  create_from_imported = 'lot_create_from_imported';
  create_from_residues = 'lot_create_from_residues';
  create_from_sdiz = 'lot_create_from_sdiz';
  sdiz_create = 'sdiz_create';
  reserve_number_apiendpoit = 'lot/numbersGos';
  export_pdf_service = 'lot/export';
  export_pdf_service_cancel = 'lot/export/canceled';
  export_pdf_service_debit = 'lot/export/debit';
  export_pdf_service_debit_from_description = 'lot/export/debit/pdf/from/description';
  export_pdf_service_debit_cancel = 'lot/export/debit/canceled';

  export_pdf_service_ammend_from_description = 'lot/export/change/pdf/from/description';
  create_ammend_api_endpoint = 'lot/createAmmend';

  list_sdiz_apiendpoint = 'sdiz/getList';
  list_apiendpoit = 'lot/getList';
  create_apiendpoit = 'lot/create';
  show_apiendpoit = 'lot/show';
  update_apiendpoit = 'lot/update';
  delete_apiendpoit = 'lot/delete';
  subscribe_service = 'lot/subscribe';
  cancel_service = 'lot/cancel';
  debit_service = 'lot/debit';
  debit_cancel_service = 'lot/debit/cancel';

  lotType: Record<string, boolean> = { is_grain: true };
  lotUSePeriod: Record<string, boolean> = { use_lot_period: true, is_grain: true };
  storeLotPeriod = 'use_lot_period_grain';
  storeLotType = 'is_grain';

  propertyNameForSdiz = 'sdiz_data';
  movedField = 'lots_moved';
  dateArray: Array<object> = dateArray();

  register_grain_lot_product_read_privileges = EAction.READ_GRAIN_LOT_REGISTER;
  filter_register_grain_lot_product_privileges = EAction.FILTER_GRAIN_LOT_REGISTER;

  view_data_privileges = EAction.READ_GRAIN_LOT_DATA;

  create_gosmonitoring_grain_lot_privileges = EAction.CREATE_GOSMONITORING_GRAIN_LOT;
  create_other_grain_lot_privileges = EAction.CREATE_OTHER_LOTS_GRAIN_LOT;
  create_surples_grain_lot_privileges = EAction.CREATE_SURPLUS_GRAIN_LOT;
  create_import_grain_lot_privileges = EAction.CREATE_IMPORT_GRAIN_LOT;
  create_sdiz_grain_lot_privileges = EAction.CREATE_SDIZ_GRAIN_LOT;

  update_privileges = EAction.UPDATE_GRAIN_LOT;
  delete_privileges = EAction.DELETE_GRAIN_LOT;
  cancel_privileges = EAction.CANCEL_GRAIN_LOT;
  cancel_lot_surples = EAction.CANCEL_GRAIN_LOT_SURPLUS;

  constructor(o?: any) {
    super(o);
    this.init(o);
  }

  getHeaders(): HeaderSdizItem[] {
    return [
      { text: 'Действия', value: 'actions' },
      { text: 'ID', value: 'id' },
      { text: 'Номер', value: 'lot_number' },
      { text: 'Дата', value: 'enter_date' },
      { text: 'Владелец', value: 'objects.owner.name' },
      { text: 'На хранении', value: 'objects.repository.name' },
      { text: 'Масса, кг', value: 'amount_kg_original_mask', notExclude: true, width: '100px', align: 'center' },
      { text: 'Текущая масса, кг', value: 'amount_kg_mask', notExclude: true, width: '100px', align: 'center' },
      { text: 'Вид с/х культуры', value: 'objects.okpd2.product_name_convert' },
      { text: 'Цель', value: 'objects.target.name' },
      { text: 'Местоположение', value: 'objects.current_location.address' },
      { text: 'Назначение', value: 'objects.purpose.name' },
      {
        text: 'Номер документа государственного мониторинга',
        value: 'objects.laboratory_monitor.laboratory_monitor_number',
      },
      { text: 'Статус', value: 'status_translate' },
    ];
  }

  getLotNumber(): string {
    return (this.lot_number || '№' + this.id).toString();
  }
  getLotType(): Record<string, boolean> {
    return this.type !== null && this.type === 'RESIDUES' ? this.lotUSePeriod : this.lotType;
  }
  getStoreLotType(): string {
    return this.type !== null && this.type === 'RESIDUES' ? this.storeLotPeriod : this.storeLotType;
  }

  getDataForCreate(): any {
    const data: any = {
      amount_kg: this.amount_kg,
      purpose_id: this.purposeId,
      okpd2_id: this.okpd2_id,
      tnved_id: this.tnved_id,
      target_id: this.target_id,
      repository_id: this.repository_id,
      current_location_id: this.current_location_id,
      origin_location_id: this.origin_location_id,
      country_destination_id:
        this.purposeCode === LotsPurposeEnum.EXPORT_FROM_RUSSIA ? this.country_destination_id : null,
      owner_id: this.owner_id,
      lot_year: this.lot_year,
      research_numbers_government_monitoring_id: this.research_numbers_government_monitoring_id ?? undefined,
      enter_date: this.enter_date !== null ? this.enter_date : currentDay(),
      quality_indicators: this.objects.quality_indicators,
      docs: this.objects.docs.map((e) => omit(e, 'type')),
      lots_moved: this.lotsMoved.map((v: any) => {
        const value: number = v.value === null ? 0 : parseFloat(v.value);
        const lot_id: number = v.lot_id === null || v.lot_id === undefined ? v.id : v.lot_id;
        return { lot_id: lot_id, value: value };
      }),
    };

    if (this.objects.sdiz_data.items.length > 0) {
      const { lot_sp_number, amount_kg } = this.objects.sdiz_data.items[0];

      data.sdiz_data = {
        lot_sp_number: lot_sp_number,
        amount_kg: amount_kg,
      };
    }

    return data;
  }

  getDataForUpdate(): any {
    const obj: any = {
      amount_kg: this.amount_kg,
      purpose_id: this.purposeId,
      okpd2_id: this.okpd2_id,
      tnved_id: this.tnved_id,
      target_id: this.target_id,
      lot_year: this.lot_year,
      owner_id: this.owner_id,
      repository_id: this.repository_id,
      current_location_id: this.current_location_id,
      origin_location_id: this.origin_location_id,
      country_destination_id:
        this.purposeCode === LotsPurposeEnum.EXPORT_FROM_RUSSIA ? this.country_destination_id : null,
      research_numbers_government_monitoring_id: this.research_numbers_government_monitoring_id ?? undefined,
      enter_date: this.enter_date,
      quality_indicators: this.objects.quality_indicators,
      docs: this.objects.docs.map((e) => omit(e, 'type')),
      lots_moved: this.processLotsMoved(this.lotsMoved, 'previous_lots_moved'),
    };

    if (this.objects.sdiz_data.items.length > 0) {
      const { lot_sp_number, amount_kg } = this.objects.sdiz_data.items[0];

      obj.sdiz_data = {
        lot_sp_number: lot_sp_number,
        amount_kg: amount_kg,
      };
    }

    return obj;
  }
  getErrorAnotherBatchGrain(): Array<string> {
    const error: Array<string> = [];
    if (this.objects.lots_moved.length === 0) {
      error.push('Выберите партию');
    }
    if (this.purposeCode === LotsPurposeEnum.IMPORT_TO_RUSSIA) {
      error.push(
        'Запрещено формирование партии с назначением "Ввоз на территорию РФ". Используйте функцию формирование партии при ввозе.'
      );
    }

    return error;
  }

  getErrorPaperLotSdiz(): Array<string> {
    const error: Array<string> = [];
    if (this.objects.sdiz_data.items.length === 0) {
      error.push('Добавьте партию зерна');
    } else {
      const item = this.objects.sdiz_data.items[0];

      if (!item.lot_sp_number) error.push('Укажите номер партии');
      if (!item.amount_kg) error.push('Укажите массу партии');
      if (validate(item.amount_kg_mask)) error.push('Граммы должны быть указаны от 001 до 999');
    }

    if (!this.valid_paper_sdiz_number) error.push('Номер партии не найден');
    return error;
  }

  getBaseError(): Array<string> {
    const error: Array<string> = [];
    if (!this.enter_date) error.push('Укажите дату');
    if (!this.lot_year) error.push('Укажите год урожая');
    if (this.amount_kg === null) error.push('Укажите массу партии');
    if (validate(this.amount_kg_mask)) error.push('Граммы должны быть указаны от 001 до 999');
    if (this.okpd2_id === null) error.push('Выберите C/Х культуру');
    if (
      this.purposeCode &&
      [LotsPurposeEnum.IMPORT_TO_RUSSIA, LotsPurposeEnum.EXPORT_FROM_RUSSIA].includes(this.purposeCode) &&
      this.tnved_id === null
    )
      error.push('Укажите ТН ВЭД');
    if (this.purposeId === null) error.push('Выберите назначение');
    if (this.current_location_id === null) error.push('Выберите местоположение');
    if (this.purposeCode === LotsPurposeEnum.EXPORT_FROM_RUSSIA && !this.country_destination_id)
      error.push('Укажите страну назначения');
    if (this.objects.quality_indicators.filter((v) => !v.value && v.value !== 0).length > 0)
      error.push('Заполните значения потребительских свойств');
    if (this.target_id === null) {
      error.push('Выберите цель использования');
    } else {
      if (this.target_id === 1 && this.objects.docs.length > 0) {
        if (this.objects.docs.filter((v) => v.type?.code === '3').length > 0)
          error.push(
            'Для выбранной цели нельзя добавлять "Ветеринарный сертификат". Измените или удалите документ из таблицы'
          );
      }

      const conformityDeclarations = this.objects.docs.filter((v) => v.type?.code === '1');

      if (conformityDeclarations.length) {
        error.push(...this.getConformityDeclarationDateErrors(conformityDeclarations));
      }
    }

    if (this.objects.docs.filter((v) => !v.type_id).length > 0) {
      error.push('Укажите вид документа');
    }

    this.objects.docs.forEach((e) => {
      if (!this.checkDate(e.date)) error.push('Дата документа не должна быть больше текущей даты');
    });

    this.objects.lots_moved.map((lot) => {
      if (!isNumber(lot.value) || isNull(lot.value)) error.push('Укажите массу для каждой партии зерна');
      if (lot.value <= 0) error.push('Укажите допустимую массу партии');
      if (validate(lot.value_mask)) error.push('Граммы должны быть указаны от 001 до 999');
    });
    return error;
  }

  getFieldErrors() {
    const errors: string[] = [];
    if (this.research_numbers_government_monitoring_id === null)
      errors.push('Укажите номер документа о результатах госмониторинга');
    if (this.amountKgError) errors.push('Укажите допустимую массу партии');
    return errors;
  }

  getErrorsImported() {
    const errors: string[] = [];

    if (!this.objects.docs.filter((e: DocsVueModel) => e.type_id === 1).length) {
      errors.push('Укажите информацию о декларации соответствия в блоке "Документы"');
    }

    return errors;
  }

  getErrors(typeLot?: LotType): Array<string> {
    const errors: Array<string> = [];
    if (typeLot === LotType.ANOTHER_BATCH_GRAIN) errors.push(...this.getErrorAnotherBatchGrain());
    if (typeLot === LotType.SDIZ) errors.push(...this.getErrorPaperLotSdiz());
    if (typeLot === LotType.FIELD) errors.push(...this.getFieldErrors());
    if (typeLot === LotType.IMPORTED) errors.push(...this.getErrorsImported());
    errors.push(...this.getBaseError());
    return errors;
  }

  getPk() {
    return { lot_id: this.id };
  }

  getNumber() {
    return this.lot_number;
  }
  getLots() {
    return this.objects.lots_moved;
  }

  getDataForVersionCreation(): any {
    return {
      lot_id: this.id,
      quality_indicators: this.objects.quality_indicators,
      reason: this.ammendReason,
    };
  }

  getDataForVersionPdf(): any {
    return {
      id: this.id,
      quality_indicators: this.objects.quality_indicators,
      reason: this.ammendReason,
    };
  }
}
