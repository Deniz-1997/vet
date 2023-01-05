import { ISubjectItem, IFilterableList, IDictionaryNode } from './common';

// complaint
/** Элемент списка нотификаций. */
export interface IComplaintItemResponse {
  id: number;
  complaint_message: string;
  complaint_type_id: number;
  attachment_file_id: number;
  complaint_type: IDictionaryNode;
  subject_id: number;
  subject: ISubjectItem;
  created: string;
}

/** Модель ответа /elevator-request/complaint */
export type TComplaintListResponse = IFilterableList<IComplaintItemResponse>;
