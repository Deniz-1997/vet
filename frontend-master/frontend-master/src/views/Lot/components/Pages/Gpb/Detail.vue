<template>
  <lot-detail
    v-if="accessGrantedAuthorities(value.view_data_privileges)"
    v-model="value"
    :sdiz-model="sdizGpbModel"
    :after-delete-push="value.name_route_list"
    :after-update-push="value.name_route_detail"
    title-cancel="Аннулирование партии переработки"
    title-change-state-debit="Списание остатков по партии переработки"
    title-create-lot-from-lot="Сформировать партию переработки из партии"
    link-create-from-another-batch="lots_gpb_create_from_another_batch"
    :link-update="value.update_apiendpoit"
    :show-item="value.show_apiendpoit"
    :update-link="value.update_apiendpoit"
    :delete-link="value.delete_apiendpoit"
  >
    <template #[`table-lots-moved`]="{ isCreate, isDetail, isEdit, isLockFiltersFromLots, model, type }">
      <lot-tables-lots-moved
        v-if="model.type === 'IN_PRODUCT'"
        v-model="model.lotsMoved"
        :type-lot="type"
        :is-create="isCreate"
        is-not-repository-filter
        :is-detail="isDetail"
        :is-edit="isEdit"
        :is-lock-filters="isLockFiltersFromLots"
        :lot="lotDataVueModel"
        @onChangeAmountKg="onChangeAmountKg"
        @onDeleteLotMoved="onChangeAmountKg"
        @onEditLotsMoved="(e) => $emit('onEditLotsMoved', e)"
        @onFirstLotGrainIsSelect="(e) => $emit('onFirstLotGrainIsSelect', e)"
        @onOpenFindLotModal="(e) => $emit('onOpenFindLotModal', e)"
      />

      <lot-tables-lots-moved
        v-if="model.type === 'IN_PRODUCT' || model.type === 'ANOTHER_BATCH_GRAIN'"
        v-model="model.gpbLotsMoved"
        :type-lot="type"
        :is-create="isCreate"
        :is-detail="isDetail"
        :is-edit="isEdit"
        :is-lock-filters="isLockFiltersFromLots"
        :lot="model"
        title-table="Предшествующие партии переработки зерна"
        @onChangeAmountKg="onChangeAmountKg"
        @onDeleteLotMoved="onChangeAmountKg"
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

    <template #[`product-type-field`]="{ isDisabledElement, onSelectedTypeOfCrop, model, isActive }">
      <select-request-component
        v-model="model.okpd2_id"
        :disabled="isDisabledElement"
        :is-active="isActive"
        :lot-type="model.getLotType()"
        :store-lot-type="model.getStoreLotType()"
        label="Вид продуктов переработки"
        placeholder="Выберите вид продуктов переработки"
        type="nsi-okpd2-msh"
        @onChange="onSelectedTypeOfCrop"
      />
    </template>
  </lot-detail>
</template>
<script lang="ts">
import { Component } from 'vue-property-decorator';
import Lot from '@/views/Lot/Lot.vue';
import LotDetail from '@/views/Lot/components/Detail.vue';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import LotTablesLotsMoved from '@/views/Lot/components/Subcomponents/Tables/LotTablesLotsMoved.vue';
import { applyMask } from '@/components/common/inputs/mask/decimalNumberMask';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import { LotType } from '@/utils/enums/LotType';
import pick from 'lodash/pick';
import merge from 'lodash/merge';
import { add } from '@/utils/decimals';

@Component({
  name: 'lot-gpb-detail',
  components: {
    ManufacturerAutocomplete,
    LotTablesLotsMoved,
    LotDetail,
    SelectRequestComponent,
    AutocompleteComponent,
    UiDateInput,
  },
})
export default class LotGpbDetail extends Lot {
  value: LotGpbDataVueModel = new LotGpbDataVueModel();
  sdizGpbModel: SdizGpbVueModel = new SdizGpbVueModel();

  onChangeAmountKg() {
    const { gpb_moved, lots_moved } = this.value.objects;
    let amount = 0;

    gpb_moved.forEach((value) => (amount = add(amount, value.value)));
    lots_moved.forEach((value) => (amount = add(amount, value.value)));

    this.value.amount_kg_mask = applyMask(amount, true);
    this.value.amount_kg = amount;
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
}
</script>
