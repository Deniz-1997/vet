<template>
  <lot-detail
    v-model="value"
    title-cancel="Аннулирование партии переработки"
    title-change-state-debit="Списание остатков по партии переработки"
    title-create-lot-from-lot="Сформировать партию переработки из партии"
    :is-show="false"
    link-create-from-another-batch="lots_gpb_create_from_another_batch"
    link-debit="lot/debitGpb"
    :link-update="value.update_apiendpoit"
    :show-item="value.show_apiendpoit"
    :update-link="value.update_apiendpoit"
    :delete-link="value.delete_apiendpoit"
    :sdiz-model="sdizModel"
  >
    <template #[`table-lots-moved`]="{ isCreate, isDetail, isEdit, isLockFiltersFromLots, model, type }">
      <lot-tables-lots-moved
        v-if="model.type === 'IN_PRODUCT'"
        v-model="model.lotsMoved"
        :type-lot="type"
        :is-create="isCreate"
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
        @onFirstLotGrainIsSelect="(e) => $emit('onFirstLotGrainIsSelect', e)"
        @onOpenFindLotModal="(e) => $emit('onOpenFindLotModal', e)"
      />
    </template>

    <template #[`manufacture-field`]="{ disabled, model }">
      <v-col cols="6" lg="6" md="6" xl="6">
        <ManufacturerAutocomplete
          v-model="model.manufacturer_id"
          :is-disabled="disabled"
          label="Товаропроизводитель"
          show-name-in-tooltip
        />
      </v-col>
    </template>

    <template #[`date-create`]="{ disabled, model }">
      <v-col cols="6" lg="3" md="3" xl="2">
        <UiDateInput
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
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import LotTablesLotsMoved from '@/views/Lot/components/Subcomponents/Tables/LotTablesLotsMoved.vue';
import { applyMask } from '@/components/common/inputs/mask/decimalNumberMask';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import { LotOgvGpbDataVueModel } from '@/models/Lot/Ogv/LotOgvGpbData.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import { SdizOgvGpbVueModel } from '@/models/Sdiz/Ogv/SdizOgvGpb.vue';

@Component({
  name: 'lot-ogv-gpb-detail',
  components: {
    LotTablesLotsMoved,
    LotDetail,
    SelectRequestComponent,
    AutocompleteComponent,
    UiDateInput,
    ManufacturerAutocomplete,
  },
})
export default class LotOgvGpbDetail extends Lot {
  value: LotOgvGpbDataVueModel = new LotOgvGpbDataVueModel();
  sdizModel: SdizOgvGpbVueModel = new SdizOgvGpbVueModel();

  onChangeAmountKg() {
    const { gpb_moved, lots_moved } = this.value.objects;
    let amount = 0;

    gpb_moved.forEach((value) => (amount = amount + value.value));
    lots_moved.forEach((value) => (amount = amount + value.value));

    this.value.amount_kg_mask = applyMask(amount, true);
    this.value.amount_kg = amount;
  }
}
</script>
