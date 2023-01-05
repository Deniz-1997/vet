import { ValidatorException } from '@/utils/global/mapper/errors';
import memoize from 'lodash/memoize';
import { catchError } from './errors';
import { Value } from './Value';

/** Базовый класс реализации маппингов. */
export abstract class Mapper<T> {
  /** Декоратор отлова ошибок. */
  static catch = catchError;
  /** Статический контекст вью. Задаётся при подключении корня приложения. */
  static $ctx: Vue;

  constructor(protected value: T) {}

  /** контекст приложения. */
  get $ctx() {
    return Mapper.$ctx;
  }

  /** Получить валидатор по методу безопасного получения значения. */
  protected get = memoize(<R>(path: (value: T) => R) => {
    return new Value(this.value, path);
  });

  /** Получить валидатор для обязательного значения. */
  protected required = memoize(<R>(value: R) => {
    return new Value({ value }, ({ value }) => value).required.value;
  });

  /** Получить валидатор для опционального значения. */
  protected optional = memoize(<R>(value: () => R) => {
    function callback() {
      try {
        return value();
      } catch (err) {
        if (err instanceof ValidatorException && err.code === 'required') {
          return undefined;
        }

        throw err;
      }
    }

    return new Value({ value }, callback).optional.value;
  });

  abstract toJSON(): Record<string, any>;
}
