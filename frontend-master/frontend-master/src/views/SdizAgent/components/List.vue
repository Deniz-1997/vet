<template>
  <v-container>
    <page-component
      :key="clear"
      v-model="model"
      :get-list="getList"
      :headers="model.getHeaders()"
      :is-clear-filters-and-reload-rows="isClearFiltersAndReloadRows"
      :is-show-additional-button="isShowAdditionalButton"
      :is-request-payload="true"
      :pageable="pageable"
      :default-sorting="{ property: 'sdiz.sdiz_number', direction: 'DESC' }"
      title="Реестр сведений предоставляемых агентом"
      @onClearFilters="
        () => {
          ++clear;
          isClearFiltersAndReloadRows = false;
        }
      "
      @onClickShow="onClickShow"
      @onOpenCreatePage="$router.push({ name: 'sdiz_agent_create' })"
    >
      <template #filters>
        <v-row>
          <v-col cols="12" lg="3" md="6" xl="3">
            <input-component v-model="model.sdiz_number" label="Номер СДИЗ" placeholder="Введите номер" type="text" />
          </v-col>
          <v-col cols="12" lg="3" md="6" xl="3">
            <input-component
              v-model="model.number_contract"
              label="Номер государственного контракта с агентом"
              placeholder="Введите номер"
              type="text"
            />
          </v-col>
          <v-col cols="12" lg="3" md="6" xl="3">
            <input-component
              v-model="model.number_resolution"
              label="Номер решения"
              placeholder="Введите номер"
              type="text"
            />
          </v-col>
          <v-col cols="12" lg="3" md="6" xl="3">
            <input-component
              v-model="model.sdiz_contract_number"
              label="Номер гражданско-правового договора"
              placeholder="Введите номер"
              type="text"
            />
          </v-col>

          <v-col cols="12" lg="3" md="6" xl="3">
            <label-component label="Дата формирования" />
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
                  placeholder="до"
                />
              </v-col>
            </v-row>
          </v-col>

          <v-col cols="12" lg="3" md="6" xl="3">
            <label-component label="Дата государственного контракта с агентом" />
            <v-row no-gutters>
              <v-col class="pr-1" cols="12" data-app sm="6">
                <UiDateInput
                  v-model="model.date_contract_from"
                  :limit-to="today"
                  :format="'DD.MM.YYYY'"
                  placeholder="от"
                />
              </v-col>
              <v-col class="pr-1" cols="12" data-app sm="6">
                <UiDateInput
                  v-model="model.date_contract_to"
                  :limit-to="today"
                  :limit-from="fromDate(model.date_contract_from)"
                  :format="'DD.MM.YYYY'"
                  placeholder="до"
                />
              </v-col>
            </v-row>
          </v-col>
          <v-col cols="12" lg="3" md="6" xl="3">
            <label-component label="Дата решения" />
            <v-row no-gutters>
              <v-col class="pr-1" cols="12" data-app sm="6">
                <UiDateInput
                  v-model="model.date_resolution_from"
                  :limit-to="today"
                  :format="'DD.MM.YYYY'"
                  placeholder="от"
                />
              </v-col>
              <v-col class="pr-1" cols="12" data-app sm="6">
                <UiDateInput
                  v-model="model.date_resolution_to"
                  :limit-to="today"
                  :limit-from="fromDate(model.date_resolution_from)"
                  :format="'DD.MM.YYYY'"
                  placeholder="до"
                />
              </v-col>
            </v-row>
          </v-col>

          <v-col cols="12" lg="3" md="6" xl="3">
            <label-component label="Дата гражданско-правового договора" />
            <v-row no-gutters>
              <v-col class="pr-1" cols="12" data-app sm="6">
                <UiDateInput v-model="model.gka_date_from" :limit-to="today" :format="'DD.MM.YYYY'" placeholder="от" />
              </v-col>
              <v-col class="pr-1" cols="12" data-app sm="6">
                <UiDateInput
                  v-model="model.gka_date_to"
                  :limit-to="today"
                  :limit-from="fromDate(model.gka_date_from)"
                  :format="'DD.MM.YYYY'"
                  placeholder="до"
                />
              </v-col>
            </v-row>
          </v-col>
          <slot :model="model" name="sdiz-owner-filter" />

          <v-col cols="12" lg="5" md="6" xl="4">
            <select-request-component
              v-model="model.okpd2_code"
              label="Вид с/х культуры"
              :lot-type="model.sdiz.getObjectLot().getLotType()"
              placeholder="Выберите вид с/х культуры"
              type="nsi-okpd2-codes"
              item-id="code"
              :is-active="false"
            />
          </v-col>

          <v-col cols="12" lg="5" md="6" xl="4">
            <elevator-autocomplete
              v-model="model.repository_id"
              label="Реестровый номер организации, осуществляющей хранение"
              placeholder="Начните вводить наименование, ИНН, КПП или ОГРН"
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
import { AgentVueModel } from '@/models/Sdiz/Agent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import { AgentOgvVueModel } from '@/models/Sdiz/Ogv/AgentOgv.vue';
import SdizAgent from '@/views/SdizAgent/SdizAgent.vue';
import ElevatorAutocomplete from '@/components/common/ElevatorAutocomplete/ElevatorAutocomplete.vue';
import { dateFrom } from '@/utils/date';

@Component({
  name: 'sdiz-agent-filter-list',
  components: {
    LabelComponent,
    PageComponent,
    InputComponent,
    UiDateInput,
    SelectRequestComponent,
    AutocompleteComponent,
    ElevatorAutocomplete,
  },
})
export default class SdizAgentFilterList extends SdizAgent {
  @Model('change', { type: [AgentVueModel, AgentOgvVueModel], required: true }) value!:
    | AgentVueModel
    | AgentOgvVueModel;
  @Prop({ type: String }) readonly getList!: string;
  @Prop({ type: String }) readonly routerLink!: string;
  @Prop({ type: Boolean, default: true }) readonly isShowAdditionalButton!: boolean;
  model: any = this.innerValue;
  clear = 0;
  isClearFiltersAndReloadRows = false;

  fromDate(date) {
    return dateFrom(date, -1);
  }

  get innerValue(): AgentVueModel | AgentOgvVueModel {
    return this.value;
  }

  set innerValue(value: AgentVueModel | AgentOgvVueModel) {
    this.model = value;
    this.$emit('change', value);
  }

  onClickShow(model: AgentVueModel): void {
    const id: string = model.id!.toString();

    this.$router.push({ name: this.routerLink, params: { id: id } });
  }

  beforeDestroy() {
    this.$store.commit('registryFilters/setFilters', {
      name_route_list: this.model.name_route_list,
      filters: this.model,
    });
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';
</style>
