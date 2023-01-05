<template>
  <lot-list
    v-model="model"
    is-elevator-page
    :is-show-additional-button="true"
    :get-list="model.list_apiendpoit"
    title="Реестр партий зерна при хранении"
  >
    <template v-if="!accessGrantedAuthorities(model.register_grain_lot_product_read_privileges)" #hidden-body>
      Для доступа к функционалу зарегистрируйтесь в реестре организаций, осуществляющих в качестве предпринимательской
      деятельности хранение зерна и оказывающих связанные с хранением услуги.
    </template>
  </lot-list>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import Lot from '@/views/Lot/Lot.vue';
import LotList from '@/views/Lot/components/List.vue';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';

@Component({
  name: 'lot-list-elevator',
  components: { LotList },
})
export default class LotElevatorList extends Lot {
  model: LotElevatorDataVueModel = new LotElevatorDataVueModel();
}
</script>
