<template>
  <div class="main">
    <router-view></router-view>
    <notifications group="rshn" position="bottom right" />
  </div>
</template>

<script lang="ts">
import { Mixins } from 'vue-property-decorator';
import { AdditionalMix } from '@/utils/mixins/additional';
import { ActionsMix } from '@/utils/mixins/actions';
import { Component } from 'vue-property-decorator';
import { dateFrom } from '@/utils/date';
import { RshnWithdrawalData } from '@/models/Rshn/Withdrawal/RshnWithdrawalData.vue';
import { RshnPrescriptionData } from '@/models/Rshn/Prescription/RshnPrescriptionData.vue';
import { RshnExpertiseData } from '@/models/Rshn/Expertise/RshnExpertiseData.vue';
import { rshnConsts } from '@/utils/consts/rshnConsts';
import { getElementById } from '@/utils/methodsForViews';

@Component({
  name: 'rshn',
})
export default class Rshn extends Mixins(AdditionalMix, ActionsMix) {
  clear = 0;
  rshn = rshnConsts;
  loading = false;
  edit = false;

  setEdit(v) {
    this.edit = v;
  }
  showItem: string | null = null;
  isClearFiltersAndReloadRows = false;
  callbackLoadList(model: any, modelArray: RshnWithdrawalData[] | RshnPrescriptionData[] | RshnExpertiseData[]) {
    return modelArray;
  }
  fromDate(date) {
    return dateFrom(date, -1);
  }
  routerShowPush(id: string, name: string) {
    this.$router.push({ name: name, params: { id } });
  }
  async findElementById(id) {
    return await getElementById(this, id);
  }
}
</script>
