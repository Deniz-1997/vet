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
            :additional-system-headers="additionalSystemHeaders"
            @change="changeAndSortHeader($event)"
          />
        </template>
        <template #[`item.actions`]="{ item }">
          <router-link v-if="!isHideActionShow" :to="{ name: model.detail_link, params: { id: item.id } }">
            <img alt="" class="icon" src="/icons/show.svg" />
          </router-link>
        </template>

        <template #[`item.button`]="{ item }">
          <ButtonComponent
            v-if="showSelectButton"
            class="ml-0 mt-3 mb-3 btn-custom"
            size="micro"
            title="Выбрать"
            variant="primary"
            @click="$emit('selectItem', item)"
          />
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
import { RshnExpertiseData } from '@/models/Rshn/Expertise/RshnExpertiseData.vue';
import { RshnWithdrawalData } from '@/models/Rshn/Withdrawal/RshnWithdrawalData.vue';
import { RshnPrescriptionData } from '@/models/Rshn/Prescription/RshnPrescriptionData.vue';
import { RshnWithdrawalShort } from '@/models/Rshn/ShortModel/RshnWithdrawalShort.vue';
import { SdizShortVue } from '@/models/Rshn/ShortModel/SdizShort.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';

export type HeaderItem = {
  text: string;
  value: string;
  notExclude: boolean | undefined;
};

@Component({
  name: 'rshn-list-table',
  components: {
    ButtonComponent,
    HeaderSettings,
    DataTable,
  },
})
export default class RshnListTable extends Vue {
  @Prop({ type: Array, required: true }) valueRows!:
    | RshnExpertiseData[]
    | RshnWithdrawalData[]
    | RshnPrescriptionData[]
    | RshnWithdrawalShort[]
    | SdizShortVue[];

  @Prop({ type: Array as PropType<HeaderItem[]>, default: () => [] }) readonly headers!: HeaderItem[];
  @Prop({ type: Object, required: true }) readonly model!:
    | RshnExpertiseData
    | RshnWithdrawalData
    | RshnPrescriptionData
    | RshnWithdrawalShort
    | SdizShortVue;

  @Prop({ type: Object, required: true }) readonly pageable!: Pageable;
  @Prop({ type: Number, required: true }) readonly total!: number;
  @Prop({ type: Boolean, default: false }) readonly isHideActionShow!: boolean;
  @Prop({ type: Boolean, default: false }) readonly showSelectButton!: boolean;
  @Prop({ type: Array, default: () => [] }) readonly additionalSystemHeaders!: Array<string>;

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

img.icon {
  min-width: 20px;
}
</style>
