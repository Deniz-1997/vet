import axios from 'axios';
import fs from 'fs';
import path from 'path';
import projectRoot from 'app-root-path';
import xlsx from 'xlsx';
import * as async from 'scripts/matrix/async';
import { noop } from 'lodash';
const readline = require('readline');

const readlineMock = (login: string, password: string) => () => ({
  question(label: string, resolve: Function) {
    if (label.toLowerCase().includes('login')) {
      resolve(login);
    }
    resolve(password);
  },
  close: noop,
});

describe('matrix update async', () => {
  describe('getFile', () => {
    test('returns file', async () => {
      const filepath = 'test-path';
      const mockReadXlsx = jest.spyOn(xlsx, 'readFile').mockImplementationOnce(noop as any);
      await async.getFile(filepath);

      expect(mockReadXlsx).toBeCalledWith(path.resolve(projectRoot.path, filepath));
    });

    test('downloads file', async () => {
      const mockRl = jest.spyOn(readline, 'createInterface').mockImplementation(readlineMock('log', 'pass'));
      const mockAxios = jest.spyOn(axios, 'get').mockImplementationOnce((() => Promise.resolve({ data: null })) as any);
      const mockWrite = jest.spyOn(fs, 'writeFileSync').mockImplementationOnce(noop as any);
      const mockReadXlsx = jest.spyOn(xlsx, 'readFile').mockImplementationOnce(noop as any);
      await async.getFile();

      expect(mockRl).toBeCalled();
      expect(mockAxios).toBeCalled();
      expect(mockWrite).toBeCalled();
      expect(mockReadXlsx).toBeCalled();
    });
  });

  describe('downloadFile', () => {
    test('downloads file', async () => {
      const mockRl = jest.spyOn(readline, 'createInterface').mockImplementation(readlineMock('log', 'pass'));
      const mockAxios = jest.spyOn(axios, 'get').mockImplementationOnce((() => Promise.resolve({ data: null })) as any);
      await async.downloadFile();

      expect(mockRl).toBeCalled();
      expect(mockAxios).toBeCalled();
    });

    test('throws error', async () => {
      const mockRl = jest.spyOn(readline, 'createInterface').mockImplementation(noop as any);
      jest.spyOn(console, 'error').mockImplementationOnce(noop);
      try {
        await async.downloadFile().catch(noop);
      } catch (err) {
        expect(() => err).toThrow();
        expect(mockRl).toBeCalled();
      }
    });
  });
});
