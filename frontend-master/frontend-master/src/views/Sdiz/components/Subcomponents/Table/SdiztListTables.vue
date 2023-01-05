<template>
  <v-row>
    <v-col cols="12">
      <data-table
        :headers="innerValue"
        :items="valueRows"
        :is-disable-sort="true"
        :items-length="total"
        :page="pageable.pageNumber"
        :per-page="pageable.pageSize"
        @onOptionsChange="$emit('onOptionsChange', $event)"
      >
        <template #top>
          <header-settings
            :id="model.component_name"
            :value-rows="valueRows"
            :headers="headers"
            @change="changeAndSortHeader($event)"
          />
        </template>
        <template #[`item.actions`]="{ item }">
          <router-link v-if="!isHideActionShow" :to="{ name: model.name_route_detail, params: { id: item.id } }">
            <fa-icon name="eye" class="icon" color="silver" />
          </router-link>
          <slot name="action-select-row" :item="item" />
        </template>
        <template #[`item.objects.seller.name`]="{ item }">
          <a v-if="item.objects.seller.name !== '-'" @click="getManufacturerByIdSubject(item.seller_id)">
            {{ item.objects.seller.name }}
          </a>
          <span v-else-if="item.objects.seller.name === '-'">{{ item.objects.seller.name }}</span>
        </template>
        <template #[`item.objects.buyer.name`]="{ item }">
          <a v-if="item.objects.buyer.name !== '-'" @click="getManufacturerByIdSubject(item.buyer_id)">
            {{ item.objects.buyer.name }}
          </a>
          <span v-else-if="item.objects.buyer.name === '-'">{{ item.objects.buyer.name }}</span>
        </template>
        <template #[`item.objects.carrier.name`]="{ item }">
          <a v-if="item.objects.carrier.name !== '-'" @click="getManufacturerByIdSubject(item.carrier_id)">
            {{ item.objects.carrier.name }}
          </a>
          <span v-else-if="item.objects.carrier.name === '-'">{{ item.objects.carrier.name }}</span>
        </template>
        <template #[`item.objects.consignee.name`]="{ item }">
          <a v-if="item.objects.consignee.name !== '-'" @click="getManufacturerByIdSubject(item.consignee_id)">
            {{ item.objects.consignee.name }}
          </a>
          <span v-else-if="item.objects.consignee.name === '-'">{{ item.objects.consignee.name }}</span>
        </template>
        <template #[`item.objects.shipper.name`]="{ item }">
          <a v-if="item.objects.shipper.name !== '-'" @click="getManufacturerByIdSubject(item.shipper_id)">
            {{ item.objects.shipper.name }}
          </a>
          <span v-else-if="item.objects.shipper.name === '-'">{{ item.objects.shipper.name }}</span>
        </template>
        <template #[`item.objects.lot.lot_number`]="{ item }">
          <router-link :to="{ name: item.to_lot_link, params: { id: item.objects.lot.id } }">
            {{ item.objects.lot.lot_number }}
          </router-link>
        </template>
        <template #[`item.objects.gpb.gpb_number`]="{ item }">
          <router-link :to="{ name: 'lots_gpb_detail', params: { id: item.objects.gpb.id } }">
            {{ item.objects.gpb.gpb_number }}
          </router-link>
        </template>
      </data-table>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Component, Prop, Mixins } from 'vue-property-decorator';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import { AdditionalMix } from '@/utils/mixins/additional';
import { Pageable } from '@/models/Request/Request.types';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import FilterForHeadersTable from '@/components/Filters/FilterForHeadTable.vue';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';
import ViewSettingsModal from '@/components/common/ViewSettings/ViewSettingsModal.vue';
import { PropType } from 'vue';
import HeaderSettings from '@/components/Filters/SettingTableHeader.vue';
import { Manufactures } from '@/utils/mixins/manufactures';

export type HeaderItem = {
  text: string;
  value: string;
  notExclude: boolean | undefined;
};

@Component({
  name: 'sdiz-list-tables',
  components: {
    HeaderSettings,
    ViewSettingsModal,
    FilterForHeadersTable,
    TextComponent,
    DataTable,
    DialogComponent,
    CheckboxComponent,
  },
})
export default class SdiztListTables extends Mixins(AdditionalMix, Manufactures) {
  @Prop({ type: Array, required: true }) valueRows!: Array<SdizGpbVueModel | SdizVueModel>;
  @Prop({ type: Array as PropType<HeaderItem[]>, default: () => [] }) readonly headers!: HeaderItem[];
  @Prop({ type: Object, required: true }) readonly model!:
    | SdizGpbVueModel
    | SdizVueModel
    | LotDataVueModel
    | LotGpbDataVueModel
    | LotElevatorDataVueModel;

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
