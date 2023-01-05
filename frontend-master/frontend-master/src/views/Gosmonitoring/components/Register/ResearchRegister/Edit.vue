<template>
  <data-fields
    v-if="accessGrantedAuthorities(model.update_privileges)"
    v-model="model"
    :edit="true"
    :loading="preloader || isLoading"
    @event="onUpdate"
    @set-is-loading="setIsLoading"
  />
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import { ResearchRegisterVueModel } from '@/models/Gosmonitoring/ResearchRegister.vue';
import DataFields from '@/views/Gosmonitoring/components/Register/ResearchRegister/DataFields.vue';
import Gosmonitoring from '@/views/Gosmonitoring/Gosmonitoring.vue';

@Component({
  name: 'ResearchRegisterEdit',
  components: { DataFields },
})
export default class ResearchRegisterEdit extends Gosmonitoring {
  model: ResearchRegisterVueModel = new ResearchRegisterVueModel();
  url = 'register/research-register';
}
</script>
