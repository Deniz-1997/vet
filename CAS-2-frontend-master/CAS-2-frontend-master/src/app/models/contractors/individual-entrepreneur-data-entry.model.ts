import {constructByInterface} from '../../utils/construct-by-interface';
import {ContractorInterface, ContractorModel} from './contractor.model';

export interface IndividualEntrepreneurDataEntryInterface {
  contractor: ContractorInterface;
  surname: string;
  name: string;
  patronymic: string;
  passportNumber: string;
  passportSerial: string;
  snils: string;
  inn: string;
  ogrnip: string;
  passportDate: string;
  passportDivision: string;
  passportDivisionCode: string;
  website: string;
  isHeadKfh: boolean;
}

export class IndividualEntrepreneurDataEntryModel implements IndividualEntrepreneurDataEntryInterface {
  contractor: ContractorModel;
  surname: string;
  name: string;
  patronymic: string;
  passportNumber: string;
  passportSerial: string;
  snils: string;
  inn: string;
  ogrnip: string;
  passportDate: string;
  passportDivision: string;
  passportDivisionCode: string;
  website: string;
  isHeadKfh: boolean;

  constructor(o?: IndividualEntrepreneurDataEntryInterface) {
    constructByInterface(o, this);
  }
}
