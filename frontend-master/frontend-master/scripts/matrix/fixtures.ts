import fs from 'fs';
import os from 'os';
import path from 'path';
import projectRoot from 'app-root-path';
import { disclaimer } from './consts';

export const tmp = fs.mkdtempSync(path.join(os.tmpdir(), 'tmp-giszp'));
export const tempFile = path.resolve(tmp, 'roles.ts');
fs.writeFileSync(tempFile, disclaimer, { encoding: 'utf-8' });
export const outputFile = path.resolve(projectRoot.path, 'src/models/roles.ts');
export const outputFixtures = (file: string) =>
  path.resolve(projectRoot.path, 'public', 'configs', 'role-model', `${file}.json`);
