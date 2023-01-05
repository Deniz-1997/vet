import { Mapper } from '@/utils';
import { IComplaintItemResponse } from '@/services/models/complaint';

export class ComplaintItem extends Mapper<IComplaintItemResponse> {
  @Mapper.catch()
  get id() {
    return this.get(({ id }) => id).required.value;
  }

  @Mapper.catch()
  get message() {
    return this.get(({ complaint_message }) => complaint_message).required.value;
  }

  @Mapper.catch()
  get complaint_name() {
    return this.get(({ complaint_type }) => complaint_type?.name).required.value;
  }

  @Mapper.catch()
  get created() {
    return this.get(({ created }) => created).required.value;
  }

  @Mapper.catch()
  get subject_name() {
    return this.get(({ subject }) => subject?.subject_data?.short_name ?? subject?.short_name).required.value;
  }

  @Mapper.catch()
  get created_by() {
    return this.get(({ subject }) => subject?.subject_data?.created_by ?? subject?.created_by).required.value;
  }

  @Mapper.catch()
  get attachment() {
    return this.get(({ attachment_file_id }) => {
      if (attachment_file_id) {
        return {
          name: 'Прикреплённое вложение',
          id: attachment_file_id,
        };
      }

      return null;
    }).optional.value;
  }

  toJSON() {
    return {
      id: this.id,
      created: this.created,
      message: this.message,
      subject_name: this.subject_name,
      complaint_name: this.complaint_name,
      created_by: this.created_by,
      attachment: this.attachment,
    };
  }
}
