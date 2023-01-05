import { constructByInterface } from '@/utils/construct-by-interface';
import moment from 'moment';
import { DocsInterface } from '@/models/Sdiz/Docs';
import { DictionaryRecordModel } from '@/models/Common/DictionaryRecord';

export interface DocsAktVueInterface extends DocsInterface {
  number: string | null;
  type: DictionaryRecordModel | null;
  type_id: number | null;
  date: moment.Moment | Date | string | number | (number | string)[] | moment.MomentInputObject | null;
}

export class DocsAktVueModel implements DocsAktVueInterface {
  type: DictionaryRecordModel | null = new DictionaryRecordModel();
  date: moment.Moment | Date | string | number | (number | string)[] | moment.MomentInputObject | null = null;
  number = '';
  type_id: number | null = null;

  constructor(o?: DocsAktVueInterface) {
    constructByInterface(o, this, { type: DictionaryRecordModel });
  }
}
