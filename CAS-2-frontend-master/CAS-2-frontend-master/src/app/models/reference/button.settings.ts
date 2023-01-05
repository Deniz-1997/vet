import {constructByInterface} from '../../utils/construct-by-interface';
import {ReferenceIconModel} from './icon.model';

export interface ReferenceButtonSettingsInterface {
  color: string;
  backgroundColor: string;
  icon: ReferenceIconModel | null;
}

export class ReferenceButtonSettings implements ReferenceButtonSettingsInterface {
  color: string;
  backgroundColor: string;
  icon: ReferenceIconModel | null;

  constructor(o?: ReferenceButtonSettingsInterface) {
    constructByInterface(o, this);
  }
}
