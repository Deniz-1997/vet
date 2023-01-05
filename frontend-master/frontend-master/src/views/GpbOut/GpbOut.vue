<template>
  <div class="main">
    <router-view></router-view>
    <notifications group="gpb-out" position="bottom right" />
  </div>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import { RequestMix } from '@/utils/mixins/request';
import { FiasMix } from '@/utils/mixins/fias';
import { AdditionalMix } from '@/utils/mixins/additional';
import { ActionsMix } from '@/utils/mixins/actions';
import { dateFrom } from '@/utils/date';
import { GpbOutDataVueModel } from '@/models/Lot/GpbOut/GpbOutData.vue';
import { PermissionMix } from '@/utils/mixins/permission';

@Component({
  name: 'gpb-out',
})
export default class GpbOut extends Mixins(RequestMix, FiasMix, AdditionalMix, ActionsMix, PermissionMix) {
  callbackLoadList(model: any, modelArray: GpbOutDataVueModel[]) {
    return modelArray;
  }
  fromDate(date) {
    return dateFrom(date, -1);
  }
  routerShowPush(id: string, name: string) {
    this.$router.push({ name: name, params: { id } });
  }

  created() {
    this.fetchLotsPurpose();
  }
}
</script>

<style scoped></style>
