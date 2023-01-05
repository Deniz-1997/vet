import { DataVueModel, StatusEnum } from '@/models/Lot/GpbOut/Data.vue';
import { AddressFiasVueModel } from '@/models/Gosmonitoring/AddressFias.vue';
import { ManufacturerVueModel } from '@/models/Sdiz/Manufacturer.vue';
import { LotsMovedVueModel } from '@/models/Lot/LotsMoved.vue';
import isNumber from 'lodash/isNumber';
import isNull from 'lodash/isNull';
import omit from 'lodash/omit';
import { EAction } from '@/utils';
import { add } from '@/utils/decimals';
import { validate } from '@/components/common/inputs/mask/decimalNumberMask';

export interface GpbOutDataVueInterface {
  id: number | null;
  enter_date: string | null;
  current_location_id: string | null;
  gpbo_number: string | number | null;
  status: StatusEnum | string | null;
  manufacturer_id: string | null;
  current_location: AddressFiasVueModel;
  manufacturer: ManufacturerVueModel;
  lots_moved: Array<LotsMovedVueModel>;
  gpb_moved: Array<LotsMovedVueModel>;
  previousLotsMoved: Array<LotsMovedVueModel>;
  previousGpbLotsMoved: Array<LotsMovedVueModel>;
  owner_id: string | number | null;
  operator_id: string | number | null;
  getDataForUpdate(): any;
  getDataForCreate(): any;
  getErrors(): Array<string>;
  getGpboNumber(): string;
}

export class GpbOutDataVueModel extends DataVueModel implements GpbOutDataVueInterface {
  id: number | null = null;
  current_location_id: string | null = null;
  manufacturer_id: string | null = null;
  current_location: AddressFiasVueModel = new AddressFiasVueModel();
  manufacturer: ManufacturerVueModel = new ManufacturerVueModel();

  gpbo_number: string | number | null = null;
  enter_date: string | null = null;

  lots_moved: Array<LotsMovedVueModel> = [];
  gpb_moved: Array<LotsMovedVueModel> = [];

  previousLotsMoved: Array<LotsMovedVueModel> = [];
  previousGpbLotsMoved: Array<LotsMovedVueModel> = [];

  status: StatusEnum | string | null = null;
  owner_id: string | number | null = null;
  operator_id: string | number | null = null;

  name_route_list = 'gpbo_gpb_out_list';

  read_register_privileges = EAction.READ_GRAIN_PROCESSING_BATCH_OUT_REGISTRY;
  read_privileges = EAction.READ_GRAIN_PROCESSING_BATCH_OUT;
  create_privileges = EAction.CREATE_GRAIN_PROCESSING_BATCH_OUT;
  update_privileges = EAction.UPDATE_GRAIN_PROCESSING_BATCH_OUT;
  delete_privileges = EAction.DELETE_GRAIN_PROCESSING_BATCH_OUT;
  cancel_privileges = EAction.CANCEL_GRAIN_PROCESSING_BATCH_OUT;

  subscribe_service = 'gpbo/subscribe';
  cancel_service = 'gpbo/cancel';

  constructor(o?: GpbOutDataVueModel) {
    super(o);
    this.init(o);
    this.setTranslateStatus();

    this.lots_moved = this.lots_moved.map((e) => new LotsMovedVueModel(e));
    this.gpb_moved = this.gpb_moved.map((e) => new LotsMovedVueModel(e));

    this.previousLotsMoved = this.lots_moved;
    this.previousGpbLotsMoved = this.gpb_moved;
  }
  public getGpboNumber(): string {
    return (' № ' + (this.gpbo_number || this.id)).toString();
  }

  get lotOrGpb(): boolean {
    return !this.lots_moved.length && !this.gpb_moved.length;
  }

  private getData(): object {
    return {
      current_location_id: this.current_location_id,
      enter_date: this.enter_date,
      lots_moved: this.lots_moved.map((e) => omit(e, 'isAmountKgAvailableProcessed')),
      gpb_moved: this.gpb_moved.map((e) => omit(e, 'isAmountKgAvailableProcessed')),
      manufacturer_id: this.manufacturer_id,
    };
  }

  public getDataForUpdate(): any {
    return {
      ...this.getData(),
    };
  }

  public getDataForCreate(): any {
    return {
      owner_id: this.owner_id,
      ...this.getData(),
    };
  }

  private static lot_error(lot) {
    const error: Array<string> = [];
    if (!isNumber(lot.value) || isNull(lot.value)) error.push('Укажите массу для каждой партии зерна');
    if (lot.value <= 0) error.push('Укажите допустимую массу партии');
    if (validate(lot.value_mask)) errors.push('Граммы должны быть указаны от 001 до 999');
    return error;
  }

  private getErrorMovedLotsAndGpbOut(): Array<string> {
    const error: Array<string> = [];
    if (this.lotOrGpb) {
      error.push('Выберите партию');
    }
    if (this.gpb_moved.length) {
      this.gpb_moved.map((lot) => {
        error.push(...GpbOutDataVueModel.lot_error(lot));
      });
    }
    if (this.lots_moved.length) {
      this.lots_moved.map((lot) => {
        error.push(...GpbOutDataVueModel.lot_error(lot));
      });
    }
    return error;
  }
  private getErrorBase(): Array<string> {
    const error: Array<string> = [];
    if (!this.enter_date) error.push('Укажите дату');
    if (!this.manufacturer_id) error.push('Укажите производителя');
    if (this.current_location_id === null) error.push('Выберите местоположение');
    return error;
  }

  public getErrors(): Array<string> {
    const errors: Array<string> = [];
    errors.push(...this.getErrorBase());
    errors.push(...this.getErrorMovedLotsAndGpbOut());
    return errors;
  }

  protected setTranslateStatus() {
    switch (this.status) {
      case StatusEnum.CREATE:
        this.status_translate = 'Проект';
        break;
      case StatusEnum.SUBSCRIBED:
        this.status_translate = 'Подписано';
        break;
      case StatusEnum.CANCELED:
        this.status_translate = 'Аннулировано';
        break;
      default:
        this.status_translate = this.status;
        break;
    }
  }

  get lotsMoved() {
    return this.lots_moved.map((e: any) => {
      const previous = this.previousLotsMoved.find((n2) => n2.id === e.id);

      if (previous && !e.isAmountKgAvailableProcessed) {
        e.amount_kg_available = add(e.amount_kg_available, previous.value);
        e.isAmountKgAvailableProcessed = true;
      }

      return e;
    });
  }

  set lotsMoved(v) {
    this.lots_moved = v;
  }

  get gpbLotsMoved() {
    return this.gpb_moved.map((e: any) => {
      const previous = this.previousGpbLotsMoved.find((n2) => n2.id === e.id);

      if (previous && !e.isAmountKgAvailableProcessed) {
        e.amount_kg_available = e.amount_kg_available + previous.value;
        e.isAmountKgAvailableProcessed = true;
      }

      return e;
    });
  }

  set gpbLotsMoved(v) {
    this.gpb_moved = v;
  }
}
