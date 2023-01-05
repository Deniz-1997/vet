<template>
  <sdiz-filter-list
    v-if="accessGrantedAuthorities(viewPrivileges)"
    v-model="model"
    :is-filters-for-elevator="false"
    :is-show-additional-button="false"
    :is-request-payload="true"
    title="Реестр СДИЗ продуктов переработки"
  >
    <template #number-filter>
      <v-col cols="12" lg="3" md="6" xl="2">
        <input-component v-model="model.sdiz_gpb_number" label="Номер" placeholder="Введите номер" />
      </v-col>
    </template>
    <template #lot-number-filters>
      <v-col cols="12" lg="6" md="6" xl="4">
        <input-component
          v-model="model.objects.gpb.gpb_number"
          label="Номер партии продуктов переработки зерна"
          placeholder="Введите номер партии"
        />
      </v-col>
    </template>
    <template #[`additional-filters`]="{ isFiltersForElevator }">
      <v-col cols="12" lg="6" :md="isFiltersForElevator ? 6 : 12" xl="4">
        <select-request-component
          v-model="model.objects.gpb.okpd2Code"
          :lot-type="model.objects.gpb.getLotType()"
          :label="model.lot_type_name"
          placeholder="Выберите вид продуктов переработки"
          :is-active="false"
          type="nsi-okpd2-codes"
          item-id="code"
        />
      </v-col>
    </template>
    <template #[`esiz-filters`]="{ isFiltersForElevator }">
      <v-col cols="12" lg="6" :md="isFiltersForElevator ? 6 : 12" xl="4" class="mt-10">
        <checkbox-component
          v-model="model.eisz_number_checkbox_init"
          label="Закупка для государственных нужд"
          class="float-left checkbox-v"
        />
      </v-col>
    </template>
    <template #[`esiz-number-filters`]="{ isFiltersForElevator }">
      <v-col cols="12" lg="6" :md="isFiltersForElevator ? 6 : 12" xl="4">
        <input-component v-model="model.eisz_number" label="Номер закупки" placeholder="Введите номер закупки" />
      </v-col>
    </template>
  </sdiz-filter-list>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import Sdiz from '@/views/Sdiz/Sdiz.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import PageComponent from '@/components/Forms/PageComponent.vue';
import SdizFilterList from '@/views/Sdiz/components/List.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import { SdizOgvGpbVueModel } from '@/models/Sdiz/Ogv/SdizOgvGpb.vue';
import { EAction } from '@/models/roles';

@Component({
  name: 'sdiz-ogv-gpb-list',
  components: {
    SdizFilterList,
    PageComponent,
    InputComponent,
    SelectRequestComponent,
    CheckboxComponent,
    TextComponent,
  },
})
export default class SdizOgvGpbList extends Sdiz {
  model: SdizOgvGpbVueModel = new SdizOgvGpbVueModel();
  viewPrivileges = EAction.READ_SDIZ_ON_PPZ_REGISTER;
}
</script>
