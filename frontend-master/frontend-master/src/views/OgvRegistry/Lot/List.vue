<template>
  <lot-list v-if="accessGrantedAuthorities(viewPrivileges)" v-model="model" :is-show-additional-button="false">
    <template #[`sdiz-owner-filter`]="{ filters, isNeedLockFilters }">
      <v-col cols="12" lg="4" md="6" sm="3" xl="4">
        <ManufacturerAutocomplete
          v-model="filters.owner_id"
          label="Владелец партии"
          placeholder="Выберите владельца партии"
          show-name-in-tooltip
          :is-disabled="filters.owner_id !== null && isNeedLockFilters"
        />
      </v-col>
    </template>
  </lot-list>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import Lot from '@/views/Lot/Lot.vue';
import { LotDataOgvVueModel } from '@/models/Lot/Ogv/LotDataOgv.vue';
import LotList from '@/views/Lot/components/List.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import { EAction } from '@/models/roles';

@Component({
  name: 'lot-ogv-list',
  components: {
    LotList,
    ManufacturerAutocomplete,
  },
})
export default class LotOgvList extends Lot {
  model: LotDataOgvVueModel = new LotDataOgvVueModel();
  viewPrivileges = EAction.READ_GRAIN_LOT_REGISTER;
}
</script>

<style scoped></style>
