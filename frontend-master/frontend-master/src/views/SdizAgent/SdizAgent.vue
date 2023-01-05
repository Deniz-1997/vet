<template>
  <div class="sdiz-agent">
    <v-container>
      <notifications group="sdiz_agent" position="bottom right" />
      <router-view></router-view>
    </v-container>
  </div>
</template>

<script lang="ts">
import { Component, Prop } from 'vue-property-decorator';
import { mixins } from 'vue-class-component';
import { RequestMix } from '@/utils/mixins/request';
import { AdditionalMix } from '@/utils/mixins/additional';
import { PermissionMix } from '@/utils/mixins/permission';

@Component({ name: 'SdizAgent' })
export default class SdizAgent extends mixins(RequestMix, AdditionalMix, PermissionMix) {
  @Prop({ type: String, default: 'Реестр сведений предоставляемых агентом' }) public title!: string;
}
</script>
