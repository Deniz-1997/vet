import { constructByInterface } from '@/utils/construct-by-interface';
import { QualityIndicatorsVueModel } from '@/models/Lot/QualityIndicators.vue';

export interface HistoryEntryInterface {
  id: number;
  version: number;
  create_date: string;
  reason: string;
  esp_id: number | null;
  quality_indicators: QualityIndicatorsVueModel[];
}

export class HistoryEntryModel {
  id!: number;
  version!: number;
  create_date!: string;
  reason = '-';
  esp_id: number | null = null;
  quality_indicators: QualityIndicatorsVueModel[] = [];

  constructor(o?: HistoryEntryInterface) {
    constructByInterface(o, this, { quality_indicators: QualityIndicatorsVueModel });
  }
}
