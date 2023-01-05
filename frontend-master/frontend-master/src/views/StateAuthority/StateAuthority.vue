<template>
  <v-container>
    <v-row>
      <v-col cols="6">
        <div class="title">
          <span> Органы государственной власти </span>
        </div>
      </v-col>

      <v-col cols="6" class="d-flex justify-end">
        <!-- TODO: Ожидает задачи по доработке асинхронного экспорта -->
        <!-- <span v-if="canExportRegister" class="settingsSpan" @click="exportAction">
          <img src="/icons/export.svg" class="iconSettings" />
          Экспорт списка
        </span> -->

        <div class="settings">
          <UiViewSettingsModal
            id="laboratory"
            v-model="filter"
            type="button"
            class="manufacture_setting"
            :sort-map="{ 'subject.inn_kpp': ['subject.inn', 'subject.kpp'] }"
            @apply-settings="fetchList"
          />
        </div>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="2">
        <Filters id="stateAuthority" v-model="filters" @apply-filters="onApplyFilters" />
      </v-col>
      <v-col class="d-flex align-lg-center justify-end">
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
          @onOptionsChange="
            ({ page, size, sort }) => onChangeFilter({ pageable: { pageNumber: page + 1, pageSize: size, sort } })
          "
        >
          <template #[`item.actions`]="{ item }">
            <span class="wrap_content_actions">
              <v-tooltip v-if="canViewGOVCard" bottom>
                <template #activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on">
                    <router-link
                      :to="{
                        path: `/stateAuthority/${item.id}`,
                        params: { id: item.id },
                      }"
                    >
                      <img src="/icons/show.svg" class="iconTable" />
                    </router-link>
                  </span>
                </template>
                <span>Просмотреть информацию</span>
              </v-tooltip>
              <v-tooltip v-if="canEditGOV" bottom>
                <template #activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on">
                    <router-link
                      v-show="!item.exclusionReason"
                      :to="{ path: `/stateAuthority/edit/${item.subjectId}` }"
                    >
                      <img src="/icons/edit.svg" class="iconTable" alt="" />
                    </router-link>
                  </span>
                </template>
                <span>Редактировать информацию</span>
              </v-tooltip>
            </span>
          </template>
          <template #[`item.subjectType`]="{ item }">
            <span> {{ subjectType(item.subjectType) }} </span>
          </template>
        </DataTableNew>
      </v-col>
    </v-row>

    <v-overlay :value="isLoading">
      <v-progress-circular indeterminate size="64" />
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import { Component, Mixins, Watch } from 'vue-property-decorator';
import SearchNewComponent from '@/components/common/SearchNew/Search.vue';
import DataTableNew from '@/components/common/DataTableNew/DataTable.vue';
import DefaultButtonNew from '@/components/common/buttons/DefaultButtonNew.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import { mapAccessFlags, EAction } from '@/utils';
import { IDictionaryNode, IFilters, TInnerFilter } from '@/services/models/common';
import { IStateAuthorityItemResponse } from '@/services/models/stateAuthority';
import mergeWith from 'lodash/mergeWith';
import { subjectType } from '@/utils/subjectType';
import Filters from '@/components/common/Filter/Filter.vue';
import { subjectTypeList } from '@/utils/subjectType';
import { IDictionaryRegions } from '@/services/models/catalogs';
import Exportable from '@/utils/global/mixins/exportable';

@Component({
  name: 'stateAuthority-list',
  components: {
    SearchNewComponent,
    DataTableNew,
    DefaultButtonNew,
    DialogComponent,
    Filters,
  },
  computed: {
    ...mapAccessFlags({
      canFilterGOVRegister: EAction.FILTER_GOV_ORG_REGISTER,
      canExportGOVRegister: EAction.EXPORT_GOV_ORG_REGISTER,
      canViewGOVCard: EAction.READ_GOV_ORG,
      canEditGOV: EAction.UPDATE_GOV_ORG,
    }),
  },
})
export default class StateAuthorityList extends Mixins(Exportable('/api/subject/ogv/exportReport')) {
  readonly canFilterGOVRegister!: boolean;
  readonly canExportGOVRegister!: boolean;
  readonly canViewGOVCard!: boolean;
  readonly canEditGOV!: boolean;

