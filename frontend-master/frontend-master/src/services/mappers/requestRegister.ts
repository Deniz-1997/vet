import { Mapper } from '@/utils';
import { TRequestRegisterItemResponse } from '@/services/models/requests';

export class RequestRegisterItem extends Mapper<TRequestRegisterItemResponse> {
  @Mapper.catch()
  get id() {
    return this.get(({ id }) => id).required.value;
  }

  @Mapper.catch()
  get subject_name() {
    return this.get(({ subject_name }) => subject_name).required.value;
  }

  @Mapper.catch()
  get getting_way() {
    return this.get(({ getting_way }) => getting_way).required.value;
  }

  @Mapper.catch()
  get created() {
    return this.get(({ created }) => created).required.value;
  }

  @Mapper.catch()
  get status() {
    return this.get(({ status }) => status).required.value;
  }

  toJSON() {
    return {
      id: this.id,
      subject_name: this.subject_name,
      getting_way: this.getting_way,
      created: this.created,
      status: this.status,
    };
  }
}
