import { Service } from '@/plugins/service/utils';
import { AxiosResponse } from 'axios';
import { StateAuthorityItemIn, StateAuthorityItemOut, StateAuthorityFilterOut } from './mappers/stateAuthority';
import { TMapperPlain } from './models/common';
import { IStateAuthorityItemResponse, TStateAuthorityInnerFilter } from './models/stateAuthority';

export default class StateAuthority extends Service {
  /** Получить список ОГВ. */
  async find(filter: TStateAuthorityInnerFilter): Promise<AxiosResponse<IStateAuthorityItemResponse[]>> {
    const response = await this.$axios.post('/api/subject/ogv/find', new StateAuthorityFilterOut(filter));
    return {
      ...response,
      data: response.data.content.map((item) => new StateAuthorityItemIn(item).toJSON()),
    };
  }

  /** Получить данные по ОГВ по его id. */
  async findOne(id: number): Promise<AxiosResponse<TMapperPlain<StateAuthorityItemIn>>> {
    const response = await this.$axios.get(`/api/subject/ogv/${id}`);
    return { ...response, data: new StateAuthorityItemIn(response.data).toJSON() };
  }

  /** Добавление новой ОГВ. */
  async create(data: any) {
    const preview = new StateAuthorityItemOut(data).toJSON();
    return await this.$axios.post('/api/subject/ogv/create', preview);
  }

  /** Редактирование существующей ОГВ. */
  async update(data: any) {
    const preview = new StateAuthorityItemOut(data).toJSON();
    return await this.$axios.post('/api/subject/ogv/update', preview);
  }
}
