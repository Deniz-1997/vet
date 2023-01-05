<template>
  <data-fields
    v-if="accessGrantedAuthorities(model.create_privileges)"
    v-model="model"
    :create="true"
    :loading="preloader || isLoading"
    @event="onCreate"
  />
</template>
<script lang="ts">
import { Component } from 'vue-property-decorator';
import Gosmonitoring from '@/views/Gosmonitoring/Gosmonitoring.vue';
import { ImplementationVueModel } from '@/models/Gosmonitoring/Implementation.vue';
import DataFields from '@/views/Gosmonitoring/components/Register/Implementation/DataFields.vue';

@Component({
  name: 'ImplementationCreate',
  components: { DataFields },
})
export default class ImplementationCreate extends Gosmonitoring {
  model: ImplementationVueModel = new ImplementationVueModel();
  url = 'register/implementation';
}
</script>
