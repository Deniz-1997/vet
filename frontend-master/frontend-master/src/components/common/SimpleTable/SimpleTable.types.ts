export interface SimpleHeader {
  align?: 'left' | 'center' | 'right';
  bold?: boolean;
  colSpan?: number;
  rowSpan?: number;
  text?: string;
}

export interface SimpleItem<C = string | number> {
  [key: string]: C;
}
