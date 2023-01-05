<template>
  <v-container v-if="accessGrantedAuthorities(model.list_privelegies)">
    <gosmonitoring-list-component v-model="model">
      <template #[`filter`]="{ clear }">
        <gosmonitoring-research-register-block-filters-list :key="clear" v-model="model" show-filter />
      </template>
    </gosmonitoring-list-component>
  </v-container>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import Gosmonitoring from '@/views/Gosmonitoring/Gosmonitoring.vue';
import { ResearchRegisterVueModel } from '@/models/Gosmonitoring/ResearchRegister.vue';
import GosmonitoringResearchRegisterBlockFiltersList from '@/views/Gosmonitoring/components/Blocks/GosmonitoringResearchRegisterBlockFiltersList.vue';
import PageComponent from '@/components/Forms/PageComponent.vue';
import GosmonitoringListComponent from '@/views/Gosmonitoring/components/Register/List.vue';

@Component({
  name: 'ResearchRegisterList',
  components: { GosmonitoringListComponent, PageComponent, GosmonitoringResearchRegisterBlockFiltersList },
})
export default class ResearchRegisterList extends Gosmonitoring {
  model: ResearchRegisterVueModel = new ResearchRegisterVueModel();
}
</script>
