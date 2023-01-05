import {constructByInterface} from '../../utils/construct-by-interface';

export interface ContactInterface {
  id: string;
  value: string;
  comment: string;
}

export class ContactModel implements ContactInterface {
  id: string;
  value: string;
  comment: string;

  constructor(o?: ContactInterface) {
    constructByInterface(o, this);
  }
}
