import {constructByInterface} from '../../utils/construct-by-interface';

export interface SupportMessageInterface {
  message: string;
  filePath: string;
  url: string;
}

export class SupportMessageModel implements SupportMessageInterface {
  message: string;
  filePath: string;
  url: string;

  constructor(o?: SupportMessageInterface) {
    constructByInterface(o, this);
  }
}
