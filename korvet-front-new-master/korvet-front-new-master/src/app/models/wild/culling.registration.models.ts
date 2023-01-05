import {constructByInterface} from '../../utils/construct-by-interface';

export interface CullingRegistrationInterface {
  id: number;
  chipNumber: string;

}

export class CullingRegistrationModel {
  id: number;
  chipNumber: string;
  sterilizationUser: string;


  constructor(o?: CullingRegistrationInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
