import Notify from '@/services/notify';

const ctx = {
  $store: {
    commit: jest.fn(),
  },
};

describe('notify service', () => {
  test('registrates in store', () => {
    const actual = new Notify(ctx);

    expect(ctx.$store.commit).toBeCalledWith('errors/init', actual);
  });

  test('push messages', () => {
    const actual = new Notify(ctx);
    expect(actual).toHaveLength(0);

    actual.push('error', { text: 'test' });
    expect(actual).toHaveLength(1);
    expect(actual.tail?.type).toBe('error');

    actual.push('error', { text: 'test' });
    actual.push('message', { text: 'test', params: { foo: 'bar' } });
    expect(actual).toHaveLength(3);
    expect(actual.tail?.type).toBe('message');
    expect(actual.tail?.text).toBe('test');
    expect(actual.tail?.params?.foo).toBe('bar');
  });

  test('hide messages', () => {
    const actual = new Notify(ctx);

    const id1 = actual.push('error', { text: 'test' });
    expect(actual).toHaveLength(1);

    actual.hide(id1);
    expect(actual).toHaveLength(0);

    actual.push('error', { text: 'test' });
    actual.push('error', { text: 'test' });
    const id2 = actual.push('error', { text: 'test' });
    expect(actual).toHaveLength(3);

    actual.hide(id2);
    expect(actual).toHaveLength(2);
  });

  test('flush messages', () => {
    const actual = new Notify(ctx);

    actual.push('error', { text: 'test' });
    expect(actual).toHaveLength(1);

    actual.flush();
    expect(actual).toHaveLength(0);

    actual.push('error', { text: 'test' });
    actual.push('error', { text: 'test' });
    actual.push('error', { text: 'test' });
    expect(actual).toHaveLength(3);

    actual.flush();
    expect(actual).toHaveLength(0);
  });

  test('registrates and executes listeners', () => {
    const actual = new Notify(ctx);

    const mock1 = jest.fn();
    actual.on('message', mock1);
    expect((actual as any).$listeners.message.size).toBe(1);

    const mock2 = jest.fn();
    actual.on('error', mock2);
    expect((actual as any).$listeners.error.size).toBe(1);

    const mock3 = jest.fn();
    actual.on('hide', mock3);
    expect((actual as any).$listeners.hide.size).toBe(1);

    const mock4 = jest.fn();
    actual.on('flush', mock4);
    expect((actual as any).$listeners.flush.size).toBe(1);

    const id1 = actual.push('error', { text: 'test' });
    actual.push('message', { text: 'test' });
    actual.hide(id1);
    actual.flush();

    expect(mock1).toBeCalledTimes(1);
    expect(mock2).toBeCalledTimes(1);
    expect(mock3).toBeCalledTimes(1);
    expect(mock4).toBeCalledTimes(1);

    const id2 = actual.push('error', { text: 'test' });
    actual.push('message', { text: 'test' });
    actual.hide(id2);
    actual.flush();

    expect(mock1).toBeCalledTimes(2);
    expect(mock2).toBeCalledTimes(2);
    expect(mock3).toBeCalledTimes(2);
    expect(mock4).toBeCalledTimes(2);
  });

  test('removes listeners', () => {
    const actual = new Notify(ctx);

    const mock1 = jest.fn();
    actual.on('message', mock1);
    expect((actual as any).$listeners.message.size).toBe(1);

    const mock2 = jest.fn();
    actual.on('error', mock2);
    expect((actual as any).$listeners.error.size).toBe(1);

    const mock3 = jest.fn();
    actual.on('hide', mock3);
    expect((actual as any).$listeners.hide.size).toBe(1);

    const mock4 = jest.fn();
    actual.on('flush', mock4);
    expect((actual as any).$listeners.flush.size).toBe(1);

    actual.off('message', mock1);
    expect((actual as any).$listeners.message.size).toBe(0);

    actual.off('error', mock2);
    expect((actual as any).$listeners.error.size).toBe(0);

    actual.off('hide', mock3);
    expect((actual as any).$listeners.hide.size).toBe(0);

    actual.off('flush', mock4);
    expect((actual as any).$listeners.flush.size).toBe(0);

    const id1 = actual.push('error', { text: 'test' });
    actual.push('message', { text: 'test' });
    actual.hide(id1);
    actual.flush();

    expect(mock1).toBeCalledTimes(0);
    expect(mock2).toBeCalledTimes(0);
    expect(mock3).toBeCalledTimes(0);
    expect(mock4).toBeCalledTimes(0);
  });

  test('returns messages', () => {
    const actual = new Notify(ctx);

    actual.push('error', { text: 'test' });
    actual.push('error', { text: 'test' });
    actual.push('error', { text: 'test' });

    expect(actual.find()).toHaveLength(3);

    actual.push('message', { text: 'test' });
    actual.push('message', { text: 'test' });
    actual.push('message', { text: 'test' });

    expect(actual.find()).toHaveLength(6);

    expect(actual.find('error')).toHaveLength(3);
    actual.find('error').forEach(({ type }) => {
      expect(type).toBe('error');
    });
    expect(actual.find('message')).toHaveLength(3);
    actual.find('message').forEach(({ type }) => {
      expect(type).toBe('message');
    });
  });
});
