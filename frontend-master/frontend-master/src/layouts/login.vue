<template>
  <v-app class="app">
    <main class="login-bg">
      <div class="main-part">
        <section class="section section--full-width">
          <slot />
        </section>
      </div>
    </main>
    <footer-component />
  </v-app>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

import FooterComponent from '@/components/Footer/Footer.vue';
import NotificationCenter from '@/components/NotificationCenter/NotificationCenter.vue';

@Component({
  components: {
    FooterComponent,
    NotificationCenter,
  },
})
export default class extends Vue {}
</script>

<style lang="scss">
@import './layout';

.login-bg {
  background-image: url('/images/bg.png');
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover;
}
</style>
