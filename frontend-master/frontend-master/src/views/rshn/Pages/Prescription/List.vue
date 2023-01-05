<template>
  <rshn-list
    v-if="accessGrantedAuthorities(model.view_data_privileges)"
    v-model="model"
    title="Реестр выданных предписаний"
    button-title="Выписать предписание"
  >
    <template #[`filter`]="{ clear }">
      <PrescriptionFilterForms :key="clear" v-model="model"></PrescriptionFilterForms>
    </template>
  </rshn-list>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import RshnList from '@/views/rshn/components/List.vue';
import Rshn from '@/views/rshn/Rshn.vue';
import { RshnPrescriptionData } from '@/models/Rshn/Prescription/RshnPrescriptionData.vue';
import PrescriptionFilterForms from '@/views/rshn/subcomponents/FilterForms/PrescriptionFilterForms.vue';
import { PermissionMix } from '@/utils/mixins/permission';

@Component({
  components: { PrescriptionFilterForms, RshnList },
})
export default class RshnPrescriptionList extends Mixins(Rshn, PermissionMix) {
  model: RshnPrescriptionData = new RshnPrescriptionData();
}
</script>
