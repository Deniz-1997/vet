<template>
  <gpb-out-list
    v-if="accessGrantedAuthorities(viewPrivileges)"
    v-model="model"
    :is-show-additional-button="false"
  ></gpb-out-list>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import GpbOut from '@/views/GpbOut/GpbOut.vue';
import GpbOutList from '@/views/GpbOut/components/List.vue';
import { GpbOutDataOgvVueModel } from '@/models/Lot/Ogv/GpbOutDataOgv.vue';
import { EAction } from '@/models/roles';

@Component({
  name: 'gpb-out-ogv-list',
  components: {
    GpbOutList,
  },
})
export default class GpbOutOgvList extends GpbOut {
  model: GpbOutDataOgvVueModel = new GpbOutDataOgvVueModel();
  viewPrivileges = EAction.READ_GRAIN_PROCESSING_BATCH_OUT_REGISTRY;
}
</script>

<style scoped></style>
