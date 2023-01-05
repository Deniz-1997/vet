import { constructByInterface } from '@/utils/construct-by-interface';
import { formatContragentName } from '@/utils/formatContragent';

export interface OwnerInterface {
  short_name: string | null;
  name: string | null;
  inn: number | null;
  kpp: number | null;
  formattedName: string | null;
}

export class OwnerModel implements OwnerInterface {
  short_name: string | null = null;
  name: string | null = null;
  inn: number | null = null;
  kpp: number | null = null;
  formattedName: string | null = null;

  constructor(o?) {
    constructByInterface(o, this);
    this.formattedName = formatContragentName(o);
  }
}
