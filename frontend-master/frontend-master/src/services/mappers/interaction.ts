import { Mapper } from '@/utils';
import { TInteractionLogRequestItem } from '@/services/models/interaction';

export class InteractionItem extends Mapper<TInteractionLogRequestItem> {
  @Mapper.catch()
  get startDate() {
    return this.get(({ start_date }) => start_date).required.date().value;
  }

  @Mapper.catch()
  get endDate() {
    return this.get(({ end_date }) => end_date).required.date().value;
  }

  @Mapper.catch()
  get initiator() {
    return this.get(({ initiator }) => initiator).required.value;
  }

  @Mapper.catch()
  get participant() {
    return this.get(({ type }) => type?.name).optional.value;
  }

  @Mapper.catch()
  get messageType() {
    return this.get(({ message_type }) => message_type.name).optional.value;
  }

  @Mapper.catch()
  get requestName() {
    return this.get(({ request_name }) => request_name).required.value;
  }

  @Mapper.catch()
  get status() {
    return this.get(({ status }) => status.name).required.value;
  }

  @Mapper.catch()
  get result() {
    return this.get(({ is_success }) => (is_success ? 'Успешно' : 'Ошибка')).required.value;
  }

  @Mapper.catch()
  get error() {
    return this.get(({ error }) => error).optional.value;
  }

  @Mapper.catch()
  get request() {
    return this.get(({ requestXml }) => requestXml).optional.value;
  }

  @Mapper.catch()
  get response() {
    return this.get(({ responseXml }) => responseXml).optional.value;
  }

  toJSON() {
    return {
      startDate: this.startDate,
      endDate: this.endDate,
      initiator: this.initiator,
      participant: this.participant,
      messageType: this.messageType,
      requestName: this.requestName,
      status: this.status,
      result: this.result,
      error: this.error,
      request: this.request,
      response: this.response,
    };
  }
}
