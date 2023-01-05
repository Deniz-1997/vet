import { mapAccessFlags, EAction } from '@/utils';

let data: ReturnType<typeof createData>;

const createData = () => ({
  check1: EAction.CANCEL_GRAIN_LOT_STORAGE,
  check2: EAction.ACTIVATE_USER_ACCOUNT,
  check3: EAction.CANCEL_AGENT_DATA,
  check4: EAction.UPDATE_USER_ACCOUNT,
  check5: EAction.CREATE_REQUEST,
});

const createContext = (value) => {
  return {
    $store: {
      getters: {
        'auth/check': () => value,
      },
    },
  };
};

beforeEach(() => {
  data = createData();
});

describe('mapAccessFlags', () => {
  test('returns flags', () => {
    const actual = mapAccessFlags(data);
    expect(actual).toBeInstanceOf(Object);
    expect(actual).toHaveProperty('check1');
    expect(actual).toHaveProperty('check2');
    expect(actual).toHaveProperty('check3');
    expect(actual).toHaveProperty('check4');
    expect(actual).toHaveProperty('check5');
    expect(typeof actual.check1).toBe('function');
    expect(typeof actual.check2).toBe('function');
    expect(typeof actual.check3).toBe('function');
    expect(typeof actual.check4).toBe('function');
    expect(typeof actual.check5).toBe('function');
  });

  test('flags are valid', () => {
    const flags = mapAccessFlags(data);

    Object.values<Function>(flags).forEach((func) => {
      const value = Number(Math.round(Math.random() * 100) % 2);
      const actual = func.apply(createContext(value));
      expect(actual).toBe(value);
    });
  });
});
