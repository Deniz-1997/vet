'use strict';
Object.defineProperty(exports, '__esModule', { value: true });
exports.script = void 0;
const tslib_1 = require('tslib');
const fs_1 = tslib_1.__importDefault(require('fs'));
const glob_1 = tslib_1.__importDefault(require('glob'));
const path_1 = tslib_1.__importDefault(require('path'));
const readline_1 = tslib_1.__importDefault(require('readline'));
const yargs_1 = tslib_1.__importDefault(require('yargs'));
const app_root_path_1 = tslib_1.__importDefault(require('app-root-path'));
class Script {
  /** Запросить название файла, если указанное не соответствует ожидаемому формату. */
  async getLabel(origin) {
    const rl = readline_1.default.createInterface({
      input: process.stdin,
      output: process.stdout,
    });
    const result = await new Promise((resolve) => {
      rl.question(
        `File name contains unexpected symbols (${origin}). Define a new one or press [Enter] to use current:\n`,
        resolve
      );
    });
    rl.close();
    return result || origin;
  }
  /** Получить конфиг и информацию по нему. */
  getConfig(to, type) {
    const configName =
      type ||
      (to.includes(path_1.default.resolve(app_root_path_1.default.path, 'public', 'files', 'video'))
        ? 'video'
        : 'documents');
    const configPath = path_1.default.resolve(
      app_root_path_1.default.path,
      'public',
      'configs',
      'files',
      `${configName}.json`
    );
    const config = JSON.parse(fs_1.default.readFileSync(configPath, { encoding: 'utf-8' }));
    return {
      name: configName,
      path: configPath,
      value: config,
    };
  }
  /** Изменить или добавить новый файл. */
  async processFile({ filepath, config, to }) {
    const delimeter = '[$%delimeter%$]';
    const filename = filepath.replace(/[/\\]/g, delimeter).split(delimeter).pop() || '';
    const file = {
      label: filename.split('.').slice(-2, -1).join('.') || '',
      name: filename,
      date: new Date().toLocaleDateString(),
    };
    if (/\w/g.test(file.label)) {
      file.label = await this.getLabel(file.label);
    }
    const index = config.findIndex(({ name }) => to.includes(name));
    let pathTo;
    if (index >= 0) {
      // Если файл заменяется, то старый удаляется а новый записывается согласно своему оригинальному имени.
      pathTo = to.replace(config[index].name, file.name);
      fs_1.default.rmSync(to);
      config[index] = file;
    } else {
      pathTo = path_1.default.resolve(to, file.name);
      config.push(file);
    }
    fs_1.default.copyFileSync(filepath, pathTo);
  }
  /** Обработчик удаления файлов. */
  removeHandler(from) {
    const config = this.getConfig(from, false);
    config.value = config.value.filter(({ name }) => {
      return !from.includes(name);
    });
    fs_1.default.rmSync(from);
    fs_1.default.writeFileSync(config.path, JSON.stringify(config.value), { encoding: 'utf-8' });
  }
  /** Обработчик добавления и изменения файлов, реализующий поддержку глоб-паттерна. */
  async handler(from, to, type) {
    const config = this.getConfig(to, type);
    for (const filepath of from) {
      await this.processFile({
        filepath,
        to,
        config: config.value,
      });
    }
    fs_1.default.writeFileSync(config.path, JSON.stringify(config.value), { encoding: 'utf-8' });
  }
  /** Запуск скрипта изменнеия, добавления и удаления файлов. */
  async upload() {
    const {
      rm,
      _: [to, from],
    } = await yargs_1.default(process.argv.slice(2)).argv;
    const isFileExist = fs_1.default.existsSync(app_root_path_1.default.resolve(to));
    const pathTo = isFileExist
      ? app_root_path_1.default.resolve(to)
      : path_1.default.resolve(app_root_path_1.default.path, 'public', 'files', to);
    if (rm) {
      this.removeHandler(to);
    } else {
      await this.handler(glob_1.default.sync(from, { root: app_root_path_1.default.path }), pathTo, !isFileExist && to);
    }
  }
}
exports.script = new Script();
exports.default = () => exports.script.upload();
