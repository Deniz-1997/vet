import sortBy from 'lodash/sortBy';
import isUndefined from 'lodash/isUndefined';
import config from '@/components/NavigationBar/config';
import { TMenuItem } from '@/components/NavigationBar/models';
import { Mapper } from '@/utils';

function checkEnable(this: Vue, enable: TMenuItem['enable']): boolean {
  if (Array.isArray(enable)) {
    return enable.every((action) => this.$store.getters['auth/check'](action));
  }

  if (typeof enable === 'string') {
    return this.$store.getters['auth/check'](enable);
  }

  if (typeof enable === 'function') {
    return enable.apply(this);
  }

  return isUndefined(enable) || enable === true;
}

export default {
  getUserInfo(state) {
    return state.user;
  },
  isAgreementApplied({ user }) {
    return !!user.personal_data_confirmation;
  },
  roles(_, { getUserInfo }) {
    return (getUserInfo.roles || []).map(({ authority }) => authority);
  },
  authorities(_, { getUserInfo }) {
    return (getUserInfo.authorities || []).map(({ authority }) => authority);
  },
  loaded({ matrix, titles }) {
    return matrix.length && titles.length;
  },
  check(state, { authorities, roles }) {
    return (actionType) => {
      if (!authorities?.length) {
        return false;
      }

      const { action, ...access } = state.matrix.find(({ action }) => action === actionType) || {};

      if (!action) {
        return false;
      }

      const check = {
        roles: Object.keys(access).filter((item) => item.startsWith('ROLE_')),
        authorities: Object.keys(access).filter((item) => !item.startsWith('ROLE_')),
      };

      return (
        check.roles.some((role) => roles.includes(role)) &&
        (!check.authorities.length || check.authorities.some((authority) => authorities.includes(authority)))
      );
    };
  },
  title(state, { roles, getUserInfo }) {
    const { prefix, name } = state.titles.find((item) => roles.some((role) => item[role])) || {};
    const fallbackTitle = getUserInfo.roles ? 'Гость' : '';

    return {
      prefix,
      name: name || fallbackTitle,
    };
  },
  pages(_, { loaded }) {
    if (!loaded) {
      return [];
    }

    return sortBy(
      config.reduce<TMenuItem[]>((list, item) => {
        const { enable, ...result } = item;

        const pages = sortBy(
          (result.pages || []).map((item) => ({
            ...item,
            enable: checkEnable.call(Mapper.$ctx, item.enable),
          })),
          'order'
        );
        const isChildrenVisible = pages.some(({ enable }) => enable);
        const isDirectionEnable = isChildrenVisible && checkEnable.call(Mapper.$ctx, enable);

        return [
          ...list,
          {
            ...result,
            enable: isDirectionEnable,
            pages: isDirectionEnable ? pages : pages.map((item) => ({ ...item, enable: false })),
          },
        ];
      }, []),
      'order'
    );
  },
  isPageAvailable(_, { pages }) {
    return (pathTo: string) => {
      const page = pages.reduce((result, item) => {
        const { path, pages } = item;

        if (pages) {
          return pages.find((item) => item.path === pathTo) || result;
        }

        if (path === pathTo) {
          return item || result;
        }

        return result;
      }, null);

      return page ? page.enable : true;
    };
  },
};
