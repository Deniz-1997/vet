import {constructByInterface} from '../../utils/construct-by-interface';
import {IconTypeEnum} from '../../modules/shared/components/icon/enum/icon-type.enum';

export interface ButtonIconInterface {
  type?: IconTypeEnum;
  name: string;
}

export class ButtonIconModel implements ButtonIconInterface {
  type?: IconTypeEnum = 0;
  name: string;

  constructor(o?: ButtonIconInterface) {
    constructByInterface(o, this);
  }
}
