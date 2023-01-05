<template>
  <dialog-component
    :key="stateFindLot"
    v-model="stateFindLot"
    :confirm-disabled="!selectedRows.length"
    :is-loading="isLoading"
    :persistent="true"
    :prompt="false"
    :with-close-icon="true"
    add-class="find-lots"
    :no-click-animation="true"
    cancel-title=""
    confirm-title=""
    controls-justify="justify-end"
    width="1450"
    @onOpen="onOpenModal"
  >
    <template #title>Выберите партию</template>
    <template #content>
      <v-container class="fill-height" fluid>
        <div class="mb-1">
          <lot-element-filters
            v-model="filters"
            :array-lock-filters="getLockFilters"
            :type-lots="lotTypeForFilters"
            :is-need-lock-filters="false"
          >
            <template #[`create-date-filter`]="{ lockFromFilterModal, arrayLockFilters, filters, isNeedLockFilters }">
              <slot
                :array-lock-filters="arrayLockFilters"
                :filters="filters"
                :is-need-lock-filters="isNeedLockFilters"
                :lock-from-filter-modal="lockFromFilterModal"
                name="create-date-filter"
              />
            </template>
            <template #[`manufacture-filter`]="{ lockFromFilterModal, arrayLockFilters, filters, isNeedLockFilters }">
              <slot
                :array-lock-filters="arrayLockFilters"
                :filters="filters"
                :is-need-lock-filters="isNeedLockFilters"
                :lock-from-filter-modal="lockFromFilterModal"
                name="manufacture-filter"
              />
            </template>
          </lot-element-filters>
        </div>
        <actions-buttons
          :preloader="isLoading"
          :disabled-success-button="isLoading"
          :disabled-cancel-button="isLoading"
          cancel-title="Сбросить"
          class="mt-2"
          cols="12"
          lg="12"
          margin-cancel-btn="8"
          sm="12"
          success-title="Поиск"
          xl="12"
          @click="fetchLots"
          @onCancel="clearFilters"
        />

        <lot-element-rows-table
          :rows="rows"
          target_blank="_blank"
          :is-loading="isLoading"
          :model="model.getLotModel()"
          :headers="model.getHeaders()"
          :is-selectable="true"
          :is-single-select="true"
          :pageable="pageable"
          :selected-rows="selectedRows"
          :total="total"
          @onClickByRow="onClickByRow"
          @onOptionsChange="onOptionsChange"
          @onSelectRows="onSelectRows"
          @onSuccess="onSuccessSelectLot"
        />

        <v-col
          cols="12"
          :class="['px-0', 'd-flex', 'align-center', { [isForSdiz ? 'justify-end' : 'justify-space-between']: true }]"
        >
          <CheckboxComponent
            v-if="!isForSdiz"
            :value="keepOpened"
            class="float-left checkbox-v"
            label="Не закрывать окно при выборе партии"
            @change="setKeepOnened"
          />

          <DefaultButton title="Закрыть" size="micro" variant="primary" @click="onCancelSelectLot" />
        </v-col>
      </v-container>
    </template>
  </dialog-component>
</template>

<script lang="ts">
import { Component, Mixins, Model, Prop, Watch } from 'vue-property-decorator';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import LotElementFilters from '@/views/Lot/components/Subcomponents/Elements/LotElementFilters.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import LotElementRowsTable from '@/views/Lot/components/Subcomponents/Elements/LotElementRowsTable.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import { isNull, isUndefined, map } from 'lodash';
import { FilterModel } from '@/models/Request/Filter.vue';
import ActionsButtons from '@/components/Forms/ActionsButtons.vue';
import { LotsMovedVueModel } from '@/models/Lot/LotsMoved.vue';
import { RequestMix } from '@/utils/mixins/request';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import { AdditionalMix } from '@/utils/mixins/additional';
import { LotType } from '@/utils/enums/LotType';
import { SdizTypeLot } from '@/models/Sdiz/Operations.vue';
import { LotsPurposeEnum } from '@/utils/enums/lotsPurpose.enum';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import { MODALS_SETTINGS_KEY } from '@/utils/consts/modalsSettingsKey';

