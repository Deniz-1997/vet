import {constructByInterface} from '../../utils/construct-by-interface';

export interface SupervisoryAuthorityInterface {
  id: string;
  name: string;
  okpo: string;
  okved: string;
  okato: string;
  okogu: string;
  okopf: string;
  okfs: string;
  deactivated: boolean;
  notificationId: number;
}

export class SupervisoryAuthorityModel implements SupervisoryAuthorityInterface {
  id: string;
  name: string;
  okpo: string;
  okved: string;
  okato: string;
  okogu: string;
  okopf: string;
  okfs: string;
  deactivated: boolean;
  notificationId: number;

  constructor(o?: SupervisoryAuthorityInterface) {
    constructByInterface(o, this);
  }
}
