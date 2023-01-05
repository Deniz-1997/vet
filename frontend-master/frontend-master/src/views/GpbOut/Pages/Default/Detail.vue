<template>
  <gpb-out-detail v-if="accessGrantedAuthorities(model.read_privileges)" v-model="model"></gpb-out-detail>
</template>
<script lang="ts">
import { Component } from 'vue-property-decorator';
import GpbOut from '@/views/GpbOut/GpbOut.vue';
import { GpbOutDataVueModel } from '@/models/Lot/GpbOut/GpbOutData.vue';
import GpbOutDetail from '@/views/GpbOut/components/Detail.vue';

@Component({
  name: 'gpb-out-detail-default',
  components: {
    GpbOutDetail,
  },
})
export default class GpbOutDetailDefault extends GpbOut {
  model: GpbOutDataVueModel = new GpbOutDataVueModel();
}
</script>
