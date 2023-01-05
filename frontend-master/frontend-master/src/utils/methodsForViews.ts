import { parseFiltersForRequests } from '@/utils/parseFiltersForRequests';
import { PaginationModel } from '@/models/Request/Pagination';

/**
 * @param thisRef
 */
export async function fetchRowsFromTable(thisRef): Promise<void> {
  thisRef.isLoading = true;
  thisRef.rows = [];
  thisRef.pagination = new PaginationModel();
  thisRef.request.filter.options = [];

  parseFiltersForRequests(thisRef);

  if (thisRef.pageable.pageNumber === 0) {
    thisRef.pageable.pageNumber = ++thisRef.pageable.pageNumber;
  }

  thisRef.request.page_size = thisRef.pageable.pageSize;
  thisRef.request.page = thisRef.pageable.pageNumber;

  try {
    const { status, response, pagination } = await thisRef.$store.dispatch(thisRef.getList, thisRef.payload);

    if (status) {
      thisRef.onSuccessResponse(response, pagination);
    }
    thisRef.isLoading = false;
  } catch (error) {
    thisRef.isLoading = false;
    console.error(error);
    if (typeof thisRef.onErrorResponse !== 'undefined') {
      thisRef.onErrorResponse(error);
    }
  }
}

/**
 * @param thisRef
 * @param options
 */
export async function getElementByFilter(
  thisRef: any,
  options: { field: string; operator: string; value: string | number }[]
): Promise<void> {
  const { response, status } = await thisRef.$store.dispatch(thisRef.getList, {
    url: thisRef.url,
    filter: { options: options },
  });

  if (!status || response.length === 0) {
    throw new Error('Not found element');
  }

  thisRef.model = new thisRef.model.constructor(response[0]);
}

/**
 * @param thisRef
 * @param id
 */
export async function getElementById(thisRef: any, id: number): Promise<any> {
  const { response, status } = await thisRef.$store.dispatch(thisRef.showItem, id);

  if (!status || response.length === 0) {
    throw new Error('Not found element');
  }

  thisRef.model = new thisRef.model.constructor(response);

  return response;
}
