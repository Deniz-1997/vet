<template>
  <rshn-prescription-form
    v-if="model.id && accessGrantedAuthorities(model.view_data_privileges)"
    v-model="model"
    :update-link="model.update_apiendpoint"
    detail
    :edit="edit"
    @edit="setEdit"
  ></rshn-prescription-form>
</template>
<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import Rshn from '@/views/rshn/Rshn.vue';
import { RshnPrescriptionData } from '@/models/Rshn/Prescription/RshnPrescriptionData.vue';
import RshnPrescriptionForm from '@/views/rshn/Pages/Prescription/From.vue';
import { PermissionMix } from '@/utils/mixins/permission';

@Component({
  components: {
    RshnPrescriptionForm,
  },
})
export default class RshnPrescriptionDetail extends Mixins(Rshn, PermissionMix) {
  model: RshnPrescriptionData = new RshnPrescriptionData();

  async created(): Promise<void> {
    this.showItem = this.model.show_apiendpoint;
    await this.findElementById(parseInt(this.$route.params.id));
  }
}
</script>
