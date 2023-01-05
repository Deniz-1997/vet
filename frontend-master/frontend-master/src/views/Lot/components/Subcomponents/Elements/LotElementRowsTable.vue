<template>
  <v-row no-gutters>
    <v-col cols="12">
      <data-table
        :headers="innerValue"
        :is-disable-sort="true"
        :items="rows"
        :items-length="total"
        :loading="isLoading"
        :page="pageable.pageNumber"
        :per-page="pageable.pageSize"
        :selected-row="selectedRows"
        :single-select="isSingleSelect"
        @handleCurrentItems="handleCurrentItems"
        @input="onSelectRows"
        @onClick="onClickByRow"
        @onOptionsChange="onOptionsChange"
      >
        <template #top>
          <header-settings
            :id="model.component_name"
            :value-rows="rows"
            :headers="headers"
            @change="changeAndSortHeader($event)"
          />
        </template>
        <template #[`item.actions`]="{ item }">
          <div :style="isSelectable ? 'width: 200px' : ''">
            <button-component
              v-if="isSelectable"
              class="mr-7"
              size="micro"
              title="Добавить"
              @click="clickSelectedLot(item)"
            />
            <router-link :to="{ name: model.name_route_detail, params: { id: item.id } }" :target="target_blank">
              <img alt="" class="icon" src="/icons/show.svg" />
            </router-link>
          </div>
        </template>
        <template #[`item.objects.owner.name`]="{ item }">
          <a
            v-if="item.objects.owner.name !== '-'"
            @click="getManufacturerByIdSubject(item.objects.owner.subject_id)"
            >{{ item.objects.owner.name }}</a
          >
          <span v-else-if="item.objects.owner.name === '-'">{{ item.objects.owner.name }}</span>
        </template>
        <template #[`item.objects.repository.name`]="{ item }">
          <a
            v-if="item.objects.repository.name !== '-'"
            @click="getManufacturerByIdSubject(item.objects.repository.subject_id)"
            >{{ item.objects.repository.name }}</a
          >
          <span v-else-if="item.objects.repository.name === '-'">{{ item.objects.repository.name }}</span>
        </template>
      </data-table>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Component, Prop } from 'vue-property-decorator';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import Pagination from '@/components/common/Pagination/Pagination.vue';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import { Pageable } from '@/models/Request/Request.types';
import FilterForHeadersTable from '@/components/Filters/FilterForHeadTable.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';
import { mixins } from 'vue-class-component';
import { Manufactures } from '@/utils/mixins/manufactures';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import HeaderSettings from '@/components/Filters/SettingTableHeader.vue';
import { PropType } from 'vue';

export type HeaderItem = {
  text: string;
  value: string;
  notExclude?: boolean | undefined;
};

@Component({
  name: 'lot-element-rows-table',
  components: {
    ButtonComponent,
    FilterForHeadersTable,
    TextComponent,
    CheckboxComponent,
    DataTable,
    Pagination,
    HeaderSettings,
  },
})
export default class LotElementRowsTable extends mixins(Manufactures) {
  @Prop({ type: Array, required: true }) rows!: any[];

  @Prop({ default: () => [] }) selectedRows!: any[];

  @Prop({ type: Number, required: true }) readonly total!: number;

  @Prop({ type: Object, required: true }) readonly model!:
    | LotDataVueModel
    | LotGpbDataVueModel
    | LotElevatorDataVueModel;

  @Prop({ type: Object, required: true }) readonly pageable!: Pageable;

  @Prop({ type: String, default: '' }) readonly target_blank!: string;

  @Prop({ type: Boolean, default: false }) readonly isLoading!: boolean;

  @Prop({ type: Boolean, default: false }) readonly isSingleSelect!: boolean;

  @Prop({ type: Boolean, default: false }) readonly isSelectable!: boolean;
  @Prop({ type: Array as PropType<HeaderItem[]>, default: () => [] }) readonly headers!: HeaderItem[];

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

  onOptionsChange(value): void {
    this.$emit('onOptionsChange', value);
  }

  onSelectRows(value): void {
    this.$emit('onSelectRows', value);
  }

  clickSelectedLot(value): void {
    this.$emit('onSuccess', value);
  }

  onClickByRow(value): void {
    this.$emit('onClickByRow', this.selectedRows.length > 0 ? null : value);
  }

  handleCurrentItems(value): void {
    this.$emit('handleCurrentItems', value);
  }
}
</script>
<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';
@import '@/assets/styles/_container';

img.icon {
  max-width: 100%;
  width: 20px;
}

.disabled-lots {
  color: $input-disabled-color !important;
  opacity: 0.3;
}

.footer {
  transition: transform 0.1s, opacity 0.1s;
}

.footer.hide {
  opacity: 0;
}
</style>
