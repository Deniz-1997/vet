import {constructByInterface} from '../../utils/construct-by-interface';

export interface TemplateReferenceInterface {
  id: number;
  name: string;
  deleted: boolean;
}

export class TemplateReferenceModel {
  'id': number;
  'name': string;
  'deleted': boolean;

  constructor(o?: TemplateReferenceInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
