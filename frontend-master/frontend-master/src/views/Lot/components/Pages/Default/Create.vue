<template>
  <lot-create v-if="checkGrantedCreatedLot(model)" v-model="model">
    <template #[`table-lots-moved`]="{ isCreate, isDetail, isEdit, isLockFiltersFromLots, model, type }">
      <lot-tables-lots-moved
        v-show="type === LotType.ANOTHER_BATCH_GRAIN || (model.objects.lots_moved.length > 0 && type !== LotType.SDIZ)"
        v-model="model.lotsMoved"
        :type-lot="type"
        :is-create="isCreate"
        :is-detail="isDetail"
        :is-edit="isEdit"
        :is-lock-filters="isLockFiltersFromLots"
        :lot="model"
        :is-not-repository-filter="isNotRepositoryFilter"
        @onChangeAmountKg="onChangeAmountKg"
        @onDeleteLotMoved="$emit('onDeleteLotMoved', $event)"
        @onEditLotsMoved="$emit('onEditLotsMoved', $event)"
        @onFirstLotGrainIsSelect="onFirstLotGrainIsSelect"
        @onOpenFindLotModal="$emit('onOpenFindLotModal', $event)"
      />
    </template>
    <template #[`date-lot`]="{ disabled, model }">
      <v-col cols="6" lg="3" md="3" xl="2">
        <select-request-component
          v-model="model.lot_year"
          :custom-items="model.dateArray"
          :disabled="disabled"
          label="Год урожая"
          placeholder="Выберите год"
        />
      </v-col>
    </template>
  </lot-create>
</template>
<script lang="ts">
import { Component } from 'vue-property-decorator';
import Lot from '@/views/Lot/Lot.vue';
import LotCreate from '@/views/Lot/components/Create.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import LotTablesLotsMoved from '@/views/Lot/components/Subcomponents/Tables/LotTablesLotsMoved.vue';
import merge from 'lodash/merge';
import pick from 'lodash/pick';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import { LotType } from '@/utils/enums/LotType';

@Component({
  name: 'lot-default-create',
  components: { LotTablesLotsMoved, LotCreate, SelectRequestComponent },
})
export default class LotDefaultCreate extends Lot {
  model: LotDataVueModel = new LotDataVueModel();

  created() {
    this.model.valid_paper_sdiz_number = false;
  }

  get isNotRepositoryFilter() {
    return this.type === LotType.ANOTHER_BATCH_GRAIN && this.model.lotType.is_grain;
  }

  onChangeAmountKg(data) {
    const { mask, value } = data.amountKg;
    this.model.amount_kg_mask = mask;
    this.model.amount_kg = value;
  }

  onFirstLotGrainIsSelect(data) {
    let fieldMoved = this.model.movedField;

    if (this.model.objects[fieldMoved].length <= 1) {
      const filters = pick(data.lot, ['target_id', 'okpd2_id', 'current_location_id']);
      merge(this.model, filters);
    }
  }
}
</script>
