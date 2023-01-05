<template>
  <v-container v-if="accessGrantedAuthorities(model.list_privelegies)">
    <gosmonitoring-list-component v-model="model" :additional-button="false">
      <template #[`filter`]="{ clear }">
        <gosmonitoring-research-register-block-filters-list :key="clear" v-model="model" />
      </template>
    </gosmonitoring-list-component>
  </v-container>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import Gosmonitoring from '@/views/Gosmonitoring/Gosmonitoring.vue';
import GosmonitoringResearchRegisterBlockFiltersList from '@/views/Gosmonitoring/components/Blocks/GosmonitoringResearchRegisterBlockFiltersList.vue';
import PageComponent from '@/components/Forms/PageComponent.vue';
import GosmonitoringListComponent from '@/views/Gosmonitoring/components/Register/List.vue';
import { ConductedResearchRegisterVue } from '@/models/Gosmonitoring/ConductedResearchRegister.vue';

@Component({
  name: 'ConductedResearchManufacturersList',
  components: {
    GosmonitoringListComponent,
    PageComponent,
    GosmonitoringResearchRegisterBlockFiltersList,
  },
})
export default class ConductedResearchManufacturersList extends Gosmonitoring {
  model: ConductedResearchRegisterVue = new ConductedResearchRegisterVue();
}
</script>

<style lang="scss" scoped></style>
