<template>
  <v-container>
    <v-row>
      <v-col cols="6">
        <!-- Вынести в компонент -->
        <div class="title">
          <span>Реестр товаропроизводителей</span>
        </div>
      </v-col>

      <v-col cols="6" class="d-flex justify-end">
        <span v-if="canExportRegister" class="settingsSpan" @click="exportAction">
          <img src="/icons/export.svg" class="iconSettings" />
          Экспорт списка
        </span>

        <span class="settings">
          <UiViewSettingsModal
            v-if="canSettingRegister"
            id="manufacture"
            v-model="filter"
            type="button"
            class="manufacture_setting"
            :sort-map="{ 'subject.inn_kpp': ['subject.inn', 'subject.kpp'] }"
            @apply-settings="fetchList"
          />
        </span>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="2">
        <Filters id="manufacture" v-model="filters" @apply-filters="onApplyFilters" />
      </v-col>
      <v-col class="d-flex align-lg-center justify-end">
        <span v-if="canFilterRegister" :style="{ marginRight: '20px' }">
          <checkbox-component
            id="all_list"
            :value="!showActualList"
            name="all_list"
            label="Все записи"
            :style="{ width: 'auto' }"
            @change="(v) => onShowAllList()"
          />
        </span>
        <SearchNewComponent
          class="justify-start"
          placeholder="Введите значение поиска"
          @search="(filter) => onChangeFilter({ filter, pageable: { pageNumber: 0 } })"
        />
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <DataTableNew
          :headers="filter.columns"
          :items="list"
          :items-length="total"
          :page="filter.pageable.pageNumber"
          :per-page="filter.pageable.pageSize"
          :search="filter.filter"
          class="lastcol_fixed"
          :filter="filter"
          multi-sort
          @onOptionsChange="
            ({ page, size, sort }) => onChangeFilter({ pageable: { pageNumber: page + 1, pageSize: size, sort } })
          "
        >
          <template #[`header.isProcessor`]>
            <checkbox-component
              id="processor"
              :value="isShowProcessor"
              name="processor"
              class="checkbox_grain_processing"
              :style="{ width: 'auto' }"
              @change="(v) => showProcessorList()"
            />
          </template>
          <template #[`item.actions`]="{ item }">
            <div id="fixed">
              <span class="wrap_content_actions">
                <v-tooltip v-if="canViewCardManufacture" bottom>
                  <template #activator="{ on, attrs }">
                    <span v-bind="attrs" v-on="on">
                      <router-link
                        :to="{
                          path: `/manufacturers/${item.id}`,
                          params: { id: item.id },
                        }"
                      >
                        <img src="/icons/show.svg" class="iconTable" />
                      </router-link>
                    </span>
                  </template>
                  <span>Просмотреть информацию</span>
                </v-tooltip>

                <v-tooltip v-if="canEditCardManufacture" bottom>
                  <template #activator="{ on, attrs }">
                    <span v-bind="attrs" v-on="on">
                      <router-link
                        v-show="!item.exclusionReason"
                        :to="{ path: `/manufacturers/edit/${item.subjectId}` }"
                      >
                        <img src="/icons/edit.svg" class="iconTable" alt="" />
                      </router-link>
                    </span>
                  </template>
                  <span>Редактировать информацию</span>
                </v-tooltip>
              </span>
            </div>
          </template>
          <template #[`item.registryInclusionDate`]="{ item }">
            {{ date(item.registryInclusionDate, { inputFormat: 'DD.MM.YYYY hh:mm', outputFormat: 'DD.MM.YYYY' }) }}
          </template>
          <template #[`item.registryExclusionDate`]="{ item }">
            {{ date(item.registryExclusionDate, { inputFormat: 'DD.MM.YYYY hh:mm', outputFormat: 'DD.MM.YYYY' }) }}
          </template>
          <template #[`item.isProcessor`]="{ item }">
            <v-tooltip v-if="item.isProcessor" bottom>
              <template #activator="{ on, attrs }">
                <span v-bind="attrs" class="processor" v-on="on">
                  <img src="/icons/grain_processing.svg" class="iconTable" />
                </span>
              </template>
              <span>Осуществляет переработку зерна</span>
            </v-tooltip>
          </template>
          <template #[`item.subjectType`]="{ item }">
            <span> {{ subjectType(item.subjectType) }} </span>
          </template>
        </DataTableNew>
      </v-col>
    </v-row>

    <ManufacturerCard
      v-if="onOpenAddModal"
      v-model="onOpenAddModal"
      :is-edit-org="isEditOrg"
      :id-card="idEditCard"
      @close="closeModal"
    />

    <v-overlay :value="isLoading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import { Component, Mixins, Watch } from 'vue-property-decorator';
