<template>
  <v-row>
    <v-col cols="12" lg="4" md="6" sm="12" xl="3">
      <label-component label="Дата сбора урожая" />
      <v-row>
        <v-col cols="12" data-app sm="6">
          <UiDateInput v-model="filters.date_from" :format="'DD.MM.YYYY'" placeholder="от" />
        </v-col>
        <v-col cols="12" data-app sm="6">
          <UiDateInput v-model="filters.date_to" :format="'DD.MM.YYYY'" placeholder="до" />
        </v-col>
      </v-row>
    </v-col>
    <v-col cols="12" lg="4" md="6" sm="12" xl="3">
      <input-component v-model="filters.prodact_monitor_number" label="Номер" placeholder="Введите номер" />
    </v-col>
    <v-col cols="12" lg="4" md="6" sm="12" xl="3">
      <select-request-component
        v-model="filters.status_id"
        label="Статус"
        placeholder="Выберите статус"
        type="gosmonitoring_research_status_id"
      />
    </v-col>

    <v-col v-if="showFilter" cols="12" lg="4" md="6" sm="12" xl="3">
      <ManufacturerAutocomplete
        v-model="filters.owner_id"
        label="Товаропроизводитель"
        placeholder="Выберите товаропроизводителя"
      />
    </v-col>
    <v-col cols="12" lg="4" md="6" sm="12" xl="3">
      <autocomplete-priority-address
        v-model="filters.place_of_cultivation_id"
        label="Место выращивания партии зерна"
        placeholder="Выберите место выращивания"
      />
    </v-col>

    <v-col cols="12" lg="4" md="6" sm="12" xl="3">
      <select-request-component
        v-model="filters.okpd2Code"
        label="Вид сельскохозяйственной культуры зерна"
        :lot-type="{ is_grain: true }"
        placeholder="Выберите вид с/х культуры"
        type="nsi-okpd2-codes"
        :is-active="false"
        item-id="code"
      />
    </v-col>

    <v-col cols="12" lg="4" md="6" sm="12" xl="3">
      <autocomplete-priority-address
        v-model="filters.current_location_id"
        label="Место хранения зерна"
        placeholder="Выберите место хранения"
      />
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Component, Model, Prop, Vue } from 'vue-property-decorator';
import LabelComponent from '@/components/common/Label/Label.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import ActionsButtons from '@/components/Forms/ActionsButtons.vue';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import { ImplementationVueModel } from '@/models/Gosmonitoring/Implementation.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';

@Component({
  name: 'gosmonitoring-implementation-block-filters-list',
  components: {
    ManufacturerAutocomplete,
    AutocompleteComponent,
    AutocompletePriorityAddress,
    ActionsButtons,
    ButtonComponent,
    LabelComponent,
    UiDateInput,
    InputComponent,
    SelectRequestComponent,
  },
})
export default class GosmonitoringImplementationBlockFiltersList extends Vue {
  @Model('change', { required: true }) filters!: ImplementationVueModel;
  @Prop({ type: Boolean, default: false }) showFilter!: boolean;
  @Prop({ default: true }) hideManufactureList!: boolean;
}
</script>

<style scoped></style>
