<template>
  <v-row>
    <v-col cols="12">
      <text-component class="title d-flex align-center" variant="span">{{ title }} </text-component>
    </v-col>
    <slot name="subheader"></slot>
    <v-col cols="12">
      <slot name="filters"> - </slot>
    </v-col>
    <v-col class="mt-3" cols="12">
      <v-row>
        <v-col v-if="isShowAdditionalButton" cols="12" sm="4">
          <slot name="additionButton">
            <v-row justify="start" no-gutters>
              <button-component :title="additionalButtonTitle" variant="primary" @click="$emit('onOpenCreatePage')" />
            </v-row>
          </slot>
        </v-col>
        <v-col :sm="!isShowAdditionalButton ? 12 : 8" cols="12">
          <v-row justify="end" no-gutters>
            <button-component
              :disabled="isEmptyFilters || isLoading"
              :loading="isLoading"
              size="micro"
              title="Сбросить"
              @click="onClearFiltersAndReloadRows"
            />
            <button-component :loading="isLoading" size="micro" title="Поиск" variant="primary" @click="onLoadRows" />
          </v-row>
        </v-col>
      </v-row>
    </v-col>
    <v-col v-if="filtersAreSet" cols="12">
      <slot
        :change="onChangeOptionsTable"
        :sort="onSortChange"
        :pageable="pageable"
        :pagination="pagination"
        :request="request"
        :rows="rows"
        :total="total"
        name="table"
      >
        <data-table
          :headers="innerValue"
          :items="rows"
          :items-length="total"
          :page="pageable.pageNumber"
          :per-page="pageable.pageSize"
          @onOptionsChange="onChangeOptionsTable"
        >
          <template #top>
            <header-settings
              :id="filters.component_name"
              :view-excel="false"
              :value-rows="rows"
              :headers="headers"
              :additional-system-headers="additionalSystemHeaders"
              :default-sorting="defaultSorting"
              @change="changeAndSortHeader($event)"
            />
          </template>
          <template #[`item.button`]="{ item }">
            <button-component
              class="ml-0 mt-3 mb-3 btn-custom"
              size="micro"
              title="Выбрать"
              variant="primary"
              @click="$emit('selectItem', item)"
            />
          </template>
          <template #[`item.actions`]="{ item }">
            <v-tooltip bottom>
              <template #activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on">
                  <img
                    v-show="isShow"
                    alt=""
                    class="iconTable"
                    src="/icons/show.svg"
                    @click="$emit('onClickShow', item)"
                  />
                </span>
              </template>
              <span>Просмотреть информацию</span>
            </v-tooltip>
          </template>
        </data-table>
      </slot>
    </v-col>
    <v-overlay :value="isLoading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-row>
</template>

<script lang="ts">
import { Component, Model, Prop, Watch } from 'vue-property-decorator';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import { validateValueForFilter } from '@/utils/parseFiltersForRequests';
import { fetchRowsFromTable } from '@/utils/methodsForViews';
import { mixins } from 'vue-class-component';
import { RequestMix } from '@/utils/mixins/request';
import forIn from 'lodash/forIn';
import FilterForHeadersTable from '@/components/Filters/FilterForHeadTable.vue';
import HeaderSettings from '@/components/Filters/SettingTableHeader.vue';
import { HeaderItem } from '@/views/Sdiz/components/Subcomponents/Table/SdiztListTables.vue';
import { PropType } from 'vue';
import { TSort } from '@/components/common/ViewSettings/ViewSettingsModal.vue';

@Component({
  name: 'actions-buttons',
  components: { HeaderSettings, FilterForHeadersTable, TextComponent, ButtonComponent, DataTable },
})
export default class PageComponent extends mixins(RequestMix) {
  @Model('change', { type: Object, required: true }) filters!: any;

  @Prop({ type: Boolean, default: false }) readonly isClearFiltersAndReloadRows!: boolean;

  @Prop({ type: Boolean, default: false }) readonly isShowAdditionalButton!: boolean;

  @Prop({ type: Boolean, default: false }) readonly isRequestPayload!: boolean;

  @Prop({ type: Boolean, default: true }) readonly isShow!: boolean;

  @Prop({ type: Boolean, default: true }) readonly settingsShow!: boolean;

  @Prop({ type: Boolean, default: false }) readonly isCustomClearFilters!: boolean;

  @Prop({ type: String, required: true }) readonly title!: string;

  @Prop({ type: String, default: 'Внести данные' }) readonly additionalButtonTitle!: string;

  @Prop({ type: String, required: true }) readonly getList!: string;