  statusButton = false;
  isLoading = false;
  checked = false;
  isShowActionModal = false;
  isOpenAddModal = false;
  showAllList = false;
  isEdit = false;
  isShowStateAuthority = false;
  list: IStateAuthorityItemResponse[] = [];
  idEditCard: number | null = null;
  total = 0;
  isViewSettingsOpen = false;

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
      },
      {
        text: 'КПП',
        value: 'kpp',
        sortAs: 'subject.kpp',
      },
      {
        text: 'ОГРН',
        value: 'ogrn',
        sortAs: 'subject.ogrn',
      },
      {
        text: 'Регион',
        value: 'regionName',
        sortAs: 'subject.address.oker.name_okato',
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
  reasonExclusionList: IDictionaryNode[] = [];
  regionList: IDictionaryRegions[] = [];

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
        text: 'ОГРН',
        placeholder: 'Введите ОГРН',
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
      // TODO: GISZP-4273 - Фильтры скрыты до вывода соответсвующих данных в колонках реестра
      // {
      //   columns: [
      //     {
      //       text: '',
      //       value: 'include_date_start',
      //       placeholder: 'Укажите дату',
      //       type: 'datepicker',
      //       limitTo: 'include_date_end',
      //     },
      //     {
      //       text: '',
      //       value: 'include_date_end',
      //       placeholder: 'Укажите дату',
      //       type: 'datepicker',
      //       limitFrom: 'include_date_start',
      //     },
      //   ],
      //   label: 'Дата включения в реестр',
      //   range: true,
      // },
      // {
      //   columns: [
      //     {
      //       text: '',
      //       value: 'exclusion_date_start',
      //       placeholder: 'Укажите дату',
      //       type: 'datepicker',
      //       limitTo: 'exclusion_date_end',
      //     },
      //     {
      //       text: '',
      //       value: 'exclusion_date_end',
      //       placeholder: 'Укажите дату',
      //       type: 'datepicker',
      //       limitFrom: 'exclusion_date_start',
      //     },
      //   ],
      //   label: 'Дата исключения',
      //   range: true,
      // },
      // {
      //   text: 'Причина исключения',
      //   placeholder: 'Выберите причину исключения',
      //   value: 'reason_exclusion',
      //   type: 'autocomplete',
      //   list: this.reasonExclusionListValue,
      //   searchCallback: this.getReasonExclusionList,
      // },
      {
        text: 'Все записи',
        value: 'actual',
        type: 'checkbox',
      },
    ];
  }

  @Watch('isOpenAddModal')
  onClearIdCard(isOpenAddModal) {
    if (!isOpenAddModal) {
      this.idEditCard = null;
    }
  }

  mounted() {
    if (!this.isOpenAddModal) {
      this.idEditCard = null;
    }
    this.getOpfList();
    this.getReasonExclusionList();
    this.getRegionList();
  }

  get opfListValue() {
    return this.opfList.map((item) => {
      return {
        name: item.name,
        id: item.code,
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

  get regionListValue() {
    return this.regionList.map((item) => {
      return {
        name: item.name_okato,
        id: item.oker_id,
      };
    });
  }

  onApplyFilters(data) {
    this.formFilters = data;
    this.showAllList = data.actual;
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

  async fetchList() {
    try {
      this.isLoading = true;
      const { data, filter } = await this.$service.stateAuthority.find({
        ...this.filter,
        ...this.formFilters,
        actual: !this.showAllList,
      });
      this.list = data;

      this.total = filter?.total ?? this.list.length;
      this.isLoading = false;
    } catch (err) {
      this.isLoading = false;
      throw err;
    }
  }

  getExportFilter() {
    const { filter, sort, pageable } = this.filter;
    return {
      ...this.formFilters,
      filter,
      actual: !this.showAllList,
      pageable: {
        ...pageable,
        sort,
      },
    };
  }

  handleCheck() {
    if (this.checked) {
      this.checked = false;
      this.statusButton = false;
      return;
    }
    this.checked = true;
  }

  async canShow(id) {
    this.isShowStateAuthority = true;
    this.isOpenAddModal = true;
    this.idEditCard = id;
  }

  async canEdit(id) {
    this.isEdit = true;
    this.isOpenAddModal = true;
    this.idEditCard = id;
  }

  showActionModal() {
    this.isShowActionModal = true;
  }

  closeModal() {
    this.isShowActionModal = false;
    this.fetchList();
  }

  closeStateAuthorityModal() {
    this.isOpenAddModal = !this.isOpenAddModal;
    this.isShowStateAuthority = false;
    this.idEditCard = null;
    this.fetchList();
  }

  subjectType(item: string) {
    return subjectType.get(item);
  }

  async getOpfList(): Promise<void> {
    const { content } = await this.$store.dispatch('sdiz/getOPF');
    this.opfList = content;
  }

  async getReasonExclusionList(): Promise<void> {
    const { data } = await this.$service.catalogs.getReasonExclusion();
    this.reasonExclusionList = data;
  }

  async getRegionList(): Promise<void> {
    const { data } = await this.$service.catalogs.getRegion();
    this.regionList = data;
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
  padding-top: 12px;
}

.processor {
  padding-left: 40px;
}

.checkbox-title {
  font-size: 14px;
  margin-left: 4px;
  color: #828286;
}

.iconTable {
  cursor: pointer;
  padding-right: 8px;
}

.checkbox-elem {
  position: relative;
  cursor: pointer;

  input {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
    cursor: pointer;
    width: 100%;
    height: 100%;
  }
}
</style>
