import axios from 'axios';
import fs from 'fs';
import path from 'path';
import projectRoot from 'app-root-path';
import xlsx from 'xlsx';
import { matrixUrl } from './consts';
import { tmp } from './fixtures';
import { getHeaders } from './utils';

export async function downloadFile() {
  try {
    return axios.get(matrixUrl, {
      responseType: 'arraybuffer',
      headers: await getHeaders(),
    });
  } catch (err) {
    const error = err as unknown as Error;
    console.error(error.message);

    throw new Error();
  }
}

export async function getFile(matrixPath?: string) {
  let file = path.resolve(projectRoot.path, matrixPath || '');
  if (!matrixPath) {
    file = path.resolve(tmp, 'sheet.xlsx');
    await downloadFile().then(({ data }) => {
      return fs.writeFileSync(file, data, { encoding: 'utf-8' });
    });
  }
  return Promise.resolve(xlsx.readFile(file));
}
