import {ElementDefInterface} from '../element.def.models';
import {constructByInterface} from '../../utils/construct-by-interface';

export interface UserInterface extends ElementDefInterface {
  groups: any[];
  username: string;
  name?: string;
  surname: string;
  patronymic?: string;
  email: string;
  password: string;
  salt: string;
  confirmationChangePasswordCode: string;
  confirmationChangePasswordRecipient: string;
  confirmationChangePasswordCodeCreatedAt: string;
  additionalRestrictions: object;
  additionalFields: object;
  phoneNumber: string;
  status: boolean;
  mode_cashbox_mobile: boolean;
  cashbox_device_id: number | null;
}

export class UserModels implements UserInterface {
  id?: number | null;
  name?: string;
  surname: string;
  patronymic?: string;
  deleted?: boolean;
  groups: any[];
  username: string;
  email: string;
  password: string;
  salt: string;
  confirmationChangePasswordCode: string;
  confirmationChangePasswordRecipient: string;
  confirmationChangePasswordCodeCreatedAt: string;
  additionalRestrictions: object;
  additionalFields: object;
  phoneNumber: string;
  status: boolean;
  mode_cashbox_mobile: boolean;
  cashbox_device_id: number | null;

  constructor(o: UserInterface) {
    constructByInterface(o, this);
  }

  getFullName() {
    return ((this.surname || '') + ' ' + (this.name || '') + ' ' + (this.patronymic || '')).trim();
  }
}
