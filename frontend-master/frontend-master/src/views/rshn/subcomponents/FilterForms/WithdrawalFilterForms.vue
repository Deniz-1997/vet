<template>
  <v-container>
    <v-row :key="clear">
      <v-col cols="12" lg="4" md="6" sm="7" xl="3">
        <label-component label="Дата" />
        <v-row>
          <v-col class="pr-1" cols="12" data-app sm="6">
            <UiDateInput v-model="model.date_from" :limit-to="today" placeholder="от" />
          </v-col>
          <v-col class="pr-1" cols="12" data-app sm="6">
            <UiDateInput v-model="model.date_to" :limit-from="fromDate(model.date_from)" placeholder="до" />
          </v-col>
        </v-row>
      </v-col>
      <v-col cols="12" lg="4" md="3" sm="4" xl="2">
        <input-component v-model="model.gw_number" label="Номер" placeholder="Перечень всех номеров изъятий" />
      </v-col>
      <v-col cols="12" lg="4" md="4" sm="4" xl="3">
        <slot name="status" :model="model" :rshn="rshn">
          <select-request-component
            v-model="model.status_id"
            :custom-items="rshn.statusList"
            label="Статус"
            placeholder="Выберите статус"
          />
        </slot>
      </v-col>

      <v-col cols="12" lg="4" md="4" sm="4" xl="3">
        <select-request-component
          v-model="model.okpd2Code"
          label="Вид с/х культуры / продукта переработки"
          placeholder="Выберите вид с/х культуры / продукта переработки"
          type="nsi-okpd2-codes"
          :is-active="false"
          item-id="code"
        />
      </v-col>

      <v-col cols="12" lg="4" md="6" sm="3" xl="4">
        <ManufacturerAutocomplete
          v-model="model.owner_id"
          label="Собственник"
          clereables
          placeholder="Выберите собственника"
        />
      </v-col>

      <v-col cols="12" lg="4" md="4" sm="4" xl="3">
        <select-request-component
          v-model="model.gw_type"
          :custom-items="rshn.typeWithdrawalList"
          label="Тип изъятия"
          placeholder="Выберите тип изъятия"
        />
      </v-col>

      <v-col cols="12" lg="4" md="3" sm="4" xl="2">
        <WeightInput v-model="filterAmountKgMask" label="Масса нетто, кг" placeholder="Введите массу" />
      </v-col>

      <v-col cols="12" lg="4" md="4" sm="4" xl="3">
        <autocomplete-priority-address
          v-model="model.current_location_id"
          label="Место изъятия"
          clearable
          placeholder="Выберите место изъятия"
        />
      </v-col>

      <v-col cols="12" lg="4" md="3" sm="4" xl="2">
        <input-component
          v-model="filterSquareMask"
          :mask="numberThousandsMask"
          label="Площадь земельного участка (га)"
          placeholder="Введите площадь"
        />
      </v-col>

      <transition-expand>
        <v-row v-show="expanded" class="ma-0">
          <v-col cols="12" lg="4" md="3" sm="4" xl="2">
            <input-component
              v-model="model.sdiz_number"
              label="Номер СДИЗ"
              placeholder="Поиск по СДИЗ все СДИЗ привязанные к поданным сведениям"
            />
          </v-col>

          <v-col cols="12" lg="4" md="3" sm="4" xl="2">
            <input-component
              v-model="model.lot_number"
              label="Номер Партии"
              placeholder="Поиск по партии все партии привязанные к поданным сведениям"
            />
          </v-col>

          <v-col cols="12" lg="4" md="4" sm="4" xl="3">
            <autocomplete-priority-address
              v-model="model.departures_transit_location_id"
              label="Пункт отправления"
              placeholder="вносится через справочник как описано в ПЗ_Адреса Форс, поддерживается поиск по внесенному началу строки"
            />
          </v-col>

          <v-col cols="12" lg="4" md="4" sm="4" xl="3">
            <autocomplete-priority-address
              v-model="model.current_transit_location_id"
              label="Пункт назначения"
              placeholder="вносится через справочник как описано в ПЗ_Адреса Форс, поддерживается поиск по внесенному началу строки"
            />
          </v-col>

          <v-col v-if="product" cols="12" lg="4" md="6" sm="3" xl="4">
            <ManufacturerAutocomplete
              v-model="model.shipper_id"
              label="Перевозчик"
              placeholder="выбирается из справочника ЮЛ"
            />
          </v-col>

          <v-col cols="12" lg="4" md="3" sm="4" xl="2">
            <input-component
              v-model="model.transport_number"
              label="Номер транспортного средства"
              placeholder="Введите номер транспортного средства"
            />
          </v-col>

          <v-col cols="12" lg="4" md="3" sm="4" xl="2">
            <input-component
              v-model="model.container_number"
              label="Номер контейнера"
              placeholder="Введите номер контейнера"
            />
          </v-col>
        </v-row>
      </transition-expand>
    </v-row>
    <v-col class="change-state-showed-filter text-center mt-4" cols="12" @click="expanded = !expanded">
      <fa-icon :name="expanded ? 'angle-up' : 'angle-down'" class="icon" scale="3" />
      <button class="ml-2">
        {{ expanded ? `Скрыть фильтры` : `Показать все фильтры` }}
      </button>
    </v-col>
  </v-container>
