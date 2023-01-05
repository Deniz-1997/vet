import { State } from '@/types';
import { Region } from './Region';

export interface Recipient extends State {
  region: Region;
}

export type RecipientShort = State;
