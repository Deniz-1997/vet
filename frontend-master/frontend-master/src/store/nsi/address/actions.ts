import { CountryService } from '@/api/nsi/country/REST';

const Country = new CountryService();
export default {
  getListCountry() {
    return Country.getList();
  },
};
