import {ElementDefInterface} from '../element.def.models';
import {constructByInterface} from '../../utils/construct-by-interface';

export interface UsersSyncInterface extends ElementDefInterface {
  status: boolean;
}

export class UsersSyncModels implements UsersSyncInterface {
  status: boolean;

  constructor(o: UsersSyncInterface) {
    constructByInterface(o, this);
  }
}
