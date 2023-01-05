<template>
  <lot-list
    v-if="accessGrantedAuthorities(model.register_grain_lot_product_read_privileges)"
    v-model="model"
    :get-list="model.list_apiendpoit"
    :is-show-additional-button="true"
    title="Реестр партии продуктов переработки зерна"
  >
    <template #[`sdiz-product-filter`]="{ filters }">
      <v-col cols="12" lg="6" md="6" sm="4" xl="4">
        <select-request-component
          v-model="filters.okpd2Code"
          :is-active="false"
          :lot-type="filters.lotType"
          label="Вид продуктов переработки"
          type="nsi-okpd2-codes"
          item-id="code"
          placeholder="Выберите вид продуктов переработки"
        />
      </v-col>
    </template>
  </lot-list>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import Lot from '@/views/Lot/Lot.vue';
import LotList from '@/views/Lot/components/List.vue';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';

@Component({
  name: 'lot-gpb-list',
  components: {
    LotList,
    SelectRequestComponent,
  },
})
export default class LotGpbList extends Lot {
  model: LotGpbDataVueModel = new LotGpbDataVueModel();
}
</script>
