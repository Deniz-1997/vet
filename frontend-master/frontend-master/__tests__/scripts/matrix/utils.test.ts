import { noop } from 'lodash';
import { headers } from 'scripts/matrix/consts';
import { getAuthorities, getToken, getHeaders } from 'scripts/matrix/utils';
const readline = require('readline');

type TData = {
  roles?: any;
  authorities?: any;
  mixed?: any;
};

const data: TData = {};
const expected: TData = {};

beforeAll(() => {
  data.roles = {
    ROLE_1: 'X',
    ROLE_2: 'X',
    ROLE_3: 'X',
  };
  expected.roles = {
    ROLE_1: true,
    ROLE_2: true,
    ROLE_3: true,
  };

  data.authorities = {
    AUTHORITY_1: 'X',
    AUTHORITY_2: 'X',
    AUTHORITY_3: 'X',
    UNEXPECTED_AUTHORITY: 'X',
  };
  expected.authorities = {
    AUTHORITY_1: true,
    AUTHORITY_2: true,
    AUTHORITY_3: true,
    UNEXPECTED_AUTHORITY: true,
  };

  data.mixed = {
    ...data.roles,
    ...data.authorities,
  };
  expected.mixed = {
    ...expected.roles,
    ...expected.authorities,
  };
});

const readlineMock = (login: string, password: string) => () => ({
  question(label: string, resolve: Function) {
    if (label.toLowerCase().includes('login')) {
      resolve(login);
    }
    resolve(password);
  },
  close: noop,
});

describe('matrix update utils', () => {
  describe('getAuthoruty', () => {
    test('return roles', () => {
      const actual = getAuthorities(data.roles);
      expect(actual).toEqual(expected.roles);
    });
    test('return authorities', () => {
      const actual = getAuthorities(data.authorities);
      expect(actual).toEqual(expected.authorities);
    });
    test('return mixed', () => {
      const actual = getAuthorities(data.mixed);
      expect(actual).toEqual(expected.mixed);
    });
    test('return empty', () => {
      const actual = getAuthorities({});
      expect(actual).toEqual({});
    });
  });

  describe('getToken', () => {
    test('return token', async () => {
      jest.spyOn(readline, 'createInterface').mockImplementation(readlineMock('log', 'pass'));
      const token1 = await getToken();
      expect(token1).toBe('bG9nOnBhc3M=');
      jest.restoreAllMocks();
      jest.spyOn(readline, 'createInterface').mockImplementation(readlineMock('Velkopopovicky', 'Kozel'));
      const token2 = await getToken();
      expect(token2).toBe('VmVsa29wb3Bvdmlja3k6S296ZWw=');
      jest.restoreAllMocks();
      jest.spyOn(readline, 'createInterface').mockImplementation(readlineMock('superuser', 'qwerty123'));
      const token3 = await getToken();
      expect(token3).toBe('c3VwZXJ1c2VyOnF3ZXJ0eTEyMw==');
      jest.restoreAllMocks();
    });
  });

  describe('getHeaders', () => {
    test('return headers', async () => {
      jest.spyOn(readline, 'createInterface').mockImplementation(readlineMock('Velkopopovicky', 'Kozel'));
      const actual = await getHeaders();
      expect(actual).toEqual({
        ...headers,
        Authorization: 'Basic VmVsa29wb3Bvdmlja3k6S296ZWw=',
      });
      jest.restoreAllMocks();
    });
  });
});
