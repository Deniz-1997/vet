import {constructByInterface} from '../../utils/construct-by-interface';
import {TemplateReferenceModel} from './template-reference.models';

export interface TemplateReferenceValuesInterface {
  id: number;
  templateReference: TemplateReferenceModel;
  value: any;
  deleted: boolean;
}

export class TemplateReferenceValuesModel {
  id: number;
  templateReference: TemplateReferenceModel;
  value: any;
  deleted: boolean;

  constructor(o?: TemplateReferenceValuesInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
