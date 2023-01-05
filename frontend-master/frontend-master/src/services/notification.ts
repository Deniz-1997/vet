import { IFilter, TInnerFilter } from './models/common';
import { Service } from '@/plugins/service';
import { AxiosResponse } from 'axios';
import { NotificationItem } from './mappers/notification';
import { ViolationItem } from './mappers/violation';
import { ENotificationObjectType } from './enums/notification';
import { FilterOut } from './mappers/common';

export default class extends Service {
  /** Получить список нотификаций. */
  async find(filter: TInnerFilter): Promise<AxiosResponse<NotificationItem[]>> {
    const response = await this.$axios.post('/api/notification/find', new FilterOut(filter));
    return { ...response, data: (response?.data?.content ?? []).map((item) => new NotificationItem(item).toJSON()) };
  }

  /** Получить количество нотификаций. */
  async getCount() {
    const response = await this.$axios.get<number>('/api/notification/count');
    this.$store.commit('notifications/setCount', response.data);
    return response;
  }

  /** Пометить нотификацию прочитанной. */
  async markAsRead<T>(id: number) {
    const response = await this.$axios.post<T>('/api/notification/markAsRead', { id });
    await this.getCount();
    return response;
  }

  [ENotificationObjectType.VIOLATION] = {
    /** Получить список нарушений. */
    find: async (filter: IFilter = {}): Promise<AxiosResponse<ViolationItem[]>> => {
      const response = await this.$axios.post('/api/violation/find', filter);
      return { ...response, data: response.data.content.map((item) => new ViolationItem(item)) };
    },
    /** Получить нарушение по идентификатору. */
    findOne: async (id: number): Promise<AxiosResponse<ViolationItem>> => {
      const response = await this.$axios.get(`/api/violation/${id}`);
      return { ...response, data: new ViolationItem(response.data) };
    },
  };

  [ENotificationObjectType.EXPORT] = {
    /** Получить список нарушений. */
    find: async (filter: IFilter = {}): Promise<AxiosResponse<ViolationItem[]>> => {
      const response = await this.$axios.post('/api/export/find', filter);
      return { ...response, data: response.data.content.map((item) => new ViolationItem(item)) };
    },
    /** Получить нарушение по идентификатору. */
    findOne: async (id: number): Promise<AxiosResponse<ViolationItem>> => {
      const response = await this.$axios.get(`/api/export/${id}`);
      return { ...response, data: new ViolationItem(response.data) };
    },
  };
}
