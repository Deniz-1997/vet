<template>
  <lot-detail
    v-if="accessGrantedAuthorities(model.view_data_privileges)"
    v-model="model"
    :sdiz-model="sdizModel"
    after-update-push="lot_elevator_detail"
    after-delete-push="lot_elevator_list"
    link-create-from-another-batch="lot_elevator_create_from_another_batch"
    is-elevator-lot
  >
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
        is-elevator-filter
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
  </lot-detail>
</template>
<script lang="ts">
import { Component } from 'vue-property-decorator';
import Lot from '@/views/Lot/Lot.vue';
import LotDetail from '@/views/Lot/components/Detail.vue';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import pick from 'lodash/pick';
import merge from 'lodash/merge';
import LotTablesLotsMoved from '@/views/Lot/components/Subcomponents/Tables/LotTablesLotsMoved.vue';

@Component({
  name: 'lot-elevator-detail',
  components: {
    LotDetail,
    SelectRequestComponent,
    LotTablesLotsMoved,
  },
})
export default class LotElevatorDetail extends Lot {
  model: LotElevatorDataVueModel = new LotElevatorDataVueModel();
  sdizModel: SdizVueModel = new SdizVueModel();

  onChangeAmountKg(data) {
    const { mask, value } = data.amountKg;
    this.model.amount_kg_mask = mask;
    this.model.amount_kg = value;
  }

  onFirstLotGrainIsSelect(data) {
    let fieldMoved = this.model.movedField;

    if (this.model.objects[fieldMoved].length <= 1) {
      const filters = pick(data.lot, ['target_id', 'owner_id', 'okpd2_id', 'current_location_id']);

      merge(this.model, filters);
    }
  }
}
</script>
