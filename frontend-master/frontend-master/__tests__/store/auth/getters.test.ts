import { ERole, EAuthority, EAction } from '@/models/roles';
import getters from '@/store/auth/getters';
import titles from './__fixtures__/roleTitles';
import matrix from './__fixtures__/accessMatrix';

let state: ReturnType<typeof createState>;

const createState = () => {
  const user = {
    $roles: [ERole.ROLE_ADMIN, ERole.ROLE_ELEVATOR_USER, ERole.ROLE_GOVERMENT_USER, ERole.ROLE_MCX_USER],
    $authorities: [EAuthority.MANAGE_SUBJECT_GOVMONITORING, EAuthority.VIEW_REQUEST],
    get roles() {
      if (!this.$roles) return null;
      return this.$roles.map((authority) => ({ authority }));
    },
    set roles(v) {
      this.$roles = v as any;
    },
    get authorities() {
      return this.$authorities.map((authority) => ({ authority }));
    },
    set authorities(v) {
      this.$authorities = v as any;
    },
  };

  return {
    user,
    titles,
    matrix,
  };
};

const callGetter = (getter) => {
  return getter.call(null, state, {
    get getUserInfo() {
      return getters.getUserInfo.call(null, state);
    },
    get roles() {
      return getters.roles.call(null, state, { getUserInfo: this.getUserInfo });
    },
    get authorities() {
      return getters.authorities.call(null, state, { getUserInfo: this.getUserInfo });
    },
  });
};

beforeEach(() => {
  state = createState();
});

describe('store auth getters', () => {
  test('getUserInfo', () => {
    const actual = callGetter(getters.getUserInfo);

    expect(actual).toEqual(state.user);
  });

  test('roles', () => {
    const actual = callGetter(getters.roles);

    expect(actual).toStrictEqual(state.user.$roles);
  });

  test('authorities', () => {
    const actual = callGetter(getters.authorities);

    expect(actual).toStrictEqual(state.user.$authorities);

    state.user.$authorities = [];

    const actual2 = callGetter(getters.authorities);

    expect(actual2).toStrictEqual([]);
  });

  test('title', () => {
    state.user.roles = null as any;
    const actual2 = callGetter(getters.title);
    expect(actual2).toMatchObject({ name: '' });

    state.user.roles = [];
    const actual1 = callGetter(getters.title);
    expect(actual1).toMatchObject({ name: 'Гость' });

    const checkTitle = (authority, name, prefix = 'Личный кабинет') => {
      state.user.$roles.push(authority);
      const actual = callGetter(getters.title);
      expect(actual).toEqual({ prefix, name });
    };

    checkTitle(ERole.ROLE_GOVERMENT_USER, 'Органа государственной власти');
    checkTitle(ERole.ROLE_AGENT_USER, 'Товаропроизводителя');
    checkTitle(ERole.ROLE_FEDERAL_RESERVE, 'сотрудника Федерального агентства по государственным резервам');
    checkTitle(
      ERole.ROLE_GOVERMENT_MONITORING,
      'сотрудника организации, осуществляющей государственный мониторинг зерна'
    );
    checkTitle(ERole.ROLE_SUBJECT_ADMIN, 'сотрудника Министерства сельского хозяйства');
  });

  test('check', () => {
    const checkAccess = (action, expected) => {
      const actual = callGetter(getters.check)(action);
      expect(actual).toBe(expected);
    };

    checkAccess(EAction.CUSTOMIZE_LABORATORY_REGISTER, false);
    checkAccess(EAction.READ_REQUEST_REGISTER, true);
    checkAccess(EAction.CREATE_LOT_NUMBER, true);
    checkAccess(EAction.FILTER_GRAIN_LOT_REGISTER, false);
    checkAccess(EAction.READ_DICTIONARY_RECORD, false);

    state.user.$authorities.push(EAuthority.VIEW_NCI);
    checkAccess(EAction.READ_DICTIONARY_RECORD, true);

    state.user.$authorities = [];
    checkAccess(EAction.READ_DICTIONARY_RECORD, false);
    checkAccess(EAction.READ_DICTIONARY_RECORD, false);
    checkAccess(EAction.READ_REQUEST_REGISTER, false);

    state.matrix = [];
    checkAccess(EAction.READ_DICTIONARY_RECORD, false);
    checkAccess(EAction.READ_DICTIONARY_RECORD, false);
    checkAccess(EAction.READ_REQUEST_REGISTER, false);
  });
});
