import {constructByInterface} from '../../utils/construct-by-interface';
import {UserModels} from '../user/user.models';
import {ReferenceStationModel} from '../reference/reference.station.models';
import {ReferenceBusinessEntityInterface, ReferenceBusinessEntityModel} from '../reference/reference.businessEntity.models';
import {ApiQueueRowInterface, ApiQueueRowModel} from './api-queue-row.model';
import {ReferenceSubdivisionInterface, ReferenceSubdivisionModel} from '../reference/reference.subdivision.models';
import {ReferenceSupervisedObjectInterface, ReferenceSupervisedObjectModel} from '../reference/reference.supervisedObects.models';

export interface ApiQueueInterface {
  id: number;
  name: string;
  createdAt: string;
  updatedAt: string;
  externalId: string;
  businessEntity: ReferenceBusinessEntityInterface;
  station: ReferenceStationModel;
  user: UserModels;
  rows: Array<ApiQueueRowInterface>;
  error: any;
  subdivision: ReferenceSubdivisionModel;
  supervisedObject: ReferenceSupervisedObjectInterface;
}

export class ApiQueueModel implements ApiQueueInterface {
  id: number;
  name: string;
  createdAt: string;
  updatedAt: string;
  externalId: string;
  businessEntity: ReferenceBusinessEntityModel;
  station: ReferenceStationModel;
  user: UserModels;
  rows: Array<ApiQueueRowModel> = [];
  error: any;
  subdivision: ReferenceSubdivisionModel;
  supervisedObject: ReferenceSupervisedObjectModel;

  constructor(o?: ApiQueueInterface) {
    constructByInterface(o, this);
  }
}

