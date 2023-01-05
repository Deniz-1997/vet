import {constructByInterface} from '../api-connector/api-connector.utils';

export class ApiMenuReferenceButtonSettingsModel {
  color;
  backgroundColor;
  icon;
  constructor(o: ApiMenuReferenceButtonSettingsModel) {
    constructByInterface(o, this);
  }
}

export interface ApiMenuInterface {
  id: number;
  name: string;
  items: any;
  url: string;
  description: string;
  state: any;
  buttonSettings: ApiMenuReferenceButtonSettingsModel;
  sort: number;
  additionalActions: any;
  type: any;
}

export class ApiMenuModel implements ApiMenuInterface {
  id: number;
  name: string;
  items;
  url: string;
  description: string;
  state;
  buttonSettings;
  sort: number;
  additionalActions;
  type;

  constructor(o: ApiMenuModel) {
    constructByInterface(o, this);
  }
}
