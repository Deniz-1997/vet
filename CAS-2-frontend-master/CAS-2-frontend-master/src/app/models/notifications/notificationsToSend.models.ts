import { constructByInterface } from '../../utils/construct-by-interface';
import { ReferenceNotificationsChannelInterface, ReferenceNotificationsChannelModel } from '../reference/reference.notifications.channel.models';

export interface NotificationsToSendInterface {
  id: number;
  type: string;
  value: number;
  channel: ReferenceNotificationsChannelInterface;
  created_at?: string;
  sended_at?: string;
  viewed: boolean;
  opened: boolean;
}

export class NotificationsToSendModel implements NotificationsToSendInterface {
  id: number;
  type: string;
  value: number;
  channel: ReferenceNotificationsChannelModel;
  created_at?: string;
  sended_at?: string;
  viewed: boolean;
  opened: boolean;

  constructor(o?: NotificationsToSendInterface) {
    constructByInterface(o, this);
  }
}
