import { Service } from '@/plugins/service';
import { AxiosResponse } from 'axios';

import { IDictionaryNode } from '@/services/models/common';
import { IDictionaryRegions } from '@/services/models/catalogs';

export default class Catalogs extends Service {
  // Справочник Регионов
  async getRegion(is_region: boolean = true): Promise<AxiosResponse<IDictionaryRegions[]>> {
    const response = await this.$axios.post('/api/nci/oker', { is_region });

    return { ...response, data: response.data.content };
  }

  // Справочник Причина исключения Реестр Товаропроизводителей
  async getReasonExclusion(): Promise<AxiosResponse<IDictionaryNode[]>> {
    return await this.$axios.get('api/subject/manufacturer/status_change_reasons');
  }

  // Справочник Причина исключения Реестр организаций (Элеваторы)
  async getReasonExclusionElevator(): Promise<AxiosResponse<IDictionaryNode[]>> {
    const response = await this.$axios.get('api/elevator/reason');

    return { ...response, data: response.data.content };
  }

  // Справочник Предоставляемые услуги
  async getServicesType(): Promise<AxiosResponse<IDictionaryNode[]>> {
    const response = await this.$axios.get('api/elevator-request/serviceTypes');

    return { ...response, data: response.data.content };
  }
}