import {constructByInterface} from '../../utils/construct-by-interface';
import {ElementDefInterface} from '../element.def.models';
import {ReferenceBreedInterface} from '../reference/reference.breed.models';

export interface WildAnimalIngInterface {
  id: number;
  name: string;
  photoType: {
    title: string,
    code: string,
  };
}

export interface WildAnimalInterface {
  id: number;
  chipNumber: string;
  type: ElementDefInterface;
  breed: ReferenceBreedInterface;
  gender: string;

  isSterilized: boolean | null;
  description: string;

  dateOfDeath: string;
  causeOfDeath: string;

  lastVaccinationDate: string;
  sterilizationDate: string;

  cullingRegistrationHistory: any[];
  wildAnimalFiles: WildAnimalIngInterface[];

  animalNumber: string;

  birthday: string;
  aggressive: boolean;

}

export class WildAnimalModel {
  id: number;
  chipNumber: string;
  type: ElementDefInterface;
  breed: ReferenceBreedInterface;
  gender: string;

  isSterilized: boolean | null;
  description: string;

  dateOfDeath: string;
  causeOfDeath: string;
  lastVaccinationDate: string;
  sterilizationDate: string;

  cullingRegistrationHistory: any[];
  wildAnimalFiles: WildAnimalIngInterface[];

  animalNumber: string;

  birthday: string;

  constructor(o?: WildAnimalInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
