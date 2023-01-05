import { EAction } from '@/models/roles';

type TParentMenuItem = { pages: TMenuItem<{ path: string }>[]; };
type TMenuItemChecker = (this: Vue) => boolean;

export type TMenuItem<T = TParentMenuItem> = {
  label: string;
  path?: string;
  order?: number;
  enable?: boolean | TMenuItemChecker | EAction | EAction[];
} & T;
