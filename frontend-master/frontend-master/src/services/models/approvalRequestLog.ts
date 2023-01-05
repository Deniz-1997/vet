import { EApprovalRequestStatus, EApprovalRequestType } from '@/services/enums/approvalRequestLog';
import { IUserItemResponse } from '@/services/models/user';
import { IDictionaryNode, ISubjectItem, TInnerFilter } from '@/services/models/common';

export type TApprovalRequestActionresponseItem = IDictionaryNode<
  string,
  {
    approval_request_action_id: number;
    description: string;
  }
>;

export type TApprovalTemplateAutomaticStageResponseItem = {
  id: number;
  code: string;
  name: string;
  description: string;
};

export type TSubjectDivisionStaffResponseItem = {
  subject_division_staff_id: number;
  subject_division_id: number;
  user_id: number;
  user: IUserItemResponse;
  start_date: string;
  end_date: string;
};

export type TDivisionResponseItem = {
  id: number;
  subject_id: number;
  name: string;
  parent_division_id: number;
  type: string;
  start_date: string;
  end_date: string;
  parent_division?: TDivisionResponseItem;
  division_staff: TSubjectDivisionStaffResponseItem[];
};

export type TApprovalTemplateStageResponseItem = {
  approval_template_stage_id: number;
  approval_template_id: number;
  parent_approval_template_stage_id: number;
  name: string;
  subject_division_id: number;
  subject_division: TDivisionResponseItem;
  subject: ISubjectItem;
  decision_period_days: number;
  is_mandatory: boolean;
  automatic_stage?: TApprovalTemplateStageResponseItem;
  workflow_automatic_stage_id: TApprovalTemplateAutomaticStageResponseItem;
};

export type TApprovalTaskResponseItem = {
  approval_task_id: number;
  request_id: number;
  start_date: string;
  decision_date_plan: string;
  end_date: string;
  note: string;
  user_id: number;
  user: IUserItemResponse;
  subject_id: number;
  subject: ISubjectItem;
  subject_division_id: number;
  subject_division: TDivisionResponseItem;
  approval_template_id: number;
  approval_request_type_id: number;
  approval_template_stage_id: number;
  stage: TApprovalTemplateStageResponseItem;
  stage_date: string;
  status: IDictionaryNode<string>;
  reject_reason_id: number;
  created: string;
  created_by: string;
  updated: string;
  updated_by: string;
};

export type TRegistryStatementRejectReasonResponseItem = IDictionaryNode<
  string,
  { request_type: IDictionaryNode<EApprovalRequestType> }
>;

export type TElevatorRegisterApplicationResponseItem = {
  elevator_id: number;
  elevator_registration_number: string;
  reject_notes?: string;
  elevator_info: any;
  elevator_site: any[];
  basis_changes: string;
};

export type TApprovalRequestResponseItem = {
  request_id: number;
  subject_id: number;
  request_date: string;
  request_status: IDictionaryNode<EApprovalRequestStatus>;
  approval_request_type: IDictionaryNode<EApprovalRequestType>;
  dispatch_date: string;
  approval_date: string;
  notes?: string;
  created: string;
  created_by: string;
  updated: string;
  updated_by: string;
  end_date: string;
  elevator_register_application: TElevatorRegisterApplicationResponseItem;
  subject: ISubjectItem;
  reject_reason?: TRegistryStatementRejectReasonResponseItem;
  task: TApprovalTaskResponseItem;
  approval_request_logs?: TApprovalRequestLogResponseItem[];
};

export type TApprovalRequestLogResponseItem = {
  id: number;
  request_id: number;
  request: TApprovalRequestResponseItem;
  event_date: string;
  request_status_id: number;
  request_status: IDictionaryNode<EApprovalRequestStatus>;
  approval_task_id: number;
  approval_task: TApprovalTaskResponseItem;
  approval_task_status_id: number;
  approval_task_status: IDictionaryNode<string>;
  reject_reason?: TRegistryStatementRejectReasonResponseItem;
  approval_template_stage_id: number;
  approval_template_stage: TApprovalTemplateStageResponseItem;
  user_id: number;
  changed_by: string;
  notes: string;
  approval_request_action_id: number;
  action: TApprovalRequestActionresponseItem;
};

export type TApprovalRequestInnerFilter = TInnerFilter & {
  requestId?: number;
  excludedStatuses?: number[];
};
