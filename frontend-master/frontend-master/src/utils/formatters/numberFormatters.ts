export function formatNumber(value: number | string): string {
  if (!value) {
    return '';
  }

  const regex = new RegExp(/(\d)(?=(\d\d\d)+(?!\d))/g);

  if (typeof value === 'string') {
    return parseFloat(value).toString().replace(regex, '$1 ');
  }

  return value.toString().replace(regex, '$1 ');
}
