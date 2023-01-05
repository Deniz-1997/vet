import { constructByInterface } from '@/utils/construct-by-interface';
import { Okpd2VueModel } from '@/models/Sdiz/Okpd2.vue';

export interface LotProductTypeVueInterface {
  prod_type_id: number | null;
  okpd2: Okpd2VueModel;
  okpd2_code: string | null;
  product_name: string | null;
  product_name_convert: string | null;
  new_product_name_convert: string | null;
  name: string | null;
  start_date: string | null;
  tnved: string | null;
}

export class LotProductTypeVueModel implements LotProductTypeVueInterface {
  prod_type_id!: number;
  okpd2: Okpd2VueModel = new Okpd2VueModel();
  okpd2_code!: string;
  product_name!: string;
  product_name_convert!: string;
  new_product_name_convert!: string;
  name!: string;
  start_date!: string;
  tnved!: string;

  constructor(o?: LotProductTypeVueInterface) {
    if (o !== undefined) {
      constructByInterface(o, this);
      this.okpd2_code = this.okpd2.code || '-';
      this.new_product_name_convert =
        this.okpd2.name + ` ( ОКПД 2: ${this.okpd2.code}; ТН ВЭД: ${this.tnved.replace(/\s+/g, '')} )`;
    }
  }
}
