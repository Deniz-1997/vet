<template>
  <v-container>
    <page-component
      :key="clear"
      v-model="innerValue"
      :headers="model.getHeaders()"
      :get-list="getList"
      :callback-rows="callbackLoadList"
      :is-show-additional-button="additionalButton"
      :url="model.url"
      :is-clear-filters-and-reload-rows="isClearFiltersAndReloadRows"
      :title="model.title"
      @onClearFilters="
        () => {
          ++clear;
          isClearFiltersAndReloadRows = false;
        }
      "
      @onClickShow="(item) => routerShowPush(item.id, model.update_apiendpoit)"
      @onOpenCreatePage="routerCreatePush(model.create_apiendpoit)"
    >
      <template #filters>
        <slot name="filter" :clear="clear" />
      </template>

      <template #table="{ change, pageable, rows, total, sort }">
        <GosmonitoringListTable
          :value-rows="rows"
          :headers="model.getHeaders()"
          :model="model"
          :pageable="pageable"
          :total="total"
          @onOptionsChange="change"
          @onSortChange="sort"
        />
      </template>
    </page-component>
  </v-container>
</template>

<script lang="ts">
import { Component, Model, Prop } from 'vue-property-decorator';
import Gosmonitoring from '@/views/Gosmonitoring/Gosmonitoring.vue';
import { ImplementationVueModel } from '@/models/Gosmonitoring/Implementation.vue';
import PageComponent from '@/components/Forms/PageComponent.vue';
import { ResearchRegisterVueModel } from '@/models/Gosmonitoring/ResearchRegister.vue';
import { ConductedResearchRegisterVue } from '@/models/Gosmonitoring/ConductedResearchRegister.vue';
import { SubmitedByManufacturerModel } from '@/models/Gosmonitoring/SubmitedByManufacturers.vue';
import GosmonitoringListTable from '@/views/Gosmonitoring/components/GosmonitoringListTable.vue';

@Component({
  name: 'gosmonitoring-list-component',
  components: {
    GosmonitoringListTable,
    PageComponent,
  },
})
export default class ImplementationList extends Gosmonitoring {
  @Model('change', { type: Object, required: true }) value!:
    | ResearchRegisterVueModel
    | ImplementationVueModel
    | ConductedResearchRegisterVue
    | SubmitedByManufacturerModel;
  @Prop({ default: true }) additionalButton!: boolean;
  model: any = this.innerValue;

  callbackLoadList(
    model: any,
    modelArray:
      | ResearchRegisterVueModel[]
      | ImplementationVueModel[]
      | ConductedResearchRegisterVue[]
      | SubmitedByManufacturerModel[]
  ) {
    return modelArray;
  }
  get innerValue(): any {
    return this.value;
  }

  set innerValue(value: any) {
    this.model = value;
    this.$emit('change', value);
  }

  routerShowPush(id: string, name: string): void {
    if (this.accessGrantedAuthorities(this.model.view_data_privileges))
      this.$router.push({ name: name, params: { id: id } });
  }
  routerCreatePush(name: string): void {
    if (this.accessGrantedAuthorities(this.model.create_privileges)) this.$router.push({ name: name });
  }

  beforeDestroy() {
    this.$store.commit('registryFilters/setFilters', {
      name_route_list: this.model.name_route_list,
      filters: this.model,
    });
  }
}
</script>
