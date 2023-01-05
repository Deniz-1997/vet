import { ERole } from '@/models/roles';
import titles from './titles.json';

export type TRoleTitle =
  | {
      [key in ERole]: boolean;
    }
  | {
      prefix?: string;
      name: string;
    };

export default titles as unknown as TRoleTitle[];
