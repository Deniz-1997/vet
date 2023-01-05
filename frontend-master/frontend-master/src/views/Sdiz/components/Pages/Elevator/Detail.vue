<template>
  <div v-if="accessGrantedAuthorities(model.view_data_privileges)">
    <sdiz-detail v-model="model" :is-elevator-menu="true" document-type-name="СДИЗ при хранении">
      <template #[`product-type-field`]="{ disabled, model }">
        <select-request-component
          v-model="model.okpd2_id"
          :disabled="disabled"
          :is-active="!disabled"
          label="Вид с/х культуры"
          :lot-type="model.lotType"
          :store-lot-type="model.storeLotType"
          placeholder="Выберите вид с/х культуры"
          type="nsi-okpd2-msh"
        />
      </template>
    </sdiz-detail>
  </div>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import Sdiz from '@/views/Sdiz/Sdiz.vue';
import SdizDetail from '@/views/Sdiz/components/Detail.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import { SdizElevatorModel } from '@/models/Sdiz/Data/SdizElevator.vue';

@Component({
  name: 'sdiz-elevator-detail',
  components: { SdizDetail, SelectRequestComponent },
})
export default class SdizElevatorDetail extends Sdiz {
  model: SdizElevatorModel = new SdizElevatorModel();
}
</script>
