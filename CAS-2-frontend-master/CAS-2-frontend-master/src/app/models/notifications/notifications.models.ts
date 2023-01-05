import {ReferenceNotificationsChannelInterface, ReferenceNotificationsChannelModel} from '../reference/reference.notifications.channel.models';
import {ReferenceNotificationsTypeInterface, ReferenceNotificationsTypeModel} from '../reference/reference.notifications.type.models';
import {NotificationsToSendInterface, NotificationsToSendModel} from './notificationsToSend.models';
import {NotificationDataInterface, NotificationDataModel} from './notifications-data.model';
import {constructByInterface} from '../../api/api-connector/api-connector.utils';

export interface MainNotificationInterface {
  id: number;
  type: ReferenceNotificationsTypeInterface;
  data: NotificationDataInterface;
  toSend: Array<NotificationsToSendInterface>;
}

export class MainNotificationModel implements MainNotificationInterface {
  id: number;
  type: ReferenceNotificationsTypeModel;
  data: NotificationDataModel;
  toSend: Array<NotificationsToSendModel>;

  constructor(o?: MainNotificationInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
