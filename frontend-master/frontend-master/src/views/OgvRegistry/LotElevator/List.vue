<template>
  <lot-list
    v-if="accessGrantedAuthorities(viewPrivileges)"
    v-model="model"
    :is-show-additional-button="false"
    :get-list="model.list_apiendpoit"
    title="Реестр партий зерна при хранении"
  >
    <template #[`repository-owner-filter`]="{ filters, isNeedLockFilters }">
      <v-col cols="12" lg="4" md="6" sm="3" xl="4">
        <ManufacturerAutocomplete
          v-model="filters.repository_id"
          label="Хранитель партии"
          placeholder="Выберите хранителя партии"
          show-name-in-tooltip
          :is-disabled="filters.repository_id !== null && isNeedLockFilters"
        />
      </v-col>
    </template>
  </lot-list>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import Lot from '@/views/Lot/Lot.vue';
import LotList from '@/views/Lot/components/List.vue';
import { LotOgvElevatorDataVueModel } from '@/models/Lot/Ogv/LotOgvElevatorData.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import { EAction } from '@/models/roles';

@Component({
  name: 'lot-ogv-list-elevator',
  components: { LotList, ManufacturerAutocomplete },
})
export default class LotOgvElevatorList extends Lot {
  model: LotOgvElevatorDataVueModel = new LotOgvElevatorDataVueModel();
  viewPrivileges = EAction.READ_GRAIN_LOT_STORAGE_REGISTER;
}
</script>
