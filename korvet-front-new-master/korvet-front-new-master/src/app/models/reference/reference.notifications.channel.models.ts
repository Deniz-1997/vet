import {constructByInterface} from '../../utils/construct-by-interface';

export interface ReferenceNotificationsChannelInterface {
  id: number;
  name: string;
}

export class ReferenceNotificationsChannelModel {
  'id': number;
  'name': string;

  constructor(o?: ReferenceNotificationsChannelInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
