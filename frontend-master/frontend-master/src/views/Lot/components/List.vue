<template>
  <v-container>
    <slot name="hidden-body">
      <page-component
        :key="clear"
        v-model="innerValue"
        :get-list="apiEndpoint === undefined ? getList : apiEndpoint"
        :is-clear-filters-and-reload-rows="isClearFiltersAndReloadRows"
        :is-request-payload="isRequestPayload"
        :is-show-additional-button="isShowAdditionalButton"
        :title="title"
        :callback-rows="callbackLoadList"
        @onClearFilters="
          () => {
            ++clear;
            isClearFiltersAndReloadRows = false;
          }
        "
      >
        <template #additionButton>
          <v-row class="ma-0" justify="start">
            <dropdown-button off-left-mrg size="micro" title="Сформировать партию" variant="primary">
              <template #list>
                <v-list-item v-for="item in buttonItems" :key="item.id" @click="routerPush(item.to, item.check)">
                  <v-list-item-content>
                    <v-list-item-title>
                      {{ item.title }}
                    </v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </template>
            </dropdown-button>
          </v-row>
        </template>

        <template #[`table`]="{ pageable, rows, total, change, sort }">
          <lot-element-rows-table
            :rows="rows"
            :link="model.name_route_detail"
            :headers="model.getHeaders()"
            :model="model"
            :pageable="pageable"
            :total="total"
            @onOptionsChange="change"
            @onSortChange="sort"
          />
        </template>

        <template #filters>
          <lot-element-filters
            :key="clear"
            v-model="model"
            :need-lock-filters="false"
            :type-lots="typeLots"
            @onChangeFilters="model = $event"
          >
            <template #[`sdiz-product-filter`]="{ lockFromFilterModal, arrayLockFilters, filters, isNeedLockFilters }">
              <slot
                :array-lock-filters="arrayLockFilters"
                :filters="filters"
                :is-need-lock-filters="isNeedLockFilters"
                :lock-from-filter-modal="lockFromFilterModal"
                name="sdiz-product-filter"
              />
            </template>
            <template #[`sdiz-owner-filter`]="{ lockFromFilterModal, arrayLockFilters, filters, isNeedLockFilters }">
              <slot
                :array-lock-filters="arrayLockFilters"
                :filters="filters"
                :is-need-lock-filters="isNeedLockFilters"
                :lock-from-filter-modal="lockFromFilterModal"
                name="sdiz-owner-filter"
              />
            </template>
            <template
              #[`repository-owner-filter`]="{ lockFromFilterModal, arrayLockFilters, filters, isNeedLockFilters }"
            >
              <slot
                :array-lock-filters="arrayLockFilters"
                :filters="filters"
                :is-need-lock-filters="isNeedLockFilters"
                :lock-from-filter-modal="lockFromFilterModal"
                name="repository-owner-filter"
              />
            </template>
          </lot-element-filters>
        </template>
      </page-component>
    </slot>
  </v-container>
</template>

<script lang="ts">
import { Component, Model, Prop } from 'vue-property-decorator';
import Lot from '@/views/Lot/Lot.vue';
import LotElementRowsTable from '@/views/Lot/components/Subcomponents/Elements/LotElementRowsTable.vue';
import LotElementFilters from '@/views/Lot/components/Subcomponents/Elements/LotElementFilters.vue';
import DropdownButton from '@/components/common/buttons/DropDownButton.vue';
import PageComponent from '@/components/Forms/PageComponent.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';
import nsiList from '@/views/NSI/config';
import { LotDataOgvVueModel } from '@/models/Lot/Ogv/LotDataOgv.vue';
import { LotOgvGpbDataVueModel } from '@/models/Lot/Ogv/LotOgvGpbData.vue';
import { LotOgvElevatorDataVueModel } from '@/models/Lot/Ogv/LotOgvElevatorData.vue';

enum ButtonItems {
  ANOTHER_BATCH = 0,
  RESIDUES,
  FROM_FIELD,
  IMPORTED,
  IN_PRODUCT,
  PAREP_SDIZ,
}

