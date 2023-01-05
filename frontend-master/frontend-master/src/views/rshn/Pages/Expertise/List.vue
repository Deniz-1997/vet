<template>
  <rshn-list
    v-if="accessGrantedAuthorities(model.view_data_privileges)"
    v-model="model"
    title="Реестр экспертиз"
    button-title="Внести данные об экспертизе"
  >
    <template #[`filter`]="{ clear }">
      <ExpertiseFilterForms :key="clear" v-model="model" />
    </template>
  </rshn-list>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import RshnList from '@/views/rshn/components/List.vue';
import Rshn from '@/views/rshn/Rshn.vue';
import { RshnExpertiseData } from '@/models/Rshn/Expertise/RshnExpertiseData.vue';
import ExpertiseFilterForms from '@/views/rshn/subcomponents/FilterForms/ExpertiseFilterForms.vue';
import { PermissionMix } from '@/utils/mixins/permission';

@Component({
  components: { ExpertiseFilterForms, RshnList },
})
export default class RshnExpertiseList extends Mixins(Rshn, PermissionMix) {
  model: RshnExpertiseData = new RshnExpertiseData();
}
</script>
