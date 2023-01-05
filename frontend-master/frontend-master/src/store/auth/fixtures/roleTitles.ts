import { ERole } from '@/models/roles';

export type TRoleTitle =
  | {
      [key in ERole]: boolean;
    }
  | {
      prefix?: string;
      name: string;
    };
