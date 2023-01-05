<template>
  <data-fields
    v-if="accessGrantedAuthorities(model.view_data_privileges)"
    v-model="model"
    :detail="true"
    :loading="preloader || isLoading"
    :url="url"
    @event="onCreate"
    @onDelete="onDelete"
    @is-loading="setIsLoading"
  />
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import { ImplementationVueModel } from '@/models/Gosmonitoring/Implementation.vue';
import Gosmonitoring from '@/views/Gosmonitoring/Gosmonitoring.vue';
import DataFields from '@/views/Gosmonitoring/components/Register/Implementation/DataFields.vue';

@Component({
  name: 'ImplementationDetail',
  components: { DataFields },
})
export default class ImplementationDetail extends Gosmonitoring {
  model: ImplementationVueModel = new ImplementationVueModel();
  url = 'register/implementation';
}
</script>
