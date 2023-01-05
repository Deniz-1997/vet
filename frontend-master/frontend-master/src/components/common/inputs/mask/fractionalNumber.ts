/**
 * Generate number mask based on input value.
 * @param {string} value
 */
export const fractionalNumberMask = (value) => {
  const numbers = value.replace(/[\D.]/g, '');
  return [numbers];
};
