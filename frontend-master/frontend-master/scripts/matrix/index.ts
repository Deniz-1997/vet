import fs from 'fs';
import yargs from 'yargs';
import { getFile } from './async';
import parseSheet from './handlers';
import { tempFile, outputFile } from './fixtures';

const { _: files } = yargs(process.argv.slice(2)).argv as { _: string[] };

(async () => {
  const { SheetNames, Sheets } = await getFile(files[0]);
  SheetNames.forEach((sheet, i) => {
    parseSheet(Sheets[sheet], i);
    const output = fs.readFileSync(tempFile, { encoding: 'utf-8' });
    fs.writeFileSync(outputFile, output, { encoding: 'utf-8' });
  });
})();
