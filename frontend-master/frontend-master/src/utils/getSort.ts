export const getSort = (e, filter, sortMap) => {
  if (e.sortBy[0]) {
    if (sortMap[e.sortBy[0]]) {
      return Array.isArray(sortMap[e.sortBy[0]]) ? sortMap[e.sortBy[0]].map(item => {
        return {
          direction: filter.sort && filter.sort[0].direction === 'ASC' ? 'DESC' : 'ASC',
          property:  item,
        };
      }) : [
        {
          direction: filter.sort && filter.sort[0].direction === 'ASC' ? 'DESC' : 'ASC',
          property:  sortMap[e.sortBy[0]],
        }
      ];
    }

    return [
      {
        direction: filter.sort && filter.sort[0].direction === 'ASC' ? 'DESC' : 'ASC',
        property:  e.sortBy[0],
      }
    ];
  }

  if (!!filter?.sort?.length) {
    return filter.sort;
  }

  return [];
}