'use strict';
Object.defineProperty(exports, '__esModule', { value: true });
exports.handlers = exports.createEnumHandler = void 0;
const tslib_1 = require('tslib');
const fs_1 = tslib_1.__importDefault(require('fs'));
const xlsx_1 = tslib_1.__importDefault(require('xlsx'));
const consts_1 = require('./consts');
const utils_1 = require('./utils');
const fixtures_1 = require('./fixtures');
const titles = (list) => {
  const titles = list.map(({ [consts_1.TitleKey.name]: name, [consts_1.TitleKey.prefix]: prefix, ...roles }) => ({
    prefix,
    name,
    ...utils_1.getAuthorities(roles),
  }));
  fs_1.default.writeFileSync(fixtures_1.outputFixtures('titles'), JSON.stringify(titles), { encoding: 'utf-8' });
};
const accessMatrix = (list) => {
  const output = list.reduce(
    (
      result,
      { [consts_1.MatrixKey.primary]: action, [consts_1.MatrixKey.description]: description, ...authorities }
    ) => ({
      matrix: [...result.matrix, { action, ...utils_1.getAuthorities(authorities) }],
      enum: result.enum + `/** ${description}. */\n` + `${action} = '${action}',\n`,
    }),
    { matrix: [], enum: '\nexport enum EAction {\n' }
  );
  fs_1.default.appendFileSync(fixtures_1.tempFile, output.enum + '}\n', { encoding: 'utf-8' });
  fs_1.default.writeFileSync(fixtures_1.outputFixtures('accessMatrix'), JSON.stringify(output.matrix), {
    encoding: 'utf-8',
  });
};
function createEnumHandler({ name, primary, description, status }) {
  return (list) => {
    const output = list.reduce((result, data) => {
      const { [primary]: key, [description]: caption, [status]: state } = data;
      if (state !== consts_1.Status.DELETE) {
        return result + `/** ${caption}. */\n` + `${key} = '${key}',\n`;
      }
      return result;
    }, `\nexport enum ${name} {\n`);
    fs_1.default.appendFileSync(fixtures_1.tempFile, output + '}\n', { encoding: 'utf-8' });
  };
}
exports.createEnumHandler = createEnumHandler;
exports.handlers = {
  accessMatrix,
  titles,
  roles: createEnumHandler({
    name: 'ERole',
    ...consts_1.RoleKey,
  }),
  authorities: createEnumHandler({
    name: 'EAuthority',
    ...consts_1.AuthorityKey,
  }),
};
function parseSheet(sheet, id) {
  const key = id < consts_1.sheets.length ? consts_1.sheets[id] : '';
  if (key) {
    const json = xlsx_1.default.utils.sheet_to_json(sheet);
    exports.handlers[key].apply(null, [json]);
  }
}
exports.default = parseSheet;
