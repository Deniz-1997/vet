import { constructByInterface } from '@/utils/construct-by-interface';

export interface QualityIndicatorsVueInterface {
  id: number | null;
  quality_indicator_id: number | null;
  value: number | string | null | undefined;
  name: string;
  measure: string;
  type: string | null;
  valueFrom: number;
  valueTo: number;
  values: [] | undefined;
}

export class QualityIndicatorsVueModel implements QualityIndicatorsVueInterface {
  id: number | null = null;
  quality_indicator_id: number | null = null;
  value: number | string | null | undefined = null;
  name = '';
  measure = '';
  type: string | null = null;
  values: [] | undefined = undefined;
  valueFrom = 0;
  valueTo = 0;

  constructor(o?: any) {
    if (o !== undefined) {
      constructByInterface(o, this, {}, true);
    }
  }
}
