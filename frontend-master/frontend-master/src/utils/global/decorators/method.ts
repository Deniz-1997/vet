import createDebounced from 'lodash/debounce';
import createThrottled from 'lodash/throttle';
import createMemoized from 'lodash/memoize';

export function Debounce(ms = 300, wrapper = createDebounced) {
  return (_target: any, _property: string, descriptor: PropertyDescriptor) => {
    const method = descriptor.value;
    descriptor.value = wrapper(method, ms);
    descriptor.value.bind = (ctx) => {
      const debounced = (...args) => descriptor.value.apply(ctx, args);
      debounced.cancel = descriptor.value.cancel;
      debounced.flush = descriptor.value.flush;
      return debounced;
    };
  };
}

export function Throttle(ms = 300, wrapper = createThrottled) {
  return (_target: any, _property: string, descriptor: PropertyDescriptor) => {
    const method = descriptor.value;
    descriptor.value = wrapper(method, ms);
  };
}

export function Memoize(resolver?: (...args: any[]) => any, wrapper = createMemoized) {
  return (_target: any, _property: string, descriptor: TypedPropertyDescriptor<any>) => {
    const method = descriptor.value;

    if (method) {
      descriptor.value = wrapper(method, resolver);
    }
  };
}
