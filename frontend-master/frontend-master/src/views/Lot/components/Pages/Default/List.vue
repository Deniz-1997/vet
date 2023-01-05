<template>
  <lot-list
    v-if="accessGrantedAuthorities(model.register_grain_lot_product_read_privileges)"
    v-model="model"
    :is-show-additional-button="true"
  />
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import Lot from '@/views/Lot/Lot.vue';
import LotList from '@/views/Lot/components/List.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';

@Component({
  name: 'lot-default-list',
  components: { LotList },
})
export default class LotDefaultList extends Lot {
  model: LotDataVueModel = new LotDataVueModel();
}
</script>
