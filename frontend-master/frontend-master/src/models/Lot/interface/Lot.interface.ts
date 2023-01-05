import { ObjectsLotVueModel } from '@/models/Lot/Objects';
import { FiltersVueInterface } from '@/models/Common/Filters.vue';
import { DefaultVueInterface, HeaderSdizItem } from '@/models/Common/Default.vue';

export interface LotInterface extends FiltersVueInterface, DefaultVueInterface {
  amount_kg: number | null;
  amount_kg_available: number | null;
  amount_kg_available_mask: string | null;
  amount_kg_original: number | null;
  purpose_id: number | null;
  target_id: number | null;
  esp_id: number | null;
  date_enter: string | null;
  enter_date: string | null;
  status: string | null;
  status_translate: string | null;
  original_data: any;
  objects: ObjectsLotVueModel;
  type: string | null;
  docs_type: string | null;
  docs_number: string | null;
  debit_cancel_service: string;
  propertyNameForSdiz: string;
  movedField: string;
  origin_location: object | null;

  getHeaders(): HeaderSdizItem[];

  getErrors(): Array<string>;

  getError(): string;

  getDataForCreate(): object;

  getDataForUpdate(): object;

  getNumber(): string | null | number;

  getPk(): object;

  getLots(): any[];

  getNameNumber(): string;

  getStatus(): { id: number; name: string; code: string }[];
}
