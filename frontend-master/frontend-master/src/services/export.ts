import { Service } from '@/plugins/service';
import { TInnerFilter } from './models/common';

export default class Export extends Service {
  async runExport(url: string, filter: TInnerFilter): Promise<void> {
    const { data } = await this.$axios.post(url, filter);
    this.$service.notify.push('message', {
      text: `Список формируется, по готовности вам будет отправлено соответствующее уведомление.<br>Номер экспорта: ${data}`,
    });
  }

  async runExportDeprecated(url: string, filter: TInnerFilter): Promise<void> {
    const { data } = await this.$axios.get(url, { params: filter });
    this.$service.notify.push('message', {
      text: `Список формируется, по готовности вам будет отправлено соответствующее уведомление.<br>Номер экспорта: ${data}`,
    });
  }
}
