<template>
  <reserves-page-component
    v-if="accessGrantedAuthorities(model.register_number_privileges)"
    v-model="model"
    :fields-for-create-rows="['okpd2_id']"
    api-type-store="lot/numbersGos"
    number-name="lot_number"
    text-add-new-record="Сгенерировать номер"
    title="Реестр выданных номеров партий зерна"
    title-for-create-modal="Создание номера для партии зерна"
    @onChangeStateAddModal="isShowModalForRecord = $event"
  >
    <template #filters>
      <v-col cols="12" lg="5" md="12" xl="4">
        <select-request-component
          v-model="isShowModalForRecords"
          label="Вид с/х культуры"
          :lot-type="{ is_grain: true }"
          placeholder="Выберите вид с/х культуры"
          :is-active="false"
          type="nsi-okpd2-codes"
          item-id="code"
        />
      </v-col>
    </template>

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
        <v-col cols="12">
          <select-request-component
            v-model="model.okpd2_id"
            label="Вид с/х культуры"
            :lot-type="{ is_grain: true }"
            store-lot-type="is_grain"
            placeholder="Выберите вид с/х культуры"
            type="nsi-okpd2-msh"
          />
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
import { ReservesNumberLotGosVueModel } from '@/models/Reserves/ReservesNumberLotGos.vue';
import Reserves from '@/views/Reserves/Reserves.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';

@Component({
  name: 'ReservesNumberLot',
  components: { TextComponent, ReservesPageComponent, SelectRequestComponent, AutocompleteComponent },
})
export default class ReservesNumberLot extends Reserves {
  model: ReservesNumberLotGosVueModel = new ReservesNumberLotGosVueModel();

  get isShowModalForRecords() {
    if (this.isShowModalForRecord) return null;
    return this.model.okpd2Code;
  }

  set isShowModalForRecords(value) {
    this.model.okpd2Code = value;
  }
}
</script>
