<template>
  <div class="register-organizations">
    <div v-if="!isShowCard">
      <div class="title mb-4">
        <span
          >Реестр организаций, осуществляющих в качестве предпринимательской деятельности хранение зерна и оказывающих
          связанные с хранением услуги</span
        >
      </div>
      <div class="block-header">
        <div class="search-wrap">
          <SearchComponent placeholder="Введите значение поиска" @search="handleSearch" />
        </div>
        <span class="span-list checkbox-elem">
          <span class="checkbox-filter">
            <label class="checkbox">
              <input type="checkbox" :checked="!showActual" @click="onShowAllList" />
              <span class="checkbox__icon">
                <img src="/icons/checkbox.svg" />
              </span>
            </label>
          </span>
          <span class="checkbox-title"> Показать исключённые из реестра предприятия </span>
        </span>
      </div>
      <div class="settings">
        <Filters id="registerOrganizations" v-model="filters" @apply-filters="onApplyFilters" />
        <span v-if="canCustomizeRegister" class="settingsSpan" @click="onOpenSettings = true">
          <img src="/icons/settings.svg" class="iconSettings" />
          Настроить вид
        </span>
        <span v-if="canExportRegister" class="settingsSpan" @click="exportAction">
          <img src="/icons/export.svg" class="iconSettings" />
          Экспорт списка
        </span>
        <div v-if="$route.fullPath === '/register-organizations'" class="actionButton">
          <!-- ToDo: Исправить, когда появится множественный выбор. -->
          <!-- <button
              class="settingsSpan"
              :disabled="!statusButton.canStop"
              @click="showActionModal(selectOrganization[0], 'stop')"
              :class="{'settingsSpan--disabled': !statusButton.canStop}"
            >
              <img
                src="/icons/stop.svg"
                class="iconSettings"
              >
                <span>Сведения о приостановке деятельности</span>
            </button> -->
          <!-- <button
              class="settingsSpan"
              :disabled="!statusButton.canResumption"
              @click="showActionModal(selectOrganization[0], 'resumption')"
              :class="{'settingsSpan--disabled': !statusButton.canResumption}"
            >
              <img
                src="/icons/confirm.svg"
                class="iconSettings"
              >
              <span>Сведения о возобновлении  деятельности</span>
            </button> -->
        </div>
      </div>

      <DataTable
        :headers="applyHeaders"
        :items="rows"
        :items-length="total"
        :page="pageable.pageNumber"
        :per-page="pageable.pageSize"
        :search="filter.filter"
        @handleSort="(e) => sortColumn(e)"
        @onOptionsChange="onOptionsChange"
      >
        <template #[`item.name`]="{ item }">
          {{ item.subject.short_name || item.subject.name }}
        </template>
        <template #[`item.subject_type`]="{ item }">
          <span> {{ subjectType(item.subject_type) }} </span>
        </template>
        <template #[`item.insurance_policy_info`]="{ item }">
          <span v-for="(info, index) in item.insurance_policy_info" :key="index"> {{ `${info}` }}<br /> </span>
        </template>
        <template #[`item.actions`]="{ item }">
          <div class="d-flex align-center">
            <v-tooltip bottom>
              <template #activator="{ on, attrs }">
                <span v-if="canReadOrganization" v-bind="attrs" class="d-flex justify-center align-center" v-on="on">
                  <img src="/icons/show.svg" class="iconTable" @click="showCard(item)" />
                </span>
              </template>
              <span>Просмотреть информацию</span>
            </v-tooltip>

            <v-tooltip bottom>
              <template #activator="{ on, attrs }">
                <span v-if="canGenerateReport" v-bind="attrs" class="d-flex justify-center align-center" v-on="on">
                  <img src="/icons/print.svg" class="iconTable" @click="exportPdf(item)" />
                </span>
              </template>
              <span>Сформировать выписку</span>
            </v-tooltip>

            <v-tooltip bottom>
              <template #activator="{ on, attrs }">
                <v-icon
                  v-if="canDeleteOrganization && !item.excluded"
                  v-bind="attrs"
                  small
                  v-on="on"
                  @click="showActionModal(item)"
                >
                  mdi-delete
                </v-icon>
              </template>
              <span>Исключить из реестра</span>
            </v-tooltip>
          </div>
        </template>

        <template #[`item.laboratory`]="{ item }">
          <span>{{ getLaboratoryStatus(item) }}</span>
        </template>
      </DataTable>
    </div>

    <ViewSettingsModal
      id="register-organizations"
      v-model="onOpenSettings"
      :headers="headers"
      :sort-map="sortMap"
      :default-sorting="{ property: 'registration_number', direction: 'ASC' }"
      @close="closeSettingsModal"
      @apply-settings="onApplySettings"
    />

    <ActionModal
      v-if="isShowActionModal"
      :action-type="actionType"
      :elevator-id="selectOrganization.elevator_id"
      :name="selectOrganization.subject.name"
      :start-date="selectOrganization.registration_date"
      @close="closeModal"
      @reset="resetStatusButton"
    />
    <AuthoritiesCardOrganization
      v-else-if="isShowCard && !isShowActionModal"
      :information="information"
      @click-close="closeCard"
    />

    <v-overlay :value="isLoading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>

    <router-view />
  </div>
