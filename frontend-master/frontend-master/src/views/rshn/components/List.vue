<template>
  <v-container>
    <page-component
      :key="clear"
      v-model="model"
      :headers="model.headers"
      :callback-rows="callbackLoadList"
      :get-list="model.list_apiendpoint"
      :settings-show="false"
      :is-show="isShow"
      :is-clear-filters-and-reload-rows="isClearFiltersAndReloadRows"
      :is-show-additional-button="isShowAdditionalButton"
      :title="title"
      :is-request-payload="isRequestPayload"
      :is-custom-clear-filters="isCustomClearFilters"
      :custom-clear-filters-function="customClearFiltersFunction"
      :use-filters-store="useFiltersStore"
      @onClearFilters="onClearFilters"
      @onClickShow="(item) => routerShowPush(item.id, model.detail_link)"
    >
      <template #additionButton>
        <v-row class="ma-0" justify="start">
          <button-component
            :title="buttonTitle"
            class="mr-7"
            size="micro"
            variant="primary"
            @click="$router.push({ name: model.create_link })"
          />
        </v-row>
      </template>

      <template #filters>
        <slot name="filter" :clear="clear" />
      </template>

      <template #table="{ change, pageable, rows, total, sort }">
        <RshnListTable
          :value-rows="rows"
          :headers="modelHeaders"
          :model="model"
          :pageable="pageable"
          :total="total"
          :show-select-button="showSelectButton"
          :additional-system-headers="additionalSystemHeaders"
          @onOptionsChange="change"
          @onSortChange="sort"
          @selectItem="(item) => $emit('onSelectItem', item)"
        />
      </template>
    </page-component>
  </v-container>
</template>

<script lang="ts">
import { Component, Model, Prop } from 'vue-property-decorator';
import PageComponent from '@/components/Forms/PageComponent.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import LotElementRowsTable from '@/views/Lot/components/Subcomponents/Elements/LotElementRowsTable.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import Rshn from '@/views/rshn/Rshn.vue';
import RshnListTable from '@/views/rshn/subcomponents/Tables/RsnhListTable.vue';
import { RshnWithdrawalData } from '@/models/Rshn/Withdrawal/RshnWithdrawalData.vue';
import { RshnPrescriptionData } from '@/models/Rshn/Prescription/RshnPrescriptionData.vue';
import { RshnExpertiseData } from '@/models/Rshn/Expertise/RshnExpertiseData.vue';

@Component({
  name: 'rshn-list',
  components: {
    ButtonComponent,
    SelectRequestComponent,
    PageComponent,
    AutocompletePriorityAddress,
    AutocompleteComponent,
    LabelComponent,
    LotElementRowsTable,
    InputComponent,
    RshnListTable,
  },
})
export default class RshnList extends Rshn {
  @Model('change', { type: Object, required: true }) value!:
    | RshnWithdrawalData
    | RshnPrescriptionData
    | RshnExpertiseData;
  @Prop({ type: String, default: 'Реестр со сведениями об изъятии' }) public title!: string;
  @Prop({ type: String, default: 'Сформировать изъятие' }) public buttonTitle!: string;
  @Prop({ type: Boolean, default: true }) readonly isShowAdditionalButton!: boolean;
  @Prop({ type: Boolean, default: false }) readonly isRequestPayload!: boolean;
  @Prop({ type: Boolean, default: true }) readonly isShow!: boolean;
  @Prop({ type: Boolean, default: false }) readonly isCustomClearFilters!: boolean;
  @Prop({ type: Function, default: () => undefined }) customClearFiltersFunction!: (...args: any) => undefined;
  @Prop({ type: Boolean, default: true }) readonly useFiltersStore!: boolean;
  @Prop({ type: Boolean, default: false }) readonly showSelectButton!: boolean;
  @Prop({ type: Array, default: () => ['button'] }) readonly additionalSystemHeaders!: Array<string>;

  get model(): any {
    return this.value;
  }

  set model(value: any) {
    this.$emit('change', value);
  }

  onClearFilters() {
    ++this.clear;
    this.isClearFiltersAndReloadRows = false;
  }

  get modelHeaders(): any[] {
    const functionalHeaders = typeof this.model.getHeaders === 'function' ? this.model.getHeaders() : [];

    return this.model.headers || functionalHeaders || [];
  }

  beforeDestroy() {
    if (this.useFiltersStore) {
      this.$store.commit('registryFilters/setFilters', {
        name_route_list: this.model.name_route_list,
        filters: this.value,
      });
    }
  }
}
</script>

<style lang="scss" scoped></style>
