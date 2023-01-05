<template>
  <lot-list
    v-if="accessGrantedAuthorities(viewPrivileges)"
    v-model="model"
    :get-list="model.list_apiendpoit"
    :is-show-additional-button="false"
    title="Реестр партии продуктов переработки зерна"
  >
    <template #[`sdiz-product-filter`]="{ lockFromFilterModal, filters, isNeedLockFilters }">
      <v-col cols="12" lg="6" md="6" sm="4" xl="4">
        <select-request-component
          v-model="filters.okpd2Code"
          :disabled="isNeedLockFilters || lockFromFilterModal('okpd2Code')"
          :lot-type="filters.getLotType()"
          :is-active="false"
          type="nsi-okpd2-codes"
          item-id="code"
          label="Вид продуктов переработки"
          placeholder="Выберите вид продуктов переработки"
        />
      </v-col>
    </template>
    <template #[`sdiz-owner-filter`]="{ filters, isNeedLockFilters }">
      <v-col cols="12" lg="4" md="6" sm="3" xl="4">
        <ManufacturerAutocomplete
          v-model="filters.owner_id"
          :is-disabled="filters.owner_id !== null && isNeedLockFilters"
          label="Владелец партии"
          placeholder="Выберите владельца партии"
          show-name-in-tooltip
        />
      </v-col>
    </template>
  </lot-list>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import Lot from '@/views/Lot/Lot.vue';
import LotList from '@/views/Lot/components/List.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import { LotOgvGpbDataVueModel } from '@/models/Lot/Ogv/LotOgvGpbData.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import { EAction } from '@/models/roles';

@Component({
  name: 'lot-ogv-gpb-list',
  components: {
    LotList,
    SelectRequestComponent,
    ManufacturerAutocomplete,
  },
})
export default class LotOgvGpbList extends Lot {
  model: LotOgvGpbDataVueModel = new LotOgvGpbDataVueModel();
  viewPrivileges = EAction.READ_GRAIN_PRODUCT_LOT_REGISTER;
}
</script>
