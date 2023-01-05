import { Vue, Component } from 'vue-property-decorator';
import config from '@/views/NSI/config';

const defaultParams = {
  params: {
    actual: true,
    pageable: {
      pageable: {
        pageNumber: 0,
        pageSize: 100,
      },
      sort: [
        {
          property: 'name',
          direction: 'ASC',
        },
      ],
    },
  },
};

@Component
export class DictionariesMix extends Vue {
  async setStoreData(type: string): Promise<void> {
    const nsiByType = config[type];
    if (!nsiByType.storeSetter || !nsiByType.apiUrl) return;

    const { data, content } = await this.$store.dispatch('nsi/getList', {
      ...defaultParams,
      url: nsiByType.apiUrl,
    });

    this.$store.commit(nsiByType.storeSetter, content || data);
  }

  async storeData(type: string): Promise<any[]> {
    const nsiByType = config[type];
    if (!nsiByType.storeGetter) return [];

    if (!this.$store.getters[nsiByType.storeGetter].length) {
      await this.setStoreData(type);
    }

    return this.$store.getters[nsiByType.storeGetter];
  }

  // eslint-disable-next-line max-params
  async dictionaryRecordByField(type: string, field: string, value: any, mandatory = false) {
    try {
      const data = (await this.storeData(type)).find((e) => e[field] === value);
      if (!data) throw new Error();
      return data;
    } catch (error) {
      if (mandatory) {
        this.$service.notify.push('error', { text: 'Ошибка при получении значений справочника' });
        throw error;
      } else return null;
    }
  }

  async dictionaryRecordById(type: string, id: number, mandatory?): Promise<any> {
    return await this.dictionaryRecordByField(type, 'id', id, mandatory);
  }

  /**
   * Поиск значения в справочнике
   * @param type ключ nsiList
   * @param code код
   * @param mandatory необходимо ли значение для бизнес-логики. Если да, вернёт ошибку.
   */
  async dictionaryRecordByCode(type: string, code: string, mandatory?): Promise<any> {
    return await this.dictionaryRecordByField(type, 'code', code, mandatory);
  }
}
