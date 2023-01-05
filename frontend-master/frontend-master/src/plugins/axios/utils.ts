import { AxiosError, AxiosResponse } from 'axios';
import { TErrorHandlerList, THandler, THandlerList, THandlerType, TReduceFunction } from '@/plugins/axios/models';

type THandlerRunnerOptions = { ctx: Vue; error: AxiosError; list: TErrorHandlerList; responseStatus: number };

/** Утилиты для обработки перехватчиков. */
class AxiosUtils {
  private processInterceptor<T>(ctx: Vue) {
    return async (value: T, handler: THandler<T>): Promise<T> => await handler.call(ctx, value);
  }

  private async runHandlers({ ctx, error, list, responseStatus }: THandlerRunnerOptions, index = 0) {
    const handler = list[index];

    if (typeof handler === 'function') {
      const result = await handler.call(ctx, error);

      if (result) {
        return result;
      }
    } else {
      const { status, callback } = handler;
      const statuses = Array.isArray(status) ? status : [status];

      if (statuses.includes(responseStatus)) {
        const result = await callback.call(ctx, error);

        if (result) {
          return result;
        }
      }
    }

    if (list[index + 1]) {
      return this.runHandlers({ ctx, error, list, responseStatus }, index + 1);
    }
  }

  /** Стандартный перехватчик ошибок. */
  private errorInterceptor(handlers: TErrorHandlerList, ctx: Vue) {
    return async (error: AxiosError) => {
      if (this.isCancel(error)) {
        return null;
      }
      const responseStatus = error.response?.status || -1;
      if (error.config?.ignoreStatuses?.includes(responseStatus)) {
        throw error;
      }

      const result = await new Promise((resolve, reject) => {
        this.runHandlers({ error, list: handlers, ctx, responseStatus }).then(resolve, reject);
      });

      if (result) return result;

      throw error;
    };
  }

  /** Стандартный обработчик перехватчиков. */
  private commonInterceptor<T>(handlers: THandlerList<THandler<T>>, ctx: Vue) {
    return async (item: T) =>
      await (handlers.reduce as TReduceFunction<T>)(await this.processInterceptor<T>(ctx), item);
  }

  /** Создать стандартный обработчик. */
  createInterceptor<T>(handlers: THandlerList<THandler<T>>, ctx: Vue): (item: T) => Promise<T>;
  /** Создать обработчик ошибок. */
  createInterceptor<_T>(
    handlers: TErrorHandlerList,
    ctx: Vue
  ): (item: AxiosError) => Promise<AxiosResponse<any> | AxiosError>;
  createInterceptor<T>(handlers: TErrorHandlerList | THandlerList<THandler<T>>, ctx: Vue) {
    if (handlers.type === 'ERROR') {
      return this.errorInterceptor(handlers as TErrorHandlerList, ctx);
    }

    return this.commonInterceptor<T>(handlers as THandlerList<THandler<T>>, ctx);
  }

  /** Обертка хендлеров дял корректной передачи типизации. */
  wrapHandlers<T>(handlers: T[], type: THandlerType<T>): THandlerList<T> {
    const result: THandlerList<T> = [...handlers];

    result.type = type;

    return result;
  }

  /** Проверяет, выбросилась ли ошибка в следствие отмены запроса со стороны пользователя. */
  isCancel(error) {
    return error?.code === 'ECONNABORTED' || error?.__CANCEL__ || String(error).includes('cancelled');
  }
}

export default new AxiosUtils();