@Component({
  name: 'lot-modal-find-lots',
  components: {
    CheckboxComponent,
    ActionsButtons,
    LotElementRowsTable,
    DefaultButton,
    LotElementFilters,
    DialogComponent,
    SelectRequestComponent,
  },
})
export default class LotModalFindLots extends Mixins(RequestMix, AdditionalMix) {
  @Model('change', { type: Object }) value!:
    | LotDataVueModel
    | LotGpbDataVueModel
    | LotElevatorDataVueModel
    | SdizGpbVueModel
    | SdizVueModel;

  @Prop({ type: Array, default: [] }) selectedLots!: LotsMovedVueModel[];

  @Prop({ type: Object, default: {} }) filterByFirstSelectedLots!:
    | LotDataVueModel
    | LotGpbDataVueModel
    | LotElevatorDataVueModel;

  @Prop({ type: Boolean, default: false }) isOpenFindLot!: boolean;
  @Prop({ type: Boolean, default: () => false }) readonly isNotRepositoryFilter!: boolean;
  /** Используется на страницах создания партии для партий на хранении */
  @Prop({ type: Boolean, default: () => false }) readonly isElevatorFilter!: boolean;
  @Prop({ type: Boolean, default: false }) readonly isForSdiz!: boolean;

  @Prop({ type: String, default: '' }) listApiEndpoint!: string;

  @Prop({ type: Boolean, default: true }) lotSelectionProcessed!: boolean;

  @Prop({ type: String, default: null }) typeLot!: LotType | null;
  @Prop({ type: Number, default: null }) sdizTypeLot!: SdizTypeLot | null;
  @Prop({ type: Boolean, required: false }) isLotsMoved!: boolean;
  nameFilterDate = 'enter_date';
  filtersCustom = {};

  keepOpened = false;
  componentName = 'lot-modal-find-lots';

  isLoading = false;
  rows: any[] = [];
  selectedRows: any[] = [];
  options: any[] = [];

  model: any = this.innerValue;

  get innerValue(): any {
    return this.value;
  }

  set innerValue(value: any) {
    this.model = value;
    this.$emit('change', value);
  }

  get filters(): any {
    return this.filtersCustom;
  }
  set filters(val) {
    this.filtersCustom = val;
  }

  get isAvailableMassFilter() {
    return this.typeLot === LotType.ANOTHER_BATCH_GRAIN;
  }

  get stateFindLot() {
    return this.isOpenFindLot;
  }

  set stateFindLot(value) {
    this.$emit('isOpenFindLot', value);
  }

  /** Принудительные фильтры от выбранных партий */
  get getLockFilters() {
    return this.filterByFirstSelectedLots;
  }

  onCancelSelectLot(): void {
    this.stateFindLot = false;

    this.$emit('onCancelSelectLot', {
      rows: this.rows,
      filters: this.filters,
    });
  }

  onSuccessSelectLot(value): void {
    if (this.isForSdiz || !this.keepOpened) {
      this.stateFindLot = false;
    }

    this.$emit('onSelectLot', {
      lot: value,
      rows: this.rows,
      filters: this.filters,
    });
  }

  created() {
    this.filtersCustom = new this.model.constructor();
    this.keepOpened = this.settings?.keepOpened || false;
  }

  get modalsSettings() {
    return JSON.parse(localStorage.getItem(MODALS_SETTINGS_KEY) || '{}');
  }

  get settings() {
    return this.modalsSettings[this.componentName];
  }

  setKeepOnened(value) {
    this.keepOpened = value;

    localStorage.setItem(
      MODALS_SETTINGS_KEY,
      JSON.stringify({ ...this.modalsSettings, [this.componentName]: { ...this.settings, keepOpened: value } })
    );
  }

  async clearFilters() {
    this.$emit('clear-filters');
    this.filters = new this.model.constructor();
    await this.fetchLots();
  }

  async onOptionsChange(data) {
    if (this.pageable.pageNumber !== data.page + 2 || this.pageable.pageSize !== data.size) {
      this.pageable.pageNumber = data.page + 2;
      this.pageable.pageSize = data.size;
      await this.fetchLots();
    }
  }

  async onSelectRows(data) {
    this.$emit('onSelectRows', data);
  }

