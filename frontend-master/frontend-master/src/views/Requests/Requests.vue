<template>
  <div class="requests">
    <div v-if="!isShowCard">
      <div class="title">
        <span>Заявления</span>
      </div>
      <div class="search-block">
        <SearchComponent placeholder="Введите значение поиска" @search="handleSearch" />
      </div>
      <div class="settings">
        <span class="settingsSpan" @click="onOpenSettings = true">
          <img src="/icons/settings.svg" class="iconSettings" />
          Настроить вид
        </span>
        <!-- TODO: Ожидает задачи по доработке асинхронного экспорта -->
        <!-- <span v-if="canExportRegister" class="settingsSpan" @click="exportAction">
          <img src="/icons/export.svg" class="iconSettings" />
          Экспорт списка
        </span> -->

        <router-link
          v-if="isCanAdd && (canCreateRequest || canCreateChangeRequest)"
          class="settingsSpan"
          to="requests/create"
          name="addRequest"
        >
          <img src="/icons/add.svg" class="iconTable" />
          Добавить
        </router-link>
      </div>

      <ViewSettingsModal
        id="requests"
        v-model="onOpenSettings"
        :headers="headers"
        :sort-map="{}"
        :default-sorting="{ property: 'request_id', direction: 'DESC' }"
        @close="closeSettingsModal"
        @apply-settings="onApplySettings"
      />

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
        <template #[`item.actions`]="{ item }">
          <v-tooltip v-if="canReadRequest || canReadChangeRequest" bottom>
            <template #activator="{ on, attrs }">
              <span v-bind="attrs" v-on="on">
                <router-link
                  :to="{
                    name: 'card-requests',
                    params: { id: item.request_id, type: 'view' },
                  }"
                >
                  <img src="/icons/show.svg" class="iconTable" />
                </router-link>
              </span>
            </template>
            <span>Просмотреть информацию</span>
          </v-tooltip>
          <template v-if="item.request_status.id === 1">
            <v-tooltip v-if="canEditRequest || canEditChangeRequest" bottom>
              <template #activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on">
                  <router-link
                    :to="{
                      name: 'card-requests',
                      params: { id: item.request_id, type: 'edit' },
                    }"
                  >
                    <img src="/icons/edit.svg" class="iconTable" />
                  </router-link>
                </span>
              </template>
              <span>Редактировать информацию</span>
            </v-tooltip>
            <v-tooltip v-if="canDeleteRequest || canDeleteChangeRequest" bottom>
              <template #activator="{ on, attrs }">
                <img
                  src="/icons/deleteBasket.svg"
                  class="iconTable"
                  v-bind="attrs"
                  @click="showConfirmDeleteModal(item.request_id)"
                  v-on="on"
                />
              </template>
              <span>Удалить черновик</span>
            </v-tooltip>
          </template>
        </template>
        <template #[`item.reject_reason.name`]="{ item }">
          <span>
            {{ item.reject_reason && item.reject_reason.name }}
            <br v-if="item.reject_reason && item.notes" />
            {{ item.notes }}
          </span>
        </template>
      </DataTable>
    </div>

    <ConfirmModalDelete
      :show-modal="showConfirmModal"
      :text="'Вы действительно хотите удалить заявление'"
      :name="`№${selectRequest}`"
      @close="closeConfirmModal"
      @apply="handleDeleteDraft"
    />
    <v-overlay :value="isLoading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>

    <router-view />
  </div>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import ConfirmModalDelete from '@/views/Authorities/components/Modal/ConfirmModalDelete.vue';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import SearchComponent from '@/components/common/Search/Search.vue';
import ViewSettingsModal, { TFilter } from '@/components/common/ViewSettings/ViewSettingsModal.vue';
import { mapAccessFlags, EAction } from '@/utils';
import Exportable from '@/utils/global/mixins/exportable';

import { SETTINGS_KEY } from '@/components/common/ViewSettings/consts';

const defaultPageable = {
  pageSize: 5,
  pageNumber: 0,
};

@Component({
  name: 'requests',
  components: {
    ConfirmModalDelete,
    DataTable,
    SearchComponent,
    ViewSettingsModal,
  },
  computed: {
    ...mapAccessFlags({
      canCreateRequest: EAction.CREATE_REQUEST,
      canReadRequest: EAction.READ_REQUEST,
      canExport: EAction.EXPORT_REQUEST_REGISTER,
      canEditRequest: EAction.UPDATE_REQUEST,
      canDeleteRequest: EAction.DELETE_REQUEST,
      canCreateChangeRequest: EAction.CREATE_CHANGE_REQUEST,
      canEditChangeRequest: EAction.UPDATE_CHANGE_REQUEST,
      canDeleteChangeRequest: EAction.DELETE_CHANGE_REQUEST,
      canReadChangeRequest: EAction.READ_CHANGE_REQUEST,
    }),
  },
})
export default class AuthoritiesRequests extends Mixins(Exportable('/api/elevator-request/exportReport')) {
  readonly canCreateRequest!: boolean;
  readonly canReadRequest!: boolean;
  readonly canExport!: boolean;
  readonly canEditRequest!: boolean;
  readonly canDeleteRequest!: boolean;
  readonly canCreateChangeRequest!: boolean;
  readonly canEditChangeRequest!: boolean;
  readonly canDeleteChangeRequest!: boolean;
  readonly canReadChangeRequest!: boolean;

