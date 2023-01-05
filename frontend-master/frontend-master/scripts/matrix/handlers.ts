import fs from 'fs';
import xlsx, { WorkSheet } from 'xlsx';

import { sheets, AuthorityKey, MatrixKey, RoleKey, Status, TitleKey } from './consts';
import { getAuthorities } from './utils';
import { tempFile, outputFixtures } from './fixtures';

type TitleList = Array<{
  [TitleKey.name]: string;
  [TitleKey.prefix]: string;
}>;

type MatrixList = Array<{
  [MatrixKey.primary]: string;
  [MatrixKey.description]: string;
}>;

type MatrixResponse = {
  enum: string;
  matrix: Array<{ action: string } | { [key: string]: boolean }>;
};

type EnumList = Array<
  | {
      [RoleKey.primary]: string;
      [RoleKey.description]: string;
      [RoleKey.status]: string;
    } & {
      [AuthorityKey.primary]: string;
      [AuthorityKey.description]: string;
      [AuthorityKey.status]: string;
    }
>;

type EnumOptions = {
  name: 'ERole' | 'EAuthority';
  primary: typeof RoleKey.primary | typeof AuthorityKey.primary;
  description: typeof RoleKey.description | typeof AuthorityKey.description;
  status: typeof RoleKey.status | typeof AuthorityKey.status;
};

const titles = (list: TitleList) => {
  const titles = list.map(({ [TitleKey.name]: name, [TitleKey.prefix]: prefix, ...roles }) => ({
    prefix,
    name,
    ...getAuthorities(roles),
  }));

  fs.writeFileSync(outputFixtures('titles'), JSON.stringify(titles), { encoding: 'utf-8' });
};

const accessMatrix = (list: MatrixList) => {
  const output = list.reduce<MatrixResponse>(
    (result, { [MatrixKey.primary]: action, [MatrixKey.description]: description, ...authorities }) => ({
      matrix: [...result.matrix, { action, ...getAuthorities(authorities) }],
      enum: result.enum + `/** ${description}. */\n` + `${action} = '${action}',\n`,
    }),
    { matrix: [], enum: '\nexport enum EAction {\n' }
  );

  fs.appendFileSync(tempFile, output.enum + '}\n', { encoding: 'utf-8' });
  fs.writeFileSync(outputFixtures('accessMatrix'), JSON.stringify(output.matrix), { encoding: 'utf-8' });
};

export function createEnumHandler({ name, primary, description, status }: EnumOptions) {
  return (list: EnumList) => {
    const output = list.reduce((result, data) => {
      const { [primary]: key, [description]: caption, [status]: state } = data;

      if (state !== Status.DELETE) {
        return result + `/** ${caption}. */\n` + `${key} = '${key}',\n`;
      }

      return result;
    }, `\nexport enum ${name} {\n`);
    fs.appendFileSync(tempFile, output + '}\n', { encoding: 'utf-8' });
  };
}

export const handlers = {
  accessMatrix,
  titles,
  roles: createEnumHandler({
    name: 'ERole',
    ...RoleKey,
  }),
  authorities: createEnumHandler({
    name: 'EAuthority',
    ...AuthorityKey,
  }),
};

export default function parseSheet(sheet: WorkSheet, id: number) {
  const key = id < sheets.length ? sheets[id] : '';

  if (key) {
    const json = xlsx.utils.sheet_to_json(sheet);
    (handlers[key] as any).apply(null, [json]);
  }
}
