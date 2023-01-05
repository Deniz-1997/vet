import { ENotificationStatus, ENotificationObjectType } from '@/services/enums/notification';
import { ISubjectItem, IFilterableList, IDictionaryNode } from './common';

// notifications
/** Элемент списка нотификаций. */
export interface INotificationItemResponse {
  created: string;
  id: number;
  message: string;
  object_id: number;
  status: IDictionaryNode<ENotificationStatus>;
  subject?: ISubjectItem;
  type: IDictionaryNode<ENotificationObjectType>;
  url: string;
}

/** Модель ответа /api/notification/find */
export type TNotificationListResponse = IFilterableList<INotificationItemResponse>;

// violations
/** Элемент списка типов нарушений. */
export type TViolationType = IDictionaryNode<string, { description: string }>;

/** Элемент списка нарушений. GET /api/violation/:id  */
export interface IViolationItemResponse {
  created: string;
  id: number;
  difference: string;
  subject: ISubjectItem;
  violation_type: TViolationType;
}

/** Модель ответа /api/violation/find */
export type TViolationListResponse = IFilterableList<IViolationItemResponse>;
