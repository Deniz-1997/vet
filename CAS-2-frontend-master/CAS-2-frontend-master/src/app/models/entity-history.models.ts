import {constructByInterface} from '../utils/construct-by-interface';

export interface EntityHistoryInterface {
  id: number;
  action: string;
  objectId: number;
  data: [];
  diff: any;
  comment: any;
  user: {
    clientId: number | null,
    userFirstname: string | null,
    userId: number | null,
    userPatronymic: string | null,
    userSurname: string | null,
    username: string | null,
  };
}

export class EntityHistoryModel implements EntityHistoryInterface {
  id: number;
  action: any;
  objectId: number;
  data: [];
  diff: any;
  comment: any;
  user: {
    clientId: number | null,
    userFirstname: string | null,
    userId: number | null,
    userPatronymic: string | null,
    userSurname: string | null,
    username: string | null,
  };


  constructor(o?: EntityHistoryInterface) {
    constructByInterface(o, this);
  }
}
