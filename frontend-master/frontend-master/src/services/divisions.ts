import { Service } from '@/plugins/service/utils';
import { AxiosResponse } from 'axios';
import { DivisionsItemIn } from './mappers/divisions';
import { IDivisionsItemResponse } from './models/divisions';

export default class Divisions extends Service {
  /** Получить список ОГВ. */
  async find(id: string): Promise<AxiosResponse<IDivisionsItemResponse[]>> {
    const response = await this.$axios.post('/api/subject/division/find', { subject_id: id });
    return { ...response, data: response.data.content.map((item) => new DivisionsItemIn(item).toJSON()) };
  }
}
