import { Service } from '@/plugins/service/utils';
import { Memoize } from '@/utils/global/decorators/method';
import { AxiosResponse } from 'axios';
import { ManufacturerItemIn, ManufacturerItemOut, ManufacturerFilterOut } from './mappers/manufacturer';
import { IDictionaryNode, TMapperPlain } from './models/common';
import { IManufacturerItemResponse, TExcludeItem, TManufacturerInnerFilter } from './models/manufacturer';

export default class Role extends Service {
  /** Получить список товаропроизводителей. */
  async find(filter: TManufacturerInnerFilter): Promise<AxiosResponse<IManufacturerItemResponse[]>> {
    const response = await this.$axios.post(
      '/api/subject/manufacturer/manufacturers',
      new ManufacturerFilterOut(filter)
    );
    return { ...response, data: response.data.content.map((item) => new ManufacturerItemIn(item).toJSON()) };
  }

  /** Получить данные по товаропроизводителю по его id. */
  async findOne(id: number): Promise<AxiosResponse<TMapperPlain<ManufacturerItemIn>>> {
    const response = await this.$axios.get(`/api/subject/manufacturer/${id}`);
    return { ...response, data: new ManufacturerItemIn(response.data).toJSON() };
  }

  /** Добавление нового товаропроизводителя. */
  async create(data: any) {
    const preview = new ManufacturerItemOut(data).toJSON();
    return await this.$axios.post('/api/subject/manufacturer/create', preview);
  }

  /** Редактирование существующего товаропроизводителя. */
  async update(data: any) {
    const preview = new ManufacturerItemOut(data).toJSON();
    return await this.$axios.post('/api/subject/manufacturer/update', preview);
  }

  /** Получение списка причин для аннулирования товаропроизводителя. */
  @Memoize()
  async getRejectList(): Promise<AxiosResponse<IDictionaryNode[]>> {
    return await this.$axios.get('/api/subject/manufacturer/status_reject_reasons');
  }

  /** Получение списка причин для аннулирования товаропроизводителя. */
  @Memoize()
  async getReasonList(): Promise<AxiosResponse<IDictionaryNode[]>> {
    return await this.$axios.get('/api/subject/manufacturer/status_exclude_reasons');
  }

  /** Аннулирование товаропроизводителя. */
  async exclude(data: TExcludeItem) {
    return await this.$axios.post('/api/subject/manufacturer/exclude', data);
  }
}
