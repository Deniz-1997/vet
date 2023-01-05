import { TApprovalRequestInnerFilter } from './../models/approvalRequestLog';
import { TApprovalRequestLogResponseItem } from '@/services/models/approvalRequestLog';
import { Mapper } from '@/utils';
import { FilterOut } from './common';

export class ApprovalRequestLogItem extends Mapper<TApprovalRequestLogResponseItem> {
  @Mapper.catch()
  get recordNumber() {
    return this.get(({ id }) => id).required.value;
  }

  @Mapper.catch()
  get requestNumber() {
    return this.get(({ request }) => request.request_id).required.value;
  }

  @Mapper.catch()
  get requestType() {
    return this.get(({ request }) => request.approval_request_type.name).required.value;
  }

  @Mapper.catch()
  get username() {
    return this.get(({ request }) => request.created_by).required.value;
  }

  @Mapper.catch()
  get subjectName() {
    return this.get(({ request }) => request?.subject?.subject_data?.short_name || request?.subject?.subject_data?.name).optional.value;
  }

  @Mapper.catch()
  get dispatchDate() {
    return this.get(({ request }) => request.dispatch_date).optional.date().value;
  }

  @Mapper.catch()
  get assignee() {
    return this.get(({ changed_by }) => changed_by).required.value;
  }

  @Mapper.catch()
  get stage() {
    return this.get(
      ({ approval_template_stage, request_status }) =>
        approval_template_stage?.automatic_stage?.name ?? approval_template_stage?.name ?? request_status.name
    ).optional.value;
  }

  @Mapper.catch()
  get status() {
    return this.get(({ request, request_status }) => request_status?.name ?? request.request_status.name).required
      .value;
  }

  @Mapper.catch()
  get action() {
    return this.get(({ action }) => action?.name).optional.value;
  }

  @Mapper.catch()
  get division() {
    return this.get(({ approval_template_stage }) => approval_template_stage.subject_division.name).optional.value;
  }

  @Mapper.catch()
  get approvalDate() {
    return this.get(({ request }) => request.approval_date).optional.date().value;
  }

  @Mapper.catch()
  get expectedDate() {
    return this.get(({ approval_task }) => approval_task.decision_date_plan).optional.date().value;
  }

  @Mapper.catch()
  get notes() {
    return this.get(({ notes, reject_reason }) => [reject_reason?.name, notes].filter((v) => !!v).join('\n')).required
      .value;
  }

  toJSON() {
    return {
      recordNumber: this.recordNumber,
      requestNumber: this.requestNumber,
      requestType: this.requestType,
      username: this.username,
      subjectName: this.subjectName,
      dispatchDate: this.dispatchDate,
      assignee: this.assignee,
      stage: this.stage,
      status: this.status,
      action: this.action,
      division: this.division,
      approvalDate: this.approvalDate,
      expectedDate: this.expectedDate,
      notes: this.notes,
    };
  }
}

export class ApprovalRequestFilterOut extends FilterOut<TApprovalRequestInnerFilter> {
  @Mapper.catch()
  get request_id() {
    return this.get(({ requestId }) => requestId).optional.value;
  }

  @Mapper.catch()
  get excluded_statuses() {
    return this.get(({ excludedStatuses }) => excludedStatuses).optional.value;
  }

  toJSON() {
    return { ...super.toJSON(), request_id: this.request_id, excluded_statuses: this.excluded_statuses };
  }
}