</template>

<script lang="ts">
// ToDo: Рефакторинг обязателен!
import { Component, Mixins } from 'vue-property-decorator';
import AuthoritiesCardOrganization from './components/CardOrganization/CardOrganization.vue';
import ActionModal from '@/views/Authorities/components/Modal/ActionModal.vue';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import SearchComponent from '@/components/common/Search/Search.vue';
import ViewSettingsModal, { TFilter } from '@/components/common/ViewSettings/ViewSettingsModal.vue';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import { EAction, mapAccessFlags } from '@/utils';
import Filters from '@/components/common/Filter/Filter.vue';

import { SETTINGS_KEY } from '@/components/common/ViewSettings/consts';
import { IDictionaryNode, IFilters } from '@/services/models/common';
import { subjectType, subjectTypeList } from '@/utils/subjectType';
import { accreditationList } from '@/utils/constants/accreditation';
import { IDictionaryRegions } from '@/services/models/catalogs';
import { getSort } from '@/utils/getSort';
import Exportable from '@/utils/global/mixins/exportable';

const FieldsMap = new Map<string, string | string[]>([
  ['name', 'subject.name'],
  ['subject_type', 'subject.subject_type'],
  ['opf_name', 'subject.opf.name'],
  ['region', 'subject.address.oker.name_okato'],
  ['subject.subject_data.inn_kpp', ['subject.inn', 'subject.kpp']],
  ['subject.subject_data.ogrn', 'subject.ogrn'],
  ['common_capacity', 'elevator_info.common_capacity'],
  ['services', 'elevator_info.all_services'],
  ['other_services', 'elevator_info.other_services'],
  ['exclusion_reason.name', 'exclusion_reason'],
]);

const defaultPageable = {
  pageSize: 5,
  pageNumber: 0,
};