import DataTableNew from '@/components/common/DataTableNew/DataTable.vue';
import SearchNewComponent from '@/components/common/SearchNew/Search.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import ManufacturerCard from '@/components/Manufacturers/ManufacturersEdit/ManufacturersCard.vue';
import DefaultButtonNew from '@/components/common/buttons/DefaultButtonNew.vue';
import ViewSettingsModal from '@/components/common/ViewSettings/ViewSettingsModal.vue';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import { EAction, mapAccessFlags } from '@/utils';
import { date } from '@/utils/global/filters';
import { IDictionaryNode, IFilters, TInnerFilter } from '@/services/models/common';
import { IManufacturerItemResponse } from '@/services/models/manufacturer';
import mergeWith from 'lodash/mergeWith';
import { subjectType } from '@/utils/subjectType';
import Filters from '@/components/common/Filter/Filter.vue';
import { subjectTypeList } from '@/utils/subjectType';
import { IDictionaryRegions } from '@/services/models/catalogs';
import Exportable from '@/utils/global/mixins/exportable';

@Component({
  name: 'ManufacturersRegisterPage',
  components: {
    DataTableNew,
    SearchNewComponent,
    DialogComponent,
    ManufacturerCard,
    DefaultButtonNew,
    ViewSettingsModal,
    CheckboxComponent,
    Filters,
  },
  computed: {
    ...mapAccessFlags({
      canFilterRegister: EAction.FILTER_MANUFACTURER_REGISTER,
      canReadRegister: EAction.READ_MANUFACTURER_DATA_REGISTER,
      canSettingRegister: EAction.SETTING_MANUFACTURER_REGISTER,
      canExportRegister: EAction.EXPORT_MANUFACTURER_REGISTER,
      canViewCardManufacture: EAction.READ_MANUFACTURER,
      canEditCardManufacture: EAction.CHANGE_MANUFACTURER,
      canReadManufacturerList: EAction.READ_MANUFACTURER_REGISTER,
    }),
  },
})
export default class ManufacturersRegisterPage extends Mixins(Exportable('/api/subject/manufacturer/exportReport')) {
  readonly canReadRegister!: boolean;
  readonly canFilterRegister!: boolean;
  readonly canSettingRegister!: boolean;
  readonly canExportRegister!: boolean;
  readonly canViewCardManufacture!: boolean;
  readonly canEditCardManufacture!: boolean;
  readonly canReadManufacturerList!: boolean;

