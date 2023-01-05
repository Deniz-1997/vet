<template>
  <v-container>
    <v-row>
      <v-col cols="6">
        <div class="title">
          <span>Реестр всех организаций</span>
        </div>
      </v-col>

      <v-col cols="6" class="d-flex justify-end">
        <DefaultButtonNew
          v-if="canCreateSubjectCard"
          title="Добавить"
          variant="primary"
          alt="Добавить"
          :prepend-icon="'mdi-plus-circle'"
          @click="$router.push('/subjects/create')"
        />

        <div v-if="canCustomizeSubjectList" class="settings">
          <UiViewSettingsModal
            id="laboratory"
            v-model="filter"
            type="button"
            class="manufacture_setting"
            @apply-settings="fetchList"
          />
        </div>
      </v-col>
    </v-row>

    <v-row v-if="canFilterSubjectList">
      <v-col>
        <Filters id="subject" v-model="filters" @apply-filters="onApplyFilters" />
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
          :headers="filteredColumns"
          :items="list"
          :items-length="total"
          :page="filter.pageable.pageNumber"
          :per-page="filter.pageable.pageSize"
          :search="filter.filter"
          class="lastcol_fixed"
          @handleSort="(e) => sortColumn(e)"
          @onOptionsChange="({ page, size }) => onChangeFilter({ pageable: { pageNumber: page + 1, pageSize: size } })"
        >
          <template #[`item.actions`]="{ item }">
            <span class="wrap_content_actions">
              <v-tooltip v-if="canViewSubjectCard" bottom>
                <template #activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on">
                    <router-link :to="{ path: `/subjects/${item.subject_id}` }">
                      <img src="/icons/show.svg" class="iconTable" alt="" />
                    </router-link>
                  </span>
                </template>
                <span>Просмотреть информацию</span>
              </v-tooltip>
              <v-tooltip v-if="canEditSubjectCard" bottom>
                <template #activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on">
                    <router-link :to="{ path: `/subjects/edit/${item.subject_id}` }">
                      <img src="/icons/edit.svg" class="iconTable" alt="" />
                    </router-link>
                  </span>
                </template>
                <span>Редактировать информацию</span>
              </v-tooltip>
            </span>
          </template>
          <template #[`item.subject_type`]="{ item }">
            <span> {{ subjectType(item.subject_type) }} </span>
          </template>
          <template #[`item.propertyMap`]="{ item }">
            <span>{{ getRegistersString(item.propertyMap) }} </span>
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
import { Component, Vue } from 'vue-property-decorator';
import SearchNewComponent from '@/components/common/SearchNew/Search.vue';
import DataTableNew from '@/components/common/DataTableNew/DataTable.vue';
import DefaultButtonNew from '@/components/common/buttons/DefaultButtonNew.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import { mapAccessFlags, EAction } from '@/utils';
import { TInnerFilter, IFilters } from '@/services/models/common';
import { merge } from 'lodash';
import { ISubjectItemResponse } from '@/services/models/subject';
import { subjectType, subjectTypeList } from '@/utils/subjectType';
import Filters from '@/components/common/Filter/Filter.vue';
import { inRegisters } from '@/utils/constants/inRegisters';

import { IDictionaryNode } from '@/services/models/common';
import { IDictionaryRegions } from '@/services/models/catalogs';

@Component({
  name: 'subject-list',
  components: {
    SearchNewComponent,
    DataTableNew,
    DefaultButtonNew,
    DialogComponent,
    Filters,
  },
  computed: {
    ...mapAccessFlags({
      canCustomizeSubjectList: EAction.CUSTOMIZE_FULL_ORGANIZATION_REGISTER,
      canFilterSubjectList: EAction.FILTER_FULL_ORGANIZATION_REGISTER,
      canCreateSubjectCard: EAction.CREATE_FULL_ORGANIZATION,
      canEditSubjectCard: EAction.CHANGE_FULL_ORGANIZATION,
      canViewSubjectCard: EAction.READ_FULL_ORGANIZATION,
    }),
  },
})
export default class SubjectList extends Vue {
  readonly canCustomizeSubjectList!: boolean;
  readonly canFilterSubjectList!: boolean;
  readonly canCreateSubjectCard!: boolean;
  readonly canEditSubjectCard!: boolean;
  readonly canViewSubjectCard!: boolean;

  isLoading = false;
  checked = false;
  showAllList = false;
  isEdit = false;
  list: ISubjectItemResponse[] = [];
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
        text: 'Наименование',
        value: 'name',
        sortAs: 'name',
        isShow: true,
      },
      {
        text: 'Вид организации',
        value: 'subject_type',
        sortAs: 'subject_type',
        isShow: true,
      },
      {
        text: 'ОПФ',
        value: 'opf_name',
        sortAs: 'opf_name',
        isShow: true,
      },
      {
        text: 'ИНН',
        value: 'inn',
        sortAs: 'inn',
        isShow: true,
      },
      {
        text: 'КПП',
        value: 'kpp',
        sortAs: 'kpp',
        isShow: true,
      },
      {
        text: 'ОГРН',
        value: 'ogrn',
        sortAs: 'ogrn',
        isShow: true,
      },
      {
        text: 'Регион',
        value: 'region',
        sortAs: 'region',
        isShow: true,
      },
      {
        text: 'Реестры',
        value: 'propertyMap',
        sortable: false,
        isShow: true,
      },
      {
        text: 'Действия',
        value: 'actions',
        class: 'th_actions fixed fixed--right',
        sortable: false,
        isShow: true,
      },
    ],
  };

  formFilters = {};
  opfList: IDictionaryNode[] = [];
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
        text: 'Состоит в реестре',
        placeholder: 'Выберите реестр',
        value: 'in_registry',
        type: 'select',
        list: inRegisters,
      },
      {
        text: 'Все записи',
        value: 'actual',
        type: 'checkbox',
      },
    ];
  }

  created() {
    this.getOpfList();
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

  get regionListValue() {
    return this.regionList.map((item) => {
      return {
        name: item.name_okato,
        id: item.oker_id,
      };
    });
  }

  get filteredColumns() {
    return this.filter?.columns?.filter((item) => !!item.isShow);
  }

  getRegistersString(registers) {
    const result: string[] = [];

    if (registers?.is_laboratory) {
      result.push('Лаборатории');
    }

    if (registers?.is_manufacturer) {
      result.push('Товаропроизводители');
    }

    if (registers?.is_ogv) {
      result.push('Органы государственной власти');
    }

    return result.join(';\n');
  }

  sortColumn(e) {
    this.filter.pageable = {
      ...this.filter.pageable,
      sort: [
        {
          direction: this.filter.pageable?.sort && this.filter.pageable?.sort[0].direction === 'ASC' ? 'DESC' : 'ASC',
          property: e.sortBy[0],
        },
      ],
    };
    this.fetchList();
  }

  onChangeFilter(filter: TInnerFilter) {
    this.filter = merge(this.filter, filter);
    this.fetchList();
  }

  onApplyFilters(data) {
    this.formFilters = data;
    this.showAllList = data.actual;
    this.fetchList();
  }

  async fetchList() {
    try {
      this.isLoading = true;
      const { data, filter } = await this.$service.subject.find({
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

  subjectType(item: string) {
    return subjectType.get(item);
  }

  async getOpfList(): Promise<void> {
    const { content } = await this.$store.dispatch('sdiz/getOPF');
    this.opfList = content;
  }

  async getRegionList(): Promise<void> {
    const { data } = await this.$service.catalogs.getRegion();
    this.regionList = data;
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';

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

.iconTable {
  cursor: pointer;
  padding-right: 8px;
}
</style>
