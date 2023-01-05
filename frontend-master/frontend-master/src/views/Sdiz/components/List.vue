<template>
  <page-component
    :key="clear"
    v-model="innerValue"
    :callback-rows="callbackLoadList"
    :get-list="getList"
    :is-clear-filters-and-reload-rows="isClearFiltersAndReloadRows"
    :is-request-payload="isRequestPayload"
    :is-show-additional-button="isShowAdditionalButton && !isRshnRole"
    :title="title"
    @change="onChangeModel"
    @onClearFilters="
      () => {
        ++clear;
        isClearFiltersAndReloadRows = false;
      }
    "
  >
    <template #additionButton>
      <v-row class="ma-0" justify="start">
        <button-component class="ml-0" title="Оформить СДИЗ" variant="primary" @click="routerCreatePush()" />
      </v-row>
    </template>

    <template #[`table`]="{ rows, pageable, total, change, sort }">
      <sdiz-list-tables
        :value-rows="rows"
        :headers="model.getHeaders()"
        :model="model"
        :pageable="pageable"
        :total="total"
        @onOptionsChange="change"
        @onSortChange="sort"
      />
    </template>

    <template #filters>
      <v-row :key="clear">
        <slot :model="model" name="number-filter" />

        <v-col cols="12" lg="5" md="6" xl="4">
          <label-component label="Дата" />
          <v-row no-gutters>
            <v-col class="pr-1" cols="12" data-app sm="6">
              <UiDateInput v-model="model.date_from" :format="'DD.MM.YYYY'" :limit-to="today" placeholder="от" />
            </v-col>
            <v-col class="pr-1" cols="12" data-app sm="6">
              <UiDateInput
                v-model="model.date_to"
                :limit-to="today"
                :limit-from="fromDate(model.date_from)"
                :format="'DD.MM.YYYY'"
                class="ml-3"
                placeholder="до"
              />
            </v-col>
          </v-row>
        </v-col>

        <v-col cols="12" lg="4" md="6" xl="3">
          <label-component label="Масса, кг" />
          <v-row no-gutters>
            <v-col class="pr-1" cols="6">
              <WeightInput v-model="filterAmountKgFromMask" label="" placeholder="От" type="text" />
            </v-col>
            <v-col class="pr-1" cols="6">
              <WeightInput v-model="filterAmountKgToMask" label="" placeholder="До" type="text" class="ml-3" />
            </v-col>
          </v-row>
        </v-col>

        <slot name="shiper-filter">
          <v-col cols="4">
            <autocomplete-priority-address
              v-model="model.shipper_location_id"
              item-value="id"
              label="Пункт отправления"
              placeholder="Пункт отправления"
            />
          </v-col>
        </slot>
        <slot name="consignee-filter">
          <v-col cols="4">
            <autocomplete-priority-address
              v-model="model.consignee_location_id"
              item-value="id"
              label="Пункт назначения"
              placeholder="Пункт назначения"
            />
          </v-col>
        </slot>

        <v-col v-if="!isFiltersForElevator" cols="12" lg="6" md="6" xl="3">
          <ManufacturerAutocomplete
            v-model="model.authorized_person"
            :placeholder="placeholderForManufacturer"
            label="Уполномоченное лицо"
            show-name-in-tooltip
            clereables
          />
        </v-col>

        <slot name="lot-number-filters">
          <v-col cols="12" :lg="isFiltersForElevator ? 4 : 6" md="6" xl="4">
            <input-component
              v-model="model.getObjectLot().lot_number"
              label="Номер партии"
              placeholder="Введите номер партии"
            />
          </v-col>
        </slot>

        <v-col cols="12" :lg="isFiltersForElevator ? 3 : 6" md="6" xl="4">
          <select-request-component
            v-model="model.status_id"
            :custom-items="model.objects.sdiz_status.getStatuses()"
            item-id="id"
            item-name="name"
            label="Статус СДИЗ"
            placeholder="Выберите статус СДИЗ"
          />
        </v-col>

        <slot :is-filters-for-elevator="isFiltersForElevator" name="additional-filters">
          <v-col cols="12" lg="6" :md="isFiltersForElevator ? 6 : 12" xl="4">
            <select-request-component
              v-model="model.getObjectLot().okpd2Code"
              :lot-type="model.getObjectLot().getLotType()"
              :label="model.lot_type_name"
              :placeholder="'Выберите' + ' ' + model.lot_type_name"
              type="nsi-okpd2-codes"
              :is-active="false"
              item-id="code"
            />
          </v-col>
        </slot>
        <slot :is-filters-for-elevator="isFiltersForElevator" name="esiz-number-filters">
          <v-col v-if="!isFiltersForElevator" cols="12" lg="6" :md="isFiltersForElevator ? 6 : 12" xl="4">
            <input-component v-model="model.eisz_number" label="Номер закупки" placeholder="Введите номер закупки" />
          </v-col>
        </slot>
        <slot :is-filters-for-elevator="isFiltersForElevator" name="esiz-filters">
          <v-col
            v-if="!isFiltersForElevator"
            cols="12"
            lg="6"
            :md="isFiltersForElevator ? 6 : 12"
            xl="4"
            class="mt-5 mt-lg-10"
          >
            <checkbox-component
              v-model="model.eisz_number_checkbox_init"
              label="Закупка для государственных нужд"
              class="float-left checkbox-v"
            />
          </v-col>
        </slot>
        <slot name="elevator-filter"> </slot>

        <v-col v-if="!isFiltersForElevator" class="pa-0" cols="12">
          <!----- фильтры для /sdiz/list/ ----->
          <transition-expand>
            <v-row v-show="expanded" class="ma-0">
              <v-col cols="12" lg="6" md="6" xl="3">
                <select-request-component
                  v-model="model.getObjectLot().target_id"
                  label="Цель использования"
                  placeholder="Выберите цель"
                  type="nsi-lots-target"
                />
              </v-col>

              <v-col cols="12" lg="6" md="6" xl="3">
                <select-request-component
                  v-model="model.getObjectLot().objects.purpose"
                  label="Назначение"
                  placeholder="Выберите назначение"
                  type="nsi-lots-purpose"
                  preserve-data
                  is-return-object
                />
              </v-col>

              <v-col cols="12" lg="6" md="6" xl="3">
                <ManufacturerAutocomplete
                  v-model="model.owner_id"
                  :placeholder="placeholderForManufacturer"
                  label="Владелец партии"
                  show-name-in-tooltip
                  clereables
                />
              </v-col>

              <v-col cols="12" lg="6" md="6" xl="3">
                <autocomplete-priority-address
                  v-model="model.getObjectLot().current_location_id"
                  label="Местоположение"
                  placeholder="Выберите местоположение"
                />
              </v-col>

              <v-col cols="12" lg="6" md="6" xl="3">
                <select-request-component
                  v-model="model.sdiz_type"
                  :custom-items="operations"
                  :multiple="true"
                  label="Операция"
                  placeholder="Выберите операцию"
                />
              </v-col>

              <v-col cols="12" lg="6" md="6" xl="3">
                <ManufacturerAutocomplete
                  v-model="model.seller_id"
                  :placeholder="placeholderForManufacturer"
                  label="Продавец"
                  show-name-in-tooltip
                  clereables
                />
              </v-col>

              <v-col cols="12" lg="6" md="6" xl="3">
                <ManufacturerAutocomplete
                  v-model="model.buyer_id"
                  :placeholder="placeholderForManufacturer"
                  label="Покупатель"
                  show-name-in-tooltip
                  clereables
                />
              </v-col>

              <v-col cols="12" lg="6" md="6" xl="3">
                <ManufacturerAutocomplete
                  v-model="model.consignee_id"
                  :placeholder="placeholderForManufacturer"
                  label="Грузополучатель"
                  show-name-in-tooltip
                  clereables
                />
              </v-col>

              <v-col cols="12" lg="6" md="6" xl="3">
                <ManufacturerAutocomplete
                  v-model="model.shipper_id"
                  :placeholder="placeholderForManufacturer"
                  label="Грузоотправитель"
                  show-name-in-tooltip
                  clereables
                />
              </v-col>

              <v-col cols="12" lg="6" md="6" xl="3">
                <input-component
                  v-model="model.contract_number"
                  label="Номер договора"
                  placeholder="Введите номер договора"
                />
              </v-col>
            </v-row>
          </transition-expand>
        </v-col>

        <!----- фильтры для /sdiz/elevator/list/ ----->
        <template v-if="isFiltersForElevator">
          <v-col cols="12" lg="4" md="6" xl="4">
            <ManufacturerAutocomplete
              v-model="model.owner_id"
              :placeholder="placeholderForManufacturer"
              label="Владелец партии"
              clereables
              show-name-in-tooltip
            />
          </v-col>

          <v-col cols="12" lg="4" md="6" xl="4">
            <ManufacturerAutocomplete
              v-model="model.buyer_id"
              :placeholder="placeholderForManufacturer"
              label="Покупатель"
              clereables
              show-name-in-tooltip
            />
          </v-col>

          <v-col cols="12" lg="4" md="6" xl="4">
            <ManufacturerAutocomplete
              v-model="model.seller_id"
              :placeholder="placeholderForManufacturer"
              label="Продавец"
              clereables
              show-name-in-tooltip
            />
          </v-col>

          <v-col cols="12" lg="4" md="6" xl="4">
            <select-request-component
              v-model="model.sdiz_type"
              :custom-items="operations"
              :multiple="true"
              label="Операция"
              placeholder="Выберите операцию"
            />
          </v-col>
        </template>

        <v-col
          v-show="!isFiltersForElevator"
          class="change-state-showed-filter text-center mt-4"
          cols="12"
          @click="expanded = !expanded"
        >
          <fa-icon :name="expanded ? 'angle-up' : 'angle-down'" class="icon" scale="3" />
          <button class="ml-2">
            {{ expanded ? `Скрыть фильтры` : `Показать все фильтры` }}
          </button>
        </v-col>
      </v-row>
    </template>
  </page-component>
