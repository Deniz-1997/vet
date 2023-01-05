import { Component, Mixins } from 'vue-property-decorator';
import { PermissionMix } from '@/utils/mixins/permission';

export enum RegionalGovernmentMode {
  SDIZ = 'sdiz',
  LOT = 'lot',
  IMPLEMENTATION = 'implementation',
}

export type RegionalGovernmentForm = {
  number: string | null;
  date: string | null;
  amount_kg: number | null;
};

@Component
export class RegionalGovernmentMix extends Mixins(PermissionMix) {
  viewPrivileges = '';

  get isAccess() {
    return this.accessGrantedAuthorities(this.viewPrivileges);
  }

  isLoading = false;

  regionalGovernmentMode = RegionalGovernmentMode;

  mode: RegionalGovernmentMode = RegionalGovernmentMode.SDIZ;

  model: any = null;

  form: RegionalGovernmentForm = {
    number: null,
    date: null,
    amount_kg: null,
  };

  endpoints = {
    sdiz: '/api/sdiz/show/for-regional-government',
    lot: '/api/lot/show/for-regional-government',
    implementation: '/api/gosmonitoring/register/implementation/show/for-regional-government',
  };

  get endpoint() {
    return this.endpoints[this.mode];
  }

  getDataForRequest() {
    switch (this.mode) {
      case RegionalGovernmentMode.SDIZ:
        return { sdiz_number: this.form.number, enter_date: this.form.date, amount_kg_original: this.form.amount_kg };
      case RegionalGovernmentMode.LOT:
        return { lot_number: this.form.number, enter_date: this.form.date, amount_kg: this.form.amount_kg };
      case RegionalGovernmentMode.IMPLEMENTATION:
        return { prodact_monitor_number: this.form.number, date_enter: this.form.date, weight: this.form.amount_kg };
    }
  }

  async onSearch() {
    try {
      this.isLoading = true;
      const { data } = await this.$axios.post(this.endpoint, this.getDataForRequest());

      if (!data.status) throw new Error();

      this.model = new this.model.constructor(data.response);
    } catch (_e) {
      this.$notify({
        group: 'regionalGovernment',
        type: 'warning',
        title: 'Не удалось загрузить документ с указанными данными',
      });

      this.model = new this.model.constructor();
    } finally {
      this.isLoading = false;
    }
  }
}
