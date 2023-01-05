import {constructByInterface} from '../../utils/construct-by-interface';
import {ContractorInterface, ContractorModel} from './contractor.model';

export interface LegalContractorDataEntryInterface {
  contractor: ContractorInterface;
  fullName: string;
  shortName: string;
  ogrn: string;
  inn: string;
  kpp: string;
  website: string;
  registrationDate: string;
  liquidationDate: string;

}

export class LegalContractorDataEntryModel implements LegalContractorDataEntryInterface {
  contractor: ContractorInterface;
  fullName: string;
  shortName: string;
  ogrn: string;
  inn: string;
  kpp: string;
  website: string;
  registrationDate: string;
  liquidationDate: string;

  constructor(o?: LegalContractorDataEntryInterface) {
    constructByInterface(o, this);
  }
}
