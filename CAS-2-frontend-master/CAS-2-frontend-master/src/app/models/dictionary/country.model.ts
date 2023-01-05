import {constructByInterface} from '../../utils/construct-by-interface';
import {ManufacturerInterface, ManufacturerModel} from './manufacturer.model';

export interface CountryInterface {
  iso: string;
  name: string;
  fullname: string;
  alpha2: string;
  alpha3: string;
  manufacturers: Array<ManufacturerInterface>;
}

export class CountryModel implements CountryInterface {
  iso: string;
  name: string;
  fullname: string;
  alpha2: string;
  alpha3: string;
  manufacturers: Array<ManufacturerModel>;

  constructor(o?: CountryInterface) {
    constructByInterface(o, this);
  }
}
