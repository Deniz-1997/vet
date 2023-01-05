/**
 * Generate number mask based on input value.
 * @param {string} value
  */
export const emailMask = value => {
  const email = value.replace(/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]{5,}\.[a-zA-Z0-9-.]+$/);
  return [email];
}