import {constructByInterface} from '../../utils/construct-by-interface';

export interface UserScheduleInterface {
  id: number;
  chipNumber: string;
}

export class UserScheduleModel {
  id: number;
  chipNumber: string;
  sterilizationUser: string;

  constructor(o?: UserScheduleInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
