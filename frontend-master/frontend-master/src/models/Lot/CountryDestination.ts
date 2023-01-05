import { constructByInterface } from '@/utils/construct-by-interface';

export interface CountryDestinationInterface {
  country_id: number | null;
  code: string | null;
  name: string | null;
  name_full: string | null;
  code_alpha2: string | null;
  code_alpha3: string | null;
  startDate: string | null;
  start_date: string | null;
  global_id: number | null;
  startTime: string | null;
  short_name: string | null;
}

export class CountryDestinationModel implements CountryDestinationInterface {
  country_id!: number;
  code!: string | null;
  name!: string | null;
  name_full!: string | null;
  code_alpha2!: string | null;
  code_alpha3!: string | null;
  startDate!: string | null;
  start_date!: string | null;
  global_id!: number | null;
  startTime!: string | null;
  short_name!: string | null;

  constructor(o?: CountryDestinationInterface) {
    constructByInterface(o, this);
  }
}
