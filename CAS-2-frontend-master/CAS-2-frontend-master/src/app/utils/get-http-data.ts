import {DataType} from '../common/data-type';
import {prepareFormData} from './prepare-form-data';
import {prepareHttpParams} from './prepare-http-params';

export function getHttpData(data: Object, dataType: DataType): any {
  switch (dataType) {
    case DataType.formData:
      return prepareFormData(data);
    case DataType.httpParams:
      return prepareHttpParams(data);
    case DataType.json:
    default:
      return data;
  }
}
