import {constructByInterface} from '../../utils/construct-by-interface';

export interface ReferenceReleaseFormInterface {
  id: number;
  name: string;
  deleted: boolean;
  sort: number;
}

export class ReferenceReleaseFormModel implements ReferenceReleaseFormInterface {
  id: number;
  name: string;
  deleted: boolean;
  sort: number;

  constructor(o?: ReferenceReleaseFormInterface) {
    constructByInterface(o, this);
  }
}
