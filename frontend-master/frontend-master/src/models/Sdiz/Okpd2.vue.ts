import { constructByInterface } from '@/utils/construct-by-interface';

export function convertOkpd2ProductName(data) {
  const { name, code } = data;
  return name && code ? name + ` ( ОКПД 2: ${code} )` : '-';
}

export interface Okpd2VueInterface {
  id: number | null;
  code: string | null;
  name: string | null;
  startDate: string | null;
  endDate: string | null;
  is_grain: boolean | null;
  is_product: boolean | null;
  product_name_convert: string;
}

export class Okpd2VueModel implements Okpd2VueInterface {
  id!: number;
  code!: string;
  name!: string;
  startDate!: string;
  endDate!: string;
  is_grain: boolean | null = null;
  is_product: boolean | null = null;
  product_name_convert: string;

  constructor(o?) {
    constructByInterface(o, this);
    this.product_name_convert = convertOkpd2ProductName(this);
  }
}
