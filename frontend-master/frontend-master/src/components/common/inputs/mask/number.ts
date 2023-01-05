/**
 * Generate number mask based on input value.
 * @param {string} value
  */
export const numberMask = value => {
  const numbers = value.replace(/[^0-9]/g, '');
  return [numbers];
}
export const allFloatNumberMask = value => {
  const numbers = value.replace(/[^0-9\.]/g, '');
  return [numbers];
}
