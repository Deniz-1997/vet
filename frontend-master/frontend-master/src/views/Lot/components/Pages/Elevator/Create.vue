<template>
  <lot-create
    v-model="model"
    is-elevator-lot
    create-from-another-batch="lot_elevator_create_from_another_batch"
    create-from-field="lot_elevator_create_from_field"
    create-from-imported="lot_elevator_create_from_imported"
    create-from-residues="lot_elevator_create_from_residues"
    create-from-sdiz="lot_elevator_create_from_sdiz"
    after-create-push="lot_elevator_detail"
    link="lot_elevator_list"
    get-list="lot/getListForElevator"
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

    <template v-if="!checkGrantedCreatedLot(model)" #hidden-body>
      Для доступа к функционалу зарегистрируйтесь в реестре организаций, осуществляющих в качестве предпринимательской
      деятельности хранение зерна и оказывающих связанные с хранением услуги.
    </template>
  </lot-create>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import Lot from '@/views/Lot/Lot.vue';
import LotCreate from '@/views/Lot/components/Create.vue';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';
import merge from 'lodash/merge';
import pick from 'lodash/pick';
import LotTablesLotsMoved from '@/views/Lot/components/Subcomponents/Tables/LotTablesLotsMoved.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';

@Component({
  name: 'lot-create-elevator',
  components: { LotTablesLotsMoved, LotCreate, SelectRequestComponent },
})
export default class LotElevatorCreate extends Lot {
  model: LotElevatorDataVueModel = new LotElevatorDataVueModel();

  created() {
    this.model.valid_paper_sdiz_number = false;
  }

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
