<template>
  <v-container v-if="accessGrantedAuthorities(model.register_read_privileges)">
    <gosmonitoring-list-component v-model="model">
      <template #[`filter`]="{ clear }">
        <gosmonitoring-implementation-block-filters-list :key="clear" v-model="model" hide-manufacture-list />
      </template>
    </gosmonitoring-list-component>
  </v-container>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import Gosmonitoring from '@/views/Gosmonitoring/Gosmonitoring.vue';
import { ImplementationVueModel } from '@/models/Gosmonitoring/Implementation.vue';
import GosmonitoringImplementationBlockFiltersList from '@/views/Gosmonitoring/components/Blocks/GosmonitoringImplementationBlockFiltersList.vue';
import PageComponent from '@/components/Forms/PageComponent.vue';
import GosmonitoringListComponent from '@/views/Gosmonitoring/components/Register/List.vue';

@Component({
  name: 'ImplementationList',
  components: {
    GosmonitoringListComponent,
    PageComponent,
    GosmonitoringImplementationBlockFiltersList,
  },
})
export default class ImplementationList extends Gosmonitoring {
  model: ImplementationVueModel = new ImplementationVueModel();
}
</script>
