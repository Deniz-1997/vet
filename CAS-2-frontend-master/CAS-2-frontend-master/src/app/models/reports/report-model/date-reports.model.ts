import {constructByInterface} from '../../../utils/construct-by-interface';
import {ReferenceBusinessEntityInterface, ReferenceBusinessEntityModel} from '../../reference/reference.businessEntity.models';
import {ReferenceSupervisedObjectInterface, ReferenceSupervisedObjectModel} from '../../reference/reference.supervisedObects.models';
import {ReportsListModel} from './reports-list.model';

export interface DataReportsInterface {
  id: number;
  reports: ReportsListModel;
  stationId: string;
  statusId: {
    code: string,
    title: string
  };
  year: number;
  quarter: number;
  month: number;
  data: string;
  businessEntity: ReferenceBusinessEntityInterface;
  supervisedObjects: ReferenceSupervisedObjectInterface;
}

export class DataReportsModel implements DataReportsInterface {
  id: number;
  reports: ReportsListModel;
  stationId: string;
  statusId: {
    code: string,
    title: string
  };
  year: number;
  quarter: number;
  month: number;
  data: string;
  businessEntity: ReferenceBusinessEntityModel;
  supervisedObjects: ReferenceSupervisedObjectModel;

  constructor(o?: DataReportsInterface) {
    constructByInterface(o, this);
  }
}
