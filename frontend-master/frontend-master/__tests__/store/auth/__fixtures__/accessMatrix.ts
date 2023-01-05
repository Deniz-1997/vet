import { EAction, ERole, EAuthority } from '@/models/roles';
import accessMatrix from './accessMatrix.json';

export type TActionStorageItem =
  | {
      action: EAction;
    }
  | { [key in ERole]: boolean }
  | { [key in EAuthority]: boolean };

const matrix: TActionStorageItem[] = accessMatrix as unknown as TActionStorageItem[];

export default matrix;
