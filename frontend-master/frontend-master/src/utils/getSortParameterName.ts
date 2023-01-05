export const getSortParameterName = (sortBy: string, sortDesc: boolean): string => {
  return sortBy ? `${sortDesc ? '-' : ''}${sortBy}` : '';
};
