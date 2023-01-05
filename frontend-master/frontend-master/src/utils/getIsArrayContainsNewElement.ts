type Item = {
  [key: string]: any,
  id: number | null,
};

export const getIsArrayContainsNewElement = (items: Item[]) : boolean => {
  return items.map(item => item.id).includes(null);
};
