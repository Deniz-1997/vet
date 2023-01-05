<template>
  <v-container>
    <v-row>
      <v-col cols="6">
        <div class="title">
          <span>Реестр лабораторий</span>
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
            v-if="canSettingRegister"
            id="laboratory"
            v-model="filter"
            type="button"
            class="manufacture_setting"
            :sort-map="{ 'subject.inn_kpp': ['subject.inn', 'subject.kpp'] }"
            @apply-settings="fetchList"
          />

          <!-- GISZP-2435 -->
          <!--          <button-->
          <!--            v-if="canExcludeLaboratory"-->
          <!--            class="settingsSpan"-->
          <!--            :disabled="!statusButton"-->
          <!--            :class="{ 'settingsSpan&#45;&#45;disabled': !statusButton }"-->
          <!--            @click="showActionModal"-->
          <!--          >-->
          <!--            <img src="/icons/delete.svg" class="iconSettings" />-->
          <!--            <span>Исключить из реестра</span>-->
          <!--          </button>-->
        </div>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="2">
        <Filters id="laboratory" v-model="filters" @apply-filters="onApplyFilters" />
      </v-col>
      <v-col v-if="canFilterRegister" class="d-flex align-lg-center justify-end">
        <span :style="{ marginRight: '20px' }">
          <checkbox-component
            id="all_list"
            :value="showAll"
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
          <template #[`item.includeDate`]="{ item }">
            {{ item.includeDate | date }}
          </template>
          <template #[`item.exclusionDate`]="{ item }">
            {{ item.exclusionDate | date }}
          </template>

          <template #[`item.actions`]="{ item }">
            <span class="wrap_content_actions">
              <v-tooltip v-if="canViewCardLaboratory" bottom>
                <template #activator="{ on, attrs }">
                  <router-link
                    v-bind="attrs"
                    :to="{
                      path: `/laboratories/${item.id}`,
                      params: { id: item.id },
                    }"
                    v-on="on"
                  >
                    <img src="/icons/show.svg" class="iconTable" />
                  </router-link>
                </template>
                <span>Просмотреть информацию</span>
              </v-tooltip>

              <v-tooltip v-if="canEditCardLaboratory" bottom>
                <template #activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on">
                    <router-link :to="{ path: `/laboratories/edit/${item.subjectId}` }">
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

    <LaboratoryCard
      :id="idEditCard"
      v-model="onOpenAddModal"
      :is-show="typeAction === 'show'"
      :show-actual="!showAll"
      @close="closeModal"
    />
    <v-overlay :value="isLoading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import DataTableNew from '@/components/common/DataTableNew/DataTable.vue';
import SearchNewComponent from '@/components/common/SearchNew/Search.vue';
import DefaultButtonNew from '@/components/common/buttons/DefaultButtonNew.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import ExcludeLaboratories from '@/views/Laboratories/components/ExcludeLaboratories.vue';
import LaboratoryCard from './components/LaboratoryCard.vue';
import { EAction, mapAccessFlags } from '@/utils';
import { ILaboratoryItemResponse } from '@/services/models/laboratory';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import { IDictionaryNode, IFilters, TInnerFilter } from '@/services/models/common';
import { date } from '@/utils/global/filters';
import { Debounce } from '@/utils/global/decorators/method';
import mergeWith from 'lodash/mergeWith';
import omit from 'lodash/omit';
import { subjectType } from '@/utils/subjectType';
import Filters from '@/components/common/Filter/Filter.vue';
import { subjectTypeList } from '@/utils/subjectType';
import Exportable from '@/utils/global/mixins/exportable';

