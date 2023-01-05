import {constructByInterface} from '../../utils/construct-by-interface';

export interface FileMonitoredObjectInterface {
  id: number;
  name: string;
  deleted: boolean;
}

export class FileMonitoredObjectModel {
  'id': number;
  'name': string;
  'deleted': boolean;

  constructor(o?: FileMonitoredObjectInterface) {
    constructByInterface(o, this);
  }
}
