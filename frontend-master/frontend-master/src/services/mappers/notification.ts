import { Mapper } from '@/utils';
import { INotificationItemResponse } from '@/services/models/notification';
import { ENotificationObjectType, ENotificationStatus } from '@/services/enums/notification';

export class NotificationItem extends Mapper<INotificationItemResponse> {
  @Mapper.catch()
  get id() {
    return this.get(({ id }) => id).required.value;
  }

  @Mapper.catch()
  get created() {
    return this.get(({ created }) => created).required.date().value;
  }

  @Mapper.catch()
  get message() {
    return this.get(({ message }) => message).required.value;
  }

  @Mapper.catch()
  get status() {
    return this.required({
      id: this.get(({ status }) => status.id).required.value,
      code: this.get(({ status }) => status.code as ENotificationStatus).required.value,
      name: this.get(({ status }) => status.name).required.value,
    });
  }

  @Mapper.catch()
  get object() {
    return this.required({
      id: this.get(({ object_id }) => object_id).optional.value,
      name: this.get(({ type }) => type.name).optional.value,
      type: this.get(({ type }) => type.code as ENotificationObjectType).optional.value,
    });
  }

  @Mapper.catch()
  get subject() {
    return this.optional(() => ({
      id: this.get(({ subject }) => subject?.subject_id).optional.value,
      name: this.get(({ subject }) => subject?.subject_data?.short_name ?? subject?.subject_data?.name).optional.value,
    }));
  }

  toJSON() {
    return {
      id: this.id,
      created: this.created,
      message: this.message,
      status: this.status,
      object: this.object,
      subject: this.subject,
    };
  }
}
