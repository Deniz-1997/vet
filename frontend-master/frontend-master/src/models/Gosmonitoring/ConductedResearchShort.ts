import { constructByInterface } from '@/utils/construct-by-interface';
import { QualityIndicatorsVueModel } from '@/models/Lot/QualityIndicators.vue';
import { LotTargetVueModel } from '@/models/Lot/LotTarget.vue';
import { AddressFiasVueModel } from '@/models/Gosmonitoring/AddressFias.vue';
import { ManufacturerVueModel } from '@/models/Sdiz/Manufacturer.vue';
import { Okpd2VueModel } from '@/models/Sdiz/Okpd2.vue';

export interface ConductedResearchShortInterface {
  address: Array<any>;
  laboratory_monitor_id: number | null;
  laboratory_monitor_number: string | null;
  research_number: string | number | null;
  date_check: string;
  place_of_checking: AddressFiasVueModel;
  place_of_checking_id: number | null;
  date_of_akt_check: string;
  number_check: string | null;
  number_of_akt_check: string | null;
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
  owner: ManufacturerVueModel;
  date_of_protocol_check_from: string | null;
  date_of_protocol_check_to: string | null;
  is_subscribed: boolean;
}

export class ConductedResearchShortModel implements ConductedResearchShortInterface {
  address: Array<any> = [];
  checkbox_status = false;
  date_check_from: string | null = null;
  date_check_to: string | null = null;
  date_of_protocol_check_from: string | null = null;
  date_of_protocol_check_to: string | null = null;
  id: number | null = null;
  laboratory_monitor_id: number | null = null;
  laboratory_monitor_number: string | null = null;
  research_number: string | number | null = null;
  place_of_checking: AddressFiasVueModel = new AddressFiasVueModel();
  place_of_checking_id: number | null = null;
  okpd2_id: number | null = null;
  okpd2: Okpd2VueModel = new Okpd2VueModel();
  lot_target: LotTargetVueModel = new LotTargetVueModel();
  target_id: number | null = null;
  status_id: number | null = null;
  number_check: string | null = null;
  number_of_akt_check: string | null = null;
  number_grain_samples: string | null = null;
  number_of_protocol_check: string | null = null;
  amount_kg: number | null = 0;
  amount_kg_available: number | null = 0;
  amount_kg_original: number | null = 0;
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
  quality_indicators: QualityIndicatorsVueModel[] = [];
  is_subscribed = false;

  constructor(o?: ConductedResearchShortModel) {
    if (o) {
      constructByInterface(o, this, {
        place_of_checking: AddressFiasVueModel,
        okpd2: Okpd2VueModel,
        lot_target: LotTargetVueModel,
        quality_indicators: QualityIndicatorsVueModel,
        owner: ManufacturerVueModel,
      });
    }
  }
}
