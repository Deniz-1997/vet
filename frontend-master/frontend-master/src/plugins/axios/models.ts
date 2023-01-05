import { AxiosError, AxiosRequestConfig, AxiosResponse } from 'axios';

export type THandler<T> = (this: Vue, item: T) => T | Promise<T>;
export type TRequestHandler = THandler<AxiosRequestConfig>;
export type TResponseHandler<T> = THandler<AxiosResponse<T>>;
type TErrorHandlerFunction = (this: Vue, item: AxiosError) => void | Promise<any>;
type TErrorHandlerObject = {
  status: number | number[];
  callback: TErrorHandlerFunction;
};
export type TErrorHandler = TErrorHandlerFunction | TErrorHandlerObject;
export type THandlerType<T> = T extends TErrorHandler ? 'ERROR' : T extends TRequestHandler ? 'REQUEST' : 'RESPONSE';
export type THandlerList<T> = T[] & {
  type?: THandlerType<T>;
};

export type TErrorHandlerList = THandlerList<TErrorHandler>;
export type TResponseHandlerList = THandlerList<TResponseHandler<any>>;
export type TRequestHandlerList = THandlerList<TRequestHandler>;

export type TReduceFunction<T> = (callback: (item: T, handler: THandler<T>) => Promise<T>, initial: T) => Promise<T>;

export type TAuthorizationConfig = {
  config: {
    refreshTokens?: true;
  };
};
