import isEmpty from 'lodash/isEmpty';
import isPlainObject from 'lodash/isPlainObject';
import isNaN from 'lodash/isNaN';
import isNull from 'lodash/isNull';
import isUndefined from 'lodash/isUndefined';
import memoize from 'lodash/memoize';
import moment from 'moment';
import { ValidatorException } from './errors';
import { MapperErrorCode } from './models';

/** Валидатор и форматтер ожидаемого значения маппинга. */
export class Value<T, P, C = P | undefined> {
  /** Промежуточный результат валидаций и форматирования. */
  private result!: C;
  /** Вычисленный путь до заданного параметра. Для отображения в ошибке. */
  private path!: string;

  constructor(value: T, path: (value: T) => P, defaultValue?: P) {
    const getter = memoize(path);
    // Сохраняем ожидаемый путь к значению.
    this.definePath(getter);

    // Пытаемся безопасно получить значение.
    try {
      const result = getter(value);
      this.result = (isUndefined(result) ? defaultValue : result) as unknown as C;
    } catch (error) {
      if (error instanceof ValidatorException) {
        throw error;
      }
      this.result = defaultValue as unknown as C;
    }
  }

  /** Определяет наличие заданных значений у промежуточного результата. */
  private get isHaveNoProperties() {
    if (!isPlainObject(this.result)) {
      return isNull(this.result);
    }

    return isEmpty(this.result) || Object.keys(this.result).every((key) => isUndefined(this.result[key]));
  }

  /** Возвращает значение по переданным правилам. */
  public get value() {
    return this.result;
  }

  /** Валидирует обязательность переданного значения. */
  public get required(): Value<T, P, C extends undefined ? never : C> {
    if (isUndefined(this.result) || this.isHaveNoProperties) {
      this.throwException('required');
    }

    return this as unknown as Value<T, P, C extends undefined ? never : C>;
  }

  /** Задает значение опциональным. Если все данные в значении не определены, возвращает undefined. */
  public get optional(): Value<T, P, C | undefined> {
    this.result = (this.isHaveNoProperties ? undefined : this.result) as any;

    return this;
  }

  /** Валидирует формат промежуточного значения по переданному регулярному выражению. */
  public format(pattern: string): Value<T, P, string> {
    if (!isUndefined(this.result) && !new RegExp(pattern, 'gi').test(this.result as unknown as string)) {
      this.throwException('format', pattern);
    }

    return this as unknown as Value<T, P, string>;
  }

  /** Валидирует промежуточное выражение как дату и конвертирует её в ISO String. */
  public date(format = 'DD.MM.YYYY hh:mm'): Value<T, P, string> {
    if (isUndefined(this.result)) {
      return this as unknown as Value<T, P, string>;
    }

    const date = moment(this.result, format);
    if (!date.isValid()) {
      this.throwException('date', format);
    }

    this.result = date.toISOString() as any;
    return this as unknown as Value<T, P, string>;
  }

  /** Валидирует, что значение совпадает с одним из элементов переданного списка. */
  public in(list: string[]): Value<T, P, string> {
    if (!isUndefined(this.result) && !list.includes(this.result as any)) {
      this.throwException('in', list);
    }

    return this as unknown as Value<T, P, string>;
  }

  /** Вычисляет путь, по которому ожидается получение значения. */
  private definePath = memoize((getter) => {
    const pathParts: string[] = [];
    try {
      const proxy = new Proxy(
        {},
        {
          get(_, property: any) {
            pathParts.push(String(property));
            return proxy;
          },
        }
      );
      getter(proxy);
    } catch (_) {
      // do nothing.
    }
    this.path = pathParts.length
      ? pathParts.reduce((prev, next) => {
          const first = !isNaN(Number(prev)) ? `[${prev}]` : prev;
          const last = !isNaN(Number(next)) ? `[${next}]` : next;
          const delimeter = last.startsWith('[') ? '' : '.';

          return [first, last].join(delimeter);
        })
      : '';
  });

  /** Выбрасывает исключение при ошибках валидации. */
  private throwException(code: MapperErrorCode, options?: string | string[]) {
    throw new ValidatorException(code as any, this.path, options as any);
  }
}
