<template>
  <gpb-out-list v-if="accessGrantedAuthorities(model.read_register_privileges)" v-model="model"></gpb-out-list>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import GpbOutList from '@/views/GpbOut/components/List.vue';
import GpbOut from '@/views/GpbOut/GpbOut.vue';
import { GpbOutDataVueModel } from '@/models/Lot/GpbOut/GpbOutData.vue';

@Component({
  name: 'gpb-out-default-list',
  components: { GpbOutList },
})
export default class GpbOutDefaultList extends GpbOut {
  model: GpbOutDataVueModel = new GpbOutDataVueModel();
}
</script>
