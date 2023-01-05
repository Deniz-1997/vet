import {constructByInterface} from '../../utils/construct-by-interface';
import {ApiQueueModel} from './api-queue.model';

export interface ApiQueueRowInterface {
  id: number;
  status: any;
  updatedAt: string;
  externalId: string;
  data: any;
  createdAt: string;
  error: any;
  apiQueue: ApiQueueModel;
}

export class ApiQueueRowModel implements ApiQueueRowInterface {
  id: number;
  apiQueue: ApiQueueModel;
  status: any;
  updatedAt: string;
  externalId: string;
  data: any;
  createdAt: string;
  error: any;

  constructor(o?: ApiQueueRowInterface) {
    constructByInterface(o, this);
  }
}

