export interface PageChange {
  page: number;
  perPage: number;
}

export type SortType = {
  sorted?: boolean,
  unsorted?: boolean,
  empty?: boolean
}

export type PaginationType = {
  pageNumber?: number,
    sort?: SortType,
    unpaged?: boolean,
    paged?: boolean,
    pageSize?: number,
    offset?: number
}