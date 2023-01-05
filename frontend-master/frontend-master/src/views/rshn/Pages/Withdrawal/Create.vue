<template>
  <v-container v-if="accessGrantedAuthorities(model.view_data_privileges)">
    <rshn-withdrawal-form v-model="model" create></rshn-withdrawal-form>
  </v-container>
</template>
<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import Rshn from '@/views/rshn/Rshn.vue';
import RshnWithdrawalForm from '@/views/rshn/Pages/Withdrawal/From.vue';
import { RshnWithdrawalData } from '@/models/Rshn/Withdrawal/RshnWithdrawalData.vue';
import { PermissionMix } from '@/utils/mixins/permission';

@Component({
  name: 'rshn-withdrawal-create',
  components: {
    RshnWithdrawalForm,
  },
})
export default class RshnWithdrawalCreate extends Mixins(Rshn, PermissionMix) {
  model: RshnWithdrawalData = new RshnWithdrawalData();
}
</script>
