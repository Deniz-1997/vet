import { EAction, ERole, EAuthority } from '@/models/roles';

export type TActionStorageItem =
  | {
      action: EAction;
    }
  | { [key in ERole]: boolean }
  | { [key in EAuthority]: boolean };
