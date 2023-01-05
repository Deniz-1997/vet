import { createLogger } from '@/utils';

let console: Partial<Console>;

beforeEach(() => {
  console = {
    log: jest.fn(),
    info: jest.fn(),
    warn: jest.fn(),
    error: jest.fn(),
  };
});

describe('createLogger util', () => {
  test('proxies logger', () => {
    const logger = createLogger({ permanent: true }, console as Console);

    expect(console.log).not.toBeCalled();
    expect(console.info).not.toBeCalled();
    expect(console.warn).not.toBeCalled();
    expect(console.error).not.toBeCalled();

    logger.log('ololo');
    expect(console.log).toBeCalledWith('ololo');

    logger.info('ololo');
    expect(console.info).toBeCalledWith('ololo');

    logger.warn('ololo');
    expect(console.warn).toBeCalledWith('ololo');

    logger.error('ololo');
    expect(console.error).toBeCalledWith('ololo');
  });

  test('prevents calls with wrong environment', () => {
    const logger = createLogger({}, console as Console);

    expect(console.log).not.toBeCalled();
    expect(console.info).not.toBeCalled();
    expect(console.warn).not.toBeCalled();
    expect(console.error).not.toBeCalled();

    logger.log('ololo');
    expect(console.log).not.toBeCalled();

    logger.info('ololo');
    expect(console.info).not.toBeCalled();

    logger.warn('ololo');
    expect(console.warn).not.toBeCalled();

    logger.error('ololo');
    expect(console.error).not.toBeCalled();
  });

  test('prevents calls with disabled key', () => {
    const logger = createLogger({ key: 'test', permanent: true }, console as Console);
    const self: any = window;

    expect(self._loggerFlags.test).toBe(false);
    logger.log('ololo');
    expect(console.log).not.toBeCalled();

    self._loggerFlags.test = true;

    logger.log('ololo');
    expect(console.log).toBeCalledWith('ololo');
  });

  test('passes prefix', () => {
    const logger = createLogger({ prefix: 'test', permanent: true }, console as Console);

    logger.log('ololo');
    expect(console.log).toBeCalledWith('[test]', 'ololo');

    logger.log('trololo');
    expect(console.log).toBeCalledWith('[test]', 'trololo');
  });
});
