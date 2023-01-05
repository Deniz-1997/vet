import Vue from 'vue';
import { noop } from 'lodash';
import { PluginChain } from '@/utils';

describe('mapAccessFlags', () => {
  test('creates instance', () => {
    const actual = new PluginChain({} as any);

    expect(actual).toBeDefined();
    expect(actual).toHaveProperty('use');
    expect(actual).toHaveProperty('$ctx');
    expect((actual as any).$ctx).toEqual({});
    expect(typeof actual.use).toBe('function');
  });

  test('use returns instance', async () => {
    const actual = new PluginChain({} as any);
    const mock = jest.spyOn(Vue, 'use').mockImplementationOnce(noop as any);

    expect(await (actual.use as any)(noop)).toBeInstanceOf(PluginChain);
    expect(typeof actual.use).toBe('function');
    expect(mock).toBeCalled();
  });

  test('enabled plugins', async () => {
    const actual = new PluginChain({} as any);
    const mock = jest.spyOn(Vue, 'use').mockImplementation(noop as any);

    const plugins = [jest.fn(), jest.fn(), jest.fn()];

    await actual.use(plugins[0]);
    expect(mock).toBeCalledTimes(1);

    await actual.use(plugins[1]);
    await actual.use(plugins[2]);
    expect(mock).toBeCalledTimes(3);
  });
});
