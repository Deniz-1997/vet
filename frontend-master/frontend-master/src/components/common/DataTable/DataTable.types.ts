import { DataTableHeader } from 'vuetify';

export type TableHeaders<T = string> = {
  align?: string;
  bold?: boolean;
  colSpan?: number;
  customSortName?: string;
  readOnly?: boolean;
  rowSpan?: number;
  sortable?: boolean;
  text?: string;
  value: T | 'actions';
  controlType?: string;
  restrictions?: any[];
  width?: string | number;
  class?: string;
  sortAs?: string;
  isShow?: boolean;
  direction?: string | null | undefined;
  activeASC?: boolean;
  activeDESC?: boolean;
  sortDisabledASC?: boolean;
  sortDisabledDESC?: boolean;
};

export type DataItem = {
  [key: string]: string | number;
};

export type ColumnItem = {
  text?: string;
  value: string;
};

export interface RowOptions {
  expand: (value: boolean) => void;
  headers: DataTableHeader[];
  isExpanded: boolean;
  isMobile: boolean;
  isSelected: boolean;
  item: unknown;
  select: (value: boolean) => void;
}
