import { MatrixKey, TitleKey } from 'scripts/matrix/consts';
import xlsx from 'xlsx';
import fs from 'fs';
import parseSheets, { handlers, createEnumHandler } from 'scripts/matrix/handlers';
import { noop } from 'lodash';
import { RoleKey, Status } from 'scripts/matrix/consts';

let mockAppendFileSync;
let mockWriteFileSync;
let xlsxMock;

beforeEach(() => {
  jest.clearAllMocks();
  jest.restoreAllMocks();
  mockAppendFileSync = jest.spyOn(fs, 'appendFileSync').mockImplementation(noop as any);
  mockWriteFileSync = jest.spyOn(fs, 'writeFileSync').mockImplementation(noop as any);
  xlsxMock = jest.spyOn(xlsx.utils, 'sheet_to_json').mockImplementation(noop as any);
});

describe('matrix update handlers', () => {
  describe('parseSheets', () => {
    test('parse correct sheets', () => {
      jest.spyOn(handlers, 'accessMatrix').mockImplementation(noop);
      jest.spyOn(handlers, 'authorities').mockImplementation(noop);
      jest.spyOn(handlers, 'roles').mockImplementation(noop);
      jest.spyOn(handlers, 'titles').mockImplementation(noop);
      parseSheets(null as any, 0);
      expect(xlsxMock).toBeCalled();
      parseSheets(null as any, 2);
      expect(xlsxMock).toBeCalledTimes(2);
      parseSheets(null as any, 3);
      parseSheets(null as any, 100);
      expect(xlsxMock).toBeCalledTimes(3);
    });
  });

  describe('createEnumHandler', () => {
    test('returns handler', () => {
      const actual = createEnumHandler({
        name: 'ERole',
        ...RoleKey,
      });

      expect(actual).toBeDefined();
      expect(typeof actual).toBe('function');

      const list = [
        {
          [RoleKey.primary]: 'ROLE_1',
          [RoleKey.description]: 'Description',
          [RoleKey.status]: 'Status',
        },
        {
          [RoleKey.primary]: 'ROLE_2',
          [RoleKey.description]: 'Description',
          [RoleKey.status]: Status.DELETE,
        },
      ] as any;
      const mockReduce = jest.spyOn(list, 'reduce');

      actual(list);

      const result = mockAppendFileSync.mock.calls[mockAppendFileSync.mock.calls.length - 1][1];

      expect(mockAppendFileSync).toBeCalled();
      expect(mockReduce).toBeCalled();
      expect(typeof result).toBe('string');
      expect(result).toMatch('ERole');
      expect(result).toMatch('ROLE_1');
      expect(result).not.toMatch('ROLE_2');
    });
  });

  describe('accessMatrix handler', () => {
    test('return accessMatrix', () => {
      const list = [
        {
          [MatrixKey.primary]: 'ACTION_1',
          [MatrixKey.description]: 'Description',
          ROLE_1: 'X',
          AUTHORITY_1: 'X',
        },
        {
          [MatrixKey.primary]: 'ACTION_2',
          [MatrixKey.description]: 'Description',
          ROLE_2: 'X',
          AUTHORITY_2: 'X',
        },
      ] as any;
      const mockReduce = jest.spyOn(list, 'reduce');

      handlers.accessMatrix(list);

      const enumResult = mockAppendFileSync.mock.calls[mockAppendFileSync.mock.calls.length - 1][1];
      const matrix = JSON.parse(mockWriteFileSync.mock.calls[mockWriteFileSync.mock.calls.length - 1][1]);
      expect(mockReduce).toBeCalled();
      expect(mockAppendFileSync).toBeCalled();
      expect(mockWriteFileSync).toBeCalled();

      expect(typeof enumResult).toBe('string');
      expect(enumResult).toMatch('EAction');
      expect(enumResult).toMatch('Description');
      expect(enumResult).toMatch('ACTION_1');
      expect(enumResult).toMatch('ACTION_2');

      expect(matrix).toHaveLength(2);
      expect(matrix).toContainEqual({ action: 'ACTION_1', ROLE_1: true, AUTHORITY_1: true });
    });
  });

  describe('titles handler', () => {
    test('return titles', () => {
      const list = [
        {
          [TitleKey.prefix]: 'ЛК',
          [TitleKey.name]: 'Клиента',
          ROLE_1: 'X',
          AUTHORITY_1: 'X',
        },
        {
          [TitleKey.prefix]: 'ПК',
          [TitleKey.name]: 'Гостя',
          ROLE_2: 'X',
          AUTHORITY_2: 'X',
        },
      ] as any;
      const mockReduce = jest.spyOn(list, 'map');

      handlers.titles(list);

      const titles = JSON.parse(mockWriteFileSync.mock.calls[mockWriteFileSync.mock.calls.length - 1][1]);
      expect(mockReduce).toBeCalled();
      expect(mockWriteFileSync).toBeCalled();

      expect(titles).toHaveLength(2);
      expect(titles).toContainEqual({ prefix: 'ЛК', name: 'Клиента', ROLE_1: true, AUTHORITY_1: true });
    });
  });
});
