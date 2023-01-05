<template>
  <v-container v-if="!accessGrantedAuthorities(model.create_sdiz_privileges)">
    Для доступа к функционалу зарегистрируйтесь в реестре организаций, осуществляющих в качестве предпринимательской
    деятельности хранение зерна и оказывающих связанные с хранением услуги.
  </v-container>
  <v-container v-else-if="subjectOfUser">
    <sdiz-create v-model="model" :is-elevator-page="true">
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
    </sdiz-create>
  </v-container>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import Sdiz from '@/views/Sdiz/Sdiz.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import SdizForm from '@/views/Sdiz/components/Form.vue';
import SdizCreate from '@/views/Sdiz/components/Create.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import { SdizElevatorModel } from '@/models/Sdiz/Data/SdizElevator.vue';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';

@Component({
  name: 'sdiz-elevator-create',
  components: { SdizCreate, SdizForm, TextComponent, ButtonComponent, SelectRequestComponent },
})
export default class SdizElevatorCreate extends Sdiz {
  model: SdizElevatorModel = new SdizElevatorModel();
  lot: LotElevatorDataVueModel = new LotElevatorDataVueModel();

  created() {
    this.model.elevator_creator = true;
    this.model.objects.lot = this.lot;
  }
}
</script>
