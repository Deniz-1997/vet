import {constructByInterface} from '../../utils/construct-by-interface';

export interface ReferenceNotificationsTypeInterface {
  id: number;
  name: string;
  template: string;
  required: boolean;
}

export class ReferenceNotificationsTypeModel {
  'id': number;
  'name': string;
  'template': string;
  'required': boolean;

  constructor(o?: ReferenceNotificationsTypeInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
