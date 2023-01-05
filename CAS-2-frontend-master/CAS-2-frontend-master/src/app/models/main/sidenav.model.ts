import {constructByInterface} from '../../utils/construct-by-interface';
import {ReferenceButtonSettings} from '../reference/button.settings';

export interface MainSidenavInterface {
  id?: number;
  name: string;
  items: Array<MainSidenavModel>;
  url: string;
  description: string;
  state: boolean;
  buttonSettings: ReferenceButtonSettings;
  sort: number;
  additionalActions: Array<MainSidenavModel>;
  type: {
    code: string;
    title: string;
  };
}

export class MainSidenavModel implements MainSidenavInterface {
  id: number;
  name: string;
  items: Array<MainSidenavModel>;
  url: string;
  description: string;
  state: boolean;
  buttonSettings: ReferenceButtonSettings;
  sort: number;
  additionalActions: Array<MainSidenavModel>;
  type: {
    code: string;
    title: string;
  };

  constructor(o?: MainSidenavInterface) {
    constructByInterface(o, this);
  }
}
