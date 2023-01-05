export function arrayToKeys(array: any[], key: string, model = null) {
  const result = {};
  array.forEach(el => result[el[key]] = model ? new model(el) : el);
  return result;
}
