import { Mapper } from '@/utils';
import { TRequestsItemResponse } from '@/services/models/requests';

export class RequestsItem extends Mapper<TRequestsItemResponse> {
  @Mapper.catch()
  get id() {
    return this.get(({ id }) => id).required.value;
  }

  @Mapper.catch()
  get body() {
    return this.get(({ body }) => body).required.value;
  }

  @Mapper.catch()
  get subject_name() {
    return this.get(({ subject }) => subject?.subject_data?.name).optional.value;
  }

  @Mapper.catch()
  get receiving_type() {
    return this.get(({ means_of_receiving }) => means_of_receiving?.name).optional.value;
  }

  @Mapper.catch()
  get receiving() {
    return this.get(({ means_of_receiving }) => means_of_receiving).optional.value;
  }

  @Mapper.catch()
  get answering_type() {
    return this.get(({ means_of_answering }) => means_of_answering?.name).optional.value;
  }

  @Mapper.catch()
  get answering() {
    return this.get(({ means_of_answering }) => means_of_answering).optional.value;
  }

  @Mapper.catch()
  get created() {
    return this.get(({ created }) => created).optional.value;
  }

  @Mapper.catch()
  get answer_date() {
    return this.get(({ answer_date }) => answer_date).optional.value;
  }

  @Mapper.catch()
  get status_name() {
    return this.get(({ status }) => status?.name).optional.value;
  }

  @Mapper.catch()
  get status() {
    return this.get(({ status }) => status).optional.value;
  }

  @Mapper.catch()
  get email() {
    return this.get(({ email }) => email).optional.value;
  }

  @Mapper.catch()
  get updated() {
    return this.get(({ updated }) => updated).optional.value;
  }

  @Mapper.catch()
  get reject_reason() {
    return this.get(({ reject_reason }) => reject_reason).optional.value;
  }

  @Mapper.catch()
  get file_id() {
    return this.get(({ file_id }) => file_id).optional.value;
  }

  @Mapper.catch()
  get answer_id() {
    return this.get(({ answer_id }) => answer_id).optional.value;
  }

  @Mapper.catch()
  get address() {
    return this.get(({ address }) => address).optional.value;
  }

  toJSON() {
    return {
      id: this.id,
      subject_name: this.subject_name,
      receiving_type: this.receiving_type,
      receiving: this.receiving,
      answering_type: this.answering_type,
      answering: this.answering,
      email: this.email,
      status_name: this.status_name,
      created: this.created,
      answer_date: this.answer_date,
      status: this.status,
      updated: this.updated,
      reject_reason: this.reject_reason,
      file_id: this.file_id,
      answer_id: this.answer_id,
      address: this.address,
      body: this.body,
    };
  }
}

export class RequestsReceiveOut extends Mapper<any> {
  @Mapper.catch()
  get body() {
    return this.get(({ body }) => body).optional.value;
  }
  @Mapper.catch()
  get email() {
    return this.get(({ email }) => email).optional.value;
  }
  @Mapper.catch()
  get address() {
    return this.get(({ address }) => address).optional.value;
  }
  @Mapper.catch()
  get file_id() {
    return this.get(({ file_id }) => file_id).optional.value;
  }
  @Mapper.catch()
  get subject_id() {
    return this.get(({ subject }) => subject).optional.value;
  }
  @Mapper.catch()
  get means_of_receive_id() {
    return this.get(({ receiving }) => receiving?.free_form_request_means_of_receiving_id).optional.value;
  }
  @Mapper.catch()
  get means_of_answering_id() {
    return this.get(({ answering }) => answering?.free_form_request_means_of_answering_id).optional.value;
  }

  toJSON() {
    return {
      body: this.body,
      email: this.email,
      address: this.address,
      subject_id: this.subject_id,
      means_of_answering_id: this.means_of_answering_id,
      means_of_receive_id: this.means_of_receive_id,
      file_id: this.file_id,
    };
  }
}

export class RequestsAnswerOut extends Mapper<any> {
  @Mapper.catch()
  get free_form_request_id() {
    return this.get(({ id }) => id).optional.value;
  }
  @Mapper.catch()
  get status_id() {
    return this.get(({ status }) => status.free_form_request_status_id).optional.value;
  }
  @Mapper.catch()
  get status() {
    return this.get(({ status }) => status).optional.value;
  }
  @Mapper.catch()
  get answer() {
    return this.get(({ answer }) => answer).optional.value;
  }
  @Mapper.catch()
  get file_id() {
    return this.get(({ file_id }) => file_id).optional.value;
  }
  @Mapper.catch()
  get reject_reason() {
    return this.get(({ reject_reason }) => reject_reason).optional.value;
  }

  @Mapper.catch()
  get reject_reason_id() {
    return this.get(({ reject_reason }) => reject_reason?.free_form_request_reject_reason_id).optional.value;
  }

  toJSON() {
    return {
      free_form_request_id: this.free_form_request_id,
      status_id: 3,
      status: {
        code: 'DONE',
        name: 'Выполнено',
        description: 'Выполнено',
        free_form_request_status_id: 3,
      },
      // status_id: this.status_id,
      // status: this.status,
      answer: this.answer,
      file_id: this.file_id,
      reject_reason: this.reject_reason,
      reject_reason_id: this.reject_reason_id,
    };
  }
}

// TODO: обсудить с беком передачу значений справочника статуса в запросе
export class RequestsToRevision extends Mapper<any> {
  @Mapper.catch()
  get free_form_request_id() {
    return this.get(({ id }) => id).optional.value;
  }
  @Mapper.catch()
  get status_id() {
    return this.get(({ status }) => status.free_form_request_status_id).optional.value;
  }
  @Mapper.catch()
  get status() {
    return this.get(({ status }) => status).optional.value;
  }
  @Mapper.catch()
  get answer() {
    return this.get(({ answer }) => answer).optional.value;
  }
  @Mapper.catch()
  get file_id() {
    return this.get(({ file_id }) => file_id).optional.value;
  }
  @Mapper.catch()
  get reject_reason() {
    return this.get(({ reject_reason }) => reject_reason).optional.value;
  }

  @Mapper.catch()
  get reject_reason_id() {
    return this.get(({ reject_reason }) => reject_reason?.free_form_request_reject_reason_id).optional.value;
  }

  toJSON() {
    return {
      free_form_request_id: this.free_form_request_id,
      status_id: 5,
      status: {
        code: 'RETURNED_FOR_REVISION',
        name: 'Отправлен на доработку',
        description: 'Отправлен на доработку',
        free_form_request_status_id: 5,
      },
      answer: this.answer,
      file_id: this.file_id,
      reject_reason: this.reject_reason,
      reject_reason_id: this.reject_reason_id,
    };
  }
}

export const requestsToPostpone = (id) => {
  return {
    id: id,
    status_id: 4,
    status: {
      code: 'POSTPONED',
      name: 'Отложено',
      description: 'Отложено',
      free_form_request_status_id: 4,
    },
  };
}
