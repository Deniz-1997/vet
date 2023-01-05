import {constructByInterface} from '../../utils/construct-by-interface';
import {ContractorInterface, ContractorModel} from './contractor.model';

export interface IndividualContractorDataEntryInterface {
  contractor: ContractorInterface;
  surname: string;
  name: string;
  patronymic: string;
  passportNumber: string;
  passportSerial: string;
  snils: string;
  inn: string;
  passportDate: string;
  passportDivision: string;
  passportDivisionCode: string;
  isRunLph: boolean;
}

export class IndividualContractorDataEntryModel implements IndividualContractorDataEntryInterface {
  contractor: ContractorModel;
  surname: string;
  name: string;
  patronymic: string;
  passportNumber: string;
  passportSerial: string;
  snils: string;
  inn: string;
  passportDate: string;
  passportDivision: string;
  passportDivisionCode: string;
  isRunLph: boolean;

  constructor(o?: IndividualContractorDataEntryInterface) {
    constructByInterface(o, this);
  }
}