  isLoading = true;
  onOpenAddModal = false;
  idEditCard = null;
  list: IManufacturerItemResponse[] = [];
  isShowProcessor = false;
  showActualList = true;
  isEditOrg = false;
  total = 0;
  filter: TInnerFilter = {
    filter: '',
    pageable: {
      pageSize: 5,
      pageNumber: 0,
      sort: [{ direction: 'ASC', property: 'subject.name' }],
    },
    actual: true,
    columns: [
      {
        text: ' ',
        value: 'isProcessor',
        sortAs: 'is_processor',
        sortable: false,
      },
      {
        text: 'Наименование',
        value: 'name',
        sortAs: 'subject.name',
      },
      {
        text: 'Вид организации',
        value: 'subjectType',
        sortAs: 'subject.subject_type',
      },
      {
        text: 'ОПФ',
        value: 'opf.name',
        sortAs: 'subject.opf.name',
      },
      {
        text: 'ИНН',
        value: 'inn',
        sortAs: 'subject.inn',
        sortable: false,
      },
      {
        text: 'КПП',
        value: 'kpp',
        sortAs: 'subject.kpp',
        sortable: false,
      },
      {
        text: 'ОГРН/ОГРНИП',
        value: 'ogrn',
        sortAs: 'subject.ogrn',
        sortable: false,
      },
      {
        text: 'Регион',
        value: 'regionName',
        sortAs: 'subject.address.oker.name_okato',
      },
      {
        text: 'Дата включения в реестр',
        value: 'registryInclusionDate',
        sortAs: 'registry_inclusion_date',
      },
      {
        text: 'Дата аннулирования',
        value: 'registryExclusionDate',
        sortAs: 'registry_exclusion_date',
      },
      {
        text: 'Причина аннулирования',
        value: 'exclusionReason',
        sortAs: 'exclusion_reason.name',
      },
      {
        text: 'Действия',
        value: 'actions',
        class: 'th_actions fixed fixed--right',
        sortable: false,
      },
    ],
  };

  formFilters = {};
  opfList: IDictionaryNode[] = [];
  regionList: IDictionaryRegions[] = [];
  reasonExclusionList: IDictionaryNode[] = [];

  // eslint-disable-next-line max-lines-per-function
  get filters(): IFilters[] {
    return [
      {
        text: 'Наименование',
        placeholder: 'Введите наименование',
        value: 'name',
        type: 'text',
      },
      {
        text: 'Вид организации',
        placeholder: 'Выберите вид организации',
        value: 'subject_type',
        type: 'select',
        list: subjectTypeList(),
      },
      {
        text: 'Организационно-правовая форма',
        placeholder: 'Выберите организационно-правовую форму',
        value: 'opf',
        type: 'autocomplete',
        list: this.opfListValue,
        searchCallback: this.getOpfList,
      },
      {
        columns: [
          {
            text: 'ИНН',
            placeholder: 'Введите ИНН',
            value: 'inn',
            type: 'text',
          },
          {
            text: 'КПП',
            placeholder: 'Введите КПП',
            value: 'kpp',
            type: 'text',
          },
        ],
      },
      {
        text: 'ОГРН (ОГРНИП)',
        placeholder: 'Введите ОГРН (ОГРНИП)',
        value: 'ogrn',
        type: 'text',
      },
      {
        text: 'Регион',
        placeholder: 'Выберите регион',
        value: 'oker_id',
        type: 'autocomplete',
        list: this.regionListValue,
        searchCallback: this.getRegionList,
      },
      {
        text: 'Организация осуществляет переработку зерна',
        value: 'is_processor',
        type: 'checkbox',
      },
      {
        columns: [
          {
            text: '',
            value: 'include_date_start',
            placeholder: 'Укажите дату',
            type: 'datepicker',
            limitTo: 'include_date_end',
          },
          {
            text: '',
            value: 'include_date_end',
            placeholder: 'Укажите дату',
            type: 'datepicker',
            limitFrom: 'include_date_start',
          },
        ],
        label: 'Дата включения в реестр',
        range: true,
      },
      {
        columns: [
          {
            text: '',
            value: 'exclusion_date_start',
            placeholder: 'Укажите дату',
            type: 'datepicker',
            limitTo: 'exclusion_date_end',
          },
          {
            text: '',
            value: 'exclusion_date_end',
            placeholder: 'Укажите дату',
            type: 'datepicker',
            limitFrom: 'exclusion_date_start',
          },
        ],
        label: 'Дата исключения',
        range: true,
      },
      {
        text: 'Причина исключения',
        placeholder: 'Выберите причину исключения',
        value: 'reason_exclusion',
        type: 'autocomplete',
        list: this.reasonExclusionListValue,
        searchCallback: this.getReasonExclusionList,
      },
    ];
  }

  created() {
    this.getOpfList();
    this.getRegionList();
    this.getReasonExclusionList();
  }

  get opfListValue() {
    return this.opfList.map((item) => {
      return {
        name: item.name,
        id: item.code,
      };
    });
  }

