import {constructByInterface} from '../../utils/construct-by-interface';

export interface WildAnimalFilesInterface {
  uploadedFile: {
    id: number,
    name: string,
  };
  wildAnimal: {
    id: number;
  };
  photoType: {
    code: string;
    title: string;
  };
}

export class WildAnimalFilesModel implements WildAnimalFilesInterface {
  uploadedFile: {
    id: number,
    name: string,
  };
  wildAnimal: {
    id: number;
  };
  photoType: {
    code: string;
    title: string;
  };

  constructor(o?: WildAnimalFilesInterface) {
    constructByInterface(o, this);
  }
}
