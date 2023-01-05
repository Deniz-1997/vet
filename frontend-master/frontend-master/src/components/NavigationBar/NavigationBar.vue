<template>
  <div class="sidebar">
    <div class="sidebar-menu">
      <v-expansion-panels v-model="panel" accordion>
        <v-expansion-panel v-for="item in configMenu" :key="item.label">
          <v-expansion-panel-header class="sidebar-menu__label">
            <div class="sidebar-menu__title">{{ item.label }}</div>
          </v-expansion-panel-header>
          <v-expansion-panel-content class="sidebar-menu__item">
            <div v-for="page in item.pages" :key="page.label">
              <router-link v-if="!page.download" :to="page.path" class="sidebar-menu__link sidebar-menu__label">
                <div
                  :class="{
                    'sidebar-menu__label--active': $route.path.startsWith(page.path),
                    'sidebar-menu__label': $route.path !== page.path,
                  }"
                >
                  {{ page.label }}
                </div>
              </router-link>

              <a v-else :href="page.path" :download="page.label" class="sidebar-menu__link sidebar-menu__label">
                <span class="sidebar-menu__label">{{ page.label }}</span>
              </a>
            </div>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>

      <div v-if="isAuditor" class="v-expansion-panel-header sidebar-menu__label">
        <a class="sidebar-menu__title sidebar-menu__title--link" @click="(e) => redirect(e)">
          Аналитическая подсистема
        </a>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Watch } from 'vue-property-decorator';
import Cookie from 'js-cookie';
import { ERole } from '@/models/roles';

@Component({
  name: 'navigation-bar',
  data: () => ({ panel: null }),
})
export default class NavigationBar extends Vue {
  panel?: number = undefined;

  get userInfo() {
    return this.$store.getters['auth/getUserInfo'];
  }

  get isAuditor() {
    return this.$store.getters['auth/roles'].includes(ERole.ROLE_AUDITOR);
  }

  get linkModule() {
    // const baseUrl = window.location;
    // eslint-disable-next-line no-console
    return `http://172.25.14.216:8080/lui2/?AppCode=MSH_ZERNO_IAM?auth=${Cookie.get('access_token')}`;
  }

  get configMenu() {
    return this.$store.getters['auth/pages'].reduce((result, item) => {
      return item.enable
        ? [
            ...result,
            {
              ...item,
              pages: (item?.pages || []).filter(({ enable }) => enable),
            },
          ]
        : result;
    }, []);
  }

  redirect(evt) {
    evt.preventDefault();
    window.open(this.linkModule, '_blank');
  }

  onNavigationUpdate() {
    const value = this.configMenu.findIndex(({ path, pages }) => {
      return (
        this.$route.path === path ||
        pages.some((item) => {
          return this.$route.path.startsWith(item.path);
        })
      );
    });

    this.panel = value >= 0 ? value : undefined;
  }

  @Watch('$route', { deep: true })
  @Watch('configMenu')
  _handleChangeMenuConfig() {
    this.onNavigationUpdate();
  }
}
</script>

<style lang="scss">
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.sidebar {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  border-right: 1px solid $light-grey-color;
  width: 380px;
  height: 100%;
}

.sidebar-menu {
  height: 100%;

  &__title {
    font-size: 14px;
    padding-right: 40px;

    &--link {
      color: #242424 !important;

      &:hover {
        color: $gold-dark-color !important;
      }
    }
  }

  &__link {
    display: block;
    padding: 8px 0;

    .sidebar-menu__label {
      color: $medium-grey-color;

      &:hover {
        color: $gold-dark-color;
      }

      &--active {
        color: $gold-dark-color;
      }
    }
  }

  .v-expansion-panel-header {
    display: flex;
    justify-content: space-between;
    position: relative;
    line-height: 1.5;

    &::after {
      content: '';
      height: 24px;
      width: 24px;
      display: block;
      position: absolute;
      right: 0;
      top: 0;
    }

    &--active {
      color: $gold-dark-color;
    }
  }

  &__label {
    color: $black-color;
    font-size: 14px;

    @include respond-to('medium') {
      font-size: 14px;
    }

    @include respond-to('small') {
      font-size: 12px;
    }
  }

  .v-expansion-panel {
    border: none;
    border-bottom: 1px solid $light-grey-color;

    &::before {
      border: none;
      box-shadow: none;
    }

    &::after {
      box-shadow: none;
      border: none !important;
    }
  }

  .v-expansion-panel-content__wrap {
    padding-top: 0;
    padding-left: 48px;
  }
}
</style>