@Component({
  name: 'lot-list',
  components: {
    PageComponent,
    DropdownButton,
    LotElementFilters,
    LotElementRowsTable,
  },
})
export default class LotList extends Lot {
  @Model('change', { type: Object }) value!:
    | LotDataVueModel
    | LotGpbDataVueModel
    | LotElevatorDataVueModel
    | LotDataOgvVueModel
    | LotOgvGpbDataVueModel
    | LotOgvElevatorDataVueModel;

  @Prop({ type: String, default: 'Реестр партий зерна' }) public title!: string;

  @Prop({ type: Boolean, default: true }) readonly isRequestPayload!: boolean;

  @Prop({ type: Boolean, default: false }) readonly isShowAdditionalButton!: boolean;

  @Prop({ type: Boolean, default: false }) readonly isElevatorPage!: boolean;

  @Prop({ type: String }) readonly apiEndpoint!: string;

  isClearFiltersAndReloadRows = false;

  model: any = this.innerValue;

  buttonItems = [
    {
      id: ButtonItems.ANOTHER_BATCH,
      title: 'из других партий',
      to: this.model.create_from_another_batch,
      check: this.model.create_other_grain_lot_privileges,
    },
    {
      id: ButtonItems.RESIDUES,
      title: 'из остатков',
      to: this.model.create_from_residues,
      check: this.model.create_surples_grain_lot_privileges,
    },
    {
      id: ButtonItems.IMPORTED,
      title: 'при ввозе',
      to: this.model.create_from_imported,
      check: this.model.create_import_grain_lot_privileges,
    },
    {
      id: ButtonItems.PAREP_SDIZ,
      title: 'на основании СДИЗ на бумажном носителе',
      to: this.model.create_from_sdiz,
      check: this.model.create_sdiz_grain_lot_privileges,
    },
  ];

  get innerValue(): any {
    return this.value;
  }

  set innerValue(value: any) {
    this.model = value;
    this.$emit('change', value);
  }
  routerPush(to, check): void {
    if (this.accessGrantedAuthorities(check)) this.$router.push({ name: to });
  }

  async created() {
    if (this.model.create_from_in_product !== '') {
      this.buttonItems.push({
        id: ButtonItems.IN_PRODUCT,
        title: 'при производстве ',
        to: this.model.create_from_in_product,
        check: this.model.create_product_grain_lot_privileges,
      });
    } else {
      const gosMonitoringButton = {
        id: ButtonItems.FROM_FIELD,
        title: 'по результатам госмониторинга',
        to: this.model.create_from_field,
        check: this.model.create_gosmonitoring_grain_lot_privileges,
      };

      this.buttonItems.splice(1, 0, gosMonitoringButton);
    }

    if (this.isElevatorPage) {
      this.buttonItems = this.buttonItems.filter((e) => e.id !== ButtonItems.IMPORTED);
    }

    await this.fetchOkpd2Msh(this.model.lotType, false);

    if (!this.$store.getters[nsiList['nsi-lots-target'].storeGetter].length) {
      await this.fetchLotsTarget();
    }

    if (!this.$store.getters[nsiList['nsi-lots-purpose'].storeGetter].length) {
      await this.fetchLotsPurpose();
    }
  }

  async onChangeModel(value): Promise<void> {
    this.innerValue = value;
  }

  callbackLoadList(
    model: any,
    modelArray:
      | LotDataVueModel[]
      | LotGpbDataVueModel[]
      | LotElevatorDataVueModel[]
      | LotDataOgvVueModel[]
      | LotOgvGpbDataVueModel[]
      | LotOgvElevatorDataVueModel[],
    response: any[]
  ): any[] {
    return response.map((entry) => {
      function removeEmpty(obj) {
        return Object.fromEntries(Object.entries(obj).filter(([_, v]) => v !== null));
      }

      const entity = removeEmpty(entry);

      return new model.constructor(entity);
    });
  }

  beforeDestroy() {
    this.$store.commit('registryFilters/setFilters', {
      name_route_list: this.model.name_route_list,
      filters: this.value,
    });
  }

  get typeLots() {
    return this.isElevatorPage ? '4' : '';
  }
}
</script>
