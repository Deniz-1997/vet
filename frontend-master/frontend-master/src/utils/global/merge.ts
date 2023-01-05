// Переопределяем merge, чтобы массивы заменяли друг друга, а не сливались по индексу.
import mergeWith from 'lodash/mergeWith';

export const merge = (a, b) =>
  mergeWith(a, b, (_, from) => {
    if (Array.isArray(from)) {
      return from;
    }
  });
