import { constructByInterface } from '@/utils/construct-by-interface';
import { DictionaryRecordModel } from '@/models/Common/DictionaryRecord';

export interface DocsVueInterface {
  id: number;
  date: string;
  end_date: string | null;
  number: string;
  type_id: number | null;
  type: DictionaryRecordModel | null;
}

export class DocsVueModel implements DocsVueInterface {
  id!: number;
  date!: string;
  end_date: string | null = null;
  number!: string;
  type_id!: number | null;
  type: DictionaryRecordModel | null = new DictionaryRecordModel();

  constructor(o?) {
    if (o !== undefined) {
      constructByInterface(o, this, { type: DictionaryRecordModel });
    }
  }
}
