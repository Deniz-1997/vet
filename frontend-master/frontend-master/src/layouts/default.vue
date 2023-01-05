<template>
  <v-app class="app">
    <header-component />
    <main>
      <div class="main-part">
        <aside>
          <navigation-bar />
        </aside>
        <section class="section">
          <NotificationCenter />
          <slot v-if="!isLoading" />
          <v-overlay :value="isLoading">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
          </v-overlay>
        </section>
      </div>
    </main>
    <footer-component />
    <AgreementModal v-model="isShowConfirm" @apply="onApply" />
  </v-app>
</template>

<script lang="ts">
import { Component, Vue, Watch } from 'vue-property-decorator';
import isUndefined from 'lodash/isUndefined';

import AgreementModal from '@/views/Login/components/AgreementModal.vue';
import HeaderComponent from '@/components/Header/Header.vue';
import NavigationBar from '@/components/NavigationBar/NavigationBar.vue';
import FooterComponent from '@/components/Footer/Footer.vue';
import NotificationCenter from '@/components/NotificationCenter/NotificationCenter.vue';
import { Route } from 'vue-router';

@Component({
  components: {
    HeaderComponent,
    FooterComponent,
    NavigationBar,
    AgreementModal,
    NotificationCenter,
  },
})
export default class extends Vue {
  isShowConfirm = false;
  isLoading = false;

  get isAgreementApplied() {
    return this.$store.getters['auth/isAgreementApplied'];
  }

  async created() {
    this.isLoading = true;
    await this.$service.auth.updateRoleModel();
    this.checkAccess(this.$route, {}, (path) => {
      if (path) {
        this.$router.push(path);
      }
    });
    this.$root.$router.beforeEach(this.onBeforeEach);
    this.isLoading = false;
  }

  onBeforeEach(to, from, next) {
    this.checkAccess(to, from, next);
    this.checkRouteValidity(to, from, next);
  }

  checkRouteValidity(to: Route, from: Route, next) {
    if (to.path.includes('/login') && this.$service.auth.isLoggedIn) {
      return next(from.matched[from.matched.length - 1]?.parent?.path ?? '/home');
    }

    return next();
  }

  checkAccess(to, _from, next) {
    if (isUndefined(to.meta?.auth)) {
      return this.$service.auth.checkPageAccess(to.path, next);
    }

    return next();
  }

  async onApply() {
    await this.$service.auth.applyAgreement();
    this.isShowConfirm = false;
  }

  @Watch('isAgreementApplied', { immediate: true })
  $onUpdateAgreement(value) {
    this.isShowConfirm = !value;
  }
}
</script>

<style lang="scss">
@import './layout';
</style>
