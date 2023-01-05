<template>
  <v-row>
    <v-col cols="12">
      <DataTable
        :headers="innerValue"
        :items="valueRows"
        :is-disable-sort="true"
        :items-length="total"
        :page="pageable.pageNumber"
        :per-page="pageable.pageSize"
        @onOptionsChange="$emit('onOptionsChange', $event)"
      >
        <template #top>
          <HeaderSettings
            :id="model.component_name"
            :value-rows="valueRows"
            :headers="headers"
            @change="changeAndSortHeader($event)"
          />
        </template>
        <template #[`item.actions`]="{ item }">
          <router-link v-if="!isHideActionShow" :to="{ name: model.name_route_detail, params: { id: item.id } }">
            <FaIcon name="eye" class="icon" color="silver" />
          </router-link>
        </template>
      </DataTable>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Component, Prop } from 'vue-property-decorator';
import Vue from 'vue';
import { PropType } from 'vue';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import { Pageable } from '@/models/Request/Request.types';
import HeaderSettings from '@/components/Filters/SettingTableHeader.vue';
import { ResearchRegisterVueModel } from '@/models/Gosmonitoring/ResearchRegister.vue';
import { ImplementationVueModel } from '@/models/Gosmonitoring/Implementation.vue';
import { ConductedResearchRegisterVue } from '@/models/Gosmonitoring/ConductedResearchRegister.vue';
import { SubmitedByManufacturerModel } from '@/models/Gosmonitoring/SubmitedByManufacturers.vue';

export type HeaderItem = {
  text: string;
  value: string;
  notExclude: boolean | undefined;
};

@Component({
  name: 'sdiz-list-tables',
  components: {
    HeaderSettings,
    DataTable,
  },
})
export default class GosmonitoringListTable extends Vue {
  @Prop({ type: Array, required: true }) valueRows!:
    | ResearchRegisterVueModel[]
    | ImplementationVueModel[]
    | ConductedResearchRegisterVue[]
    | SubmitedByManufacturerModel[];

  @Prop({ type: Array as PropType<HeaderItem[]>, default: () => [] }) readonly headers!: HeaderItem[];
  @Prop({ type: Object, required: true }) readonly model!:
    | ResearchRegisterVueModel
    | ImplementationVueModel
    | ConductedResearchRegisterVue
    | SubmitedByManufacturerModel;

  @Prop({ type: Object, required: true }) readonly pageable!: Pageable;
  @Prop({ type: Number, required: true }) readonly total!: number;
  @Prop({ type: Boolean, default: false }) readonly isHideActionShow!: boolean;
  applyHeaders: HeaderItem[] = [];
  get innerValue() {
    return this.applyHeaders;
  }

  set innerValue(value) {
    this.applyHeaders = value;
  }

  changeAndSortHeader(data): void {
    this.innerValue = data.columns;
    this.$emit('onSortChange', data.sort);
  }
}
</script>
<style lang="scss" scoped>
@import './src/assets/styles/_variables';
@import './src/assets/styles/_mixins';
@import './src/assets/styles/_container';

.checkbox-filter {
  width: 100%;
}
</style>
