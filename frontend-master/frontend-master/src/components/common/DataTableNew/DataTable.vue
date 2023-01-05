<template>
  <!-- eslint-disable vue/no-v-model-argument, vue/valid-v-slot -->
  <horizontal-scroll-new :loading="loading" scroll-target=".v-data-table__wrapper" :hide-arrows="false">
    <text-component v-if="title" variant="h4">
      {{ title }}
    </text-component>
    <tabs
      v-if="tabs && tabs.length"
      :current-tab-index="currentTabIndex"
      :loading="isTabsLoading"
      :on-change="onTabChange"
      :tabs="tabs"
    />
    <slot name="tabs.after" />
    <!-- ToDo при необходимости добавить метод для сортировки столбцов таблицы
      @update:options="handleOptionsChange" -->
    <v-data-table
      ref="table"
      v-model:expanded="expanded"
      :disable-sort="isDisableSort"
      :fixed-header="fixedHeader"
      :headers="columns ? columns : headers"
      :headers-length="columns ? columns.length : headers.length"
      :hide-default-header="hideHeader"
      :item-class="itemClass"
      :items="items"
      :loading="loading"
      :server-items-length="itemsLength"
      :show-expand="showExpand"
      :show-select="isSelectable"
      :single-expand="isSingleExpand"
      :single-select="singleSelect"
      :options.sync="options"
      :multi-sort="multiSort"
      class="table"
      hide-default-footer
      loading-text=""
      @current-items="handleCurrentItems"
      @input="handleSelectItems"
      @update:page="handlePageChange"
      @item-expanded="handleItemExpanded"
      @click:row="handleRowClick"
    >
      <template #no-data>
        <text-component class="no-data">
          {{ noDataText }}
        </text-component>
      </template>
      <template v-if="isSelectable && isUseOurCheckbox" #item.data-table-select="{ isSelected, select }">
        <checkbox-component :value="isSelected" @change="select($event)" />
      </template>
      <template v-if="additionalHeaders.length" #header>
        <thead>
          <tr v-for="(row, rowIndex) in additionalHeaders" :key="rowIndex">
            <th
              v-for="({ align, bold, colSpan, rowSpan, text, width }, columnIndex) in row"
              :key="columnIndex"
              :style="{ width: width }"
              :rowspan="rowSpan"
              :colspan="colSpan"
              :class="{
                [`text-${align}`]: align,
                depressed: !text,
              }"
            >
              <text-component :class="['base-small gray', { 'base-small-bold': bold }]">
                {{ text }}
              </text-component>
            </th>
          </tr>
        </thead>
      </template>
      <template v-if="!hideFooter" #footer="{ props: { pagination } }">
        <div v-if="pagination.itemsLength > 0" class="footer">
          <pagination-vuetify
            :active-page="selectPage"
            :items-length="pagination.itemsLength"
            :initial-per-page="perPage"
            @onPageChange="handlePageChange"
          />
          <!-- <pagination
            v-else
            :active-page="page"
            @onPageChange="handlePageChange"
            :items-length="pagination.itemsLength"
            :initial-per-page="perPage"
          /> -->
        </div>
      </template>
      <template
        v-for="{ value: name } in (columns || headers).filter(({ value }) => !['actions', 'check'].includes(value))"
        #[`item.${name}`]="slotData"
      >
        <UiHighlightedText :key="name" :search="search" :text="get(slotData.item, name)" class="text-start" />
      </template>
      <template v-for="(_, name) in $scopedSlots" #[name]="slotData">
        <td :key="name" class="text-start">
          <slot :name="name" v-bind="slotData" />
        </td>
      </template>
      <template #[`expanded-item`]="{ item }">
        <td :colspan="headers.length">
          <slot name="expanded-item" :item="item" />
        </td>
      </template>
    </v-data-table>
  </horizontal-scroll-new>
</template>

<script lang="ts">
import { PropType } from 'vue';
import { DataOptions } from 'vuetify';
import { Component, Prop, Vue, Watch } from 'vue-property-decorator';
import get from 'lodash/get';
import { getSortParameterName } from '@/utils';
import { ColumnItem, DataItem, RowOptions, TableHeaders } from './DataTable.types';
import { PageChange } from '@/components/common/Pagination/Pagination.types';
import { BaseTab, CustomTab } from '@/components/common/Tabs/Tabs.types';
import HorizontalScrollNew from '@/components/common/HorizontalScrollNew/HorizontalScroll.vue';
import Pagination from '@/components/common/Pagination/Pagination.vue';
import PaginationNew from '@/components/common/Pagination/PaginationNew.vue';
import PaginationVuetify from '@/components/common/Pagination/PaginationVuetify.vue';
import Tabs from '@/components/common/Tabs/Tabs.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import { TInnerFilter } from '@/services/models/common';

