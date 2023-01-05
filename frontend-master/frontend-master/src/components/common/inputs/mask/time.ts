/**
 * Generate a time mask based on input value (23:59)
 * @param {string} value
  */
export const timeMask : Function = (value) => {
  const hours = [
    /[0-2]/,
    value.charAt(0) === '2' ? /[0-3]/ : /[0-9]/,
  ];
  const minutes = [/[0-5]/, /[0-9]/];
  return value.length > 2
    ? [...hours, ':', ...minutes]
    : hours;
};
