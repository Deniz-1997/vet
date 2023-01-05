import fs from 'fs';
import glob from 'glob';
import path from 'path';
import readline from 'readline';
import yargs from 'yargs';
import projectRoot from 'app-root-path';

/** Аргументы скрипта. */
type TOptions = {
  /** Пути к файлам. */
  _: [string, string];
  /** Если в конце указано `--rm`, то переданный файл удаляется и убирается из конфига. */
  rm: boolean;
};

/** Опции обработчика добавления и редактирования файлов. */
type TProcessOptions = {
  /** Путь передаваемого файла. */
  filepath: string;
  /** Текущая конфигурация. */
  config: any[];
  /** Путь к заменяемому файлу или папке, в которую добавляется файл. */
  to: string;
};

class Script {
  /** Запросить название файла, если указанное не соответствует ожидаемому формату. */
  async getLabel(origin: string): Promise<string> {
    const rl = readline.createInterface({
      input: process.stdin,
      output: process.stdout,
    });
    const result = await new Promise<string>((resolve) => {
      rl.question(
        `File name contains unexpected symbols (${origin}). Define a new one or press [Enter] to use current:\n`,
        resolve
      );
    });
    rl.close();

    return result || origin;
  }

  /** Получить конфиг и информацию по нему. */
  getConfig(to: string, type: string | false) {
    const configName =
      type || (to.includes(path.resolve(projectRoot.path, 'public', 'files', 'video')) ? 'video' : 'documents');
    const configPath = path.resolve(projectRoot.path, 'public', 'configs', 'files', `${configName}.json`);
    const config: any[] = JSON.parse(fs.readFileSync(configPath, { encoding: 'utf-8' }));

    return {
      name: configName,
      path: configPath,
      value: config,
    };
  }

  /** Изменить или добавить новый файл. */
  async processFile({ filepath, config, to }: TProcessOptions) {
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
    let pathTo: string;

    if (index >= 0) {
      // Если файл заменяется, то старый удаляется а новый записывается согласно своему оригинальному имени.
      pathTo = to.replace(config[index].name, file.name);
      fs.rmSync(to);
      config[index] = file;
    } else {
      pathTo = path.resolve(to, file.name);
      config.push(file);
    }

    fs.copyFileSync(filepath, pathTo);
  }

  /** Обработчик удаления файлов. */
  removeHandler(from: string) {
    const config = this.getConfig(from, false);
    config.value = config.value.filter(({ name }) => {
      return !from.includes(name);
    });

    fs.rmSync(from);
    fs.writeFileSync(config.path, JSON.stringify(config.value), { encoding: 'utf-8' });
  }

  /** Обработчик добавления и изменения файлов, реализующий поддержку глоб-паттерна. */
  async handler(from: string[], to: string, type: string | false) {
    const config = this.getConfig(to, type);
    for (const filepath of from) {
      await this.processFile({
        filepath,
        to,
        config: config.value,
      });
    }

    fs.writeFileSync(config.path, JSON.stringify(config.value), { encoding: 'utf-8' });
  }

  /** Запуск скрипта изменнеия, добавления и удаления файлов. */
  async upload() {
    const {
      rm,
      _: [to, from],
    } = (await yargs(process.argv.slice(2)).argv) as unknown as TOptions;

    const isFileExist = fs.existsSync(projectRoot.resolve(to));
    const pathTo = isFileExist ? projectRoot.resolve(to) : path.resolve(projectRoot.path, 'public', 'files', to);

    if (rm) {
      this.removeHandler(to);
    } else {
      await this.handler(glob.sync(from, { root: projectRoot.path }), pathTo, !isFileExist && to);
    }
  }
}

export const script = new Script();
export default () => script.upload();
