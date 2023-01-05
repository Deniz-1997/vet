'use strict';
Object.defineProperty(exports, '__esModule', { value: true });
exports.outputFixtures = exports.outputFile = exports.tempFile = exports.tmp = void 0;
const tslib_1 = require('tslib');
const fs_1 = tslib_1.__importDefault(require('fs'));
const os_1 = tslib_1.__importDefault(require('os'));
const path_1 = tslib_1.__importDefault(require('path'));
const app_root_path_1 = tslib_1.__importDefault(require('app-root-path'));
const consts_1 = require('./consts');
exports.tmp = fs_1.default.mkdtempSync(path_1.default.join(os_1.default.tmpdir(), 'tmp-giszp'));
exports.tempFile = path_1.default.resolve(exports.tmp, 'roles.ts');
fs_1.default.writeFileSync(exports.tempFile, consts_1.disclaimer, { encoding: 'utf-8' });
exports.outputFile = path_1.default.resolve(app_root_path_1.default.path, 'src/models/roles.ts');
const outputFixtures = (file) =>
  path_1.default.resolve(app_root_path_1.default.path, 'public', 'configs', 'role-model', `${file}.json`);
exports.outputFixtures = outputFixtures;
