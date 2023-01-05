import { Service } from '@/plugins/service/utils';
import { AxiosResponse } from 'axios';
import { FilterOut } from './mappers/common';
import { RoleItem } from './mappers/roles';
import { TInnerFilter, IFilterableList } from './models/common';
import { TRoleResponseItem } from './models/roles';

export default class Role extends Service {
  /** Получить список ролей по фильтру. */
  async find(filter: TInnerFilter): Promise<AxiosResponse<RoleItem[]>> {
    const response = await this.$axios.post<IFilterableList<TRoleResponseItem>>(
      '/api/security/role/find',
      new FilterOut(filter)
    );
    const list = response.data.content || [];
    return { ...response, data: list.map((data) => new RoleItem(data)) };
  }

  /** Получить информацию о роли по id. */
  async findOne(id: number): Promise<AxiosResponse<RoleItem>> {
    const response = await this.$axios.get<TRoleResponseItem>(`/api/security/role/${id}`);
    return { ...response, data: new RoleItem(response.data) };
  }
}