@Component({
  name: 'authorities-register-organizations',
  components: {
    AuthoritiesCardOrganization,
    ActionModal,
    DataTable,
    SearchComponent,
    ViewSettingsModal,
    CheckboxComponent,
    Filters,
  },
  computed: {
    ...mapAccessFlags({
      canDeleteOrganization: EAction.DELETE_ORGANIZATION,
      canReadOrganization: EAction.READ_ORGANIZATION,
      canGenerateReport: EAction.GENERATE_ORGANIZATION_REGISTER_REPORT,
      canExportRegister: EAction.EXPORT_ORGANIZATION_REGISTER,
      canCustomizeRegister: EAction.CUSTOMIZE_ORGANIZATION_REGISTER,
    }),
  },
})
export default class AuthoritiesRegisterOrganizations extends Mixins(
  Exportable('/api/elevator/elevator/exportReport')
) {
  readonly canDeleteOrganization!: boolean;
  readonly canReadOrganization!: boolean;
  readonly canGenerateReport!: boolean;
  readonly canExportRegister!: boolean;
  readonly canCustomizeRegister!: boolean;
  information = {};
  isShowCard = false;
  checked = false;
  selectOrganization: any = {};
  statusButton: any = {};
  onOpenSettings = false;
  isShowActionModal = false;
  actionType = '';
  isLoading = false;
  showActual = true;
  elevatorFilter = {
    queryString: '',
    name: '',
  };
  rows: any[] = [];
  pageable: any = defaultPageable;
  total = 0;
  applyHeaders: any[] = [];
  searchValue = '';
  searchQuery = '';
  isSubjectUser = true;
  filter: Partial<TFilter> = {};
  headers = [
    {
      text: 'Действия',
      value: 'actions',
      sortable: false,
    },
    {
      text: 'Номер включения в реестр',
      value: 'registration_number',
    },
    {
      text: 'Наименование организации',
      value: 'name',
    },
    {
      text: 'Вид организации',
      value: 'subject_type',
    },
    {
      text: 'ОПФ',
      value: 'opf_name',
    },
    {
      text: 'Регион',
      value: 'region',
    },
    {
      text: 'ИНН / КПП',
      value: 'subject.subject_data.inn_kpp',
      sortable: false,
    },
    {
      text: 'ОГРН / ОГРНИП',
      value: 'subject.subject_data.ogrn',
      sortable: false,
    },
    {
      text: 'Номер свидетельства о регистрации опасных производственных объектов, используемых организацией',
      value: 'elevator_info.hazardous_object_info.doc_num',
      width: 300,
      sortable: false,
    },
    {
      text: 'Дата свидетельства о регистрации опасных производственных объектов, используемых организацией',
      value: 'elevator_info.hazardous_object_info.doc_date',
      width: 300,
      sortable: false,
    },
    {
      text: 'Номер договора страхования гражданской ответственности организации',
      value: 'elevator_info.insurance_policy_info.doc_num',
      width: 300,
      sortable: false,
    },
    {
      text: 'Дата начала действия договора',
      value: 'elevator_info.insurance_policy_info.doc_date',
      width: 300,
      sortable: false,
    },
    {
      text: 'Дата окончания действия договора',
      value: 'elevator_info.insurance_policy_info.validity_date',
      width: 300,
      sortable: false,
    },
    {
      text: 'Общая мощность',
      value: 'common_capacity',
    },
    {
      text: 'Лаборатория',
      value: 'laboratory',
    },
    {
      text: 'Предоставляемые услуги',
      value: 'services',
      sortable: false,
    },
    {
      text: 'Иные услуги',
      value: 'other_services',
      sortable: false,
    },
    {
      text: 'Дата включения в реестр',
      value: 'registration_date',
    },
    {
      text: 'Дата исключения из реестра',
      value: 'exclusion_date',
    },
    {
      text: 'Причина исключения',
      value: 'exclusion_reason.name',
    },
  ];

  formFilters = {};
  opfList: IDictionaryNode[] = [];
  regionList: IDictionaryRegions[] = [];
  reasonExclusionList: IDictionaryNode[] = [];
  servicesList: IDictionaryNode[] = [];

  // eslint-disable-next-line max-lines-per-function
  get filters(): IFilters[] {
    return [
      {
        text: 'Номер включения в реестр',
        placeholder: 'Введите полный номер включения в реестр',
        value: 'registration_number',
        type: 'text',
      },
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
        text: 'Номер свидетельства о регистрации опасных производственных объектов',
        placeholder: 'Введите полный номер свидетельства',
        value: 'hazardous_object_number',
        type: 'text',
      },
      {
        columns: [
          {
            text: '',
            value: 'hazardous_object_doc_date_start',
            placeholder: 'Укажите дату',
            type: 'datepicker',
            limitTo: 'hazardous_object_doc_date_end',
          },
          {
            text: '',
            value: 'hazardous_object_doc_date_end',
            placeholder: 'Укажите дату',
            type: 'datepicker',
            limitFrom: 'hazardous_object_doc_date_start',
          },
        ],
        label: 'Дата свидетельства о регистрации опасных производственных объектов',
        range: true,
      },
      {
        text: 'Номер договора страхования гражданской ответственности организации',
        placeholder: 'Введите полный номер договора',
        value: 'insurance_policy_number',
        type: 'text',
      },
      {
        columns: [
          {
            text: '',
            value: 'insurance_policy_doc_date_start',
            placeholder: 'Укажите дату',
            type: 'datepicker',
            limitTo: 'insurance_policy_doc_date_end',
          },
          {
            text: '',
            value: 'insurance_policy_doc_date_end',
            placeholder: 'Укажите дату',
            type: 'datepicker',
            limitFrom: 'insurance_policy_doc_date_start',
          },
        ],
        title: 'Дата договора страхования гражданской ответственности организации',
        label: 'Дата начала действия договора',
        range: true,
      },
      {
        columns: [
          {
            text: '',
            value: 'insurance_policy_validity_date_start',
            placeholder: 'Укажите дату',
            type: 'datepicker',
            limitTo: 'insurance_policy_validity_date_end',
          },
          {
            text: '',
            value: 'insurance_policy_validity_date_end',
            placeholder: 'Укажите дату',
            type: 'datepicker',
            limitFrom: 'insurance_policy_validity_date_start',
          },
        ],
        label: 'Дата окончания действия договора',
        range: true,
      },
      {
        columns: [
          {
            text: 'Мин',
            placeholder: 'Введите мин мощность',
            value: 'common_capacity_min',
            type: 'text',
          },
          {
            text: 'Макс',
            placeholder: 'Введите макс мощность',
            value: 'common_capacity_max',
            type: 'text',
          },
        ],
        label: 'Общая мощность',
      },
      {
        text: 'Аккредитация испытательной лаборатории',
        placeholder: 'Выберите аккредитацию',
        value: 'accreditation',
        type: 'select',
        list: accreditationList,
      },
      {
        text: 'Предоставляемые услуги',
        placeholder: 'Выберите услугу',
        value: 'services',
        type: 'autocomplete',
        list: this.servicesListValue,
        searchCallback: this.getServicesList,
      },
      {
        text: 'Иные услуги',
        placeholder: 'Введите услугу',
        value: 'other_services',
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
        type: 'autocomplete',
        list: this.reasonExclusionListValue,
        searchCallback: this.getReasonExclusionList,
      },
      {
        text: 'Все записи',
        value: 'actual',
        type: 'checkbox',
      },
    ];
  }

  async created() {
    this.initSettings();
    const data = await this.$store.dispatch('user/getInfo');
    this.$store.commit('auth/setUserInfo', data);
    this.getRoleSubjectUser();
    this.getOpfList();
  }

  mounted() {
    this.getRegionList();
    this.getReasonExclusionList();
    this.getServicesList();
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

  get servicesListValue() {
    return this.servicesList.map((item) => {
      return {
        name: item.name,
        id: item.name,
      };
    });
  }

  get settings(): { [key: string]: { [key: string]: any } } {
    return JSON.parse(localStorage.getItem(SETTINGS_KEY) || '{}');
  }

  initSettings() {
    const { registerOrganizations } = this.settings;
    this.applyHeaders = registerOrganizations?.columns;
  }

  get sortMap() {
    return {
      name: ['subject.short_name', 'subject.name'],
      subject_type: 'subject.subject_type',
      region: 'subject.address.oker.name_okato',
      opf_name: 'subject.opf.name',
      'subject.inn_kpp': ['subject.inn', 'subject.kpp'],
      'exclusion_reason.name': 'exclusion_reason',
      services: 'elevator_info.all_services',
      common_capacity: 'elevator_info.common_capacity',
      laboratory: 'elevator_info.testing_laboratory',
    };
  }

  get printingFields() {
    return (this.applyHeaders || []).reduce((result, { value }) => {
      if (['check', 'actions'].includes(value)) {
        return result;
      }

      const res = FieldsMap.get(value) || value;

      return Array.isArray(res) ? [...result, ...res] : [...result, res];
    }, []);
  }

  subjectType(item: string) {
    return subjectType.get(item);
  }

  getLaboratoryStatus({ elevator_info: { testing_laboratory } }) {
    if (testing_laboratory === 'EURASIA') {
      return 'Испытательная лаборатория включена в единый реестр органов по оценке соответствия Евразийского экономического союза';
    }

    if (testing_laboratory === 'NATIONAL') {
      return 'Испытательная лаборатория аккредитована в национальной системе аккредитации';
    }

    return 'Нет';
  }

  sortColumn(e) {
    this.filter = {
      ...this.filter,
      sort: getSort(e, this.filter, this.sortMap),
    };
    this.updateInformation();
  }

  closeCard() {
    this.isShowCard = false;
  }

  getRoleSubjectUser() {
    this.$store.state.auth.user.roles.reverse().find((item: any) => {
      if (item.authority === 'ROLE_ADMIN' || item.authority === 'ROLE_GOVERMENT_USER') {
        this.isSubjectUser = false;
      } else {
        this.isSubjectUser = true;
      }
    });
    return this.isSubjectUser;
  }

  async showCard(item: any) {
    this.isLoading = true;
    const data = await this.$store.dispatch('elevator/getInfoElevatorForCardOrganization', item.elevator_id);
    this.information = data;
    this.isShowCard = true;
    this.isLoading = false;
  }

  async onShowAllList() {
    //ToDo: Дождаться исправления на бэке.
    this.showActual = !this.showActual;
    this.pageable.pageNumber = 0;
    this.updateInformation();
  }

  async handleSearch(filter) {
    this.filter.filter = filter;
    this.pageable.pageNumber = 0;
    this.updateInformation();
  }

  getExportFilter() {
    const { filter, sort } = this.filter;
    return {
      ...this.formFilters,
      filter,
      actual: this.showActual,
      pageable: { ...this.pageable, sort },
      printing_fields: this.printingFields,
    };
  }

  onOptionsChange(data) {
    this.selectOrganization = null;
    this.checked = false;
    this.statusButton = {};
    if (data.page !== undefined) {
      this.pageable.pageNumber = data.page + 1;
      this.pageable.pageSize = data.size;
    }
    this.updateInformation();
  }

  onApplySettings(data) {
    this.applyHeaders = data.columns;
    this.filter.sort = data.sort;
    this.updateInformation();
  }

  exportPdf(item: any) {
    this.isLoading = true;
    this.$store.dispatch('elevator/getStatement', item.elevator_id);
    this.isLoading = false;
  }

  onApplyFilters(data) {
    this.formFilters = data;
    this.showActual = !data.actual;
    this.updateInformation();
  }

  async updateInformation() {
    const { filter, sort } = this.filter;
    this.isLoading = true;
    const { content, totalElements } = await this.$store.dispatch('organization/getList', {
      filter,
      ...this.formFilters,
      actual: this.showActual,
      pageable: { ...this.pageable, sort },
      printing_fields: this.printingFields,
    });
    this.total = totalElements;
    this.rows = content;
    this.isLoading = false;
    this.searchQuery = filter || '';
  }

  showActionModal(info) {
    this.isShowActionModal = true;
    this.actionType = 'exclude';
    this.selectOrganization = info;
  }

  closeModal() {
    this.isShowActionModal = false;
    this.statusButton = {};
    this.selectOrganization = null;
    this.checked = false;

    this.pageable.pageNumber = 0;
  }

  closeSettingsModal() {
    this.onOpenSettings = !this.onOpenSettings;
  }

  resetStatusButton() {
    this.statusButton = {};
    this.checked = false;
    this.updateInformation();
  }

  async getOpfList(): Promise<void> {
    const { content } = await this.$store.dispatch('sdiz/getOPF');
    this.opfList = content;
  }

  async getRegionList(): Promise<void> {
    const { data } = await this.$service.catalogs.getRegion();
    this.regionList = data;
  }

  async getReasonExclusionList(): Promise<void> {
    const { data } = await this.$service.catalogs.getReasonExclusionElevator();
    this.reasonExclusionList = data;
  }

  async getServicesList(): Promise<void> {
    const { data } = await this.$service.catalogs.getServicesType();
    this.servicesList = data;
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.checkbox {
  cursor: pointer;
  width: 16px;
  height: 16px;
  position: relative;

  [type='checkbox'] {
    position: absolute;
    opacity: 0;
  }

  &__icon {
    align-items: center;
    justify-content: center;
    background: $check-bg;
    display: flex;
    height: 16px;
    width: 16px;
    border: 1px solid $input-border-color;
    border-radius: 4px;

    img {
      width: 9px;
      display: block;
      opacity: 0;
    }
  }

  [type='checkbox']:checked {
    & + .checkbox__icon {
      background: $gold-light-color;
      border-color: $gold-light-color;

      img {
        opacity: 1;
      }
    }
  }
}

.title {
  font-style: normal;
  font-weight: 500;
  font-size: 24px;
  line-height: 24px;
  color: $black-color;

  @include respond-to('medium') {
    font-size: 22px;
  }

  @include respond-to('small') {
    font-size: 18px;
  }
}

.search-wrap {
  flex-basis: 380px;

  &::v-deep .search__input {
    width: auto;
    flex-grow: 1;
  }
}

.settings {
  margin: 20px 0;
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  gap: 12px;
}

.actionButton {
  align-items: center;
  display: flex;
}

.settingsSpan {
  background: none;
  border: none;
  display: flex;
  align-items: center;
  text-decoration-line: underline;
  font-size: 14px;
  line-height: 16px;
  color: $medium-grey-color !important;
  cursor: pointer;
  text-align: left;
  margin: 0 4px;

  &--disabled {
    opacity: 0.5;
    cursor: default;
  }
}

.iconSettings {
  margin-right: 5px;
}

.iconTable {
  width: 16px;
  height: 16px;
  margin-left: 3px;
  cursor: pointer;
}

.block-header {
  display: flex;
  align-items: center;
}

.checkbox-elem {
  display: flex;
  margin-left: 16px;
}

.processor {
  padding-left: 40px;
}

.checkbox-title {
  font-size: 14px;
  margin-left: 4px;
  color: #828286;
}

.search-cell {
  &__query {
    background-color: $highlight-bg;
  }
}
</style>
