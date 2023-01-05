/**
 * Generate a snils mask based on input value.
 * @param {string} value
 */
export const snilsMask = () => {
  const mask = [/\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\s/, /\d/, /\d/];
  return mask;
};
