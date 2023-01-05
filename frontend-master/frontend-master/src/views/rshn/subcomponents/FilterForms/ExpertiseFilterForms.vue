<template>
  <v-row :key="clear">
    <v-col cols="12" md="4">
      <label-component label="Дата решения о проведении экспертизы" />
      <v-row>
        <v-col class="pr-1" cols="12" data-app sm="6">
          <UiDateInput v-model="model.decision_date_from" :limit-to="today" placeholder="от" />
        </v-col>
        <v-col class="pr-1" cols="12" data-app sm="6">
          <UiDateInput
            v-model="model.decision_date_to"
            :limit-from="fromDate(model.decision_date_from)"
            placeholder="до"
          />
        </v-col>
      </v-row>
    </v-col>
    <v-col cols="12" md="4">
      <input-component
        v-model="model.expertise_number"
        label="Номер решения о проведении экспертизы"
        placeholder="Введите номер решения о проведении экспертизы"
      />
    </v-col>
    <v-col cols="12" md="4">
      <select-request-component
        v-model="model.status_id"
        :custom-items="rshn.statusList"
        label="Статус"
        placeholder="Выберите статус"
      />
    </v-col>

    <v-col cols="12" md="4">
      <label-component label="Дата проведения экспертизы" />
      <v-row>
        <v-col class="pr-1" cols="12" data-app sm="6">
          <UiDateInput v-model="model.expertise_date_from" :limit-to="today" placeholder="от" />
        </v-col>
        <v-col class="pr-1" cols="12" data-app sm="6">
          <UiDateInput
            v-model="model.expertise_date_to"
            :limit-from="fromDate(model.expertise_date_from)"
            placeholder="до"
          />
        </v-col>
      </v-row>
    </v-col>
    <v-col cols="12" md="4">
      <input-component v-model="model.selection_number" label="Номер пробы" placeholder="Введите номер пробы" />
    </v-col>
    <v-col cols="12" md="4">
      <select-request-component
        v-model="model.expertise_type"
        :custom-items="rshn.typeExpertiseList"
        label="Тип экспертизы"
        placeholder="Выберите тип"
      />
    </v-col>

    <v-col cols="12" md="4">
      <label-component label="Дата направления образца" />
      <v-row>
        <v-col class="pr-1" cols="12" data-app sm="6">
          <UiDateInput v-model="model.departure_date_from" :limit-to="today" placeholder="от" />
        </v-col>
        <v-col class="pr-1" cols="12" data-app sm="6">
          <UiDateInput
            v-model="model.departure_date_to"
            :limit-from="fromDate(model.departure_date_from)"
            placeholder="до"
          />
        </v-col>
      </v-row>
    </v-col>
    <v-col cols="12" md="4">
      <label-component label="Дата отбора образца" />
      <v-row>
        <v-col class="pr-1" cols="12" data-app sm="6">
          <UiDateInput v-model="model.selection_date_from" :limit-to="today" placeholder="от" />
        </v-col>
        <v-col class="pr-1" cols="12" data-app sm="6">
          <UiDateInput
            v-model="model.selection_date_to"
            :limit-from="fromDate(model.selection_date_from)"
            placeholder="до"
          />
        </v-col>
      </v-row>
    </v-col>
    <v-col v-if="showFormFilterWithdrawal" cols="12" md="4">
      <input-component v-model="model.gw_number" label="Номер изъятия" placeholder="Введите номер изъятия" />
    </v-col>
    <v-col v-if="showFormFilterSdiz" cols="12" md="4">
      <input-component v-model="model.sdiz_number" label="Номер СДИЗ" placeholder="Введите номер СДИЗ" />
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Component, Model } from 'vue-property-decorator';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import LotElementRowsTable from '@/views/Lot/components/Subcomponents/Elements/LotElementRowsTable.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import TransitionExpand from '@/components/Forms/TransitionExpand.vue';
import Rshn from '@/views/rshn/Rshn.vue';
import { RshnExpertiseData } from '@/models/Rshn/Expertise/RshnExpertiseData.vue';
import { ExpertiseEnum } from '@/utils/enums/RshnEnums';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';

@Component({
  components: {
    UiDateInput,
    TransitionExpand,
    ButtonComponent,
    SelectRequestComponent,
    AutocompletePriorityAddress,
    AutocompleteComponent,
    LabelComponent,
    LotElementRowsTable,
    InputComponent,
  },
})
export default class ExpertiseFilterForms extends Rshn {
  @Model('change', { type: Object, required: true }) model!: RshnExpertiseData;
  get showFormFilterWithdrawal() {
    return this.model.expertise_type === ExpertiseEnum.WITHDRAWAL;
  }
  get showFormFilterSdiz() {
    return this.model.expertise_type === ExpertiseEnum.IMPORT || this.model.expertise_type === ExpertiseEnum.EXPORT;
  }
}
</script>

<style scoped></style>
