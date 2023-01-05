import { SocketDataType } from 'src/app/common/socket-type';
import {constructByInterface} from '../../api/api-connector/api-connector.utils';

export interface SocketDataInterface {
  type: SocketDataType;
  data: string;
}

export class SocketDataModel {
  type: SocketDataType;
  data: string;

  constructor(o?: SocketDataInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
