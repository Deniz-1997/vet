import { Polling } from '@/utils';
import { noop } from 'lodash';

let timer: {
  start: jest.Mock;
  clear: jest.Mock;
};

beforeEach(() => {
  timer = {
    start: jest.fn((callback) => {
      callback();
      return 100;
    }),
    clear: jest.fn(),
  };
});

describe('polling util', () => {
  test.skip('creates short polling', () => {
    const callback = () => Promise.resolve();
    const polling = new Polling(
      'short',
      {
        id: 'test',
        delay: 30,
        callback,
      },
      false
    );

    timer.start = jest.fn(() => 100);
    (polling as any).$timer = timer;
    polling.run();

    expect(timer.start).toBeCalledTimes(1);
    expect(timer.start.mock.calls[0]).toContain(30000);

    polling.pause();

    expect(timer.clear).toBeCalledTimes(1);
    expect(timer.clear).toBeCalledWith(100);
  });

  test('short polling works', async () => {
    const callback = jest.fn(() => Promise.resolve(1));
    const polling = new Polling(
      'short',
      {
        id: 'test',
        delay: 30,
        callback,
      },
      false
    );

    const mockInit = jest.spyOn(polling as any, '$initShortPolling');
    timer.start = jest.fn().mockImplementationOnce(timer.start);
    (polling as any).$timer = timer;
    polling.run();

    expect(callback).toBeCalled();
    expect(mockInit).toBeCalledTimes(1);
    await new Promise((resolve) => {
      setTimeout(() => {
        expect(mockInit).toBeCalledTimes(2);
        resolve(1);
      });
    });
  });

  test('short polling throw message on error', async () => {
    const callback = jest.fn(() => Promise.reject(1));
    const polling = new Polling(
      'short',
      {
        id: 'test',
        delay: 30,
        callback,
      },
      false
    );

    const mockInit = jest.spyOn(polling as any, '$initShortPolling');
    const warnMock = jest.fn();
    (polling as any).$logger = {
      warn: warnMock,
    };
    timer.start = jest.fn().mockImplementationOnce(timer.start);
    (polling as any).$timer = timer;
    polling.run();

    expect(callback).toBeCalled();
    expect(mockInit).toBeCalledTimes(1);
    await new Promise((resolve) => {
      setTimeout(() => {
        expect(warnMock).toBeCalledWith('(test) Something gone wrong. Next try in 30 seconds.');
        expect(mockInit).toBeCalledTimes(2);
        resolve(1);
      });
    });
  });

  test('short polling sends events', async () => {
    const callback = jest.fn(() => Promise.resolve(1));
    const polling = new Polling(
      'short',
      {
        id: 'test',
        delay: 30,
        callback,
      },
      false
    );

    const mockHandler = jest.fn();
    const mockInit = jest.spyOn(polling as any, '$initShortPolling');
    timer.start = jest.fn().mockImplementationOnce(timer.start);
    (polling as any).$timer = timer;
    polling.on(mockHandler);
    polling.run();

    expect(callback).toBeCalled();
    expect(mockInit).toBeCalledTimes(1);
    await new Promise((resolve) => {
      setTimeout(() => {
        expect(mockHandler).toBeCalledWith(1);
        expect(mockInit).toBeCalledTimes(2);
        resolve(1);
      });
    });
  });

  test('short polling stops', async () => {
    const polling = new Polling(
      'short',
      {
        id: 'test',
        delay: 30,
        callback: jest.fn(() => {
          polling.pause();
          return Promise.resolve(1);
        }),
      },
      false
    );

    const mockInit = jest.spyOn(polling as any, '$initShortPolling');
    timer.start = jest.fn().mockImplementationOnce(timer.start);
    (polling as any).$timer = timer;
    polling.run();

    await new Promise((resolve) => {
      setTimeout(() => {
        expect(mockInit).toBeCalledTimes(2);
        expect(timer.start).toBeCalledTimes(1);
        resolve(1);
      });
    });
  });

  test.skip('creates long polling', () => {
    const callback = () => new Promise(noop);
    const polling = new Polling(
      'long',
      {
        id: 'test',
        callback,
      },
      false
    );

    (polling as any).$timer = timer;
    polling.run();

    expect(timer.start).toBeCalledTimes(1);
    expect(timer.start.mock.calls[0]).toContain(0);

    polling.pause();

    expect(timer.clear).toBeCalledTimes(1);
    expect(timer.clear).toBeCalledWith(100);
  });

  test('long polling works', async () => {
    const callback = jest.fn(() => new Promise(noop));
    const polling = new Polling(
      'long',
      {
        id: 'test',
        callback,
      },
      false
    );

    const mockInit = jest.spyOn(polling as any, '$initLongPolling');
    (polling as any).$timer = timer;
    callback.mockImplementationOnce(() => Promise.resolve());
    polling.run();

    expect(callback).toBeCalledTimes(1);
    expect(mockInit).toBeCalledTimes(1);

    await new Promise((resolve) => {
      setTimeout(() => {
        expect(callback).toBeCalledTimes(2);
        expect(mockInit).toBeCalledTimes(2);
        resolve(1);
      });
    });
  });

  test('long polling throw message and delayed on error', async () => {
    const callback = jest.fn(() => new Promise(noop));
    const warnMock = jest.fn();
    const polling = new Polling(
      'long',
      {
        id: 'test',
        callback,
      },
      false
    );

    (polling as any).$logger = {
      warn: warnMock,
    };
    const mockInit = jest.spyOn(polling as any, '$initLongPolling');
    (polling as any).$timer = timer;
    callback.mockImplementationOnce(() => Promise.reject());
    polling.run();

    expect(callback).toBeCalledTimes(1);
    expect(mockInit).toBeCalledTimes(1);

    await new Promise((resolve) => {
      setTimeout(() => {
        expect(warnMock).toBeCalledWith('(test) Connection was lost. Try to reconnect in 20 seconds.');
        expect(timer.start.mock.calls[1]).toContain(20000);
        expect(callback).toBeCalledTimes(2);
        expect(mockInit).toBeCalledTimes(2);
        resolve(1);
      });
    });
  });

  test('long polling sends events', async () => {
    const callback = jest.fn(() => new Promise(noop));
    const polling = new Polling(
      'long',
      {
        id: 'test',
        callback,
      },
      false
    );

    const handler = jest.fn();
    const mockInit = jest.spyOn(polling as any, '$initLongPolling');
    (polling as any).$timer = timer;
    callback.mockImplementationOnce(() => Promise.resolve());
    polling.on(handler);
    polling.run();

    expect(callback).toBeCalledTimes(1);
    expect(mockInit).toBeCalledTimes(1);

    await new Promise((resolve) => {
      setTimeout(() => {
        expect(callback).toBeCalledTimes(2);
        expect(mockInit).toBeCalledTimes(2);
        expect(handler).toBeCalledTimes(1);
        resolve(1);
      });
    });
  });

  test('long polling stops', async () => {
    const callback = jest.fn(() => new Promise(noop));
    const polling = new Polling(
      'long',
      {
        id: 'test',
        callback,
      },
      false
    );

    const mockInit = jest.spyOn(polling as any, '$initLongPolling');
    (polling as any).$timer = timer;
    callback.mockImplementationOnce(() => {
      polling.pause();
      return Promise.resolve();
    });
    polling.run();

    expect(callback).toBeCalledTimes(1);
    expect(mockInit).toBeCalledTimes(1);

    await new Promise((resolve) => {
      setTimeout(() => {
        expect(callback).toBeCalledTimes(1);
        expect(timer.start).toBeCalledTimes(1);
        expect(mockInit).toBeCalledTimes(2);
        resolve(1);
      });
    });
  });

  test('polling registrates and removes listeners', () => {
    const callback = jest.fn(() => new Promise(noop));
    const polling = new Polling(
      'long',
      {
        id: 'test',
        callback,
      },
      false
    );

    const callbacks = (): Set<Function> => (polling as any).$callbacks;
    const handler1 = jest.fn();
    const handler2 = jest.fn();
    const handler3 = jest.fn();

    polling.on(handler1);
    expect(callbacks().size).toBe(1);
    expect(callbacks().has(handler1)).toBe(true);

    polling.on(handler1);
    expect(callbacks().size).toBe(1);
    expect(callbacks().has(handler1)).toBe(true);

    polling.on(handler2);
    polling.on(handler3);
    expect(callbacks().size).toBe(3);
    expect(callbacks().has(handler1)).toBe(true);
    expect(callbacks().has(handler2)).toBe(true);
    expect(callbacks().has(handler3)).toBe(true);

    polling.off(handler2);
    expect(callbacks().size).toBe(2);
    expect(callbacks().has(handler1)).toBe(true);
    expect(callbacks().has(handler2)).toBe(false);
    expect(callbacks().has(handler3)).toBe(true);

    polling.off(handler2);
    expect(callbacks().size).toBe(2);
    expect(callbacks().has(handler1)).toBe(true);
    expect(callbacks().has(handler2)).toBe(false);
    expect(callbacks().has(handler3)).toBe(true);

    polling.off(handler1);
    polling.off(handler3);
    expect(callbacks().size).toBe(0);
    expect(callbacks().has(handler1)).toBe(false);
    expect(callbacks().has(handler2)).toBe(false);
    expect(callbacks().has(handler3)).toBe(false);
  });
});
