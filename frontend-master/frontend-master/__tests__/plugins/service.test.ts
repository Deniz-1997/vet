import { checkPluginRegistration } from './__utils__/checkPluginRegistration';
import plugin, { Service } from '@/plugins/service';

describe('plugins service.ts', () => {
  test('registrates plugin', async () => {
    const actual = await checkPluginRegistration(plugin, { foo: Service, bar: Service });

    expect(actual).toHaveProperty('$service');
    expect(actual.$service).toHaveProperty('foo');
    expect(actual.$service).toHaveProperty('bar');
    expect(actual.$service.foo).toBeInstanceOf(Service);
    expect(actual.$service.bar).toBeInstanceOf(Service);
  });

  test('creates service instance', () => {
    const ctx = {
      $axios: 'axios',
      $route: 'route',
      $router: 'router',
      $service: 'service',
      $store: 'store',
    } as any;

    const actual = new Service(ctx) as any;
    expect(actual).toMatchObject(ctx);
    expect(actual.$ctx).toMatchObject(ctx);
  });
});
