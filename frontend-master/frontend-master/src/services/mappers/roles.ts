import { Mapper } from '@/utils';
import { TRoleResponseItem } from '@/services/models/roles';

export class RoleItem extends Mapper<TRoleResponseItem> {
  @Mapper.catch()
  get id() {
    return this.get(({ role_id }) => role_id).required.value;
  }

  @Mapper.catch()
  get code() {
    return this.get(({ role }) => role).required.value;
  }

  @Mapper.catch()
  get name() {
    return this.get(({ name, description }) => name || description).required.value;
  }

  @Mapper.catch()
  get description() {
    return this.get(({ description }) => description).required.value;
  }

  @Mapper.catch()
  get authorities() {
    return this.get(({ authority }) => authority || []).required.value.map((_, index) => ({
      id: this.get(({ authority }) => authority?.[index]?.authority_id).required.value,
      code: this.get(({ authority }) => authority?.[index]?.code).required.value,
      name: this.get(({ authority }) => authority?.[index]?.authority_name).required.value,
      description: this.get(({ authority }) => authority?.[index]?.description).required.value,
    }));
  }

  @Mapper.catch()
  get endDate() {
    return this.get(({ deleted_date }) => deleted_date).date().optional.value;
  }

  toJSON() {
    return {
      id: this.id,
      code: this.code,
      name: this.name,
      description: this.description,
      authorities: this.authorities,
      endDate: this.endDate,
    };
  }
}
