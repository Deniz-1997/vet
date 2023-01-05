<template>
  <div class="header">
    <v-container fluid class="header-top">
      <div class="logo">
        <router-link to="/home">
          <img src="/icons/logo.svg" />
        </router-link>
      </div>
      <v-row>
        <v-col cols="12">
          <v-row>
            <v-col cols="7">
              <div class="title-arm">
                <v-row>
                  <v-col cols="12">
                    <div v-if="nameArm.prefix" class="spanTitle">{{ nameArm.prefix }}</div>
                    <div class="spanTitle">{{ nameArm.name }}</div>
                  </v-col>
                </v-row>
              </div>
            </v-col>

            <v-col cols="5">
              <div class="user-panel">
                <div class="user-panel__item">
                  <div class="user-info__wrapper">
                    <div class="user" @click="isModalShow.user = true">{{ clientName }}</div>
                    <v-tooltip v-if="companyName.length > 55" bottom>
                      <template #activator="{ on, attrs }">
                        <span v-bind="attrs" class="company" v-on="on">
                          {{ companyName.slice(0, 55) + '...' }}
                        </span>
                      </template>
                      <span>{{ companyName }}</span>
                    </v-tooltip>
                    <div v-else class="company">{{ companyName }}</div>
                  </div>
                  <img
                    v-if="companies.length > 1"
                    src="/icons/changeOrg.svg"
                    class="changeOrg"
                    @click="isModalShow.company = true"
                  />
                </div>
                <router-link
                  v-if="canReadNotificationRegister"
                  to="/notifications"
                  class="link user-panel__list user-panel__item"
                >
                  <div class="user-panel__icon">
                    <svg width="17" height="19" viewBox="0 0 17 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path
                        d="M6.20898 16.7217C6.68293 17.7674 7.75369 18.5002 8.99999 18.5002C10.2463 18.5002 11.317 17.7674 11.791 16.7217H6.20898Z"
                        fill="#828286"
                      />
                      <path
                        d="M16.67 14.4757C16.6086 14.4371 16.1654 14.1329 15.7134 13.2071C14.8927 11.51 14.7172 9.11857 14.7172 7.41286C14.7172 7.40429 14.7172 7.4 14.7172 7.39143C14.7084 5.12 13.3041 3.17 11.2987 2.30429C11.11 1.28 10.1928 0.5 9.09131 0.5H8.907C7.80552 0.5 6.88835 1.28 6.69965 2.3C4.68539 3.17 3.28111 5.13286 3.28111 7.41286C3.28111 9.11857 3.10996 11.51 2.28495 13.2071C1.83733 14.1329 1.38972 14.4371 1.32828 14.4757C1.07814 14.5871 0.95527 14.8486 1.01232 15.1143C1.06937 15.38 1.32828 15.5643 1.60475 15.5643H16.3892C16.67 15.5643 16.9246 15.38 16.9816 15.1143C17.043 14.8486 16.9202 14.5871 16.67 14.4757Z"
                        fill="#828286"
                      />
                    </svg>

                    <div v-if="notificationsCount" class="user-panel__counter">
                      {{ notificationsCount > 99 ? '99+' : notificationsCount }}
                    </div>
                  </div>
                </router-link>

                <div class="user-panel__list user-panel__item" @click="handleLogOut">
                  <div class="user-panel__icon">
                    <img src="/icons/log_out.svg" />
                  </div>
                </div>
              </div>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-container>

    <div class="breadcrumbs">
      <v-container fluid>
        <v-row>
          <v-col cols="12"> <Breadcrumbs /></v-col>
        </v-row>
      </v-container>
    </div>
    <UserCardModal v-model="isModalShow.user" />
    <CompanyPickerModal
      v-model="isModalShow.company"
      :companies="companies"
      :current-company-id="currentCompany.subject_id"
    />
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import Breadcrumbs from '@/components/common/Breadcrumbs/Breadcrumbs.vue';
import { EAction, mapAccessFlags, Polling } from '@/utils';
import CompanyPickerModal from '@/views/Login/components/CompanyPickerModal.vue';
import UserCardModal from '@/components/User/UserCardModal.vue';
import { SubjectItem } from '@/services/mappers/auth';

