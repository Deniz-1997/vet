import {constructByInterface} from '../../utils/construct-by-interface';

export interface CullingRegistrationFileInterface {
  uploadedFile: {
    id: number,
    name: string,
  };
  cullingRegistration: {
    id: number;
  };
  photoType: {
    code: string;
    title: string;
  };
}

export class CullingRegistrationFileModel implements CullingRegistrationFileInterface {
  uploadedFile: {
    id: number,
    name: string,
  };
  cullingRegistration: {
    id: number;
  };
  photoType: {
    code: string;
    title: string;
  };

  constructor(o?: CullingRegistrationFileInterface) {
    constructByInterface(o, this);
  }
}
