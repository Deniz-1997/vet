import { ISubjectItem } from './common';

export type TRequestsItemResponse = {
  id?: number;
  subject?: ISubjectItem;
  means_of_answering: TMeansOfAnswering;
  created?: string;
  answer_date?: string;
  status?: TStatusItem;
  means_of_receiving?: TMeansOfReceiving;
  reject_reason: any;
  email?: string;
  updated?: string;
  file?: any;
  file_id?: number;
  answer_id: number;
  address?: string;
  body?: string;
};

export type TMeansOfReceiving = {
  code: string;
  description: string;
  free_form_request_means_of_answering_id: number;
  name: string;
};

export type TMeansOfAnswering = {
  code: string;
  description: string;
  free_form_request_means_of_receiving_id: number;
  name: string;
};

export type TStatusItem = {
  code: string;
  description: string;
  free_form_request_status_id: number;
  name: string;
};

export interface IRequestsItemReceiveOut {

}