@Component({
  name: 'data-table-new',
  components: {
    CheckboxComponent,
    HorizontalScrollNew,
    Pagination,
    PaginationNew,
    PaginationVuetify,
    Tabs,
    TextComponent,
  },
  methods: { get },
})
export default class DataTableNew extends Vue {
  @Prop({ type: Array as PropType<ColumnItem[]> }) readonly columns!: ColumnItem[];
  @Prop({ type: Array as PropType<TableHeaders[]>, default: () => [] }) readonly headers!: TableHeaders[];
  @Prop({ type: Array as PropType<TableHeaders[]>, default: () => [] }) readonly additionalHeaders!: TableHeaders[][];
  @Prop({ type: Array as PropType<DataItem[]>, default: () => [] }) readonly items!: DataItem[];
  @Prop({ type: Array as PropType<CustomTab[] | BaseTab[]> }) readonly tabs!: CustomTab[] | BaseTab[];
  @Prop({ type: String, default: 'Ничего не найдено.' }) readonly noDataText!: string;
  @Prop({ type: Boolean, default: true }) readonly fixedHeader!: boolean;
  @Prop({ type: Boolean, default: false }) readonly isDisableSort!: boolean;
  @Prop({ type: Boolean, default: false }) readonly multiSort!: boolean;
  @Prop(Function) readonly onPageChange!: (pageNumber: number, perPage: number) => void;
  @Prop(Function) readonly onShowAll!: (isShowAll: boolean) => void;
  @Prop(Function) readonly onTabChange!: (value: unknown) => void;
  @Prop(Number) readonly currentTabIndex!: number;
  @Prop(Boolean) readonly hideFooter!: boolean;
  @Prop(Boolean) readonly hideHeader!: boolean;
  @Prop(Boolean) readonly isSelectable!: boolean;
  @Prop(Boolean) readonly singleSelect!: boolean;
  @Prop(Boolean) readonly isTabsLoading!: boolean;
  @Prop(Array) readonly selectedRow!: DataItem;
  @Prop(Number) readonly itemsLength!: number;
  @Prop([String, Function]) readonly itemClass!: string;
  @Prop(Boolean) readonly loading!: boolean;
  @Prop(String) readonly title!: string;
  @Prop(String) readonly search!: string;
  @Prop(Number) readonly perPage!: number;
  @Prop(Boolean) readonly showExpand!: boolean;
  @Prop(Boolean) readonly isSingleExpand!: boolean;
  @Prop(Boolean) readonly isUseOurCheckbox!: boolean;
  @Prop(Object) readonly filter!: TInnerFilter;
  @Prop({ type: Number, default: 0 }) page!: number;
  // @Prop(Object) readonly options!: any;
  expanded: DataItem[] = [];

  selectPage = this.page;

  sort = '-id';
  size = 5;
  options = {};

  @Watch('options')
  onChangeFilter(values) {
    this.$emit('handleSort', values);

    const { sortBy, sortDesc } = values;
    if (!sortBy.length || !this.filter) return;

    this.$emit('onOptionsChange', {
      page: (this.filter.pageable?.pageNumber || 0) - 1,
      size: this.filter.pageable?.pageSize,
      sort: (sortBy || []).reduce((result, key, index) => {
        const property = ((this.columns?.length ? this.columns : this.headers) as any[]).find(
          ({ value, sortAs }) => value === key && sortAs
        )?.sortAs;

        if (property) {
          return [...result, { property, direction: sortDesc[index] ? 'ASC' : 'DESC' }];
        }

        return result;
      }, []),
    });
  }

  clickCh(e) {
    console.info(e);
  }

  handleItemExpanded(item: unknown): void {
    this.$emit('onExpandItemClick', item);
  }

  handleSelectItems(values: unknown[]): void {
    this.$emit('input', values);
  }

  handleCurrentItems(values: unknown[]): void {
    this.$emit('handleCurrentItems', values);
  }

  handlePageChange(pageChange: PageChange): void {
    const { page, perPage } = pageChange;
    this.selectPage = page - 1;
    this.size = perPage;

    let event: any = {
      page: this.selectPage,
      size: this.size,
    };

    if (this.filter) {
      event.sort = this.filter?.pageable?.sort;
    }

    this.$emit('onOptionsChange', event);
  }

