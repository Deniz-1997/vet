<template>
  <v-container v-if="accessGrantedAuthorities(model.view_data_privileges)">
    <rshn-prescription-form v-model="model" create></rshn-prescription-form>
  </v-container>
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
export default class RshnPrescriptionCreate extends Mixins(Rshn, PermissionMix) {
  model: RshnPrescriptionData = new RshnPrescriptionData();

  async created() {
    this.model.legal_operator_id = this.$store.state.auth.user['subject']?.subject_id;
    this.model.legal_operator = this.$store.state.auth.user['subject'];
    (this.model.operator as any).full_name = this.$store.state.auth.user.fullName;
  }
}
</script>
