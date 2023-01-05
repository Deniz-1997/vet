import Vue from 'vue';
import Component from 'vue-class-component';
import { formatContragent } from '@/utils/formatContragent';

@Component
export class Manufactures extends Vue {
  hideNoDataManufacturesList = true;
  hintOfManufacturer = 'Введите наименование от 1х символов';

  get getManufacturerListMix(): Array<any> {
    return this.$store.state.manufacturers.list
      .map((manufacturer) => manufacturer.subject)
      .map((e) => formatContragent(e));
  }

  async fetchManufacturesMix(query = ''): Promise<any> {
    const { content } = await this.$store.dispatch('manufacturers/getList', {
      filter: query,
      pageable: {
        pageNumber: 0,
        pageSize: 15,
      },
      actual: true,
      with_total_count: false,
    });
    this.$store.commit('manufacturers/setList', content);
    this.hideNoDataManufacturesList = false;
  }

  async searchManufacturesMix(value: string | null): Promise<void> {
    const searchName = value && typeof value !== 'object' ? value?.split(' (')[0] || value : null;

    if (!this.getManufacturerListMix.filter((v) => v.short_name === searchName || (value && v.name === value)).length) {
      await this.fetchManufacturesMix(searchName || '');
    }
  }

  async getManufacturerByIdMix(id: number, arrayName = '') {
    try {
      if (id) {
        const resp = await this.$store.dispatch('manufacturers/getListManufacturersSubject', id);
        if (arrayName) this[arrayName].push(resp.subject);
        this.$store.commit('manufacturers/appendList', resp);
        return resp;
      }
    } catch (e) {
      return undefined;
    }
  }

  getManufacturersByArrayNameMix(arrayName: string) {
    if (arrayName) return this[arrayName].length === 0 ? this.getManufacturerListMix : this[arrayName];
    return this.getManufacturerListMix;
  }

  async onSearchUpdateMix(value: string | null, arrayName = ''): Promise<void> {
    if (value && value?.length > 2) {
      await this.searchManufacturesMix(value);
    }
    if (arrayName) {
      this[arrayName] = this.getManufacturerListMix;
    }
  }

  async getManufacturerByIdSubject(idSubject) {
    try {
      const { id } = await this.$store.dispatch('manufacturers/getListManufacturersSubject', idSubject);
      const routeData = this.$router.resolve({ name: 'OrganizationViewCard', params: { id: id } });
      window.open(routeData.href, '_blank');
    } catch (e) {
      return undefined;
    }
  }
}
