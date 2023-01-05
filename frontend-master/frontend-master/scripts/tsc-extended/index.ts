import { exec } from 'child_process';
import fs from 'fs';
import path from 'path';
import yargs from 'yargs';
import projectRoot from 'app-root-path';

type ScriptOptions = {
  _: string[];
  type: 'tsc' | 'vue-tsc';
};

type TSConfig = {
  include: string[];
  compilerOptions: {
    noEmit: true;
    noImplicitAny: true;
    noUnusedLocals: true;
    noUnusedParameters: true;
    noImplicitThis: true;
  };
};

try {
  (() => {
    const { _: filePaths, type } = yargs(process.argv.slice(2)).argv as unknown as ScriptOptions;
    const command = process.platform === 'win32' ? '.cmd' : '';

    const bin = path.resolve(projectRoot.path, 'node_modules', '.bin', type + command);
    const defaultConfig = path.resolve(projectRoot.path, 'tsconfig.json');
    const tempConfig = path.resolve(projectRoot.path, 'tsconfig.temp.json');
    const config: TSConfig = JSON.parse(fs.readFileSync(defaultConfig, { encoding: 'utf-8' }));
    if (filePaths.length) {
      config.include = ['src/shims-*.d.ts', ...filePaths];
    }
    config.compilerOptions.noEmit = true;
    config.compilerOptions.noImplicitAny = true;
    config.compilerOptions.noUnusedLocals = true;
    config.compilerOptions.noUnusedParameters = true;
    config.compilerOptions.noImplicitThis = true;
    fs.writeFileSync(tempConfig, JSON.stringify(config), { encoding: 'utf-8' });

    const task = exec(`${bin} -p ${tempConfig}`);
    task.stdout?.pipe(process.stdout);
    task.on('exit', () => fs.unlinkSync(tempConfig));
  })();
} catch (err) {
  console.error(err);

  throw err;
}
