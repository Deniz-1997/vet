<template>
  <reserves-page-component
    v-if="accessGrantedAuthorities(model.register_number_privileges)"
    v-model="model"
    :fields-for-create-rows="[]"
    api-type-store="sdiz/numbers"
    number-name="sdiz_number"
    text-add-new-record="Сгенерировать номер"
    title="Реестр выданных номеров СДИЗ"
    title-for-create-modal="Создание номера для СДИЗ"
    @onChangeStateAddModal="isShowModalForRecord = $event"
  >
    <template #create-form>
      <v-row>
        <v-col align-self="center" cols="6">
          <text-component variant="h5">Дата</text-component>
          <text-component>{{ currentDay }}</text-component>
        </v-col>
        <v-col cols="6">
          <text-component variant="h5">Оформляет</text-component>
          <text-component>{{ getUsername }}</text-component>
        </v-col>
      </v-row>
    </template>
  </reserves-page-component>
</template>

<script lang="ts">
import { Component } from 'vue-property-decorator';
import ReservesPageComponent from '@/views/Reserves/components/Subcomponents/ReservesPage.vue';
import TextComponent from '@/components/common/TextComponent.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import Reserves from '@/views/Reserves/Reserves.vue';
import { ReservesNumberSdizGosVueModel } from '@/models/Reserves/ReservesNumberSdizGos.vue';

@Component({
  name: 'ReservesNumberSdiz',
  components: { TextComponent, ReservesPageComponent, SelectRequestComponent },
})
export default class ReservesNumberSdiz extends Reserves {
  model: ReservesNumberSdizGosVueModel = new ReservesNumberSdizGosVueModel();
}
</script>
