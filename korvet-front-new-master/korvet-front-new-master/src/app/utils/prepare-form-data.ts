export function prepareFormData(data: Object): FormData {
  const formData = new FormData();
  Object.keys(data).forEach(key => data[key] !== null && formData.append(key, data[key]));
  return formData;
}
