export interface BaseTab<V = string> {
  name: string;
  value: V;
  disabled?: boolean;
}

export type CustomTab<T = BaseTab> = {
  [K in keyof T]: T[K];
}
