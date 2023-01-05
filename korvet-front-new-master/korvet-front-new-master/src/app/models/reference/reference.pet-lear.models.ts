import {constructByInterface} from 'src/app/api/api-connector/api-connector.utils';
import {ReferenceBreedModel} from './reference.breed.models';

export class ReferencePetLearModel {
  id: number;
  name: string;
  breed: ReferenceBreedModel;
  deleted: boolean;

  constructor(o: ReferencePetLearModel) {
    constructByInterface(o, this, {breed: ReferenceBreedModel});
  }
}
