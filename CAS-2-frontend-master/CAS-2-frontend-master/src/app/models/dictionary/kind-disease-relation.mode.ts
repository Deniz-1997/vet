import {constructByInterface} from '../../utils/construct-by-interface';
import {ActionDataInterface, ActionDataModel} from './action-data.model';
import {DiseaseInterface} from './disease.model';
import {KindInterface} from './kind.model';

export interface KindDiseaseRelationInterface {
  id: string;
  animalKind: KindInterface;
  disease: DiseaseInterface;
  action: Array<ActionDataInterface>;
}

export class KindDiseaseRelationModel implements KindDiseaseRelationInterface {
  id: string;
  animalKind: KindInterface;
  disease: DiseaseInterface;
  action: Array<ActionDataModel>;

  constructor(o?: KindDiseaseRelationInterface) {
    constructByInterface(o, this);
  }
}
