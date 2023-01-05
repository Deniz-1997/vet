<template>
  <div v-if="!accessGrantedAuthorities(model.register_sdiz_privileges)">
    Для доступа к функционалу зарегистрируйтесь в реестре организаций, осуществляющих в качестве предпринимательской
    деятельности хранение зерна и оказывающих связанные с хранением услуги.
  </div>
  <div v-else>
    <sdiz-filter-list
      v-model="model"
      title="Реестр СДИЗ при хранении"
      :api-endpoint="model.list_apiendpoit"
      :is-filters-for-elevator="true"
      :is-request-payload="true"
    >
      <template #number-filter>
        <v-col cols="12" lg="3" md="6" xl="2">
          <input-component v-model="model.sdiz_number" label="Номер" placeholder="Введите номер" />
        </v-col>
      </template>
    </sdiz-filter-list>
  </div>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import Sdiz from '@/views/Sdiz/Sdiz.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import TextComponent from '@/components/common/TextComponent.vue';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import SdizListTables from '@/views/Sdiz/components/Subcomponents/Table/SdiztListTables.vue';
import PageComponent from '@/components/Forms/PageComponent.vue';
import SdizFilterList from '@/views/Sdiz/components/List.vue';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import { SdizElevatorModel } from '@/models/Sdiz/Data/SdizElevator.vue';

@Component({
  name: 'sdiz-elevator-list',
  components: {
    CheckboxComponent,
    SdizFilterList,
    PageComponent,
    SdizListTables,
    AutocompletePriorityAddress,
    TextComponent,
    DefaultButton,
    AutocompleteComponent,
    ButtonComponent,
    DataTable,
    InputComponent,
    LabelComponent,
    SelectRequestComponent,
  },
})
export default class SdizElevatorList extends Sdiz {
  model: SdizElevatorModel = new SdizElevatorModel();

  created() {
    this.model.elevator_creator = true;
  }
}
</script>
