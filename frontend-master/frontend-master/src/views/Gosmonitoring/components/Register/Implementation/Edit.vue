<template>
  <data-fields
    v-if="accessGrantedAuthorities(model.update_privileges)"
    v-model="model"
    :edit="true"
    :loading="preloader || isLoading"
    @event="onUpdate"
  />
</template>
<script lang="ts">
import { Component } from 'vue-property-decorator';
import DataFields from '@/views/Gosmonitoring/components/Register/Implementation/DataFields.vue';
import { ImplementationVueModel } from '@/models/Gosmonitoring/Implementation.vue';
import Gosmonitoring from '@/views/Gosmonitoring/Gosmonitoring.vue';

@Component({
  name: 'ImplementationCreate',
  components: { DataFields },
})
export default class ImplementationEdit extends Gosmonitoring {
  model: ImplementationVueModel = new ImplementationVueModel();
  url = 'register/implementation';
}
</script>
