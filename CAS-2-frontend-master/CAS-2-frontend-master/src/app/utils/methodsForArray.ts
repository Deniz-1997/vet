function deepArray<T extends Array<any>>(collection: T, count: number = 1): T {
  if (count === 1) {
    return collection.map(value => {
      return duplicateElement(value);
    }) as T;
  }

  const array = [] as T;

  for (let i = 0; i < count; i++) {
    array.push(collection.map(value => duplicateElement(value)));
  }

  return array;
}

function deepObject<T>(source: T): T {
  const result = {};
  Object.keys(source).forEach((key) => {
    const value = source[key];
    result[key] = duplicateElement(value);
  }, {});
  return result as T;
}

export function duplicateElement<T>(value: T, count: number = 1): T | Array<any> {
  if (typeof value !== 'object' || value === null || count < 1) {
    return value;
  }

  if (Array.isArray(value)) {
    return deepArray(value, count);
  }

  return deepObject(value);
}

export function arrayToKeys(array: Array<any>, key: string, model = null) {
  const result = {};
  array.forEach(el => result[el[key]] = model ? new model(el) : el);
  return result;
}
