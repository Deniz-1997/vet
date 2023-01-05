import { Service } from '@/plugins/service';
import { FilterOut } from './mappers/common';
import { UserItem, UserRequestOut } from './mappers/user';
import { TInnerFilter, TMapperPlain } from './models/common';

export default class User extends Service {
  async find(filter: TInnerFilter) {
    const response = await this.$axios.post('/api/security/user/find/', new FilterOut(filter));

    return { ...response, data: response.data?.content || [] };
  }

  async findOne(id?: number) {
    const response = id
      ? await this.$axios.post('/api/security/user/show', { id })
      : await this.$axios.get('/api/security/user/currentUser');

    return new UserItem(response.data).toJSON();
  }

  update(form: TMapperPlain<UserItem>) {
    return this.$axios.post('/api/security/user/update', new UserRequestOut(form));
  }
}
