import { Service } from '@/plugins/service/utils';
import { AxiosResponse } from 'axios';
import { FilterOut } from './mappers/common';
import { ComplaintItem } from './mappers/complaint';
import { IFilterableList, TInnerFilter, TMapperPlain } from './models/common';
import { IComplaintItemResponse } from './models/complaint';

export default class Role extends Service {
  /** Получить список жалоб. */
  async find(filter: TInnerFilter): Promise<AxiosResponse<TMapperPlain<ComplaintItem>[]>> {
    const response = await this.$axios.post<IFilterableList<IComplaintItemResponse>>(
      '/api/elevator-request/complaint',
      new FilterOut(filter)
    );
    const list = response.data.content || [];
    return { ...response, data: list.map((data) => new ComplaintItem(data).toJSON()) };
  }

  /** Получить информацию о жалобе `id` */
  async findOne(id: number): Promise<AxiosResponse<TMapperPlain<ComplaintItem>>> {
    const response = await this.$axios.get<IComplaintItemResponse>(`/api/elevator-request/complaint/${id}`);
    return { ...response, data: new ComplaintItem(response.data).toJSON() };
  }
}
