import { Service } from '@/plugins/service/utils';
import { AxiosResponse } from 'axios';
import { FilterOut } from './mappers/common';
import { InteractionItem } from './mappers/interaction';
import { IFilterableList, TInnerFilter, TMapperPlain } from './models/common';
import { TInteractionLogRequestItem } from './models/interaction';

export default class Role extends Service {
  /** Получить список импортов. */
  async find(filter: TInnerFilter): Promise<AxiosResponse<TMapperPlain<InteractionItem>[]>> {
    const response = await this.$axios.post<IFilterableList<TInteractionLogRequestItem>>(
      '/api/security/log/interaction/find',
      new FilterOut(filter)
    );
    const list = response.data.content || [];
    return { ...response, data: list.map((data) => new InteractionItem(data).toJSON()) };
  }

  /** Получить информацию об импорте по `id` */
  async findOne(id: number): Promise<AxiosResponse<TMapperPlain<InteractionItem>>> {
    const response = await this.$axios.get<TInteractionLogRequestItem>(`/api/security/log/interaction/${id}`);
    return { ...response, data: new InteractionItem(response.data).toJSON() };
  }
}
