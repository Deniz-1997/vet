'use strict';
Object.defineProperty(exports, '__esModule', { value: true });
exports.getFile = exports.downloadFile = void 0;
const tslib_1 = require('tslib');
const axios_1 = tslib_1.__importDefault(require('axios'));
const fs_1 = tslib_1.__importDefault(require('fs'));
const path_1 = tslib_1.__importDefault(require('path'));
const app_root_path_1 = tslib_1.__importDefault(require('app-root-path'));
const xlsx_1 = tslib_1.__importDefault(require('xlsx'));
const consts_1 = require('./consts');
const fixtures_1 = require('./fixtures');
const utils_1 = require('./utils');
async function downloadFile() {
  try {
    return axios_1.default.get(consts_1.matrixUrl, {
      responseType: 'arraybuffer',
      headers: await utils_1.getHeaders(),
    });
  } catch (err) {
    const error = err;
    console.error(error.message);
    throw new Error();
  }
}
exports.downloadFile = downloadFile;
async function getFile(matrixPath) {
  let file = path_1.default.resolve(app_root_path_1.default.path, matrixPath || '');
  if (!matrixPath) {
    file = path_1.default.resolve(fixtures_1.tmp, 'sheet.xlsx');
    await downloadFile().then(({ data }) => {
      return fs_1.default.writeFileSync(file, data, { encoding: 'utf-8' });
    });
  }
  return Promise.resolve(xlsx_1.default.readFile(file));
}
exports.getFile = getFile;
