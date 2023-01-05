<template>
  <gpb-out-detail v-model="model" :is-show="false"></gpb-out-detail>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import GpbOut from '@/views/GpbOut/GpbOut.vue';
import GpbOutDetail from '@/views/GpbOut/components/Detail.vue';
import { GpbOutDataOgvVueModel } from '@/models/Lot/Ogv/GpbOutDataOgv.vue';

@Component({
  name: 'gpb-out-ogv-detail',
  components: {
    GpbOutDetail,
  },
})
export default class GpbOutOgvDetail extends GpbOut {
  model: GpbOutDataOgvVueModel = new GpbOutDataOgvVueModel();
}
</script>

<style scoped></style>
