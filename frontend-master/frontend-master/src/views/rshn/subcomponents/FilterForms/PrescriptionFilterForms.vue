<template>
  <v-row :key="clear">
    <v-col cols="12" lg="4" md="6" sm="7" xl="3">
      <label-component label="Дата" />
      <v-row>
        <v-col class="pr-1" cols="12" data-app sm="6">
          <UiDateInput v-model="model.date_from" :limit-to="today" placeholder="от" />
        </v-col>
        <v-col class="pr-1" cols="12" data-app sm="6">
          <UiDateInput v-model="model.date_to" :limit-from="fromDate(model.date_from)" placeholder="до" />
        </v-col>
      </v-row>
    </v-col>

    <v-col cols="12" lg="4" md="3" sm="4" xl="2">
      <input-component v-model="model.gp_row_number" label="Номер документа" placeholder="Введите номер" />
    </v-col>

    <v-col cols="12" lg="4" md="4" sm="4" xl="3">
      <select-request-component
        v-model="model.status_id"
        :custom-items="rshn.statusList"
        label="Статус"
        placeholder="Выберите статус"
      />
    </v-col>
    <v-col cols="12" lg="4" md="4" sm="4" xl="3">
      <select-request-component
        v-model="model.restrictions_text"
        :custom-items="rshn.typePrescriptionRestrictions"
        label="Сведения об ограничении действия с партией"
        placeholder="Выберите значение"
      />
    </v-col>
    <v-col cols="12" lg="4" md="4" sm="4" xl="3">
      <select-request-component
        v-model="model.restrictions_bin"
        :custom-items="rshn.restrictionsBinList"
        label="Изолированное хранение"
        placeholder="Выберите значение"
      />
    </v-col>
    <v-col cols="12" lg="4" md="6" sm="3" xl="4">
      <ManufacturerAutocomplete
        v-model="model.operator_id"
        show-name-in-tooltip
        label="Должностное лицо, выдавшее предписание "
        placeholder="Выберите Должностное лицо"
      />
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Component, Model } from 'vue-property-decorator';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import LotElementRowsTable from '@/views/Lot/components/Subcomponents/Elements/LotElementRowsTable.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import TransitionExpand from '@/components/Forms/TransitionExpand.vue';
import Rshn from '@/views/rshn/Rshn.vue';
import { RshnPrescriptionData } from '@/models/Rshn/Prescription/RshnPrescriptionData.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';

@Component({
  components: {
    TransitionExpand,
    ButtonComponent,
    SelectRequestComponent,
    AutocompletePriorityAddress,
    ManufacturerAutocomplete,
    AutocompleteComponent,
    LabelComponent,
    LotElementRowsTable,
    InputComponent,
    UiDateInput,
  },
})
export default class PrescriptionFilterForms extends Rshn {
  @Model('change', { type: Object, required: true }) model!: RshnPrescriptionData;
}
</script>

<style scoped></style>
