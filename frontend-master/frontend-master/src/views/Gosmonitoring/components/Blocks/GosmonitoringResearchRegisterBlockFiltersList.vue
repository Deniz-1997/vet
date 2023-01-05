<template>
  <v-row>
    <v-col cols="12" lg="4" md="6" sm="7" xl="3">
      <label-component label="Дата проведения отбора" />
      <v-row>
        <v-col cols="12" data-app sm="6">
          <UiDateInput v-model="filters.date_check_from" :format="'DD.MM.YYYY'" placeholder="от" />
        </v-col>
        <v-col cols="12" data-app sm="6">
          <UiDateInput v-model="filters.date_check_to" :format="'DD.MM.YYYY'" placeholder="до" />
        </v-col>
      </v-row>
    </v-col>

    <v-col cols="12" sm="4" xl="3">
      <input-component
        v-model="filters.number_grain_samples"
        label="Номер (шифр) пробы зерна"
        placeholder="Введите номер (шифр) пробы зерна"
      />
    </v-col>

    <v-col cols="12" sm="4" xl="3">
      <select-request-component
        v-model="filters.target_id"
        label="Цель использования"
        placeholder="Выберите цель использования"
        type="nsi-lots-target"
      />
    </v-col>

    <v-col cols="12" sm="4" xl="3">
      <v-tooltip bottom>
        <template #activator="{ on, attrs }">
          <div v-bind="attrs" v-on="on">
            <input-component
              v-model="filters.laboratory_monitor_number"
              label="Номер исследований"
              placeholder="Введите номер исследования"
            />
          </div>
        </template>
        <span>Для поиска используйте латинские буквы</span>
      </v-tooltip>
    </v-col>

    <v-col cols="12" sm="4" xl="3">
      <label-component label="Дата протокола исследования" />
      <v-row>
        <v-col cols="12" data-app sm="6">
          <UiDateInput v-model="filters.date_of_protocol_check_from" :format="'DD.MM.YYYY'" placeholder="от" />
        </v-col>
        <v-col cols="12" data-app sm="6">
          <UiDateInput v-model="filters.date_of_protocol_check_to" :format="'DD.MM.YYYY'" placeholder="до" />
        </v-col>
      </v-row>
    </v-col>

    <v-col cols="12" sm="4" xl="3">
      <select-request-component
        v-model="filters.status_id"
        label="Статус"
        placeholder="Выберите статус"
        type="status_id"
      />
    </v-col>

    <v-col cols="12" sm="6" xl="3">
      <select-request-component
        v-model="filters.okpd2Code"
        label="Вид с/х культуры"
        placeholder="Выберите вид с/х культуры"
        :lot-type="{ is_grain: true }"
        type="nsi-okpd2-codes"
        :is-active="false"
        item-id="code"
      />
    </v-col>

    <v-col cols="12" sm="6" xl="3">
      <autocomplete-priority-address
        v-model="filters.place_of_checking_id"
        label="Место формирования партии в целях отбора проб"
        placeholder="Выберите местоположение"
      />
    </v-col>
    <v-col v-if="showFilter" cols="4" xl="4">
      <ManufacturerAutocomplete
        v-model="filters.owner_id"
        label="Товаропроизводитель"
        placeholder="Выберите товаропроизводителя"
      />
    </v-col>
  </v-row>
</template>

<script lang="ts">
import LabelComponent from '@/components/common/Label/Label.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import { Component, Model, Prop, Vue } from 'vue-property-decorator';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import ActionsButtons from '@/components/Forms/ActionsButtons.vue';
import { ResearchRegisterVueModel } from '@/models/Gosmonitoring/ResearchRegister.vue';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';

@Component({
  name: 'gosmonitoring-research-register-block-filters-list',
  components: {
    ManufacturerAutocomplete,
    AutocompletePriorityAddress,
    ActionsButtons,
    AutocompleteComponent,
    ButtonComponent,
    LabelComponent,
    UiDateInput,
    InputComponent,
    SelectRequestComponent,
  },
})
export default class GosmonitoringResearchRegisterBlockFiltersList extends Vue {
  @Model('change', { type: ResearchRegisterVueModel, required: true }) filters!: ResearchRegisterVueModel;
  @Prop({ type: Boolean, default: false }) showFilter!: boolean;
}
</script>
