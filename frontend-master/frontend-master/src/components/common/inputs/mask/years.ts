/**
 * Generate a year mask based on input value.
 * @param {string} value
  */
 export const yearMask = (value) => {
  const firstPart = [
    /[0-2]/,
    value.charAt(0) === '1' ? /[9]/ : /[0-9]/,
  ];
  const secondPart = [/[0-9]/, /[0-9]/];
  return [...firstPart, ...secondPart]
};