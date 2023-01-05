import { Lot } from '@/models/Lot/Extends/Lot.vue';
import { HeaderSdizItem } from '@/models/Common/Default.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';
import { isNull, isNumber } from 'lodash';
import { EAction } from '@/utils';
import { LotType } from '@/utils/enums/LotType';
import { DocsVueModel } from '@/models/Lot/Docs.vue';
import { LotsPurposeEnum } from '@/utils/enums/lotsPurpose.enum';
import { validate } from '@/components/common/inputs/mask/decimalNumberMask';
import omit from 'lodash/omit';

export interface LotGpbDataVueInterface {
  okpd2_id: number | null;
  tnved_id: number | null;
  manufacturer_id: number | null;
  gpb_row_number: number | null; // Порядковый номер партии ( из которой создаем переработку)
  gpb_number: number | null; // Не заполняем
  gpb_sp_number: string | number | null;
  create_date: string | null;
  status_mean: string | null;
  origin_location_id: string | null;
}

export class LotGpbDataVueModel extends Lot implements LotGpbDataVueInterface {
  titleForFirstBlockSdiz = 'Сведения о партии продукта переработки';
  component_name = 'lot_ppz';

  origin_location_id: string | null = null;
  country_destination_id: number | null = null;

  propertyNameForSdiz = 'sdiz_data';
  movedField = 'gpb_moved';
  filter_name = 'Вид продуктов переработки культуры';

  okpd2_id: number | null = null;
  tnved_id: number | null = null;
  manufacturer_id: number | null = null;

  gpb_row_number: number | null = null;
  gpb_number: number | null = null;

  create_date: string | null = null;

  status_mean: string | null = null;
  valid_paper_sdiz_number = true;
  gpb_sp_number: string | number | null = null;
  number_type = 'gpb_number';
  filter_sdiz_lot_number = 'gpb.gpb_number';
  error_find_number_from_reserves = 'Номер партии переботки продукта не найден';
  available_filters: any[] = [
    ...this.getAvailableFilters(),
    { name: 'gpb_number', operator: '%%', type: 'text' },
    { name: 'manufacturer_id', type: 'number' },
    { name: 'create_date', type: 'text' },
  ];
  lot_tables_paper_store_title = 'Предшествующие партии продуктов переработки зерна';
  name_from_another_batch = 'Формирование партии продуктов переработки зерна из других партий';
  name_from_imported = 'Формирование партии продуктов переработки зерна при ввозе';
  name_from_residues = 'Формирование партии продуктов переработки зерна из остатков';
  name_from_sdiz = 'Формирование партии продуктов переработки зерна на основании СДИЗ на бумажном носителе';

  name_route_list = 'lots_gpb_list';
  name_route_detail = 'lots_gpb_detail';
  create_from_another_batch = 'lots_gpb_create_from_another_batch';
  create_from_in_product = 'lots_gpb_create_from_in_product';
  create_from_imported = 'lots_gpb_create_from_imported';
  create_from_residues = 'lots_gpb_create_from_residues';
  create_from_sdiz = 'lots_gpb_create_from_sdiz';
  sdiz_create = 'sdiz_gpb_create';
  export_pdf_service = 'gpb/export';
  export_pdf_service_cancel = 'gpb/export/canceled';
  export_pdf_service_debit = 'gpb/export/debit';
  export_pdf_service_debit_from_description = 'gpb/export/debit/pdf/from/description';
  export_pdf_service_debit_cancel = 'gpb/export/debit/canceled';

  export_pdf_service_ammend_from_description = 'gpb/export/change/pdf/from/description';
  create_ammend_api_endpoint = 'lot/createAmmendGpb';

  subscribe_service = 'gpb/subscribe';
  cancel_service = 'gpb/cancel';
  debit_service = 'lot/debitGpb';
  debit_cancel_service = 'gpb/debit/cancel';

  list_sdiz_apiendpoint = 'sdiz/getListForGpb';
  list_apiendpoit = 'lot/getListGpb';
  create_apiendpoit = 'lot/createGpb';
  show_apiendpoit = 'lot/showGpb';
  update_apiendpoit = 'lot/updateGpb';
  delete_apiendpoit = 'lot/deleteGpb';
  reserve_number_apiendpoit = 'lot/numbersGosGpb';
  lotType: Record<string, boolean> = { is_product: true };
  lotUSePeriod: Record<string, boolean> = { use_lot_period: true, is_product: true };
  storeLotPeriod = 'use_lot_period_product';
  storeLotType = 'is_product';

  register_grain_lot_product_read_privileges = EAction.READ_GRAIN_PRODUCT_LOT_REGISTER;
  filter_register_grain_lot_product_privileges = EAction.FILTER_GRAIN_PRODUCT_LOT_REGISTER;

  view_data_privileges = EAction.READ_GRAIN_PRODUCT_LOT;

