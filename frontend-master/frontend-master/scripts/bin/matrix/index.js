'use strict';
Object.defineProperty(exports, '__esModule', { value: true });
const tslib_1 = require('tslib');
const fs_1 = tslib_1.__importDefault(require('fs'));
const yargs_1 = tslib_1.__importDefault(require('yargs'));
const async_1 = require('./async');
const handlers_1 = tslib_1.__importDefault(require('./handlers'));
const fixtures_1 = require('./fixtures');
const { _: files } = yargs_1.default(process.argv.slice(2)).argv;
(async () => {
  const { SheetNames, Sheets } = await async_1.getFile(files[0]);
  SheetNames.forEach((sheet, i) => {
    handlers_1.default(Sheets[sheet], i);
    const output = fs_1.default.readFileSync(fixtures_1.tempFile, { encoding: 'utf-8' });
    fs_1.default.writeFileSync(fixtures_1.outputFile, output, { encoding: 'utf-8' });
  });
})();
