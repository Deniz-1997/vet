<template>
  <lot-detail v-model="model" :is-show="false" :sdiz-model="sdizModel">
    <template #[`date-lot`]="{ disabled, model }">
      <v-col cols="6" lg="3" md="3" xl="2">
        <select-request-component
          v-model="model.lot_year"
          :custom-items="model.dateArray"
          :disabled="disabled"
          label="Год урожая"
          placeholder="Выберите год"
        />
      </v-col>
    </template>
  </lot-detail>
</template>
<script lang="ts">
import { Component } from 'vue-property-decorator';
import Lot from '@/views/Lot/Lot.vue';
import LotDetail from '@/views/Lot/components/Detail.vue';
import { LotOgvElevatorDataVueModel } from '@/models/Lot/Ogv/LotOgvElevatorData.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import { SdizOgvVueModel } from '@/models/Sdiz/Ogv/SdizOgv.vue';

@Component({
  name: 'lot-ogv-elevator-detail',
  components: {
    LotDetail,
    SelectRequestComponent,
  },
})
export default class LotOgvElevatorDetail extends Lot {
  model: LotOgvElevatorDataVueModel = new LotOgvElevatorDataVueModel();
  sdizModel: SdizOgvVueModel = new SdizOgvVueModel();
}
</script>