</template>

<script lang="ts">
import { Component, Model, Prop } from 'vue-property-decorator';
import Sdiz from '@/views/Sdiz/Sdiz.vue';
import SdizListTables from '@/views/Sdiz/components/Subcomponents/Table/SdiztListTables.vue';
import PageComponent from '@/components/Forms/PageComponent.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import TransitionExpand from '@/components/Forms/TransitionExpand.vue';
import IconComponent from '@/components/common/IconComponent/IconComponent.vue';
import { currentDay } from '@/utils';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import { SdizOgvVueModel } from '@/models/Sdiz/Ogv/SdizOgv.vue';
import { SdizOgvGpbVueModel } from '@/models/Sdiz/Ogv/SdizOgvGpb.vue';
import { dateFrom } from '@/utils/date';
import { SdizElevatorModel } from '@/models/Sdiz/Data/SdizElevator.vue';
import { ERole } from '@/models/roles';
import { decimalNumberUnmask } from '@/components/common/inputs/mask/decimalNumberMask';
import WeightInput from '@/views/Lot/components/Subcomponents/WeightInput.vue';

@Component({
  name: 'sdiz-filter-list',
  components: {
    WeightInput,
    ButtonComponent,
    IconComponent,
    TransitionExpand,
    LabelComponent,
    AutocompletePriorityAddress,
    ManufacturerAutocomplete,
    PageComponent,
    SdizListTables,
    SelectRequestComponent,
    InputComponent,
    AutocompleteComponent,
    UiDateInput,
    CheckboxComponent,
    TextComponent,
  },
})
export default class SdizList extends Sdiz {
  @Model('change', {
    type: [SdizGpbVueModel, SdizVueModel, SdizOgvVueModel, SdizOgvGpbVueModel, SdizElevatorModel],
    required: true,
  })
  value!: SdizGpbVueModel | SdizVueModel | SdizOgvVueModel | SdizOgvGpbVueModel | SdizElevatorModel;