@Component({
  name: 'laboratories-list',
  filters: { date },
  components: {
    SearchNewComponent,
    DataTableNew,
    DefaultButtonNew,
    DialogComponent,
    ExcludeLaboratories,
    LaboratoryCard,
    CheckboxComponent,
    Filters,
  },
  computed: {
    ...mapAccessFlags({
      canFilterRegister: EAction.FILTER_LABORATORY_REGISTER,
      canSettingRegister: EAction.CUSTOMIZE_LABORATORY_REGISTER,
      canExportRegister: EAction.EXPORT_LABORATORY_REGISTER,
      canViewCardLaboratory: EAction.READ_LABORATORY,
      canEditCardLaboratory: EAction.UPDATE_LABORATORY,
    }),
  },
})
export default class LadoratoriesList extends Mixins(Exportable('/api/laboratory/exportReport2')) {
  readonly canFilterRegister!: boolean;
  readonly canSettingRegister!: boolean;
  readonly canExportRegister!: boolean;
  readonly canExcludeLaboratory!: boolean;
  readonly canViewCardLaboratory!: boolean;
  readonly canEditCardLaboratory!: boolean;
  list: ILaboratoryItemResponse[] = [];
  isLoading = false;
  showAll = false;
  applyHeaders: any[] = [];
  idEditCard = '';
  onOpenAddModal = false;
  onOpenSettings = false;
  checked = false;
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
        sortable: false,
      },
      {
        text: 'Адреса осуществления деятельности',
        value: 'location_concat',
        sortAs: 'location_concat',
      },
      {
        text: 'Аттестаты аккредитации',
        value: 'certificates_string',
        sortAs: 'certificates_string',
      },
      {
        text: 'Дата включения в реестр',
        value: 'includeDate',
        sortAs: 'include_date',
      },
      {
        text: 'Дата исключения из реестра',
        value: 'exclusionDate',
        sortAs: 'exclusion_date',
      },
      {
        text: 'Причина исключения',
        value: 'exclusionReason',
        sortAs: 'reason_exclusion',
        sortable: false,
      },
      {
        text: 'Действия',
        value: 'actions',
        class: 'th_actions fixed fixed--right',
        sortable: false,
      },
    ],
  };
  typeAction = 'view';
  total = 0;
  infoAboutLaboratory: any = {};

  formFilters = {};
  opfList: IDictionaryNode[] = [];

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
        text: 'Адрес осуществления деятельности',
        placeholder: 'Введите адрес',
        value: 'locations',
        type: 'text',
      },
      {
        text: 'Аттестат аккредитации',
        placeholder: 'Введите аттестат аккредитации',
        value: 'certificates',
        type: 'text',
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
        type: 'text',
      },
      {
        text: 'Все записи',
        value: 'actual',
        type: 'checkbox',
      },
    ];
  }

  // TODO: GISZP-2435
  // get selectLaboratory() {
  //   if (this.selectLaboratories.length === 1) {
  //     // ToDo: Исправить типизацию.
  //     const organization: any = this.list.find((item: any) => item.id === this.selectLaboratories[0]);
  //
  //     return {
  //       name: organization.subject ? organization.subject.subject_data.name : null,
  //       id: organization.id,
  //       exclusion_date: !organization.exclusion_date,
  //     };
  //   } else {
  //     return {
  //       name: '',
  //       id: 0,
  //       exclusion_date: false,
  //     };
  //   }
  // }

  mounted() {
    this.getOpfList();
  }

  get opfListValue() {
    return this.opfList.map(item => {
      return {
        name: item.name,
        id: item.code,
      }
    });
  }

  onApplyFilters(data) {
    this.formFilters = data;
    this.showAll = data.actual;
    this.fetchList();
  }

  @Debounce()
  async fetchList() {
    try {
      this.isLoading = true;
      const { data, filter } = await this.$service.laboratory.find({
        ...this.filter,
        ...this.formFilters,
        actual: !this.showAll,
      });
      this.list = data;

      this.total = filter?.total ?? this.list.length;
      this.isLoading = false;
    } catch (err) {
      this.isLoading = false;
      throw err;
    }
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
    this.showAll = !this.showAll;
    this.fetchList();
  }

  async showModal(id: string, typeAction: string) {
    this.isLoading = true;
    this.onOpenAddModal = true;
    this.idEditCard = id ? String(id) : '';
    this.typeAction = typeAction;
    this.isLoading = false;
  }

  handleCheck() {
    if (this.checked) {
      this.checked = false;
      // GISZP-2435
      // this.statusButton = false;
      return;
    }
    this.checked = true;
    // GISZP-2435
    // this.statusButton = this.selectLaboratory.exclusion_date;
  }

  getExportFilter() {
    return { ...this.formFilters, ...omit(this.filter, ['columns', 'hash']) };
  }

  closeModal() {
    this.onOpenAddModal = false;
    this.idEditCard = '';
    this.fetchList();
    this.checked = false;
  }

  subjectType(item: string) {
    return subjectType.get(item);
  }

  async getOpfList(): Promise<void> {
    const { content } = await this.$store.dispatch('sdiz/getOPF');
    this.opfList = content;
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
  color: $medium-grey-color;
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
