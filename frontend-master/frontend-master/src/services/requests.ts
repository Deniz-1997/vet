import { Service } from '@/plugins/service/utils';
import { AxiosResponse } from 'axios';
import { FilterOut } from './mappers/common';
import {
  RequestsAnswerOut, RequestsItem, RequestsReceiveOut,
  RequestsToRevision, requestsToPostpone
} from './mappers/requests';
import { TInnerFilter, TMapperPlain } from './models/common';
// import { TRequestsItemResponse } from './models/requests';

export default class Requests extends Service {
  /** Получить список запросов. */
  async find(filter: TInnerFilter): Promise<AxiosResponse<TMapperPlain<RequestsItem>[]>> {
    // Рабозбраться с типизацией
    const response = await this.$axios.post<any>('/api/elevator-request/free/form/request/find', new FilterOut(filter));
    const list = response.data.content || [];
    return { ...response, data: list.map((data) => new RequestsItem(data).toJSON()) };
  }

  async updload(file) {
    const content = new FormData();
    content.append('file', file);
    const data = await this.$axios.post('api/elevator-request/file/upload', content);
    return data;
  }

  /** Получить информацию о запросе по `id` */
  async findOne(id: number): Promise<AxiosResponse<TMapperPlain<any>>> {
    const response = await this.$axios.get<any>(`/api/elevator-request/free/form/request/${id}`);
    return { ...response, data: new RequestsItem(response.data).toJSON() };
  }

  /** Получить информацию о запросе по `id` */
  async create(data): Promise<AxiosResponse<TMapperPlain<any>>> {
    const preview: any = new RequestsReceiveOut(data).toJSON();
    return await this.$axios.post('/api/elevator-request/free/form/request/create', preview);
  }

  /** Получить информацию о запросе по `id` */
  async update(data): Promise<AxiosResponse<TMapperPlain<any>>> {
    const preview: any = new RequestsAnswerOut(data).toJSON();
    const res = await this.$axios.post('/api/elevator-request/free/form/request/answer/create', preview, {
      responseType: 'blob',
    });
    return res;
  }

  /** Отправить на доработку */
  async toRevision(data): Promise<AxiosResponse<TMapperPlain<any>>> {
    const preview: any = new RequestsToRevision(data).toJSON();
    const res = await this.$axios.post('/api/elevator-request/free/form/request/answer/create', preview, {
      responseType: 'blob',
    });
    return res;
  }

  /** Отложить */
  async toPostpone(id): Promise<AxiosResponse<any>> {
    const preview: any = requestsToPostpone(id);
    const res = await this.$axios.post('/api/elevator-request/free/form/request/postponed', preview);
    return res;
  }

  async inWork(id): Promise<AxiosResponse<any>> {
    const response = await this.$axios.post('/api/elevator-request/free/form/request/processing', { id: id });
    return { ...response, data: new RequestsItem(response.data).toJSON() };
  }

  async findAnswerList(): Promise<AxiosResponse<any>> {
    const response = await this.$axios.get<any>('/api/elevator-request/free/form/request/means-of-answering');
    return response.data;
  }

  async findReceivingList(): Promise<AxiosResponse<any>> {
    const response = await this.$axios.get<any>('/api/elevator-request/free/form/request/means-of-receiving');
    return response.data;
  }

  async findRejectReasonList(): Promise<AxiosResponse<any>> {
    const response = await this.$axios.get<any>('/api/elevator-request/free/form/request/reject-reason');
    return response.data;
  }
}
