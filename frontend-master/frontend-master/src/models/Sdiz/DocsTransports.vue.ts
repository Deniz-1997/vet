import { constructByInterface } from '@/utils/construct-by-interface';
import { currentDay } from '@/utils';
import { DictionaryRecordModel } from '@/models/Common/DictionaryRecord';

export interface DocsTransportsVueInterface {
  date: any;
  type: DictionaryRecordModel | null;
  type_id: number | null;
  number: string | null;
  number_tc: string | null;
}

export class DocsTransportsVueModel implements DocsTransportsVueInterface {
  date: string = currentDay();
  type: DictionaryRecordModel | null = new DictionaryRecordModel();
  type_id: number | null = null;
  number: string | null = null;
  number_tc: string | null = null;

  constructor(o?: DocsTransportsVueInterface) {
    constructByInterface(o, this, { type: DictionaryRecordModel });
  }
}
