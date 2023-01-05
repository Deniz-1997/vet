<template>
  <v-col cols="12">
    <v-row>
      <v-col cols="6" md="4">
        <UiDateInput
          v-model="model.enter_date"
          :disabled="disabledForm"
          :limit-to="today"
          :format="'DD.MM.YYYY'"
          label="Дата формирования"
          placeholder="Выберите дату"
        />
      </v-col>
      <v-col cols="12" md="4">
        <ManufacturerAutocomplete
          v-model="model.owner_id"
          :is-disabled="disabledForm"
          label="Собственник"
          placeholder="Выберите собственника"
          clereables
          show-name-in-tooltip
        />
      </v-col>

      <v-col cols="12" md="4">
        <WeightInput
          v-model="model.amount_kg_mask"
          :disabled="disabledForm"
          label="Масса нетто, кг"
          placeholder="Введите массу"
          @input="model.amount_kg = decimalNumberUnmask(model.amount_kg_mask)"
        />
      </v-col>
      <v-col cols="12" md="4">
        <select-request-component
          v-model="model.okpd2"
          :disabled="disabledForm"
          label="Вид с/х культуры / продукта переработки"
          placeholder="Выберите вид с/х культуры / продукта переработки"
          type="nsi-okpd2-msh"
          :is-active="false"
          is-return-object
        />
      </v-col>

      <v-col cols="12" md="8">
        <autocomplete-priority-address
          v-model="model.current_check_location_id"
          :is-disabled="disabledForm"
          :label="model.currentCheckLocationTitle"
          :placeholder="model.currentCheckLocationPlaceholder"
        />
      </v-col>

      <v-col v-if="model.isShippingOrStorage" cols="12">
        <checkbox-component
          v-model="model.is_not_doc"
          :disabled="disabledForm"
          label="Отсутствуют документы на партию зерна или партию переработки зерна"
          class="checkbox-v"
        />
      </v-col>
      <v-col v-if="model.isShippingOrStorage" cols="12" md="6">
        <input-component
          v-model="model.sdiz_number"
          :disabled="disabledForm || model.is_not_doc"
          label="Номер СДИЗ"
          placeholder="Введите номер СДИЗ"
        />
      </v-col>
      <v-col v-if="model.isShippingOrStorage" cols="12" md="6">
        <input-component
          v-model="model.lot_number"
          :disabled="disabledForm || model.is_not_doc"
          label="Номер партии"
          placeholder="Введите номер партии"
        />
      </v-col>
      <v-col v-if="model.isRepositoryIdField" cols="12" md="6">
        <ElevatorAutocomplete
          v-model="model.repository_id"
          :is-disabled="disabledForm"
          label="Организация, осуществляющая хранение"
          clereables
          placeholder="Укажите организацию, осуществляющую хранение"
        />
      </v-col>
      <v-col v-if="model.isCurrentLocationIdField" cols="12" md="6">
        <autocomplete-priority-address
          v-model="model.current_location_id"
          :is-disabled="disabledForm"
          clearable
          label="Адрес местонахождения зернохранилища"
          placeholder="Выберите местонахождение зернохранилища"
        />
      </v-col>
      <template v-if="type === typeTab.SHIPPING">
        <v-col cols="12" md="6">
          <autocomplete-priority-address
            v-model="model.departures_transit_location_id"
            :is-disabled="disabledForm"
            label="Пункт отправления"
            clearable
            placeholder="Выберите пункт"
          />
        </v-col>
        <v-col cols="12" md="6">
          <autocomplete-priority-address
            v-model="model.current_transit_location_id"
            :is-disabled="disabledForm"
            label="Пункт назначения"
            placeholder="Выберите пункт"
            clearable
          />
        </v-col>
        <v-col cols="12" md="4">
          <v-row>
            <v-col cols="12">
              <select-request-component
                v-if="!disabledForm"
                v-model="model.transport_type"
                label="Вид транспорта"
                placeholder="Выберите вид транспорта"
                type="nsi-transport-type"
                item-text="name"
                is-return-object
                preserve-data
              />
              <InputComponent v-else disabled label="Вид транспорта" :value="transportTypeName" />
            </v-col>
            <v-col cols="12">
              <input-component
                v-model="model.transport_number"
                :disabled="disabledForm"
                label="Номер ГРН/Номер вагона/Номер воздушного судна"
                placeholder="Введите номер"
              />
            </v-col>
            <v-col cols="12">
              <input-component
                v-model="model.container_number"
                :disabled="disabledForm"
                label="Номер контейнера "
                placeholder="Введите номер"
              />
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="12" md="4">
          <v-row>
            <v-col cols="12">
              <ManufacturerAutocomplete
                v-model="model.shipper_id"
                label="Перевозчик"
                clereables
                show-name-in-tooltip
                :is-disabled="disabledForm"
                placeholder="Выберите перевозчика"
              />
            </v-col>
            <template v-if="false">
              <v-col v-if="model.shipper_id" cols="12">
                <label-component label="ИНН" />
                <text-component>
                  {{ model.shipper.inn }}
                </text-component>
              </v-col>
              <v-col v-if="model.shipper_id" cols="12">
                <label-component label="КПП" />
                <text-component>
                  {{ model.shipper.kpp }}
                </text-component>
              </v-col>
            </template>
          </v-row>
        </v-col>
      </template>
      <v-col v-if="model.isSquareField" cols="12">
        <WeightInput
          v-model="model.square_mask"
          :disabled="disabledForm"
          label="Площадь земельного участка или его части (поля) (га)"
          placeholder="Введите площадь"
          @input="model.square = decimalNumberUnmask(model.square_mask)"
        />
      </v-col>
    </v-row>
  </v-col>
</template>

<script lang="ts">
import { Component, Watch, Mixins } from 'vue-property-decorator';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import { RshnFormsMix } from '@/utils/mixins/rshn/rshnForms';
import { WithdrawalTypeEnum } from '@/utils/enums/RshnEnums';
import { decimalNumberMask, decimalNumberUnmask } from '@/components/common/inputs/mask/decimalNumberMask';
import ElevatorAutocomplete from '@/components/common/ElevatorAutocomplete/ElevatorAutocomplete.vue';
import { DictionariesMix } from '@/utils/mixins/dictionaries';
import WeightInput from '@/views/Lot/components/Subcomponents/WeightInput.vue';

@Component({
  name: 'withdrawal-forms',
  components: {
    WeightInput,
    ElevatorAutocomplete,
    TextComponent,
    LabelComponent,
    SelectRequestComponent,
    CheckboxComponent,
    InputComponent,
    AutocompleteComponent,
    ManufacturerAutocomplete,
    AutocompletePriorityAddress,
    UiDateInput,
  },
})
export default class WithdrawalForms extends Mixins(RshnFormsMix, DictionariesMix) {
  declare type: WithdrawalTypeEnum;

  decimalNumberMask = decimalNumberMask;
  decimalNumberUnmask = decimalNumberUnmask;

  @Watch('model.is_not_doc')
  handleIsNotDocChange(isNotDoc) {
    if (isNotDoc) {
      this.model.sdiz_number = null;
      this.model.lot_number = null;
    }
  }

  @Watch('edit')
  async onEditChange(edit) {
    if (edit) {
      this.model.transport_type = await this.dictionaryRecordByCode(
        'nsi-transport-type',
        this.model.transport_type?.code || ''
      );

      this.model.transport_type_id = this.model.transport_type?.id || null;
    }
  }

  get transportTypeName() {
    return this.model.transport_type?.name || '-';
  }
}
</script>

<style scoped></style>
