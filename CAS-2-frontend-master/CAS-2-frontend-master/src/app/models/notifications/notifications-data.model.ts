import {constructByInterface} from '../../api/api-connector/api-connector.utils';


export interface NotificationDataInterface {
  header: string;
  url: string;
  date: string;
  template: string;
}

export class NotificationDataModel {
  header: string;
  url: string;
  date: string;
  template: string;

  constructor(o?: NotificationDataInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
