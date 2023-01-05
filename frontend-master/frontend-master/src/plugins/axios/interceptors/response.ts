import Utils from '@/plugins/axios/utils';
import { TResponseHandler } from '@/plugins/axios/models';
import { IFilterableList } from '@/services/models/common';
import { Filter } from '@/services/mappers/common';

const proxyFilter: TResponseHandler<IFilterableList<any>> = function (data) {
  if (data.data.content && data.data.totalElements) {
    data.filter = new Filter(data.data);
  }

  return data;
};

export default Utils.wrapHandlers([proxyFilter], 'RESPONSE');
