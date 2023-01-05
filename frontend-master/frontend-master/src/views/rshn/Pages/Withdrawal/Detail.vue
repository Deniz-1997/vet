<template>
  <rshn-withdrawal-form
    v-if="model.id && accessGrantedAuthorities(model.view_data_privileges)"
    v-model="model"
    :update-link="model.update_apiendpoint"
    detail
    :edit="edit"
    @edit="setEdit"
  ></rshn-withdrawal-form>
</template>
<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import Rshn from '@/views/rshn/Rshn.vue';
import RshnWithdrawalForm from '@/views/rshn/Pages/Withdrawal/From.vue';
import { RshnWithdrawalData } from '@/models/Rshn/Withdrawal/RshnWithdrawalData.vue';
import { PermissionMix } from '@/utils/mixins/permission';

@Component({
  name: 'rshn-withdrawal-detail',
  components: {
    RshnWithdrawalForm,
  },
})
export default class RshnWithdrawalDetail extends Mixins(Rshn, PermissionMix) {
  model: RshnWithdrawalData = new RshnWithdrawalData();

  async created(): Promise<void> {
    this.showItem = this.model.show_apiendpoint;
    await this.findElementById(parseInt(this.$route.params.id));
  }
}
</script>
