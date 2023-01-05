'use strict';
Object.defineProperty(exports, '__esModule', { value: true });
const tslib_1 = require('tslib');
const child_process_1 = require('child_process');
const fs_1 = tslib_1.__importDefault(require('fs'));
const path_1 = tslib_1.__importDefault(require('path'));
const yargs_1 = tslib_1.__importDefault(require('yargs'));
const app_root_path_1 = tslib_1.__importDefault(require('app-root-path'));
try {
  (() => {
    const { _: filePaths, type } = yargs_1.default(process.argv.slice(2)).argv;
    const command = process.platform === 'win32' ? '.cmd' : '';
    const bin = path_1.default.resolve(app_root_path_1.default.path, 'node_modules', '.bin', type + command);
    const defaultConfig = path_1.default.resolve(app_root_path_1.default.path, 'tsconfig.json');
    const tempConfig = path_1.default.resolve(app_root_path_1.default.path, 'tsconfig.temp.json');
    const config = JSON.parse(fs_1.default.readFileSync(defaultConfig, { encoding: 'utf-8' }));
    if (filePaths.length) {
      config.include = ['src/shims-*.d.ts', ...filePaths];
    }
    config.compilerOptions.noEmit = true;
    config.compilerOptions.noImplicitAny = true;
    config.compilerOptions.noUnusedLocals = true;
    config.compilerOptions.noUnusedParameters = true;
    config.compilerOptions.noImplicitThis = true;
    fs_1.default.writeFileSync(tempConfig, JSON.stringify(config), { encoding: 'utf-8' });
    const task = child_process_1.exec(`${bin} -p ${tempConfig}`);
    task.stdout?.pipe(process.stdout);
    task.on('exit', () => fs_1.default.unlinkSync(tempConfig));
  })();
} catch (err) {
  console.error(err);
  throw err;
}