  @Prop({ type: Boolean, default: false }) readonly isRequestPayload!: boolean;

  @Prop({ type: Boolean, default: true }) readonly isShowAdditionalButton!: boolean;

  @Prop({ type: Boolean, default: false }) readonly isFiltersForElevator!: boolean;

  @Prop({ type: String, default: 'Реестр СДИЗ' }) readonly title!: string;

  @Prop({ type: String }) readonly apiEndpoint!: string;

  model: any = this.innerValue;

  currentDay = currentDay();

  expanded = false;

  isClearFiltersAndReloadRows = false;

  placeholderForManufacturer = 'Начните вводить наименование, ИНН, КПП или ОГРН';

  decimalNumberUnmask = decimalNumberUnmask;

  fromDate(date) {
    return dateFrom(date, -1);
  }

  routerCreatePush() {
    if (this.accessGrantedAuthorities(this.model.create_sdiz_privileges))
      this.$router.push({ name: this.model.name_route_create });
  }

  get getList() {
    return typeof this.apiEndpoint === 'undefined' ? this.model.list_apiendpoit : this.apiEndpoint;
  }

  get isRshnRole() {
    return this.$store.getters['auth/roles'].includes(ERole.ROLE_RSHN);
  }

  get operations() {
    return this.$store.getters['sdiz/getTypes'];
  }

  get innerValue(): SdizGpbVueModel | SdizVueModel | SdizOgvVueModel | SdizOgvGpbVueModel {
    return this.value;
  }

  set innerValue(value: SdizGpbVueModel | SdizVueModel | SdizOgvVueModel | SdizOgvGpbVueModel) {
    this.model = value;
    this.$emit('change', value);
  }

  async onChangeModel(value): Promise<void> {
    this.innerValue = value;
  }

  async created() {
    await this.fetchTypes();
  }

  get filterAmountKgFromMask() {
    return this.model.getObjectLot().amount_kg_from_mask;
  }

  set filterAmountKgFromMask(v) {
    this.model.getObjectLot().amount_kg_from_mask = v;
    this.model.getObjectLot().amount_kg_from = decimalNumberUnmask(v);
  }

  get filterAmountKgToMask() {
    return this.model.getObjectLot().amount_kg_to_mask;
  }

  set filterAmountKgToMask(v) {
    this.model.getObjectLot().amount_kg_to_mask = v;
    this.model.getObjectLot().amount_kg_to = decimalNumberUnmask(v);
  }

  beforeDestroy() {
    this.$store.commit('registryFilters/setFilters', {
      name_route_list: this.model.name_route_list,
      filters: this.model,
    });
  }
}
</script>

<style lang="scss">
@import './src/assets/styles/_variables';
@import './src/assets/styles/_mixins';

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

  .icon {
    width: auto;
    height: 23px;
    max-width: 100%;
    max-height: 100%;
    transition: all 0.25s;
    color: $light-grey-color;
  }
}
</style>
