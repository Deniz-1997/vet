import { ObjectsVueModel } from '@/models/Sdiz/Objects';
import { SdizTypesInterface } from '@/models/Sdiz/interfaces/SdizTypes.interface';
import { SdizAuthorizedPersonsInterface } from '@/models/Sdiz/interfaces/SdizAuthorizedPersons.interface';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import { HeaderSdizItem } from '@/models/Common/Default.vue';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';
import { LotOgvGpbDataVueModel } from '@/models/Lot/Ogv/LotOgvGpbData.vue';
import { LotDataOgvVueModel } from '@/models/Lot/Ogv/LotDataOgv.vue';
import { LotOgvElevatorDataVueModel } from '@/models/Lot/Ogv/LotOgvElevatorData.vue';
import { SdizCarrierInterface } from '@/models/Sdiz/SdizCarrier';

export interface SdizInterface {
  id: number | null;
  consignee_location: any;
  shipper_location: any;
  eisz_contract_number: string | number | null;
  eisz_contract_date: string | null;
  laboratory_id: number | null;

  eisz_number: string | null;
  eisz_number_checkbox_init: boolean | null;
  elevator_creator: boolean;

  moving_lot_checkbox_init: boolean | null;

  contract_number: string | null;
  authorized_person: string | null;
  sdiz_gpb_number: string | null;

  sdiz_type: number | null;
  status_id: number | null;
  prototype_sdiz: number | null;

  consignee_id: number | null;
  shipper_id: number | null;
  seller_id: number | null;
  buyer_id: number | null;

  ved_con_number: number | string | null;
  ved_dop_number: number | string | null;
  ved_con_date: string | null;
  ved_dop_date: string | null;

  consignee_repository_id: number | null;
  shipper_repository_id: number | null;

  consignee_location_id: number | null;
  shipper_location_id: number | null;

  contract_date: string | null;
  enter_date: string | null;
  gka_date: string | null;

  gka_number: string | null;

  repository_contract: string | null;

  carriers: SdizCarrierInterface[];
  objects: ObjectsVueModel;
  sdiz_types: SdizTypesInterface[];
  authorized_persons: SdizAuthorizedPersonsInterface[];

  extinguish_api_endpoint: string | null;
  link_find_items_in_modal: string | null;

  excludedKeyInData: string[];

  getObjectLot():
    | LotGpbDataVueModel
    | LotDataVueModel
    | LotElevatorDataVueModel
    | LotOgvGpbDataVueModel
    | LotDataOgvVueModel
    | LotOgvElevatorDataVueModel;

  getHeaders(): HeaderSdizItem[];

  getErrors(): Array<string>;

  getData(): object;

  getError(): string;

  getDataForCreate(): object;

  getDataForUpdate(): object;
}
