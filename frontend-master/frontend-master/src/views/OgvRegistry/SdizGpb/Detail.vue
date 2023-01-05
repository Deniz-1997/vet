<template>
  <sdiz-detail v-model="model" :is-show="false" document-type-name="СДИЗ продуктов переработки">
    <template #[`date-create`]="{ disabled, model }">
      <v-col cols="6" lg="3" md="3" xl="2">
        <UiDateInput
          v-model="model.create_date"
          :disabled="disabled"
          mask=""
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
  </sdiz-detail>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import Sdiz from '@/views/Sdiz/Sdiz.vue';
import SdizDetail from '@/views/Sdiz/components/Detail.vue';
import { SdizOgvGpbVueModel } from '@/models/Sdiz/Ogv/SdizOgvGpb.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';

@Component({
  name: 'sdiz-ogv-gpb-detail',
  components: { UiDateInput, ManufacturerAutocomplete, SdizDetail },
})
export default class SdizOgvGpbDetail extends Sdiz {
  model: SdizOgvGpbVueModel = new SdizOgvGpbVueModel();
}
</script>
