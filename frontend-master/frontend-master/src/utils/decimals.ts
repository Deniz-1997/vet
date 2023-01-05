export const add = (x = 0, y = 0, precision = 4): number => {
  const offset = Math.pow(10, precision);
  return (x * offset + y * offset) / offset;
};

export const subtract = (x = 0, y = 0, precision = 4): number => {
  const offset = Math.pow(10, precision);
  return (x * offset - y * offset) / offset;
};
