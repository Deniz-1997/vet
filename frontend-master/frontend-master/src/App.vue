<template>
  <UiPageLayout>
    <router-view v-if="!isLoading" />
    <v-overlay :value="isLoading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </UiPageLayout>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

import { PluginChain } from '@/utils';
import services from '@/services';
import ServicePlugin from '@/plugins/service';
import AxiosPlugin from '@/plugins/axios';
import Config from '@/plugins/config';

const defaultTitle = process.env.NODE_ENV === 'development' ? 'GISZP-DEV' : 'Министерство сельского хозяйства РФ';

@Component({
  name: 'app',
  metaInfo() {
    return {
      titleTemplate: `%s | ${defaultTitle}`,
    };
  },
})
export default class App extends Vue {
  isLoading = true;

  async created() {
    const connector = new PluginChain(this);
    await Promise.all([connector.use(Config), connector.use(AxiosPlugin), connector.use(ServicePlugin, services)]);
    if (this.$config.isDev) {
      window['$giszp'] = this;
    }
    await this.$service.auth.updateRoleModel();
    this.isLoading = false;
  }
}
</script>

<style lang="scss">
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';
@import '@/assets/styles/_global';
@import '@/assets/styles/_grid';
@import '@/assets/styles/_typography';
@import '@/assets/styles/_buttons';
@import '@/assets/styles/_form_elements';
@import './node_modules/vue-select/src/scss/vue-select';

.app {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  min-height: 100vh;
}

.section {
  position: relative;
  overflow: hidden;

  &--full-width {
    width: 100%;
  }
}

.main-part {
  display: flex;
  min-height: calc(100vh - 200px);
  overflow-y: auto;
}

body {
  overflow: hidden;
}

* {
  padding: 0;
  margin: 0;
}

::-webkit-scrollbar {
  width: 13px;
  height: 13px;

  @include respond-to('medium') {
    width: 12px;
    height: 12px;
  }
}

::-webkit-scrollbar-track {
  background: transparent;
}

::-webkit-scrollbar-thumb {
  background: $webkit-color;
  border-radius: 25px;
}

::-webkit-scrollbar-thumb:hover {
  background: $webkit-color;
}

::-webkit-scrollbar-corner {
  background: transparent;
}
</style>