  create_product_grain_lot_privileges = EAction.CREATE_PRODUCTION_GRAIN_PRODUCT_LOT;
  create_other_grain_lot_privileges = EAction.CREATE_OTHER_LOT_GRAIN_PRODUCT_LOT;
  create_surples_grain_lot_privileges = EAction.CREATE_SURPLUS_GRAIN_PRODUCT_LOT;
  create_import_grain_lot_privileges = EAction.CREATE_IMPORT_GRAIN_PRODUCT_LOT;
  create_sdiz_grain_lot_privileges = EAction.CREATE_SDIZ_GRAIN_PRODUCT_LOT;

  update_privileges = EAction.UPDATE_GRAIN_PRODUCT_LOT;
  delete_privileges = EAction.DELETE_GRAIN_PRODUCT_LOT;
  cancel_privileges = EAction.CANCEL_GRAIN_PRODUCT_LOT;
  cancel_lot_surples = EAction.CANCEL_GRAIN_PRODUCT_LOT_SURPLUS;

  constructor(o?: any) {
    super(o);
    this.init(o);
  }

  getHeaders(): HeaderSdizItem[] {
    return [
      { text: 'Действия', value: 'actions' },
      { text: 'ID', value: 'id' },
      { text: 'Номер', value: 'gpb_number' },
      { text: 'Дата', value: 'enter_date' },
      { text: 'Владелец партии', value: 'objects.owner.name' },
      { text: 'Масса, кг', value: 'amount_kg_original_mask', notExclude: true, width: '100px', align: 'center' },
      { text: 'Текущая масса, кг', value: 'amount_kg_mask', notExclude: true, width: '100px', align: 'center' },
      { text: 'Вид продуктов переработки', value: 'objects.okpd2.product_name_convert' },
      { text: 'Цель', value: 'objects.target.name' },
      { text: 'Местоположение', value: 'objects.current_location.address' },
      { text: 'Назначение', value: 'objects.purpose.name' },
      { text: 'Статус', value: 'status_translate' },
    ];
  }

  getLotNumber(): string {
    return (this.gpb_number || '№' + this.id).toString();
  }

  getLotType(): Record<string, boolean> {
    return this.type !== null && this.type === 'RESIDUES' ? this.lotUSePeriod : this.lotType;
  }
  getStoreLotType(): string {
    return this.type !== null && this.type === 'RESIDUES' ? this.storeLotPeriod : this.storeLotType;
  }

  getDataForCreate() {
    const data: any = {
      amount_kg: this.amount_kg,
      purpose_id: this.purposeId,
      target_id: this.target_id,
      okpd2_id: this.okpd2_id,
      tnved_id: this.tnved_id,
      current_location_id: this.current_location_id,
      manufacturer_id: this.manufacturer_id,
      origin_location_id: this.origin_location_id,
      country_destination_id:
        this.purposeCode === LotsPurposeEnum.EXPORT_FROM_RUSSIA ? this.country_destination_id : null,
      enter_date: this.enter_date,
      create_date: this.create_date,
      quality_indicators: this.objects.quality_indicators,
      docs: this.objects.docs.map((e) => omit(e, 'type')),
      lots_moved: this.processLotsMoved(this.lotsMoved, 'previous_lots_moved'),
      gpb_moved: this.processLotsMoved(this.gpbLotsMoved, 'previous_gpb_moved'),
    };
    if (this.objects.sdiz_data.items.length > 0) {
      const { lot_sp_number, amount_kg } = this.objects.sdiz_data.items[0];
      data.sdiz_data = { lot_sp_number: lot_sp_number, amount_kg: amount_kg };
    }
    return data;
  }

  getNumber() {
    return this.gpb_number;
  }

  getPk() {
    return { gpb_id: this.id };
  }

  getLots() {
    return this.objects.gpb_moved;
  }

  getNameNumber() {
    return 'gpb_number';
  }

  getTypeModel(): string {
    return 'Gpb';
  }

  getTnvedFieldName(): string {
    return 'tnved_id';
  }

