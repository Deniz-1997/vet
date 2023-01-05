<template>
  <div class="applicationsApproval">
    <div v-if="!isShowCard">
      <div class="title">
        <span>Рассмотрение</span>
      </div>
      <div v-if="canFilterRegister" class="block-header">
        <div class="search">
          <SearchComponent v-model="filter.filter" placeholder="Введите значение поиска" @search="handleSearch" />
        </div>
      </div>
      <div class="settings">
        <span v-if="canCustomizeRegister" class="settingsSpan" @click="onOpenSettings = true">
          <img src="/icons/settings.svg" class="iconSettings" />
          Настроить вид
        </span>
        <!-- TODO: Ожидает задачи по доработке асинхронного экспорта -->
        <!-- <span v-if="canExportRegister" class="settingsSpan" @click="exportAction">
          <img src="/icons/export.svg" class="iconSettings" />
          Экспорт списка
        </span> -->
        <span class="settingsSpan" @click="onOpenFilter = true">Фильтры</span>
      </div>

      <ViewSettingsModal
        id="applications-approval"
        v-model="onOpenSettings"
        :headers="headers"
        @close="closeSettingsModal"
        @apply-settings="onApplySettings"
      />

      <ApplicationApprovalFilter
        id="filter"
        v-model="onOpenFilter"
        :select-status="statusTask"
        @close="closeFilterModal"
        @apply-filters="onApplyFilter"
      />

      <DataTable
        :headers="applyHeaders"
        :items="rows"
        :item-class="paintRow"
        :items-length="total"
        :page="pageable.pageNumber"
        :per-page="pageable.pageSize"
        :search="filter.filter"
        @handleSort="(e) => sortColumn(e)"
        @onOptionsChange="onOptionsChange"
      >
        <template #[`item.actions`]="{ item }">
          <v-tooltip v-if="canReadTask" bottom>
            <template #activator="{ on, attrs }">
              <span v-bind="attrs" v-on="on">
                <router-link
                  :to="{
                    name: 'card-requests-tasks',
                    params: { id: item.approval_task_id, type: 'view' },
                  }"
                >
                  <img src="/icons/show.svg" class="iconTable" />
                </router-link>
              </span>
            </template>
            <span>Просмотреть заявление</span>
          </v-tooltip>
        </template>
        <template #[`item.note`]="{ item }">
          <span v-if="item.reject_reason && item.reject_reason.name">{{ item.reject_reason.name }}</span>
          <div v-if="item.note">{{ item.note }}</div>
        </template>
      </DataTable>
    </div>

    <AuthoritiesCardRequest v-if="!!isShowCard" is-showcase :form="form" @click-close="closeCard" />
    <v-overlay :value="isLoading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </div>
</template>

<script lang="ts">
import { Component, Mixins, Prop } from 'vue-property-decorator';
import AuthoritiesCardRequest from '@/views/Requests/components/CardRequest/CardRequest.vue';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import SearchComponent from '@/components/common/Search/Search.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import ViewSettingsModal, { TFilter } from '@/components/common/ViewSettings/ViewSettingsModal.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import ApplicationApprovalFilter from '@/components/ApplicationsApproval/Filter.vue';
import { mapAccessFlags, EAction } from '@/utils';
import Exportable from '@/utils/global/mixins/exportable';

import { SETTINGS_KEY } from '@/components/common/ViewSettings/consts';

const defaultPageable = {
  pageSize: 5,
  pageNumber: 0,
};

@Component({
  name: 'authorities-applications-approval',
  components: {
    AuthoritiesCardRequest,
    DataTable,
    SearchComponent,
    DefaultButton,
    ViewSettingsModal,
    DialogComponent,
    ApplicationApprovalFilter,
  },
  computed: {
    ...mapAccessFlags({
      canReadRegister() {
        return [EAction.READ_TASK_REGISTER, EAction.READ_TASK_REGISTER_DIVISION].some((action) =>
          this.$store.getters['auth/check'](action)
        );
      },
      canFilterRegister: EAction.FILTER_TASK_REGISTER,
      canCustomizeRegister: EAction.CUSTOMIZE_TASK_REGISTER,
      canExportRegister: EAction.EXPORT_TASK_REGISTER,
      canReadTask: EAction.READ_TASK,
      canApproveTask: EAction.APPROVE_TASK,
      canRejectTask: EAction.REJECT_TASK,
    }),
  },
})
export default class AuthoritiesApplicationsApproval extends Mixins(Exportable('/api/approval-task/exportReport')) {
  @Prop({ type: String, default: '' }) readonly search!: string;
  readonly canReadRegister!: boolean;
  readonly canFilterRegister!: boolean;
  readonly canCustomizeRegister!: boolean;
  readonly canExportRegister!: boolean;
  readonly canReadTask!: boolean;
  readonly canApproveTask!: boolean;
  readonly canRejectTask!: boolean;

  typeId: number | null = null;
  requesrequestIdtsId: number | null = null;
  requestId: number | null = null;
  rows: any[] = [];
  form: any = {};
  isShowCard = false;
  isLoading = false;
  onOpenSettings = false;
  onOpenFilter = false;
  statusTask: any = { text: 'Ожидает решения', value: 5 };
  pageable: any = defaultPageable;
  total = 0;
  applyHeaders: any = [];

  filter: Partial<TFilter> = {};

