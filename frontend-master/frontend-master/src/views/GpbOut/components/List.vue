<template>
  <v-container>
    <page-component
      :key="clear"
      v-model="model"
      :headers="model.headers"
      :callback-rows="callbackLoadList"
      :get-list="model.list_apiendpoit"
      :settings-show="false"
      :is-clear-filters-and-reload-rows="isClearFiltersAndReloadRows"
      :is-show-additional-button="isShowAdditionalButton"
      :title="title"
      @onClearFilters="onClearFilters"
      @onClickShow="(item) => routerShowPush(item.id, model.detail_link)"
    >
      <template #additionButton>
        <v-row class="ma-0" justify="start">
          <button-component
            title="Сформировать производство"
            class="mr-7"
            size="micro"
            variant="primary"
            @click="$router.push({ name: model.create_link })"
          />
        </v-row>
      </template>

      <template #filters>
        <v-row justify-md="start">
          <v-col cols="12" lg="4" md="6" sm="7" xl="3">
            <label-component label="Дата" />
            <v-row>
              <v-col class="pr-1" cols="12" data-app sm="6">
                <UiDateInput v-model="model.date_from" :limit-to="today" :format="'DD.MM.YYYY'" placeholder="от" />
              </v-col>
              <v-col class="pr-1" cols="12" data-app sm="6">
                <UiDateInput
                  v-model="model.date_to"
                  :limit-to="today"
                  :limit-from="fromDate(model.date_from)"
                  :format="'DD.MM.YYYY'"
                  placeholder="до"
                />
              </v-col>
            </v-row>
          </v-col>
          <v-col cols="12" lg="4" md="3" sm="4" xl="2">
            <input-component
              v-model="model.gpbo_number"
              label="Номер производства"
              placeholder="Введите номер производства"
            />
          </v-col>
          <v-col cols="12" lg="4" md="4" sm="5" xl="4">
            <autocomplete-priority-address
              v-model="model.current_location_id"
              label="Местоположение"
              placeholder="Выберите местоположение"
            />
          </v-col>
          <v-col cols="12" lg="4" md="6" sm="3" xl="4">
            <ManufacturerAutocomplete
              v-model="model.manufacturer_id"
              show-name-in-tooltip
              label="Владелец партии"
              placeholder="Выберите владельца партии"
            />
          </v-col>
          <v-col cols="12" lg="4" md="4" sm="4" xl="3">
            <select-request-component
              v-model="model.status"
              :custom-items="statusList"
              label="Статус"
              placeholder="Выберите статус"
            />
          </v-col>
        </v-row>
      </template>
    </page-component>
  </v-container>
</template>

<script lang="ts">
import { Component, Model, Prop } from 'vue-property-decorator';
import PageComponent from '@/components/Forms/PageComponent.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import GpbOut from '@/views/GpbOut/GpbOut.vue';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import LotElementRowsTable from '@/views/Lot/components/Subcomponents/Elements/LotElementRowsTable.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import { GpbOutDataVueModel } from '@/models/Lot/GpbOut/GpbOutData.vue';
import { GpbOutDataOgvVueModel } from '@/models/Lot/Ogv/GpbOutDataOgv.vue';

@Component({
  name: 'gpb-out-list',
  components: {
    ButtonComponent,
    SelectRequestComponent,
    PageComponent,
    AutocompletePriorityAddress,
    ManufacturerAutocomplete,
    AutocompleteComponent,
    UiDateInput,
    LabelComponent,
    LotElementRowsTable,
    InputComponent,
  },
})
export default class GpbOutList extends GpbOut {
  @Model('change', { type: Object, required: true }) value!: GpbOutDataVueModel | GpbOutDataOgvVueModel;
  @Prop({ type: String, default: 'Реестр производств, не подлежащих учету в системе' }) public title!: string;
  @Prop({ type: Boolean, default: true }) readonly isShowAdditionalButton!: boolean;
  @Prop({ type: Boolean, default: true }) readonly isRequestPayload!: boolean;
  clear = 0;
  isClearFiltersAndReloadRows = false;

  get model(): any {
    return this.value;
  }

  set model(value: any) {
    this.$emit('change', value);
  }

  get statusList() {
    return this.model.statusList.map((v) => {
      return { id: v.code, name: v.name };
    });
  }

  onClearFilters() {
    ++this.clear;
    this.isClearFiltersAndReloadRows = false;
  }

  beforeDestroy() {
    this.$store.commit('registryFilters/setFilters', {
      name_route_list: this.model.name_route_list,
      filters: this.model,
    });
  }
}
</script>
