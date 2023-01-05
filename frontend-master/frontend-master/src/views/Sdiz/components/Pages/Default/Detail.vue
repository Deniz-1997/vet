<template>
  <div v-if="accessGrantedAuthorities(model.view_data_privileges)">
    <sdiz-detail v-model="model">
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
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';

@Component({
  name: 'sdiz-default-detail',
  components: { SdizDetail, SelectRequestComponent },
})
export default class SdizDefaultDetail extends Sdiz {
  model: SdizVueModel = new SdizVueModel();
}
</script>
