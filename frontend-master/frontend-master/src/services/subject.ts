import { Service } from '@/plugins/service/utils';
import { AxiosResponse } from 'axios';
import { SubjectFilterOut, LabSubjectItemIn, SubjectItemIn, SubjectItemOut } from './mappers/subject';
import { TMapperPlain } from './models/common';
import { ISubjectItemResponse, TSubjectInnerFilter } from './models/subject';

export default class Subject extends Service {
  /** Получить список организаций. */
  async find(filter: TSubjectInnerFilter): Promise<AxiosResponse<ISubjectItemResponse[]>> {
    const response = await this.$axios.post('/api/subject/common/find', new SubjectFilterOut(filter));

    return {
      ...response,
      data: response.data.content.map((item) => new SubjectItemIn(item).toJSON()),
    };
  }

  async findOne(id: string): Promise<AxiosResponse<TMapperPlain<LabSubjectItemIn>>> {
    const response = await this.$axios.get(`/api/subject/common/${id}`);

    return {
      ...response,
      data: new LabSubjectItemIn(response.data).toJSON(),
    };
  }

  async create(data): Promise<any> {
    const preview = new SubjectItemOut(data).toJSON();
    await this.$axios.post('/api/subject/common/create', preview);
  }

  async update(data): Promise<AxiosResponse<TMapperPlain<LabSubjectItemIn>>> {
    const preview = new SubjectItemOut(data).toJSON();
    return await this.$axios.post('/api/subject/common/update', preview);
  }
}