@Component({
  name: 'header-component',
  components: {
    Breadcrumbs,
    UserCardModal,
    CompanyPickerModal,
  },
  computed: {
    ...mapAccessFlags({
      canReadNotificationRegister() {
        return [EAction.VIEW_NOTIFICATION_REGISTRY, EAction.VIEW_NOTIFICATION_USER_REGISTRY].some((action) =>
          this.$store.getters['auth/check'](action)
        );
      },
    }),
  },
})
export default class HeaderComponent extends Vue {
  readonly canReadNotificationRegister!: boolean;
  polling!: Polling;
  isModalShow = {
    company: false,
    user: false,
  };
  companies: ReturnType<SubjectItem['toJSON']>[] = [];

  get notificationsCount() {
    return this.$store.getters['notifications/count'];
  }

  get userInfo() {
    return this.$store.getters['auth/getUserInfo'];
  }

  get currentCompany() {
    return this.userInfo?.subject || {};
  }

  get companyName(): string {
    const { short_name, name = '', inn, kpp } = this.currentCompany;
    return `${short_name || name}${inn ? ' (' + inn + (kpp ? '/' + kpp : '') + ')' : ''}`;
  }

  get clientName() {
    const { first_name, last_name, email, username } = this.userInfo;

    if (!first_name && !last_name) {
      return email || username;
    }

    return [first_name, last_name].join(' ');
  }

  get nameArm() {
    return this.$store.getters['auth/title'];
  }

  async created() {
    const userInfo = await this.$store.dispatch('user/getInfo');
    this.$store.commit('auth/setUserInfo', userInfo);

    const { data } = await this.$service.auth.getOrganizations();
    if (data.length > 1) {
      this.companies = data;
    }

    if (this.canReadNotificationRegister) {
      this.polling = new Polling('short', {
        id: 'notification-count',
        delay: this.$config.shortPolling.notifications,
        callback: () => this.$service.notification.getCount(),
      });
      this.polling.run();
    }
  }

  beforeDestroy() {
    if (this.canReadNotificationRegister) {
      this.polling.stop();
    }
  }

  async handleLogOut() {
    this.$service.auth.logout();
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_colors';
@import '@/assets/styles/_mixins';

.container {
  width: 99%;

  @include respond-to('medium') {
    width: 98.5%;
  }

  @include respond-to('small') {
    width: 97.5%;
  }
}

.header {
  position: relative;
  width: 100%;

  .row {
    justify-content: space-between;
  }
}

.spanTitle {
  color: $white-color;
  font-family: $font-stack !important;
  font-weight: 500;
  font-size: 20px;
  min-height: 24px;
  line-height: 24px;

  @include respond-to('medium') {
    font-size: 18px;
  }

  @include respond-to('small') {
    font-size: 16px;
  }
}
.changeOrg {
  margin-left: 5px;
  height: 35px;
  width: 35px;
  cursor: pointer;
}

/**New styles */

.header {
  background-image: url('/images/background.jpg');
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover;

  &-top {
    height: 64px;
    padding-left: 168px;
    display: flex;
    align-items: center;
  }
}

.logo {
  position: absolute;
  top: 20px;
  left: 48px;
  height: 88px;
  width: 88px;

  a {
    display: block;
    width: 100%;
    height: 100%;
  }
}

.title-arm {
  font-weight: 500;
  font-size: 24px;
  line-height: 32px;
  letter-spacing: -0.01em;
  font-feature-settings: 'case' on, 'kern' off;
  color: $white-color;
}

.user-panel {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  padding-top: 5px;
  position: relative;

  &__counter {
    background: $red-bg;
    border-radius: 100%;
    width: 22px;
    color: $white-color;
    height: 22px;
    position: absolute;
    top: -6px;
    right: 12px;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  &__icon {
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
  }

  &__item {
    align-items: center;
    display: flex;
    padding-left: 24px;
    padding-right: 24px;
    border-right: 1px solid rgba($white-bg, 0.2);
    height: 35px;
    position: relative;

    &:last-child {
      border-right: 0;
      padding-right: 0;
    }
  }

  .user-info {
    &__wrapper {
      font-size: 14px;
      line-height: 16px;
      text-align: right;
      font-feature-settings: 'zero' on;
      color: $white-color;
      font-weight: 400;
      margin-right: 6px;
      cursor: pointer;
    }
  }

  .company {
    color: $gray-color;
    white-space: nowrap;
  }
}

.breadcrumbs {
  border-top: 1px solid rgba($white-bg, 0.2);
  height: 64px;
  display: flex;
  align-items: center;
  padding-left: 168px;

  .container {
    margin: 0;
  }
}
</style>