  async onClickByRow(data) {
    this.selectedRows = isNull(data) ? [] : [data];
  }

  filterOptionOther(): Array<any> {
    const options: FilterModel[] = [];
    Object.keys(this.filterByFirstSelectedLots).forEach((value) => {
      if (this.filterByFirstSelectedLots[value]) {
        if (value === 'okpd2Code') {
          options.push({ field: 'okpd2.code', value: this.filterByFirstSelectedLots[value] } as FilterModel);
        } else {
          options.push({ field: value, operator: '=', value: this.filterByFirstSelectedLots[value] } as FilterModel);
        }
      }
    });
    this.selectedLots.forEach((value) => {
      if (typeof value.lot_id !== 'undefined')
        options.push({ field: 'id', operator: '!=', value: value.lot_id } as FilterModel);
      if (typeof value.gpb_id !== 'undefined')
        options.push({ field: 'id', operator: '!=', value: value.gpb_id } as FilterModel);
    });
    if (this.isAvailableMassFilter) {
      options.push({ field: 'amount_kg_available', operator: '!=', value: 0 } as FilterModel);
    }
    if (this.isNotRepositoryFilter) {
      if (!(this.value instanceof LotGpbDataVueModel))
        options.push({ field: 'repository_id', operator: '=', value: null } as FilterModel);
    }
    if (this.isElevatorFilter) {
      const userSubjectId = this.$store.state.auth.user.subject?.subject_id;
      options.push({ field: 'repository_id', operator: '=', value: userSubjectId } as FilterModel);
    }
    return options;
  }
  filterPurpose() {
    const options: FilterModel[] = [];
    if (this.sdizTypeLot === SdizTypeLot.IN_RUSSIA) {
      options.push({
        field: 'bug',
        operator: '=',
        options: [
          {
            field: 'objects.purpose.code',
            operator: '=',
            value: LotsPurposeEnum.STORAGE_AND_PROCESSING,
          } as FilterModel,
          {
            field: 'objects.purpose.code',
            operator: '=',
            value: LotsPurposeEnum.PROCESSING,
            relation: 'OR',
          } as FilterModel,
        ],
      } as FilterModel);
    } else if (this.sdizTypeLot === SdizTypeLot.IMPORT_TO_RUSSIA) {
      options.push({
        field: 'objects.purpose.code',
        operator: '=',
        value: LotsPurposeEnum.IMPORT_TO_RUSSIA,
      } as FilterModel);
    } else if (this.sdizTypeLot === SdizTypeLot.EXPORT_FROM_RUSSIA) {
      options.push({
        field: 'objects.purpose.code',
        operator: '=',
        value: LotsPurposeEnum.EXPORT_FROM_RUSSIA,
      } as FilterModel);
    } else if (this.sdizTypeLot === SdizTypeLot.ELEVATOR || this.isElevatorFilter) {
      options.push({
        field: 'objects.purpose.code',
        operator: '!=',
        value: LotsPurposeEnum.EXPORT_FROM_RUSSIA,
      } as FilterModel);
    }
    return options;
  }