  handleOptionsChange(options: DataOptions): void {
    const { sortBy, sortDesc } = options;

    const [sortName] = sortBy;
    const [sortOrder] = sortDesc;
    const sortHeader = this.getHeaderBySortName(sortName) ?? ({} as TableHeaders<string>);

    this.sort = getSortParameterName(sortHeader.customSortName || sortName, sortOrder);

    if (this.sort) {
      this.$emit('onOptionsChange', {
        page: null,
        size: this.size,
        sort: this.sort,
      });
    }
  }

  handleRowClick(expandedRow: unknown, options: RowOptions): void {
    if (this.showExpand) {
      options.expand(!options.isExpanded);
    }

    this.$emit('onClick', expandedRow);
  }

  getHeaderBySortName(sortName: string): TableHeaders | undefined {
    return this.headers.find((header) => header.value === sortName);
  }

  created(): void {
    this.size = this.perPage;
  }
}
</script>

<style scoped lang="scss">
@import '@/assets/styles/_variables';

.table::v-deep {
  width: 100%;

  &.v-data-table {
    line-height: 1.2;
  }

  table {
    border-collapse: separate;
    border-spacing: 0 6px !important;
    width: 100%;
    padding: 7px;
  }

  thead {
    tr {
      border-bottom: none;
      th {
        box-shadow: none !important;
      }
    }
  }

  tbody {
    tr {
      box-shadow: 0 0 10px rgb(9 16 28 / 7%);
    }
  }

  .v-data-table-header th {
    color: $medium-grey-color !important;
    padding: 0 12px !important;
    height: auto;
    vertical-align: middle;

    & > td {
      border-bottom: 0;
      padding: 0 !important;
    }

    &.th_actions {
      color: $gold-light-color !important;

      span {
        display: flex;
        height: 100%;
        padding: 15px;
        align-items: center;
        border-left: 1px solid rgba(130, 130, 134, 0.2);
      }
    }

    .v-data-table-header__icon {
      margin-left: 3px;
    }
  }

  td {
    border-bottom: 0 !important;
    color: $footer-color;
    font-size: 13px !important;
    font-weight: normal;
    padding: 18px 12px !important;
    height: auto;
  }

  .v-data-table__wrapper {
    max-height: 750px;
    overflow-y: auto;
  }

  .v-data-table__progress th {
    padding: 0;
  }

  .v-data-table__expanded__content td {
    padding: 0;
  }

  .v-data-table__empty-wrapper td {
    height: auto;
    padding: 12px 0 0;

    &:not(:empty) {
      border-bottom: 0 !important;
    }
  }

  tbody tr {
    border: 0;

    &:hover {
      background: transparent !important;
    }
  }

  tbody tr:only-child td {
    border-bottom: 1px solid $light-grey-color;
  }

  .v-progress-linear__background,
  .v-progress-linear__indeterminate {
    background-color: $light-grey-color !important;
    border-color: $light-grey-color !important;
  }

  .v-data-table__checkbox .v-icon {
    color: $medium-grey-color;
  }

  .v-data-table__expanded__row {
    border-bottom: 1px solid $light-grey-color !important;
  }

  .v-data-table__expanded__content {
    box-shadow: none !important;
  }

  .v-data-table__expanded__content td .v-data-table {
    background-color: $light-grey-color;
  }

  .no-data {
    border-bottom: 1px solid $light-grey-color;
    color: $light-grey-color;
    display: block;
    font-size: 20px;
    font-weight: 500;
    line-height: 24px;
    padding: 33px 0;
  }
}

.footer {
  align-items: center;
  border-bottom: 1px solid $light-grey-color;
  display: flex;
  justify-content: space-between;
}

.pagination {
  align-items: center;
  display: flex;
}

.depressed {
  box-shadow: none !important;
}
</style>

<style lang="scss">
@import '@/assets/styles/_variables';

.wrap_content_actions {
  display: flex;
  height: 100%;
  padding: 0 15px;
  align-items: center;
  border-left: 1px solid rgb(130 130 134 / 20%);
}

.mdi-arrow-up::before {
  content: "" !important;
  background-image: url("/icons/chevron_down_table.svg") !important;
  width: 8px;
  height: 5px;
  display: inline-block;
  transform: rotate(180deg);
}

.v-data-table__wrapper {
  &::-webkit-scrollbar {
    width: 10px;
    height: 4px;
  }

  &::-webkit-scrollbar-track {
    background: transparent;
  }

  &::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
  }

  &::-webkit-scrollbar-thumb:hover {
    background: #c1c1c1;
  }

  &::-webkit-scrollbar-corner {
    background: transparent;
  }
}

.fixed {
    position: sticky;

  &--right {
    right: 0;
  }

  &--left {
    left: 0;
  }
}

.lastcol_fixed {
  tbody > tr > td:last-child {
    position: sticky;
    right: 0;
    background: #fff;
  }
}
</style>
