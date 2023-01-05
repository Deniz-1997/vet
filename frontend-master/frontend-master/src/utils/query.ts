import forEach from 'lodash/forEach';

export interface QueryParams {
  [key: string]: string | number | boolean | BigInt | Array<number | string>;
}

export interface QueryParamsData {
  [key: string]: File | Blob | string;
}

export function query(queryParams: QueryParams): string {
  const params = new URLSearchParams();

  forEach(queryParams, (queryParam, queryParamName) => queryParam && params.append(queryParamName, queryParam.toString()));

  return params.toString();
}

export function queryData(queryParams: QueryParamsData): FormData {
  const formData = new FormData();

  forEach(queryParams, (queryParam, queryParamName) => formData.append(queryParamName, queryParam));

  return formData;
}

export function queryRepeatKey(key: string, values: number[]): string {
  const params = new URLSearchParams();

  forEach(values, (value) => params.append(key, value.toString()));

  return params.toString();
}
