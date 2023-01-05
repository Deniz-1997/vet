<template>
  <lot-create
    v-if="checkGrantedCreatedLot(value)"
    v-model="value"
    :after-create-push="value.name_route_detail"
    :create-link="value.create_apiendpoit"
    :delete-link="value.delete_apiendpoit"
    :get-list="value.list_apiendpoit"
    :show-item="value.show_apiendpoit"
    :update-link="value.update_apiendpoit"
  >
    <template #[`table-lots-moved`]="{ isCreate, isDetail, isEdit, isLockFiltersFromLots, model, type }">
      <lot-tables-lots-moved
        v-if="type === LotType.IN_PRODUCT"
        v-model="model.lotsMoved"
        :type-lot="type"
        is-not-repository-filter
        :is-create="isCreate"
        :is-detail="isDetail"
        :is-edit="isEdit"
        :is-lock-filters="isLockFiltersFromLots"
        :lot="lotDataVueModel"
        @onChangeAmountKg="onChangeAmountKg"
        @onEditLotsMoved="(e) => $emit('onEditLotsMoved', e)"
        @onFirstLotGrainIsSelect="onFirstLotGrainSelect($event, type)"
        @onOpenFindLotModal="(e) => $emit('onOpenFindLotModal', e)"
      />

      <lot-tables-lots-moved
        v-if="type === LotType.ANOTHER_BATCH_GRAIN || type === LotType.IN_PRODUCT"
        v-model="model.gpbLotsMoved"
        :is-create="isCreate"
        :type-lot="type"
        :is-detail="isDetail"
        :is-edit="isEdit"
        :is-lock-filters="isLockFiltersFromLots"
        :lot="model"
        class="mt-5 mb-5"
        title-table="Предшествующие партии переработки зерна"
        @onChangeAmountKg="onChangeAmountKg"
        @onEditLotsMoved="(e) => $emit('onEditLotsMoved', e)"
        @onFirstLotGrainIsSelect="onFirstLotGpbIsSelect($event, type)"
        @onOpenFindLotModal="(e) => $emit('onOpenFindLotModal', e)"
      >
        <template #[`create-date-filter`] />

        <template #[`manufacture-filter`] />
      </lot-tables-lots-moved>
    </template>

    <template #[`manufacture-field`]="{ disabled, model }">
      <v-col cols="6" lg="6" md="6" xl="6">
        <ManufacturerAutocomplete
          :key="model.manufacturer_id"
          v-model="model.manufacturer_id"
          :is-disabled="disabled"
          label="Товаропроизводитель"
          show-name-in-tooltip
        />
      </v-col>
    </template>

    <template
      #[`product-type-field`]="{
        isDisabledElement,
        onSelectedTypeOfCrop,
        model,
        type,
        lotType,
        newKey,
        storeLotType,
        isActive,
      }"
    >
      <select-request-component
        :key="newKey"
        v-model="model.okpd2_id"
        :disabled="isDisabledElement || type === LotType.ANOTHER_BATCH_GRAIN"
        label="Вид продуктов переработки"
        :lot-type="lotType"
        :store-lot-type="storeLotType"
        :is-active="isActive"
        placeholder="Выберите вид продуктов переработки"
        type="nsi-okpd2-msh"
        @onChange="onSelectedTypeOfCrop"
      />
    </template>

    <template #[`date-create`]="{ disabled, model, newKey }">
      <v-col cols="6" lg="3" md="3" xl="2">
        <UiDateInput
          :key="newKey"
          v-model="model.create_date"
          :disabled="disabled"
          :format="'DD.MM.YYYY'"
          label="Дата изготовления"
          placeholder="Выберите дату"
        />
      </v-col>
    </template>
  </lot-create>
</template>
<script lang="ts">
import { Component } from 'vue-property-decorator';
import Lot from '@/views/Lot/Lot.vue';
import LotCreate from '@/views/Lot/components/Create.vue';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import LotTablesLotsMoved from '@/views/Lot/components/Subcomponents/Tables/LotTablesLotsMoved.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import { applyMask } from '@/components/common/inputs/mask/decimalNumberMask';
import merge from 'lodash/merge';
import pick from 'lodash/pick';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import { LotType } from '@/utils/enums/LotType';
import { add } from '@/utils/decimals';

@Component({
  name: 'lot-gpb-create',
  components: {
    ManufacturerAutocomplete,
    LotTablesLotsMoved,
    LotCreate,
    SelectRequestComponent,
    AutocompleteComponent,
    UiDateInput,
  },
})
export default class LotGpbCreate extends Lot {
  value: LotGpbDataVueModel = new LotGpbDataVueModel();

  onChangeAmountKg() {
    const { gpb_moved, lots_moved } = this.value.objects;
    let amount = 0;

    gpb_moved.forEach((value) => (amount = add(amount, value.value)));
    lots_moved.forEach((value) => (amount = add(amount, value.value)));

    this.value.amount_kg_mask = applyMask(amount, true);
    this.value.amount_kg = amount;
  }

  onFirstLotGrainSelect(data) {
    let fieldMoved = this.value.movedField;
    if (this.value.objects[fieldMoved].length <= 1) {
      const filters = pick(data.lot, ['target_id', 'current_location_id']);
      merge(this.value, filters);
    }
  }

  onFirstLotGpbIsSelect(data, type: LotType) {
    let fieldMoved = this.value.movedField;
    if (this.value.objects[fieldMoved].length <= 1) {
      let filters: any = null;

      switch (type) {
        case LotType.ANOTHER_BATCH_GRAIN:
          filters = pick(data.lot, ['target_id', 'okpd2_id', this.value.getTnvedFieldName(), 'current_location_id']);
          merge(this.value, filters);
          break;

        case LotType.IN_PRODUCT:
          filters = pick(data.lot, ['target_id', 'current_location_id']);
          merge(this.value, filters);
          break;
      }
    }
  }

  async created() {
    const routesForTypes = {
      lots_gpb_create_from_another_batch: 'ANOTHER_BATCH_GRAIN',
    };

    const lotTypeForCurrentRoute = routesForTypes[this.$route.name || ''];

    if (lotTypeForCurrentRoute) this.value.type = lotTypeForCurrentRoute;
    this.value.valid_paper_sdiz_number = false;
  }
}
</script>
