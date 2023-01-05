import { HttpParams } from '@angular/common/http';
import { isString } from 'util';
import * as _ from 'lodash';

export enum DataType {
  json = 'json',
  formData = 'formData',
  httpParams = 'httpParams',
}

export function constructByInterface<T>(o: T, thisRef: any, types: {[key: string]: new(...args: Array<any>) => any} = {}): void {
  if (o) {
    Object.keys(o).forEach(key => {
      if (types[key]) {
        if (o[key] instanceof Array) {
          thisRef[key] = o[key].map(item => new types[key](item));
        } else {
          if (o[key]) {
            thisRef[key] = new types[key](o[key]);
          }
        }
      } else {
        thisRef[key] = o[key];
      }
    });
  }
}

export function prepareHttpParams(params: Object): HttpParams {
  return Object.keys(params).reduce((acc, key) => params[key] !== null ? acc.append(key, params[key]) : acc, new HttpParams());
}

export function prepareFormData(data: Object): FormData {
  const formData = new FormData();
  Object.keys(data).forEach(key => data[key] !== null && formData.append(key, data[key]));
  return formData;
}

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

export function arrayToKeys(array: Array<any>, key: string, model: any = null): object {
  const result = {};
  array.forEach(el => result[el[key]] = model ? new model(el) : el);
  return result;
}

export function stringToObject(str: string, separator: string = '.'): Object|string {
  return str.split('.').reduceRight((acc, v) => ({[v]: acc}) as any);
}

export function objectToStrings(obj: Object|string, separator: string = '.'): Array<string> {
  const result: Array<string> = [];
  objectToString(obj, result);
  return result;
}

function objectToString(obj: Object, result: Array<string>, str: string = ''): void {
  Object.keys(obj).forEach(key => {
    const current = str + (str.length > 0 ? '.' : '') + key;
    if (isString(obj[key])) {
      result.push(current + '.' + obj[key]);
    } else {
      objectToString(obj[key], result, current);
    }
  });
}

export function clearNulls(o: any): void {
  Object.keys(o).forEach(key =>
    (_.isNil(o[key]) || _.isNaN(o[key]) || o[key] === '')
    && delete o[key]);
}

export function clearNullsDeep(o: any): void {
  Object.keys(o).forEach(key => {
    if (o[key] instanceof Object) {
      if (o[key] instanceof Array && o[key].length === 0) {
        delete o[key];
      } else {
        clearNullsDeep(o[key]);
        if (Object.keys(o[key]).length === 0) {
          delete o[key];
        }
      }
    } else if (_.isNaN(o[key]) || o[key] === '') {
      delete o[key];
    } else if (o[key] === 'isNull') {
      o[key] = null;
    }
  });
}
