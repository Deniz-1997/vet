<template>
  <v-container v-if="accessGrantedAuthorities(model.create_privileges)">
    <gpb-out-form v-model="model" create></gpb-out-form>
  </v-container>
</template>
<script lang="ts">
import { Component } from 'vue-property-decorator';
import GpbOut from '@/views/GpbOut/GpbOut.vue';
import { GpbOutDataVueModel } from '@/models/Lot/GpbOut/GpbOutData.vue';
import GpbOutForm from '@/views/GpbOut/components/Form.vue';

@Component({
  name: 'gpb-out-create',
  components: {
    GpbOutForm,
  },
})
export default class GpbOutCreate extends GpbOut {
  model: GpbOutDataVueModel = new GpbOutDataVueModel();
}
</script>
