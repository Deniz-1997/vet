import memoize from 'lodash/memoize';
import { createLogger, Mapper, serialize } from '@/utils';
import { MapperErrorCode, MapperErrorOptions } from './models';

/** Ошибка валидации маппинга. */
export class ValidatorException extends Error {
  constructor(code: 'required', path: string);
  constructor(code: 'format', path: string, options?: string);
  constructor(code: 'date', path: string, options: string);
  constructor(code: 'in', path: string, options: string[]);
  constructor(public readonly code: MapperErrorCode, public readonly path: string, public readonly options?: any) {
    super('Validation Error');
  }
}

/** Декоратор, отлавливающий ошибки валидации и направляющий их в центр нотификаций. */
export function catchError<T extends Mapper<any>>(...codes: MapperErrorCode[]) {
  return (target: T, property: string, descriptor: PropertyDescriptor) => {
    // Переопределяем аксессор.
    const method = descriptor.get;
    descriptor.get = function (...args) {
      try {
        return method?.apply(this, args);
      } catch (err) {
        // Если это ошибка валидации, отображаем блокирующее модальное окно.
        if (err instanceof ValidatorException) {
          const data = err as unknown as ValidatorException;
          if (!codes.length || codes.includes(data.code)) {
            const error = new MapperError({
              ...data,
              name: (this as any).constructor.name,
              property,
              data: serialize({}),
            });
            target.$ctx.$service.notify.push('error', { text: error.message, params: { error } });
          }

          return;
        }

        throw err;
      }
    };
  };
}

/** Ошибка маппинга. */
export class MapperError extends Error {
  static messages = {
    required: memoize((property: string) => `Property "${property}" has not found`),
    format: memoize(
      (property: string, options?: string) =>
        `Property "${property}" has invalid format.${options ? ` Expected format is "${options}"` : ''}`
    ),
    date: memoize(
      (property: string, options: string) =>
        `Property "${property}" contains invalid date or date in unexpected format. Expected format is ${options}`
    ),
    in: memoize(
      (property: string, options: string[]) => `Property "${property}" must be part of ${options.toLocaleString()}`
    ),
  };

  constructor({ code, options, name, property, data, path }: MapperErrorOptions) {
    const logger = createLogger({ prefix: 'mapper', key: 'mapper' });
    const message: string[] = [`(${name})`, MapperError.messages[code](property, options as any)];

    if (path) {
      message.push(`by path \`${path}\`.`);
    }

    super(message.join(' '));
    logger.error(message.join(' '), data);
  }
}