  @Prop({ type: String, default: '' }) readonly url!: string;

  @Prop({ type: String, default: 'sdiz' }) readonly groupNotice!: string;

  @Prop({ type: Function, default: () => undefined }) customClearFiltersFunction!: (...args: any) => any;

  @Prop({ type: Object, default: () => null }) readonly defaultSorting!: TSort;

  @Prop({ type: Array as PropType<HeaderItem[]>, default: () => [] }) readonly headers!: HeaderItem[];

  @Prop({ type: Array, default: () => [] }) readonly additionalSystemHeaders!: string[];

  @Prop({ type: Boolean, default: true }) readonly useFiltersStore!: boolean;

  @Prop() readonly callbackRows!: any;
  isLoading = false;
  rows: any[] = [];
  applyHeaders: HeaderItem[] = [];
  filtersAreSet = false;

  get innerValue() {
    return this.applyHeaders;
  }

  set innerValue(value) {
    this.applyHeaders = value;
  }

  async changeAndSortHeader(data) {
    this.innerValue = data.columns;
    await this.onSortChange(data.sort);
  }

  get payload() {
    return !this.isRequestPayload ? { url: this.url, data: this.request } : this.request;
  }

  setFiltersFromStore() {
    if (
      typeof this.$store.state.registryFilters[this.filters.name_route_list] === 'object' &&
      Object.keys(this.$store.state.registryFilters[this.filters.name_route_list]).length
    ) {
      const newModel = this.$store.state.registryFilters[this.filters.name_route_list];
      this.$emit('change', newModel);
    }
  }

  async mounted() {
    if (this.useFiltersStore) {
      this.setFiltersFromStore();
    }

    this.filtersAreSet = true;

    if (!this.settingsShow) {
      this.innerValue = this.headers;
      await fetchRowsFromTable(this);
    }
  }

  get isEmptyFilters() {
    return !this.filters.available_filters.filter(({ name, type, child }) => {
      if (type === 'objects') {
        const obj = this.filters[name];
        let isEmpty = false;

        forIn(child, (value, key) => {
          if (typeof obj[key] !== 'undefined') {
            forIn(value, ({ name }) => {
              if (validateValueForFilter(obj[key][name])) {
                isEmpty = true;
              }
            });
          }
        });

        return isEmpty;
      }
      return validateValueForFilter(this.filters[name]);
    }).length;
  }

  get model(): any[] {
    return this.filters;
  }

  @Watch('isClearFiltersAndReloadRows')
  async onChangeStateIsClearFilters() {
    if (this.isClearFiltersAndReloadRows) {
      await this.onClearFiltersAndReloadRows();
    }
  }

  async onClearFiltersAndReloadRows() {
    this.isLoading = true;
    this.$store.commit('registryFilters/clearFilters', this.filters.name_route_list);
    if (!this.isCustomClearFilters) {
      this.$emit('change', new this.filters.constructor(undefined));
      this.$emit('onClearFilters');
    } else {
      this.$emit('change', this.customClearFiltersFunction(this.filters));
      this.$emit('onClearFilters');
    }
  }

  /**
   * Обновляем таблицу при изменении номера страницы или размера строк
   * @param array
   */
  async onChangeOptionsTable(array) {
    this.$emit('onChangeOptionsTable', array);

    if (this.pageable.pageNumber !== array.page + 2 || this.pageable.pageSize !== array.size) {
      this.pageable.pageNumber = array.page + 2;
      this.pageable.pageSize = array.size;
      await fetchRowsFromTable(this);
    }
  }
  /**
   * Обновляем таблицу при изменении сортировки
   */
  async onSortChange(option) {
    this.request.sort.options = option.map((val) => {
      return {
        field: val.property,
        direction: val.direction,
      };
    });
    await fetchRowsFromTable(this);
  }

  async onLoadRows() {
    this.pageable.pageNumber = 0;
    await fetchRowsFromTable(this);
  }

  /**
   * Обрабатываем успешный ответ от сервера
   *
   * @param response
   * @param pagination
   */
  onSuccessResponse(response: any, pagination: any) {
    this.pagination = pagination;
    this.total = pagination.totalResults;
    const rows = response.map((v) => this.getModelFilter(v));
    this.rows = typeof this.callbackRows !== 'undefined' ? this.callbackRows(this.model, rows, response) : rows;
  }

  /**
   * Возвращаем модель компонента (models/Lot|Sdiz etc...)
   * @param data
   */
  getModelFilter(data): any {
    return new this.filters.constructor(data);
  }
}
</script>
<style lang="scss" scoped></style>
