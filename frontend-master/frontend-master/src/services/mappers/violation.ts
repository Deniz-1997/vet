import { Mapper } from '@/utils';
import { IViolationItemResponse } from '@/services/models/notification';

export class ViolationItem extends Mapper<IViolationItemResponse> {
  @Mapper.catch()
  get id() {
    return this.get(({ id }) => id).required.value;
  }

  @Mapper.catch()
  get created() {
    return this.get(({ created }) => created).required.date().value;
  }

  @Mapper.catch()
  get difference() {
    return this.get(({ difference }) => difference).required.value;
  }

  @Mapper.catch()
  get subject() {
    return this.required({
      id: this.get(({ subject }) => subject.subject_id).required.value,
      name: this.get(({ subject }) => subject?.subject_data?.short_name || subject?.subject_data?.name).optional.value,
      lastVerificationDate: this.get(({ subject }) => subject.last_verification_date).optional.date().value,
    });
  }

  @Mapper.catch()
  get type() {
    return this.required({
      id: this.get(({ violation_type }) => violation_type.id).required.value,
      code: this.get(({ violation_type }) => violation_type.code).required.value,
      name: this.get(({ violation_type }) => violation_type.name).required.value,
    });
  }

  toJSON() {
    return {
      id: this.id,
      created: this.created,
      difference: this.difference,
      subject: this.subject,
      type: this.type,
    };
  }
}
