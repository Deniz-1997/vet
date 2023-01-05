export const getExistParams = (rest: Record<string, any>): Record<string, any> => {
  const searchParams: Record<string, any> = {};
  (Object.keys(rest) as (keyof typeof rest)[]).forEach((key: keyof typeof rest) => {
    if (rest[key] !== undefined) {
      searchParams[key] = rest[key];
    }
  });
  return searchParams;
};
