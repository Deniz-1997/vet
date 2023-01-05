import { TApprovalRequestInnerFilter } from './models/approvalRequestLog';
import { TMapperPlain } from './models/common';
import { Service } from '@/plugins/service';
import { AxiosResponse } from 'axios';
import { ApprovalRequestLogItem, ApprovalRequestFilterOut } from './mappers/approvalRequestLog';

export default class extends Service {
  /** Получить список записей журнала согласования заявлений. */
  async find(filter: TApprovalRequestInnerFilter): Promise<AxiosResponse<TMapperPlain<ApprovalRequestLogItem>[]>> {
    const response = await this.$axios.post('/api/approval-request/log/find', new ApprovalRequestFilterOut(filter));
    const data = (response?.data?.content || []).map((item) => new ApprovalRequestLogItem(item).toJSON());
    return { ...response, data };
  }

  /** Получить запись журнала согласования заявлений. */
  async findOne(id: number): Promise<AxiosResponse<TMapperPlain<ApprovalRequestLogItem>>> {
    const response = await this.$axios.get(`/api/approval-request/log/${id}`);
    return { ...response, data: new ApprovalRequestLogItem(response.data).toJSON() };
  }
}
