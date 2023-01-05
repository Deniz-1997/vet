<template>
  <rshn-list v-if="accessGrantedAuthorities(model.view_data_privileges)" v-model="model">
    <template #[`filter`]="{ clear }">
      <withdrawal-filter-forms :key="clear" v-model="model" />
    </template>
  </rshn-list>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import RshnList from '@/views/rshn/components/List.vue';
import Rshn from '@/views/rshn/Rshn.vue';
import { RshnWithdrawalData } from '@/models/Rshn/Withdrawal/RshnWithdrawalData.vue';
import WithdrawalFilterForms from '@/views/rshn/subcomponents/FilterForms/WithdrawalFilterForms.vue';
import { PermissionMix } from '@/utils/mixins/permission';

@Component({
  name: 'rshn_withdrawal_list',
  components: { WithdrawalFilterForms, RshnList },
})
export default class RshnWithDrawalList extends Mixins(Rshn, PermissionMix) {
  model: RshnWithdrawalData = new RshnWithdrawalData();
}
</script>
