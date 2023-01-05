import { Service } from '@/plugins/service/utils';
import { AxiosResponse } from 'axios';
import { LaboratoryItemIn, LaboratoryItemOut, LaboratoryFilterOut } from './mappers/laboratory';
import { TMapperPlain } from './models/common';
import { ILaboratoryItemResponse, TExcludeItem, TLaboratoryInnerFilter } from './models/laboratory';

export default class Laboratory extends Service {
  /** Получить список лабораторий. */
  async find(filter: TLaboratoryInnerFilter): Promise<AxiosResponse<ILaboratoryItemResponse[]>> {
    const response = await this.$axios.post('/api/laboratory/find', new LaboratoryFilterOut(filter));
    return { ...response, data: response.data.content.map((item) => new LaboratoryItemIn(item).toJSON()) };
  }

  /** Получить данные по лаборатории по его id. */
  async findOne(id: number): Promise<AxiosResponse<TMapperPlain<LaboratoryItemIn>>> {
    const response = await this.$axios.post('/api/laboratory/show', { id: id });
    return { ...response, data: new LaboratoryItemIn(response.data).toJSON() };
  }

  /** Добавление новой лаборатории. */
  async create(data: any) {
    const preview = new LaboratoryItemOut(data).toJSON();
    return await this.$axios.post('/api/laboratory/include', preview);
  }

  /** Редактирование существующей лаборатории. */
  async update(data: any) {
    const preview = new LaboratoryItemOut(data).toJSON();
    return await this.$axios.post('/api/subject/laboratory/update', preview);
  }

  /** Аннулирование лаборатории. */
  async exclude(data: TExcludeItem) {
    return await this.$axios.post('/api/laboratory/exclude', data);
  }
}
