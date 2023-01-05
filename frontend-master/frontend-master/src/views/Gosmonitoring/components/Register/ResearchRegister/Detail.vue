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
import { ResearchRegisterVueModel } from '@/models/Gosmonitoring/ResearchRegister.vue';

@Component({
  name: 'ResearchRegisterDetail',
  components: { DataFields },
})
export default class ResearchRegisterDetail extends Gosmonitoring {
  model: ResearchRegisterVueModel = new ResearchRegisterVueModel();
  url = 'register/research-register';
}
</script>