  headers = [
    // {
    //     label: '',
    //     field: 'icon',
    // },
    {
      text: 'Действия',
      value: 'actions',
      sortable: false,
    },
    {
      text: 'Номер заявления',
      value: 'request_id',
    },
    {
      text: 'Организация, подавшая заявление',
      value: 'subject.name',
    },
    {
      text: 'Объект рассмотрения',
      value: 'approval_request_type.name',
    },
    {
      text: 'Подразделение',
      value: 'subject_division.name',
    },
    {
      text: 'Дата и время создания задачи',
      value: 'start_date',
    },
    {
      text: 'Дата и время решения',
      value: 'end_date',
    },
    {
      text: 'Статус',
      value: 'status.name',
      sortable: false,
    },
    {
      text: 'Комментарий',
      value: 'note',
      sortable: false,
    },
  ];

  isNotResponsibleOnly = true;

  created() {
    this.filter.filter = this.search;
  }

  mounted() {
    // отображаем notify, если переход на форму после созласования заявления
    if (this.$route.params.showNotifyMsg) {
      this.$service.notify.push('message', { text: this.$route.params.showNotifyMsg });
    }

    this.initSettings();
  }

  get settings(): { [key: string]: { [key: string]: any } } {
    return JSON.parse(localStorage.getItem(SETTINGS_KEY) || '{}');
  }

  initSettings() {
    const { applicationsApproval } = this.settings;
    this.applyHeaders = applicationsApproval.columns;
  }

  closeCard() {
    this.isShowCard = false;
  }

  async showCard(id) {
    if (id) {
      this.form = {};
      this.form = await this.$store.dispatch('elevator/getInfoElevator', id);
    } else {
      this.form = {};
    }
    this.isShowCard = true;
  }

  onApplySettings(data) {
    this.applyHeaders = data.columns;
    this.filter.sort = data.sort;
    this.getList();
  }

  closeSettingsModal() {
    this.onOpenSettings = false;
  }

  closeFilterModal() {
    this.onOpenFilter = false;
  }

  onApplyFilter(data) {
    this.statusTask = { ...data };
    this.getList();
    this.onOpenFilter = false;
  }

  onOptionsChange(data) {
    if (data.page !== undefined) {
      this.pageable.pageNumber = data.page + 1;
      this.pageable.pageSize = data.size;
      this.getList();
    }
  }

  sortColumn(e) {
    this.filter = {
      ...this.filter,
      sort: [
        {
          direction: this.filter.sort && this.filter.sort[0].direction === 'ASC' ? 'DESC' : 'ASC',
          property: e.sortBy[0],
        },
      ],
    };
    this.getList();
  }

  async getList() {
    if (this.canReadRegister) {
      const { filter, sort } = this.filter;
      this.isLoading = true;
      const data = await this.$store.dispatch('approvalTask/getListTask', {
        filter,
        status: { code: this.statusTask.value },
        pageable: { ...this.pageable, sort },
        actual: this.isNotResponsibleOnly,
      });
      this.total = data.totalElements;
      this.rows = data.content;
      this.isLoading = false;
    }
  }

  async handleSearch(value: string) {
    this.filter.filter = value;
    this.$emit('search', value);
    this.pageable.pageNumber = 0;
    this.getList();
  }

  getExportFilter() {
    const { filter, sort } = this.filter;

    return {
      filter,
      status: { code: this.statusTask.value },
      actual: this.isNotResponsibleOnly,
      pageable: { ...this.pageable, sort },
    };
  }

  paintRow(item) {
    if (item.status.id === 1 || item.status.id === 5) {
      return 'approve-item';
    } else if (item.status.id === 3) {
      return 'error-item';
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.checkbox-elem {
  display: flex;
  padding-top: 16px;
}

.checkbox-filter {
  padding-top: 2px;
}

.checkbox-title {
  font-size: 14px;
  margin-left: 4px;
  color: #828286;
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

.search {
  margin-top: 10px;
  display: flex;
  flex-direction: row;
  justify-content: flex-start;

  @include respond-to('small') {
    margin-top: 10px;
  }
}

.inputSearch {
  border: 1px solid $input-border-color;
  border-radius: 3px;
  border-radius: 3px;
  border-radius: 3px;
  background: $white-color !important;
  outline: none;
  width: 420px;
  height: 40px;
  color: $black-color;
  font-size: 20px;
  padding: 0 40px 0 10px;

  @include respond-to('medium') {
    width: 380px;
    height: 30px;
    font-size: 16px;
  }

  @include respond-to('small') {
    width: 310px;
    height: 25px;
    font-size: 14px;
  }
}

::placeholder {
  color: $input-border-color;
  font-size: 16px;

  @include respond-to('medium') {
    font-size: 16px;
  }

  @include respond-to('small') {
    font-size: 14px;
  }
}

.icon {
  margin-left: -35px;
  margin-top: 2px;
  cursor: pointer;

  @include respond-to('medium') {
    margin-left: -30px;
  }
}

.settings {
  margin: 20px 0;
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
}

.settingsSpan {
  background: none;
  border: none;
  margin-right: 14px;
  display: flex;
  align-items: center;
  text-decoration-line: underline;
  font-size: 14px;
  line-height: 16px;
  color: $medium-grey-color !important;
  cursor: pointer;
  text-align: left;

  &--disabled {
    opacity: 0.5;
    cursor: default;
  }
}

.iconSettings {
  margin-right: 5px;
}

.block-header {
  display: flex;
}

.iconTable {
  width: 16px;
  height: 16px;
  margin-left: 3px;
  cursor: pointer;

  &.noActive {
    cursor: not-allowed;
  }
}
</style>
