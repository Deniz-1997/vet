import { MapperError } from './errors';

export type MapperErrorCode = keyof typeof MapperError.messages;

export type MapperErrorOptions = {
  code: MapperErrorCode;
  options?: string | string[];
  property: string;
  name: string;
  path: string;
  data: string;
};
