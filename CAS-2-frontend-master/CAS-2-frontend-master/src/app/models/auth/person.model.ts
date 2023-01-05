import {constructByInterface} from '../../utils/construct-by-interface';
import {SupervisoryAuthorityInterface, SupervisoryAuthorityModel} from '../contractors/supervisory-authority.model';

export interface PersonInterface {
  id: string;
  name: string;
  patronymic: string;
  surname: string;
  phone: number;
  station: SupervisoryAuthorityInterface;
  isInvalid: boolean;

}

export class PersonModel implements PersonInterface {
  id: string;
  name: string;
  patronymic: string;
  surname: string;
  phone: number;
  station: SupervisoryAuthorityModel;
  isInvalid: boolean;

  constructor(o?: PersonInterface) {
    constructByInterface(o, this);
  }
}
