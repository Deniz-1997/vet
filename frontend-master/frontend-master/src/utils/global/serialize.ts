import memoize from 'lodash/memoize';

export const serialize = memoize(<T>(value: T): string => {
  return JSON.parse(JSON.stringify(value));
});
