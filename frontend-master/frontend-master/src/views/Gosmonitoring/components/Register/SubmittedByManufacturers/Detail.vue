<template>
  <data-fields
    v-if="accessGrantedAuthorities(model.view_data_privileges)"
    v-model="model"
    :detail="true"
    :loading="preloader"
    :url="url"
    @event="onCreate"
    @onDelete="onDelete"
  />
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import Gosmonitoring from '@/views/Gosmonitoring/Gosmonitoring.vue';
import DataFields from '@/views/Gosmonitoring/components/Register/Implementation/DataFields.vue';
import { SubmitedByManufacturerModel } from '@/models/Gosmonitoring/SubmitedByManufacturers.vue';

@Component({
  name: 'SubmitedByManufacturersDetail',
  components: { DataFields },
})
export default class SubmitedByManufacturersDetail extends Gosmonitoring {
  model: SubmitedByManufacturerModel = new SubmitedByManufacturerModel();
  url = 'register/implementation/submitted-by-manufacturers';
}
</script>