</template>

<script lang="ts">
import { Component, Model } from 'vue-property-decorator';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import LotElementRowsTable from '@/views/Lot/components/Subcomponents/Elements/LotElementRowsTable.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import TransitionExpand from '@/components/Forms/TransitionExpand.vue';
import { WithdrawalTypeEnum } from '@/utils/enums/RshnEnums';
import { RshnWithdrawalData } from '@/models/Rshn/Withdrawal/RshnWithdrawalData.vue';
import Rshn from '@/views/rshn/Rshn.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import { decimalNumberMask, decimalNumberUnmask } from '@/components/common/inputs/mask/decimalNumberMask';
import { numberThousandsMask, numberThousandsUnmask } from '@/components/common/inputs/mask/numberThousandsMask';
import WeightInput from '@/views/Lot/components/Subcomponents/WeightInput.vue';

@Component({
  name: 'withdrawal-filter-forms',
  components: {
    WeightInput,
    TransitionExpand,
    ButtonComponent,
    SelectRequestComponent,
    AutocompletePriorityAddress,
    ManufacturerAutocomplete,
    AutocompleteComponent,
    LabelComponent,
    LotElementRowsTable,
    InputComponent,
    UiDateInput,
  },
})
export default class WithdrawalFilterForms extends Rshn {
  @Model('change', { type: Object, required: true }) model!: RshnWithdrawalData;
  expanded = false;

  decimalNumberMask = decimalNumberMask;
  decimalNumberUnmask = decimalNumberUnmask;

  numberThousandsMask = numberThousandsMask;
  numberThousandsUnmask = numberThousandsUnmask;

  get product() {
    return this.model.gw_type === WithdrawalTypeEnum.PRODUCT;
  }

  get filterAmountKgMask() {
    return this.model.amount_kg_mask;
  }

  set filterAmountKgMask(v) {
    this.model.amount_kg_mask = v;
    this.model.amount_kg = decimalNumberUnmask(v);
  }

  get filterSquareMask() {
    return this.model.square_mask;
  }

  set filterSquareMask(v) {
    this.model.square_mask = v;
    this.model.square = numberThousandsUnmask(v);
  }
}
</script>

<style lang="scss" scoped>
@import './src/assets/styles/_variables';

.change-state-showed-filter {
  border-bottom: 1px solid $light-grey-color;
  background-color: $white-color;
  cursor: pointer;
  color: $medium-grey-color;
  font-weight: 400;
  transition: background-color 0.25s;

  &:hover {
    background-color: $light-grey-color;
  }
}
</style>