  isLoading = false;
  isShowCard = false; //Отвечает за отображение карточки
  isError = false;
  showConfirmModal = false;
  onOpenSettings = false;
  pageable: any = defaultPageable;
  total = 0;
  isTypeView = 'view';
  filter: Partial<TFilter> = {};
  selectRequest: number | null = null;

  form: any = {
    approval_request_type: {
      name: '',
      code: null,
    },
    elevator_register_application: {
      basis_changes: '',
    },
    elevator_info: {
      elevator_info_insurance: [],
      elevator_info_service: [],
      elevator_info_processing: [],
      elevator_info_product: [],
    },
    subject: {
      address: '',
      address_text: '',
      inn: '',
      kpp: '',
      name: '',
      nza: '',
      ogrn: '',
      opf: { code: '', name: '' },
      opf_name: '',
      short_name: '',
    },
    elevator_site: [],
  };
  isCanAdd = false;
  rows: any[] = [];

  applyHeaders: any = [];
  headers: any[] = [
    {
      text: 'Действия',
      value: 'actions',
    },
    {
      text: 'Объект согласования',
      value: 'approval_request_type.name',
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
      text: 'Дата и время подачи заявления',
      value: 'dispatch_date',
    },
    {
      text: 'Дата и время рассмотрения',
      value: 'approval_date',
    },
    {
      text: 'Статус',
      value: 'request_status.name',
    },
    {
      text: 'Причина отказа',
      value: 'reject_reason.name',
    },
  ];

  get subjectId() {
    const userInfo = this.$store.getters['auth/getUserInfo'];
    return userInfo.subject?.subject_id;
  }

  mounted() {
    this.initSettings();
  }

  get settings(): { [key: string]: { [key: string]: any } } {
    return JSON.parse(localStorage.getItem(SETTINGS_KEY) || '{}');
  }

  initSettings() {
    const { requests } = this.settings;
    this.applyHeaders = requests.columns;
  }

  async checkDraft() {
    const { totalElements } = await this.$store.dispatch('elevator/getListRequests', {
      subject_id: this.subjectId,
      actual: true,
      request_status: {
        // Идентификатор статуса черновика
        id: 1,
      },
    });

    if (totalElements > 0) {
      return (this.isCanAdd = false);
    } else {
      this.isCanAdd = true;
    }
  }

  async getListRequests() {
    const { filter, sort } = this.filter;
    this.isLoading = true;
    const { content, totalElements } = await this.$store.dispatch('elevator/getListRequests', {
      filter,
      actual: true,
      pageable: { ...this.pageable, sort },
    });
    this.total = totalElements;
    this.rows = content;

    this.checkDraft();
    this.isLoading = false;
  }

  onOptionsChange(data) {
    const { page, size } = data;
    const { pageNumber, pageSize } = this.pageable;
    if (page !== undefined && (page + 1 !== pageNumber || size !== pageSize)) {
      this.pageable.pageNumber = page + 1;
      this.pageable.pageSize = size;
      this.getListRequests();
    }
  }

  closeSettingsModal() {
    this.onOpenSettings = !this.onOpenSettings;
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
    this.getListRequests();
  }

  onApplySettings(data) {
    this.applyHeaders = data.columns;
    this.filter.sort = data.sort;
    this.getListRequests();
  }

  showConfirmDeleteModal(id: number) {
    this.selectRequest = id;
    this.showConfirmModal = true;
  }

  async handleDeleteDraft() {
    await this.$store.dispatch('elevator/deleteDraft', this.selectRequest);
    this.pageable.page = 0;
    await this.getListRequests();
    this.showConfirmModal = false;
  }

  handleSearch(value) {
    this.filter.filter = value;
    this.pageable.pageNumber = 0;
    this.getListRequests();
  }

  getExportFilter() {
    const { filter, sort } = this.filter;
    return {
      filter,
      actual: true,
      pageable: { ...this.pageable, sort },
    };
  }

  closeConfirmModal() {
    this.showConfirmModal = false;
  }

  closeCard() {
    this.isShowCard = false;
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

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

.search-block {
  margin-top: 10px;
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
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

.iconTable {
  width: 16px;
  height: 16px;
  margin-right: 3px;
  cursor: pointer;

  &.noActive {
    cursor: not-allowed;
  }
}
</style>