  get regionListValue() {
    return this.regionList.map((item) => {
      return {
        name: item.name_okato,
        id: item.oker_id,
      };
    });
  }

  get reasonExclusionListValue() {
    return this.reasonExclusionList.map((item) => {
      return {
        name: item.name,
        id: item.id,
      };
    });
  }

  get filteredColumns() {
    return this.filter?.columns?.filter((item) => !!item.isShow);
  }

  @Watch('onOpenAddModal')
  onClearIdCard(onOpenAddModal) {
    if (!onOpenAddModal) {
      this.idEditCard = null;
    }
  }

  onApplyFilters(data) {
    this.formFilters = data;
    this.isShowProcessor = data.is_processor;
    this.fetchList();
  }

  async fetchList() {
    try {
      if (this.canReadManufacturerList) {
        this.isLoading = true;
        const { data, filter } = await this.$service.manufacturer.find({
          ...this.filter,
          ...this.formFilters,
          actual: this.showActualList,
          is_processor: this.isShowProcessor,
        });
        this.list = data;

        this.total = filter?.total ?? this.list.length;
      }
      this.isLoading = false;
    } catch (err) {
      this.isLoading = false;
      throw err;
    }
  }

  closeModal() {
    this.onOpenAddModal = false;
    this.idEditCard = null;
    this.isEditOrg = false;

    this.fetchList();
  }

  onChangeFilter(filter: TInnerFilter) {
    this.filter = mergeWith(this.filter, filter, (_, from) => {
      if (Array.isArray(from)) {
        return from;
      }
    });
    this.fetchList();
  }

  async onShowAllList() {
    this.showActualList = !this.showActualList;
    this.fetchList();
  }

  async showProcessorList() {
    this.isShowProcessor = !this.isShowProcessor;
    this.fetchList();
  }

  getExportFilter() {
    const { filter, pageable } = this.filter;
    return {
      ...this.formFilters,
      filter,
      actual: this.showActualList,
      pageable,
      is_processor: this.isShowProcessor,
    };
  }

  subjectType(item: string) {
    return subjectType.get(item);
  }

  date = date;

  async getOpfList(): Promise<void> {
    const { content } = await this.$store.dispatch('sdiz/getOPF');
    this.opfList = content;
  }

  async getRegionList(): Promise<void> {
    const { data } = await this.$service.catalogs.getRegion();
    this.regionList = data;
  }

  async getReasonExclusionList(): Promise<void> {
    const { data } = await this.$service.catalogs.getReasonExclusion();
    this.reasonExclusionList = data;
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.settings {
  display: inline-block;
  vertical-align: middle;
  background-color: #fff;
  color: $medium-grey-color;
  text-align: center;
  text-decoration: none;
  font-size: 15px;
  border-radius: 4px;
  margin-right: 0;
  margin-left: 0;
  border: 1px solid $input-border-color;
  width: 40px;
  height: 40px;
  line-height: 3.1;
  cursor: pointer;
}

.settingsSpan {
  vertical-align: middle;
  background-color: #fff;
  color: $medium-grey-color;
  padding: 0 16px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 15px;
  border-radius: 4px;
  margin-right: 8px;
  margin-left: 0;
  border: 1px solid $input-border-color;
  height: 40px;
  line-height: 2.5;
  cursor: pointer;

  &--disabled {
    opacity: 0.5;
    cursor: default;
  }
}

.iconSettings {
  margin-right: 5px;
}

.checkbox-elem {
  display: flex;
}

.checkbox-title {
  font-size: 14px;
  margin-left: 4px;
  color: #828286;
}

.iconTable {
  cursor: pointer;
  margin: 0 4px;
}
</style>

<style lang="scss">
.checkbox_grain_processing {
  .v-input__slot .v-icon::before {
    background-image: url('/icons/grain_processing.svg') !important;
    width: 14px !important;
    background-size: contain;
  }

  &.v-input--is-dirty {
    .v-input__slot .v-icon::before {
      background-image: url('/icons/grain_processing_check.svg') !important;
    }
  }

  .v-input--selection-controls__input {
    margin-right: 0;
  }
}
</style>
