<template>
  <v-container v-if="accessGrantedAuthorities(model.create_sdiz_privileges)">
    <sdiz-create
      v-model="model"
      :is-elevator-page="false"
      title-button-add-lot="Изменить партию продуктов переработки"
      title-page-create="Оформление СДИЗ продуктов переработки"
    >
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

      <template #[`manufacture-field`]="{ disabled, model }">
        <v-col cols="6" lg="6" md="6" xl="6">
          <ManufacturerAutocomplete
            v-model="model.manufacturer_id"
            :is-disabled="disabled"
            label="Товаропроизводитель"
            placeholder="Выберите товаропроизводителя"
            show-name-in-tooltip
          />
        </v-col>
      </template>
      <template #[`product-type-field`]="{ disabled, model }">
        <select-request-component
          v-model="model.okpd2_id"
          :disabled="disabled"
          :is-active="!disabled"
          label="Вид продуктов переработки"
          :lot-type="model.lotType"
          :store-lot-type="model.storeLotType"
          placeholder="Выберите вид продуктов переработки"
          type="nsi-okpd2-msh"
        />
      </template>
    </sdiz-create>
  </v-container>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import Sdiz from '@/views/Sdiz/Sdiz.vue';
import SdizCreate from '@/views/Sdiz/components/Create.vue';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';

@Component({
  name: 'sdiz-gpb-create',
  components: { ManufacturerAutocomplete, SdizCreate, UiDateInput, SelectRequestComponent },
})
export default class SdizGpbCreate extends Sdiz {
  model: SdizGpbVueModel = new SdizGpbVueModel();
}
</script>
