import { EAction } from '@/models/roles';

/** Сформировать список вычисляемых свойств, высчитывающих доступность действий на основе матрицы доступов. */
function mapAccessFlags<T>(options: { [key in keyof T]: EAction | ((this: Vue) => boolean) }): {
  [key in keyof T]: () => boolean;
} {
  return Object.entries(options).reduce((result: any, [key, action]) => {
    if (typeof action === 'function') {
      result[key] = function (...args) {
        return action.call(this, ...args);
      };
    } else {
      result[key] = function () {
        return this.$store.getters['auth/check'](action);
      };
    }

    return result;
  }, {});
}

export { EAction, mapAccessFlags };
