import { constructByInterface } from '@/utils/construct-by-interface';
import { LotsPurposeEnum } from '@/utils/enums/lotsPurpose.enum';

export interface LotPurposeVueInterface {
  id: number;
  code: LotsPurposeEnum;
  name: string;
  start_date: string;
}

export class LotPurposeVueModel implements LotPurposeVueInterface {
  id!: number;
  code!: LotsPurposeEnum;
  name!: string;
  start_date!: string;

  constructor(o?: LotPurposeVueInterface) {
    constructByInterface(o, this);
  }
}
