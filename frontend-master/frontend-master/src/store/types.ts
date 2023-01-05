import { PaginationType } from "@/components/common/Pagination/Pagination.types";


export type DataRegisterType = {
  pageable?: PaginationType,
  id?: number,
  actual: boolean,
  date?: string,
  filter?: string,
  code?: string
}