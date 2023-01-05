import { State } from '@/types';

export interface Region {
  id: number;
  code: string;
  name: string;
  shortName: string;
  okato: string;
  oktmo: string;
}

export type RegionShort = State;

export type PagingEntityRegion = any;
