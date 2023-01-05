import { Service } from '@/plugins/service/utils';
import { AxiosResponse } from 'axios';
import { FilterOut } from './mappers/common';
import { DeclarationItem } from './mappers/declaration';
import { IFilterableList, TInnerFilter, TMapperPlain } from './models/common';
import { TDeclarationLogRequestItem } from './models/declaration';

export default class Role extends Service {
  /** Получить список импортов. */
  async find(filter: TInnerFilter): Promise<AxiosResponse<TMapperPlain<DeclarationItem>[]>> {
    const response = await this.$axios.post<IFilterableList<TDeclarationLogRequestItem>>(
      '/api/declaration-info/find',
      new FilterOut(filter)
    );
    const list = response.data.content || [];
    return { ...response, data: list.map((data) => new DeclarationItem(data).toJSON()) };
  }

  /** Получить информацию об импорте по `id` */
  async findOne(id: number): Promise<AxiosResponse<TMapperPlain<DeclarationItem>>> {
    const response = await this.$axios.post<TDeclarationLogRequestItem>('/api/declaration-info/show', { id });
    return { ...response, data: new DeclarationItem(response.data).toJSON() };
  }
}
