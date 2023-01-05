import { catchError, MapperError, ValidatorException } from '@/utils/global/mapper/errors';
import { Mapper } from '@/utils';
import { Value } from '@/utils/global/mapper/Value';
import { config } from 'yargs';

describe('mapper', () => {
  test('Mapper', () => {
    class Test extends Mapper<any> {
      toJSON() {
        return {};
      }
    }
    const actual = new Test({});

    expect(actual).toHaveProperty('$ctx');
    expect(actual).toHaveProperty('toJSON');
    expect((actual as any).get((a: any = {}) => a.b)).toBeInstanceOf(Value);
    expect((actual as any).required({ foo: 'bar' })).toEqual({ foo: 'bar' });
    expect((actual as any).optional({})).toBeUndefined();
  });

  test('Value', () => {
    const actual1: any = new Value({ foo: 'bar' }, (item) => item.foo);

    expect(actual1.result).toBe('bar');
    expect(actual1.path).toBe('foo');
    expect(actual1.isHaveNoProperties).toBe(false);
    expect(actual1.value).toBe('bar');
    expect(actual1.required).toBeInstanceOf(Value);
    expect(actual1.optional).toBeInstanceOf(Value);
    expect(actual1.format('bar')).toBeInstanceOf(Value);
    expect(actual1.in(['bar'])).toBeInstanceOf(Value);

    const actual2: any = new Value({ foo: {} }, (item) => item.foo);

    expect(actual2.result).toEqual({});
    expect(actual2.path).toBe('foo');
    expect(actual2.isHaveNoProperties).toBe(true);
    expect(actual2.value).toEqual({});
    try {
      expect(actual2.required).toBeInstanceOf(Value);
    } catch (error) {
      expect(error).toBeInstanceOf(ValidatorException);
    }

    const actual3: any = new Value({ foo: {} }, (item) => item.foo);
    expect(actual3.optional).toBeInstanceOf(Value);
    expect(actual3.value).toBeUndefined();
    try {
      expect(actual3.date()).toBeInstanceOf(Value);
    } catch (error) {
      expect(error).toBeInstanceOf(ValidatorException);
    }

    const actual4: any = new Value({ foo: '12.12.2012 12:12' }, (item) => item.foo);
    expect(actual4.date()).toBeInstanceOf(Value);
    expect(actual3.value).toBeUndefined();

    const actual5: any = new Value({ foo: '12.12.2012 12:12' }, (item) => item.foo);
    try {
      expect(actual5.throwException()).toBeInstanceOf(ValidatorException);
    } catch (error) {
      expect(error).toBeInstanceOf(ValidatorException);
    }

    const actual6: any = new Value({ foo: { bar: '12.12.2012 12:12' } }, (item) => item.foo.bar);
    expect(actual6.path).toBe('foo.bar');
  });

  test('catchError', () => {
    const catchFunc = catchError();

    const target = {
      $ctx: {
        $service: {
          notify: {
            push: jest.fn(),
          },
        },
      },
    };

    const property = 'test';
    const descriptor = (Error: any = ValidatorException) => ({
      get() {
        throw new Error('format');
      },
    });

    try {
      catchFunc(target as any, property, descriptor());
    } catch (err) {
      expect(err).toBeInstanceOf(ValidatorException);
      expect(target.$ctx.$service.notify.push).toBeCalled();
    }

    try {
      catchFunc(target as any, property, descriptor(Error as any));
    } catch (err) {
      expect(err).toBeInstanceOf(Error);
      expect(target.$ctx.$service.notify.push).toBeCalledTimes(1);
    }

    const sortedCatchFunc = catchError('date');

    try {
      sortedCatchFunc(target as any, property, descriptor());
    } catch (err) {
      expect(err).toBeInstanceOf(ValidatorException);
      expect(target.$ctx.$service.notify.push).toBeCalledTimes(1);
    }
  });

  test('ValidatorException', () => {
    const error = new ValidatorException('date', 'some.path', 'format');
    expect(error).toBeInstanceOf(Error);
    expect(error.code).toBe('date');
    expect(error.path).toBe('some.path');
    expect(error.options).toBe('format');
  });

  test('MapperError', () => {
    const config: any = {
      code: 'required',
      options: 'string',
      property: 'string',
      name: 'string',
      path: 'string',
      data: 'string',
    };
    const error = new MapperError(config);
    expect(error).toBeInstanceOf(Error);
    expect(new MapperError({ ...config, code: 'date' }).message).toBe(
      '(string) Property "string" contains invalid date or date in unexpected format. Expected format is string by path `string`.'
    );
    expect(new MapperError({ ...config, code: 'format' }).message).toBe(
      '(string) Property "string" has invalid format. Expected format is "string" by path `string`.'
    );
    expect(new MapperError({ ...config, code: 'required' }).message).toBe(
      '(string) Property "string" has not found by path `string`.'
    );
    expect(new MapperError({ ...config, code: 'in' }).message).toBe(
      '(string) Property "string" must be part of string by path `string`.'
    );
  });
});