  getDataForUpdate(): any {
    const obj: any = {
      amount_kg: this.amount_kg,
      purpose_id: this.purposeId,
      target_id: this.target_id,
      okpd2_id: this.okpd2_id,
      tnved_id: this.tnved_id,
      current_location_id: this.current_location_id,
      origin_location_id: this.origin_location_id,
      country_destination_id:
        this.purposeCode === LotsPurposeEnum.EXPORT_FROM_RUSSIA ? this.country_destination_id : null,
      enter_date: this.enter_date,
      create_date: this.create_date,
      quality_indicators: this.objects.quality_indicators,
      docs: this.objects.docs.map((e) => omit(e, 'type')),
      lots_moved: this.processLotsMoved(this.lotsMoved, 'previous_lots_moved'),
      gpb_moved: this.processLotsMoved(this.gpbLotsMoved, 'previous_gpb_moved'),
      manufacturer_id: this.manufacturer_id,
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

  getMassErrors() {
    const errors: string[] = [];
    if (Array.isArray(this.objects.lots_moved) && this.objects.lots_moved.length) {
      this.objects.lots_moved.map((lot) => {
        if (!isNumber(lot.value) || isNull(lot.value)) errors.push('Укажите массу для каждой партии зерна');
        if (lot.value <= 0) errors.push('Укажите допустимую массу партии');
        if (validate(lot.value_mask)) errors.push('Граммы должны быть указаны от 001 до 999');
      });
    }

    if (Array.isArray(this.objects.gpb_moved) && this.objects.gpb_moved.length) {
      this.objects.gpb_moved.map((lot) => {
        if (!isNumber(lot.value) || isNull(lot.value))
          errors.push('Укажите массу для каждой партии продуктов переработки');
        if (lot.value <= 0) errors.push('Укажите допустимую массу партии');
        if (validate(lot.value_mask)) errors.push('Граммы должны быть указаны от 001 до 999');
      });
    }

    return errors;
  }

  // eslint-disable-next-line max-lines-per-function
  getErrorBase(): Array<string> {
    const error: Array<string> = [];
    if (!this.enter_date) error.push('Укажите дату');
    if (this.amount_kg === null) error.push('Укажите массу партии');
    if (validate(this.amount_kg_mask)) error.push('Граммы должны быть указаны от 001 до 999');
    if (this.okpd2_id === null) error.push('Укажите вид продуктов переработки');
    if (this.purposeId === null) error.push('Выберите назначение');
    if (
      this.purposeCode &&
      [LotsPurposeEnum.IMPORT_TO_RUSSIA, LotsPurposeEnum.EXPORT_FROM_RUSSIA].includes(this.purposeCode) &&
      this.tnved_id === null
    )
      error.push('Укажите ТН ВЭД');
    if (this.purposeCode === LotsPurposeEnum.EXPORT_FROM_RUSSIA && !this.country_destination_id)
      error.push('Укажите страну назначения');
    if (this.target_id === null) {
      error.push('Выберите цель использования');
    } else {
      if (this.target_id === 1 && this.objects.docs.length > 0) {
        if (this.objects.docs.filter((v) => v.type?.code === '3').length > 0) {
          error.push(
            'Для выбранной цели нельзя добавлять "Ветеринарный сертификат". Измените или удалите документ из таблицы'
          );
        }
      }
    }

    if (this.current_location_id === null) error.push('Выберите местоположение');
    if (this.objects.quality_indicators.filter((v) => !v.value).length > 0)
      error.push('Заполните значения потребительских свойств');
    const conformityDeclarations = this.objects.docs.filter((v) => v.type?.code === '1');
    if (conformityDeclarations.length) {
      error.push(...this.getConformityDeclarationDateErrors(conformityDeclarations));
    }

    if (this.objects.docs.filter((v) => !v.type_id).length > 0) {
      error.push('Укажите вид документа');
    }

    this.objects.docs.forEach((e) => {
      if (!this.checkDate(e.date)) error.push('Дата документа не должна быть больше текущей даты');
    });

    return error;
  }
  getErrorPaperLotSdiz(): Array<string> {
    const errors: Array<string> = [];
    if (!this.manufacturer_id) errors.push('Укажите товаропроизводителя');
    if (!this.valid_paper_sdiz_number) errors.push('Номер партии не найден');
    if (this.objects.sdiz_data.items.length === 0) {
      errors.push('Добавьте партию зерна');
    } else {
      const item = this.objects.sdiz_data.items[0];

      if (!item.lot_sp_number) errors.push('Укажите номер партии');
      if (!item.amount_kg) errors.push('Укажите массу партии');
      if (validate(item.amount_kg_mask)) errors.push('Граммы должны быть указаны от 001 до 999');
    }

    return errors;
  }

  getErrorAnotherBatchGrain(): Array<string> {
    const errors: Array<string> = [];
    if (this.objects.gpb_moved.length === 0) errors.push('Выберите партию');

    return errors;
  }

  getErrorsCreateFromResidues(): Array<string> {
    const errors: Array<string> = [];
    if (!this.create_date) errors.push('Укажите дату изготовления');
    if (!this.manufacturer_id) errors.push('Укажите товаропроизводителя');
    return errors;
  }

  getErrorsImported(): Array<string> {
    const errors: Array<string> = [];

    if (!this.manufacturer_id) errors.push('Укажите товаропроизводителя');

    if (!this.objects.docs.filter((e: DocsVueModel) => e.type_id === 1).length) {
      errors.push('Укажите информацию о декларации соответствия в блоке "Документы"');
    }

    return errors;
  }

  getErrors(typeLot?: LotType): Array<string> {
    const errors: Array<string> = [];
    errors.push(...this.getErrorBase());
    errors.push(...this.getMassErrors());
    if (typeLot === LotType.SDIZ) errors.push(...this.getErrorPaperLotSdiz());
    if (typeLot === LotType.ANOTHER_BATCH_GRAIN) errors.push(...this.getErrorAnotherBatchGrain());
    if (typeLot === LotType.RESIDUES) errors.push(...this.getErrorsCreateFromResidues());
    if (typeLot === LotType.IMPORTED) errors.push(...this.getErrorsImported());
    return errors;
  }

  getLotModel(lot): LotGpbDataVueModel | LotDataVueModel | LotElevatorDataVueModel {
    return new LotGpbDataVueModel(lot);
  }

  getDataForVersionCreation(): any {
    return {
      gpb_id: this.id,
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
