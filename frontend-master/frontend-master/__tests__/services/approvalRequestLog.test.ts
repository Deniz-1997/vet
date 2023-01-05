import ApprovalRequestLog from '@/services/approvalRequestLog';
import { FilterOut } from '@/services/mappers/common';

let service: ApprovalRequestLog;
let ctx = {
  $axios: {
    get: jest.fn(() => Promise.resolve({ data: { content: [] } })),
    post: jest.fn(() => Promise.resolve({ data: { content: [] } })),
  },
};

beforeEach(() => {
  ctx = {
    $axios: {
      get: jest.fn(() => Promise.resolve({ data: { content: [] } })),
      post: jest.fn(() => Promise.resolve({ data: { content: [] } })),
    },
  };
  service = new ApprovalRequestLog(ctx as any);
});

describe('password service', () => {
  test('find', async () => {
    await service.find({ filter: '', actual: false });
    expect(ctx.$axios.post).toBeCalledWith('/api/approval-request/log/find', expect.any(FilterOut));
  });
});
