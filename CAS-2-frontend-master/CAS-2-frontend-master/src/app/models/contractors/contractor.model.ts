import {constructByInterface} from '../../utils/construct-by-interface';
import {ContactInterface, ContactModel} from './contact.model';
import {IndividualContractorDataEntryInterface, IndividualContractorDataEntryModel} from './individual-contractor-data-entry.model';
import {IndividualEntrepreneurDataEntryInterface, IndividualEntrepreneurDataEntryModel} from './individual-entrepreneur-data-entry.model';
import {LegalContractorDataEntryInterface, LegalContractorDataEntryModel} from './legal-contractor-data-entry.model';

export interface ContractorInterface {
  id: string;
  value: string;
  comment: string;
  name: string;
  individualDataEntry: IndividualContractorDataEntryInterface;
  individualEntrepreneurDataEntry: IndividualEntrepreneurDataEntryInterface;
  legalDataEntry: LegalContractorDataEntryInterface;
  contacts: Array<ContactInterface>;
}

export class ContractorModel implements ContractorInterface {
  id: string;
  value: string;
  comment: string;
  name: string;
  individualDataEntry: IndividualContractorDataEntryModel;
  individualEntrepreneurDataEntry: IndividualEntrepreneurDataEntryModel;
  legalDataEntry: LegalContractorDataEntryModel;
  contacts: Array<ContactModel>;

  constructor(o?: ContractorInterface) {
    constructByInterface(o, this);
  }
}
