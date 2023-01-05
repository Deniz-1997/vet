import { constructByInterface } from '@/utils/construct-by-interface';

export interface DictionaryRecordInterface {
  id: number;
  code: string;
  name: string;
}

export class DictionaryRecordModel implements DictionaryRecordInterface {
  id!: number;
  code!: string;
  name!: string;

  constructor(o?: DictionaryRecordInterface) {
    constructByInterface(o, this);
  }
}
