import {constructByInterface} from '../../utils/construct-by-interface';
import {ActionKindInterface, ActionKindModel} from './action-kind.model';
import {KindDiseaseRelationInterface} from './kind-disease-relation.mode';

export interface ActionDataInterface {
  id: string;
  additionalName: string;
  kindDiseaseRelation: KindDiseaseRelationInterface;
  actionKind: ActionKindInterface;
}

export class ActionDataModel implements ActionDataInterface {
  id: string;
  additionalName: string;
  kindDiseaseRelation: KindDiseaseRelationInterface;
  actionKind: ActionKindModel;

  constructor(o?: ActionDataInterface) {
    constructByInterface(o, this);
  }
}
