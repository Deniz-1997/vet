import { noop } from 'lodash';
import { script } from 'scripts/upload/main';
const fs = require('fs');
const readline = require('readline');

const readlineMock = (name: string) => () => ({
  question: jest.fn((_label: string, resolve: Function) => {
    resolve(name);
  }),
  close: jest.fn(),
});

describe('upload script', () => {
  test('getLabel', async () => {
    const mock = jest.spyOn(readline, 'createInterface');
    mock.mockImplementation(readlineMock('name'));
    expect(await script.getLabel('origin')).toBe('name');

    mock.mockImplementationOnce(readlineMock(''));
    expect(await script.getLabel('origin')).toBe('origin');
  });

  test('getConfig', () => {
    const mock = jest.spyOn(fs, 'readFileSync');
    mock.mockImplementationOnce(() => JSON.stringify([1, 2, 3]));

    const actual1 = script.getConfig('trololo', false);
    expect(actual1.name).toBe('documents');
    expect(actual1.path).toMatch(/\w+[\\/]public[\\/]configs[\\/]files[\\/]documents.json/);
    expect(actual1.value).toHaveLength(3);

    mock.mockImplementationOnce(() => JSON.stringify([1, 2, 3, 4, 5]));
    const actual2 = script.getConfig('trololo', 'video');
    expect(actual2.name).toBe('video');
    expect(actual2.path).toMatch(/\w+[\\/]public[\\/]configs[\\/]files[\\/]video.json/);
    expect(actual2.value).toHaveLength(5);

    mock.mockImplementationOnce(() => JSON.stringify(['foo', 'bar', 2]));
    const actual3 = script.getConfig('trololo', 'video');
    expect(actual3.value).toEqual(['foo', 'bar', 2]);
  });

  test('processFile adds new file', async () => {
    const mockCopy = jest.spyOn(fs, 'copyFileSync').mockImplementation(noop);
    const mockRm = jest.spyOn(fs, 'rmSync').mockImplementation(noop);
    const config = [];

    await script.processFile({
      config,
      filepath: 'название.js',
      to: 'files/',
    });

    expect(mockRm).not.toBeCalled();
    expect(mockCopy).toBeCalled();
    expect(mockCopy.mock.calls[0][0]).toBe('название.js');
    expect(mockCopy.mock.calls[0][1]).toContain('files');
    expect(config).toHaveLength(1);
    expect(config).toContainEqual({ label: 'название', name: 'название.js', date: new Date().toLocaleDateString() });
  });

  test('processFile checks name on adding new', async () => {
    jest.spyOn(readline, 'createInterface').mockImplementation(readlineMock('имечко'));
    jest.spyOn(fs, 'copyFileSync').mockImplementation(noop);
    jest.spyOn(fs, 'rmSync').mockImplementation(noop);
    const config = [];

    await script.processFile({
      config,
      filepath: 'index.js',
      to: 'files/',
    });

    expect(config).toContainEqual({ label: 'имечко', name: 'index.js', date: new Date().toLocaleDateString() });
  });

  test('processFile updates existing file', async () => {
    const mockCopy = jest.spyOn(fs, 'copyFileSync').mockImplementation(noop);
    const mockRm = jest.spyOn(fs, 'rmSync').mockImplementation(noop);
    const config = [{ name: 'file.js', label: 'trololo', date: '12.12.2012' }];

    await script.processFile({
      config,
      filepath: 'название.js',
      to: 'files/file.js',
    });

    expect(mockRm).toBeCalledWith('files/file.js');
    expect(mockCopy).toBeCalled();
    expect(mockCopy.mock.calls[0][0]).toBe('название.js');
    expect(mockCopy.mock.calls[0][1]).toContain('files/название.js');
    expect(config).toHaveLength(1);
    expect(config).toContainEqual({ label: 'название', name: 'название.js', date: new Date().toLocaleDateString() });
  });

  test('removeFile', async () => {
    const mockGetConfig = jest.spyOn(script, 'getConfig').mockImplementation(
      () =>
        ({
          path: '/config.json',
          value: [{ name: 'index.js', path: 'index.js', date: '12.12.2012' }],
        } as any)
    );
    const mockWrite = jest.spyOn(fs, 'writeFileSync').mockImplementation(noop);
    const mockRm = jest.spyOn(fs, 'rmSync').mockImplementation(noop);

    await script.removeHandler('path/to/index.js');

    expect(mockGetConfig).toBeCalled();
    expect(mockRm).toBeCalledWith('path/to/index.js');
    expect(mockWrite).toBeCalledWith('/config.json', '[]', { encoding: 'utf-8' });
  });

  test('handler', async () => {
    const mockGetConfig = jest.spyOn(script, 'getConfig').mockImplementation(
      () =>
        ({
          path: '/config.json',
          value: [{ name: 'index.js', path: 'index.js', date: '12.12.2012' }],
        } as any)
    );
    const mockWrite = jest.spyOn(fs, 'writeFileSync').mockImplementation(noop);
    const mockProcessFile = jest.spyOn(script, 'processFile').mockImplementation(noop as any);

    await script.handler(['', '', ''], '', false);
    expect(mockGetConfig).toBeCalledTimes(1);
    expect(mockWrite).toBeCalledTimes(1);
    expect(mockProcessFile).toBeCalledTimes(3);

    await script.handler(['', '', '', '', '', ''], '', false);
    expect(mockGetConfig).toBeCalledTimes(2);
    expect(mockWrite).toBeCalledTimes(2);
    expect(mockProcessFile).toBeCalledTimes(9);

    await script.handler([], '', false);
    expect(mockGetConfig).toBeCalledTimes(3);
    expect(mockWrite).toBeCalledTimes(3);
    expect(mockProcessFile).toBeCalledTimes(9);
  });
});
