<template>
  <data-fields
    v-if="accessGrantedAuthorities(model.create_privileges)"
    v-model="model"
    :create="true"
    :loading="preloader || isLoading"
    @event="onCreate"
    @quality-indicators-loading="setIsLoading"
  />
</template>
<script lang="ts">
import { Component } from 'vue-property-decorator';
import { ResearchRegisterVueModel } from '@/models/Gosmonitoring/ResearchRegister.vue';
import DataFields from '@/views/Gosmonitoring/components/Register/ResearchRegister/DataFields.vue';
import Gosmonitoring from '@/views/Gosmonitoring/Gosmonitoring.vue';

@Component({
  name: 'ResearchRegisterCreate',
  components: { DataFields },
})
export default class ResearchRegisterCreate extends Gosmonitoring {
  model: ResearchRegisterVueModel = new ResearchRegisterVueModel();
  url = 'register/research-register';
}
</script>
