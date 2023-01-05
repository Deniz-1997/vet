<template>
  <data-fields
    v-if="accessGrantedAuthorities(model.view_data_privileges)"
    v-model="model"
    :detail="true"
    :loading="preloader || isLoading"
    :url="url"
    @event="onCreate"
    @onDelete="onDelete"
    @set-is-loading="setIsLoading"
  />
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import Gosmonitoring from '@/views/Gosmonitoring/Gosmonitoring.vue';
import DataFields from '@/views/Gosmonitoring/components/Register/ResearchRegister/DataFields.vue';
import { ConductedResearchRegisterVue } from '@/models/Gosmonitoring/ConductedResearchRegister.vue';

@Component({
  name: 'ResearchRegisterDetail',
  components: { DataFields },
})
export default class ConductedResearchRegisterDetail extends Gosmonitoring {
  model: ConductedResearchRegisterVue = new ConductedResearchRegisterVue();
  url = 'register/conducted-research-manufacturers';
}
</script>
