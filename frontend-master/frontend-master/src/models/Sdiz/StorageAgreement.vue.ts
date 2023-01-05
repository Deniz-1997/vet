import { constructByInterface } from '@/utils/construct-by-interface';
import { DictionaryRecordModel } from '@/models/Common/DictionaryRecord';

export interface StorageAgreementVueInterface {
  date: string;
  cost: number;
  type: DictionaryRecordModel | null;
  area?: number | null;
  place_id: number;
  number: string;
  service: any[];
  time_store: string;
  conditions: string;
  moving_date?: string | undefined;
  moving_place_id?: number | undefined;
  moving_type?: DictionaryRecordModel | null;
  moving_conditions?: string | undefined;
}

export class StorageAgreementVueModel implements StorageAgreementVueInterface {
  date = '';
  cost = 0;
  type: DictionaryRecordModel | null = new DictionaryRecordModel();
  area?: number | null;
  place_id = 0;
  number = '';
  service: any[] = [];
  time_store = '';
  conditions = '';
  moving_date!: string | undefined;
  moving_place_id!: number | undefined;
  moving_type: DictionaryRecordModel | null = new DictionaryRecordModel();
  moving_conditions!: string | undefined;

  constructor(o?: StorageAgreementVueInterface) {
    if (o) {
      constructByInterface(o, this, { type: DictionaryRecordModel, moving_type: DictionaryRecordModel });
      this.service = this.service.map((service) => {
        return {
          id: service.elevator_service_type.id,
          code: service.elevator_service_type.code,
          label: service.elevator_service_type.name,
        };
      });
    }
  }
}
