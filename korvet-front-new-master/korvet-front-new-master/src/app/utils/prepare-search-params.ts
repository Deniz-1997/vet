export function prepareSearchParams(params: Object) {
  const result = new URLSearchParams();
  Object.keys(params).forEach(key => result.append(key, params[key]));
  return result;
}
