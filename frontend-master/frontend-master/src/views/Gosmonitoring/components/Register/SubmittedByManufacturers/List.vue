<template>
  <v-container v-if="accessGrantedAuthorities(model.register_reads_privileges)">
    <gosmonitoring-list-component v-model="model" :additional-button="false">
      <template #[`filter`]="{ clear }">
        <gosmonitoring-implementation-block-filters-list :key="clear" v-model="model" show-filter />
      </template>
    </gosmonitoring-list-component>
  </v-container>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import Gosmonitoring from '@/views/Gosmonitoring/Gosmonitoring.vue';
import GosmonitoringImplementationBlockFiltersList from '@/views/Gosmonitoring/components/Blocks/GosmonitoringImplementationBlockFiltersList.vue';
import PageComponent from '@/components/Forms/PageComponent.vue';
import { SubmitedByManufacturerModel } from '@/models/Gosmonitoring/SubmitedByManufacturers.vue';
import GosmonitoringListComponent from '@/views/Gosmonitoring/components/Register/List.vue';

@Component({
  name: 'SubmittedByManufacturersList',
  components: {
    GosmonitoringListComponent,
    PageComponent,
    GosmonitoringImplementationBlockFiltersList,
  },
})
export default class SubmittedByManufacturersList extends Gosmonitoring {
  model: SubmitedByManufacturerModel = new SubmitedByManufacturerModel();
}
</script>