  filterOptionBase(): Array<any> {
    const options: FilterModel[] = [];
    if (this.filters.objects.okpd2?.code) {
      options.push({ field: 'okpd2.code', operator: '=', value: this.filters.objects.okpd2?.code } as FilterModel);
    }
    if (this.isLotsMoved)
      options.push({
        field: 'objects.purpose.code',
        operator: '!=',
        value: LotsPurposeEnum.IMPORT_TO_RUSSIA,
      } as FilterModel);
    if (this.filters.target_id)
      options.push({ field: 'target_id', operator: '=', value: this.filters.target_id } as FilterModel);
    if (this.filters.purposeCode)
      options.push({ field: 'objects.purpose.code', operator: '=', value: this.filters.purposeCode } as FilterModel);
    if (this.filters.lot_number)
      options.push({ field: 'lot_number', operator: '%%', value: this.filters.lot_number } as FilterModel);
    if (this.filters.gpb_number)
      options.push({ field: 'gpb_number', operator: '%%', value: this.filters.gpb_number } as FilterModel);
    if (this.filters.amount_kg_from)
      options.push({ field: 'amount_kg', operator: '>=', value: this.filters.amount_kg_from } as FilterModel);
    if (this.filters.amount_kg_to)
      options.push({ field: 'amount_kg', operator: '<=', value: this.filters.amount_kg_to } as FilterModel);
    if (this.filters.date_from)
      options.push({ field: this.nameFilterDate, operator: '>=', value: this.filters.date_from } as FilterModel);
    if (this.filters.date_to)
      options.push({ field: this.nameFilterDate, operator: '<=', value: this.filters.date_to } as FilterModel);
    if (this.filters.create_date)
      options.push({ field: 'create_date', operator: '=', value: this.filters.create_date } as FilterModel);
    if (this.filters.owner_id)
      options.push({ field: 'owner_id', operator: '=', value: this.filters.owner_id } as FilterModel);
    if (this.filters.manufacturer_id)
      options.push({ field: 'manufacturer_id', operator: '=', value: this.filters.manufacturer_id } as FilterModel);
    options.push({ field: 'status', operator: '=', value: 'SUBSCRIBED' } as FilterModel);
    return options;
  }

  paginationChange(pagination): void {
    this.pagination = pagination;
    this.total = pagination.totalResults;
  }

  fetchParams(options): object {
    return {
      filter: {
        options: options,
      },
      page_size: this.pageable.pageSize,
      page: this.pageable.pageNumber,
      sort: { options: [{ field: 'id', direction: 'DESC' }] },
    };
  }

  filterOptions(options: Array<FilterModel>) {
    return options.filter((value, index, self) => {
      return self.findIndex((el) => el.field === value.field && el.value === value.value) === index;
    });
  }

  filterRepository(): FilterModel[] {
    const options: FilterModel[] = [];
    // Партии ппз не имеют repository_id.
    if (this.value instanceof LotGpbDataVueModel) return options;
    if (this.sdizTypeLot === SdizTypeLot.IMPORT_TO_RUSSIA || this.sdizTypeLot === SdizTypeLot.EXPORT_FROM_RUSSIA) {
      options.push({ field: 'repository_id', operator: '=', value: null } as FilterModel);
    }

    return options;
  }

  async fetchLots() {
    try {
      const options: FilterModel[] = [];

      if (!this.filters.purposeCode) {
        options.push(...this.filterPurpose());
      }
      options.push(...this.filterOptionBase());
      options.push(...this.filterOptionOther());
      options.push(...this.filterRepository());
      if (this.pageable.pageNumber === 0) {
        ++this.pageable.pageNumber;
      }

      this.isLoading = true;

      const { status, response, pagination } = await this.$store.dispatch(
        this.listApiEndpoint || this.model.link_find_items_in_modal || this.model.list_apiendpoit,
        this.fetchParams(this.filterOptions(options))
      );
      if (status) {
        let models: LotDataVueModel | LotGpbDataVueModel | LotElevatorDataVueModel | SdizGpbVueModel | SdizVueModel =
          this.innerValue;
        this.rows = map(response, (lot) => {
          return models.getLotModel(Object.fromEntries(Object.entries(lot).filter(([_, v]) => v !== null)));
        });
        this.paginationChange(pagination);
      } else throw new Error();
    } catch (_e) {
      this.$notify({ group: 'notifications-m', type: 'error', title: 'Ошибка при загрузке партий' });
    } finally {
      this.isLoading = false;
    }
  }

  async onOpenModal(value) {
    if (isUndefined(value)) {
      await this.fetchLots();
    }

    this.$emit('onOpenModal');
  }

  @Watch('lotSelectionProcessed')
  async onLotSelectionProcessedChange(value: boolean) {
    if (value) await this.fetchLots();
  }

  get lotTypeForFilters() {
    const typeLot = this.isElevatorFilter ? 'elevator' : this.typeLot;

    return this.sdizTypeLot ? String(this.sdizTypeLot) : typeLot || '';
  }
}
</script>

<style lang="scss" scoped>
.find-lots .v-dialog > .v-card > .v-card__title,
.find-lots .v-dialog > .v-card > .v-card__text,
.find-lots .v-card__actions {
  padding: 20px;
}
</style>
