import { Service } from '@/plugins/service/utils';
import { AxiosResponse } from 'axios';
import { ImportFilterOut, ImportItem } from './mappers/import';
import { IFilterableList, TMapperPlain } from './models/common';
import { TImportInnerFilter, TImportResponseItem } from './models/import';

export default class Role extends Service {
  /** Получить список импортов. */
  async find(filter: TImportInnerFilter): Promise<AxiosResponse<TMapperPlain<ImportItem>[]>> {
    const response = await this.$axios.post<IFilterableList<TImportResponseItem>>(
      '/api/security/import/find',
      new ImportFilterOut(filter)
    );
    const list = response.data.content || [];
    return { ...response, data: list.map((data) => new ImportItem(data).toJSON()) };
  }

  /** Получить информацию об импорте по `id` */
  async findOne(id: number): Promise<AxiosResponse<TMapperPlain<ImportItem>>> {
    const response = await this.$axios.get<TImportResponseItem>(`/api/security/import/${id}`);
    return { ...response, data: new ImportItem(response.data).toJSON() };
  }

  /** Загрузить файл для импорта. */
  async upload(file: File, onUploadProgress?: (progressEvent: { loaded: number; total: number }) => void) {
    const data = new FormData();
    data.set('file', file);
    return this.$axios.post('/api/security/import/', data, { onUploadProgress });
  }
}
