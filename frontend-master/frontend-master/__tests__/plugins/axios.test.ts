import { checkPluginRegistration } from './__utils__/checkPluginRegistration';
import plugin from '@/plugins/axios';
import utils from '@/plugins/axios/utils';
import { noop } from 'lodash';

const createError = (status) => {
  return {
    response: { status },
    request: {},
    config: {},
  };
};

describe('plugins axios.ts', () => {
  test('registrates plugin', async () => {
    const actual = await checkPluginRegistration(plugin);

    expect(actual).toHaveProperty('$axios');
  });

  describe('registrates interceptors', () => {
    test('wrapHandlers', () => {
      const check = (list, type) => {
        const actual = utils.wrapHandlers(list, type);

        expect(actual).toHaveLength(list.length);
        expect(actual.type).toBe(type);
      };

      check([1, 2, 3], 'REQUEST');
      check([0, 0, 0], 'RESPONSE');
      check([12, 154, 'foo'], 'ERROR');
    });

    test('createInterceptor', () => {
      const mockCommon = jest.spyOn(utils, 'commonInterceptor' as any);
      const mockError = jest.spyOn(utils, 'errorInterceptor' as any);

      const list: any = [];
      utils.createInterceptor(list, {} as any);

      expect(mockError).not.toBeCalled();
      expect(mockCommon).toBeCalled();
      list.type = 'ERROR';

      utils.createInterceptor(list, {} as any);

      expect(mockError).toBeCalled();
      expect(mockCommon).toBeCalledTimes(1);
    });

    test('processInterceptor', async () => {
      const actual = (utils as any).processInterceptor({} as any);

      expect(typeof actual).toBe('function');

      const mock = jest.fn(() => Promise.resolve(true));
      const result1 = actual(123, mock);
      expect(mock).toBeCalledWith(123);
      expect(result1).toBeInstanceOf(Promise);

      const result2 = actual(234, mock);
      expect(mock).toBeCalledWith(234);
      expect(result2).toBeInstanceOf(Promise);

      const result3 = actual(456, mock);
      expect(mock).toBeCalledWith(456);
      expect(result3).toBeInstanceOf(Promise);
      expect(await result3).toBe(true);
    });

    test('commonInterceptor', () => {
      const list = [1, 2, 3, 4, 5, 6];
      const mock = jest.spyOn(utils, 'processInterceptor' as any).mockImplementation(() => noop);
      const actual = (utils as any).commonInterceptor(list, {} as any);

      expect(typeof actual).toBe('function');

      actual('test');
      expect(mock).toBeCalledTimes(1);
      actual('foo');
      expect(mock).toBeCalledTimes(2);
      actual('bar');
      expect(mock).toBeCalledTimes(3);
    });

    test('errorInterceptor stops on return', async () => {
      const list = [jest.fn(() => Promise.resolve(true)), jest.fn(() => Promise.resolve(true))];

      const actual = await (utils as any).errorInterceptor(list, {} as any);
      expect(typeof actual).toBe('function');

      const errors = [0, 200].map(createError);

      actual(errors[0]);
      expect(list[0]).toBeCalledWith(errors[0]);
      expect(list[1]).not.toBeCalled();

      actual(errors[1]);
      expect(list[0]).toBeCalledWith(errors[1]);
      expect(list[1]).not.toBeCalled();
    });

    test('errorInterceptor stops on reject', async () => {
      const list = [
        jest.fn(() => Promise.resolve(null)),
        {
          status: 200,
          callback: jest.fn(() => Promise.reject('test')),
        },
        jest.fn(() => Promise.resolve('bar')),
      ];

      const actual = await (utils as any).errorInterceptor(list, {} as any);

      const errors = [200].map(createError);

      actual(errors[0])
        .then(() => {
          expect(true).toBe(false);
        })
        .catch((value) => {
          expect(list[0]).toBeCalled();
          expect((list[1] as any).callback).toBeCalled();
          expect(value).toBe('test');
        });
    });

    test('errorInterceptor throws on no return', async () => {
      const list = [
        jest.fn(() => Promise.resolve(null)),
        {
          status: 200,
          callback: jest.fn(() => Promise.resolve(null)),
        },
        jest.fn(() => Promise.resolve(null)),
      ];

      const actual = await (utils as any).errorInterceptor(list, {} as any);

      const errors = [200].map(createError);

      actual(errors[0])
        .then(() => {
          expect(true).toBe(false);
        })
        .catch((value) => {
          expect(list[0]).toBeCalled();
          expect((list[1] as any).callback).toBeCalled();
          expect(list[2]).toBeCalled();
          expect(value).toBe(errors[0]);
        });
    });

    test('isCancel', async () => {
      const actual1 = utils.isCancel({});
      expect(actual1).toBe(false);

      const actual2 = utils.isCancel({ code: 'ECONNABORTED' });
      expect(actual2).toBe(true);

      const actual3 = utils.isCancel({ __CANCEL__: true });
      expect(actual3).toBe(true);

      const actual4 = utils.isCancel('cancelled');
      expect(actual4).toBe(true);
    });
  });
});
