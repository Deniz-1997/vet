import { PluginObject } from 'vue';

export const checkPluginRegistration = async <T>(plugin: (ctx: any) => PluginObject<T>, options?: T): Promise<any> => {
  const ctx = {};
  const actual = plugin(ctx as any);

  expect(actual).toHaveProperty('install');
  expect(typeof actual.install).toBe('function');

  const constructor = { prototype: {} };
  await actual.install(constructor as any, options);

  return constructor.prototype;
};
