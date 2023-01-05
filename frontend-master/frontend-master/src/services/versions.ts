import { AxiosError } from 'axios';
import { Service } from '@/plugins/service';
import { TVersionProviderItem, TVersionItem } from '@/views/Versions/Versions.types';

export default class Versions extends Service {
  async getVersionItem({ callback, id, name }: TVersionProviderItem<any>) {
    try {
      const version = await callback.apply(this);
      const [string, ...rest] = String(typeof version === 'string' ? version : version.data).split(' ');
      const dbConnection = rest.join(' ').toLowerCase();
      const isValidDb = !dbConnection?.includes('db:') || Boolean(dbConnection?.includes('db: ok'));
      const isValid = Boolean(string && /^(\d{1,3}\.?)*/.test(string) && isValidDb);
      let result = isValid ? string : 'Ошибка получения версии';

      if (!isValidDb) {
        result = string + ' (DB Error)';
      }

      return {
        id,
        version: result,
        name: name ? `${name} (${id})` : id,
        active: isValid,
      };
    } catch (err) {
      const error = err as unknown as AxiosError;
      return {
        id,
        version: error.code === 'ECONNABORTED' || !error.response?.status ? 'Timeout (15s)' : 'Ошибка получения версии',
        name: name ? `${name} (${id})` : id,
        active: false,
      };
    }
  }

  getVersionList(config: TVersionProviderItem<any>[]): Promise<TVersionItem[]> {
    return Promise.all(config.map((item) => this.getVersionItem(item)));
  }
}
