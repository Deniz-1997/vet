import { ApprovalRequestLogItem } from '@/services/mappers/approvalRequestLog';
import { checkMapper } from './__utils__/checkMapper';

describe('password mappers', () => {
  test('ApprovalRequestLogItem', () => {
    checkMapper(
      ApprovalRequestLogItem,
      {
        id: 100,
        request: {
          request_id: 200,
          approval_request_type: {
            name: 'Test Type',
          },
          created_by: 'TEST username',
          subject: {
            name: 'TEST subject TEST',
          },
          dispatch_date: '12.12.2012 13:31',
          approval_date: '13.12.2012',
          request_status: {
            name: 'Status test',
          },
        },
        changed_by: 'assignee test',
        approval_template_stage: {
          name: 'Stage test',
          subject_division: {
            name: 'Division test name',
          },
        },
        approval_task: {
          decision_date_plan: '14.12.2012',
        },
        notes: 'notes notes \n notes',
        action: {
          name: 'action',
        },
      } as any,
      {
        recordNumber: 100,
        requestNumber: 200,
        requestType: 'Test Type',
        username: 'TEST username',
        subjectName: 'TEST subject TEST',
        dispatchDate: '2012-12-12T09:31:00.000Z',
        assignee: 'assignee test',
        stage: 'Stage test',
        status: 'Status test',
        division: 'Division test name',
        approvalDate: '2012-12-12T20:00:00.000Z',
        expectedDate: '2012-12-13T20:00:00.000Z',
        notes: 'notes notes \n notes',
        action: 'action',
      }
    );
  });
});
