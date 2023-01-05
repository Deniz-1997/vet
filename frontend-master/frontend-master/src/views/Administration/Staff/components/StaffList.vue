<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <div class="title">
          <span>Реестр пользователей</span>
        </div>
      </v-col>
      <v-col v-if="canFilterRegister" cols="12" md="4">
        <SearchComponent placeholder="Введите значение поиска" @search="handleSearch" />
      </v-col>
      <v-col v-if="canFilterRegister" cols="12" md="3">
        <UiCheckbox v-model="filter.actual" name="show-all" label="Отображать все записи" @input="getStaffList()" />
      </v-col>

      <v-col cols="12">
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
          <span v-if="canAddUser" id="addUser" class="settingsSpan" @click="showModal(null, 'add')">
            <img src="/icons/add.svg" class="iconSettings" />
            Добавить
          </span>
          <button
            v-if="canActivate"
            class="settingsSpan"
            :disabled="!canActivatePickedUser"
            :class="{ 'settingsSpan--disabled': !canActivatePickedUser }"
            @click="onActiveUser"
          >
            <img src="/icons/agree_status.svg" class="iconSettings" />
            <span>Разблокировать</span>
          </button>
          <button
            v-if="canDeactivate"
            class="settingsSpan"
            :disabled="!canDeactivatePickedUser"
            :class="{ 'settingsSpan--disabled': !canDeactivatePickedUser }"
            @click="onDeactiveUser"
          >
            <img src="/icons/reject.svg" class="iconSettings" />
            <span>Заблокировать</span>
          </button>
        </div>
      </v-col>

      <v-col cols="12">
        <DataTable
          :headers="headers"
          :items="rows"
          :items-length="totalRows"
          :page="pageable.pageNumber"
          :per-page="pageable.pageSize"
          :search="filter.filter"
          @handleSort="(e) => sortColumn(e)"
          @onOptionsChange="onOptionsChange"
        >
          <template v-if="canDeactivate || canActivate" #[`item.check`]="{ item }">
            <span class="spanList">
              <label :for="item.id" class="checkbox">
                <input
                  :id="item.user_id"
                  :checked="userId && userId.userId === item.user_id"
                  type="checkbox"
                  :name="item.id"
                  :value="item.user_id"
                  @change="handleCheck(item.user_id, item.status.code)"
                />
                <span class="checkbox__icon">
                  <img src="/icons/checkbox.svg" />
                </span>
              </label>
            </span>
          </template>
          <template #[`item.actions`]="{ item }">
            <v-tooltip bottom>
              <template #activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on">
                  <img src="/icons/show.svg" class="iconTable" @click="showModal(item.user_id, 'show')" />
                </span>
              </template>
              <span>Просмотреть информацию</span>
            </v-tooltip>
            <v-tooltip v-if="canEditUser && item.status_id !== 3" bottom>
              <template #activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on">
                  <img
                    src="/icons/edit.svg"
                    class="iconTable"
                    :disabled="item.canEdit"
                    @click="showModal(item.user_id, 'edit')"
                  />
                </span>
              </template>
              <span>Редактировать информацию</span>
            </v-tooltip>
            <v-tooltip v-if="canDeleteUser && item.status_id !== 3" bottom>
              <template #activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on">
                  <img src="/icons/deleteBasket.svg" class="iconTable" @click="deleteUser(item.user_id)" />
                </span>
              </template>
              <span>Удалить информацию</span>
            </v-tooltip>
          </template>
          <template #[`item.status`]="{ item }">
            <span v-if="item.status === 1" class="processor"> активен </span>
            <span v-else class="processor"> не активен </span>
          </template>
        </DataTable>
      </v-col>
    </v-row>
    <DialogComponent
      v-model="onOpenAddModal"
      :prompt="false"
      cancel-title=""
      confirm-title=""
      width="800"
      with-close-icon
      controls-justify="justify-end"
    >
      <template #title>
        <span v-if="typeAction === 'show' || typeAction === 'edit'">Данные пользователя</span>
        <span v-else-if="typeAction === 'add'">Добавление пользователя</span>
      </template>

      <template #content>
        <StaffCard
          v-if="onOpenAddModal"
          :card="editCard"
          :is-show="typeAction === 'show'"
          :is-loading="isLoading"
          :options="options"
          @close="closeModal"
          @saveUser="saveUser"
          @activeUser="activeUser"
        />
      </template>
    </DialogComponent>
    <ViewSettingsModal
      id="staff"
      v-model="onOpenSettings"
      :headers="headers"
      :sort-map="sortMap"
      :default-sorting="{ property: 'full_name', direction: 'ASC' }"
      @close="closeSettingsModal"
      @apply-settings="onApplySettings"
    />
    <Dialog-component
      v-model="onShowModalTempPassword"
      :prompt="false"
      cancel-title=""
      confirm-title=""
      width="600"
      with-close-icon
      controls-justify="justify-end"
    >
      <template #title>
        <span>Временный пароль</span>
      </template>

      <template #content>
        <TempPasswordModal v-if="onShowModalTempPassword" :value="tempPassword" @close="closeTempPasswordModal" />
      </template>
    </Dialog-component>
    <v-overlay :value="isLoading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import isEqual from 'lodash/isEqual';
import omit from 'lodash/omit';
import sortBy from 'lodash/sortBy';
import xor from 'lodash/xor';
import { Component, Mixins } from 'vue-property-decorator';
import SearchComponent from '@/components/common/Search/Search.vue';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import StaffCard from '@/views/Administration/Staff/components/StaffCard.vue';
import ViewSettingsModal, { TFilter } from '@/components/common/ViewSettings/ViewSettingsModal.vue';
import TempPasswordModal from '@/views/Administration/Staff/components/TempPasswordModal.vue';
import { mapAccessFlags, EAction } from '@/utils';
import { getSort } from '@/utils/getSort';
import Exportable from '@/utils/global/mixins/exportable';

import { SETTINGS_KEY } from '@/components/common/ViewSettings/consts';

const defaultPageable = {
  pageSize: 5,
  pageNumber: 0,
};

enum UserStatus {
  ACTIVE = 1,
  BLOCKED,
}

@Component({
  name: 'laboratories-list',
  components: {
    TempPasswordModal,
    SearchComponent,
    DataTable,
    DialogComponent,
    StaffCard,
    ViewSettingsModal,
  },
  computed: {
    ...mapAccessFlags({
      canFilterRegister: EAction.FILTER_USER_REGISTRY,
      canCustomizeRegister: EAction.CUSTOMIZE_USER_REGISTRY,
      canExportRegister: EAction.EXPORT_USER_REGISTRY,
      canAddUser: EAction.CREATE_USER_ACCOUNT,
      canEditUser: EAction.UPDATE_USER_ACCOUNT,
      canDeleteUser: EAction.DELETE_USER_ACCOUNT,
      canDeactivate: EAction.DEACTIVATE_USER_ACCOUNT,
      canActivate: EAction.ACTIVATE_USER_ACCOUNT,
    }),
  },
})
export default class StaffList extends Mixins(Exportable('/api/security/user/exportReport')) {
  readonly canFilterRegister!: boolean;
  readonly canCustomizeRegister!: boolean;
  readonly canExportRegister!: boolean;
  readonly canAddUser!: boolean;
  readonly canEditUser!: boolean;
  readonly canDeactivate!: boolean;
  readonly canActivate!: boolean;
  statusButton = false;
  selectLaboratories: any = [];
  rows: any = [];
  isLoading = false;
  editCard: any = null;
  isConfirmModalShow = true;
  isShowActionModal = false;
  onOpenAddModal = false;
  pageable = defaultPageable;
  onOpenSettings = false;
  total = 0;
  typeAction = 'view';
  totalRows = 0;
  searchResult = '';
  tempPassword = '';
  onShowModalTempPassword = false;
  userId: any = null;
  filter: Partial<TFilter> & { actual?: boolean } = { actual: false };
  options = {
    roles: [],
    organizations: [],
  };
  headers = [
    {
      text: '',
      value: 'check',
      sortable: false,
    },
    {
      text: 'Действия',
      value: 'actions',
      sortable: false,
    },
    {
      text: 'ФИО',
      value: 'full_name',
    },
    {
      text: 'ID пользователя',
      value: 'user_id',
    },
    {
      text: 'Логин',
      value: 'login',
    },
    {
      text: 'Организация',
      value: 'subject_names',
    },
    {
      text: 'Статус пользователя',
      value: 'status.name',
    },
  ];

  get canActivatePickedUser() {
    return this.userId?.status === UserStatus.BLOCKED;
  }

  get canDeactivatePickedUser() {
    return this.userId?.status === UserStatus.ACTIVE;
  }

  get editingUser() {
    return this.editCard || {};
  }

  async created() {
    // await Promise.all([this.fetchListOrganizationApproval(), this.fetchRoles()]);
    await Promise.all([this.fetchRoles()]);
  }

  mounted() {
    this.initSettings();
  }

  onApplySettings(data) {
    this.headers = data.columns;
    this.filter.sort = data.sort;
    this.getStaffList();
  }

  get settings(): { [key: string]: { [key: string]: any } } {
    return JSON.parse(localStorage.getItem(SETTINGS_KEY) || '{}');
  }

  initSettings() {
    const { staff } = this.settings;
    this.headers = staff.columns;
  }

  // async fetchListOrganizationApproval() {
  //   const { data } = await this.$axios.post('/api/subject/subjects', { actual: true });

  //   this.options.organizations = data.content;
  // }

  get sortMap() {
    return {
      subject_names: 'subject.name',
    };
  }

  async fetchRoles() {
    const roles = await this.$store.dispatch('userRoles/getUserRolesList');
    const availableForAssign = await this.$store.dispatch('userRoles/getForAssign');

    this.options.roles = roles.map((item) => ({
      ...item,
      disabled: !availableForAssign.some(({ role_id }) => role_id === item.role_id),
    }));
    this.options.roles = sortBy(this.options.roles, ['disabled']);
  }

  async getStaffList() {
    this.isLoading = true;
    const { filter, sort, actual } = this.filter;
    const { content, totalElements } = await this.$store.dispatch('staff/getStaffList', {
      filter,
      actual: !actual,
      pageable: {
        ...this.pageable,
        sort,
      },
    });
    this.totalRows = totalElements;
    this.rows = content;
    this.isLoading = false;
  }

  async handleSearch(filter: string) {
    this.filter.filter = filter;
    this.filter.pageNumber = 0;
    this.pageable.pageNumber = 0;
    this.getStaffList();
  }

  async showModal(id: string, typeAction: string) {
    this.isLoading = true;
    this.editCard = null;
    if (id) {
      const data = await this.$store.dispatch('staff/showInfoStaff', { id });
      this.editCard = data;
    }
    this.typeAction = typeAction;
    this.onOpenAddModal = true;
    this.isLoading = false;
  }

  async createUser(form, subjects) {
    const { temp_password, ...data } = await this.$store.dispatch('staff/createStaff', form);
    if (temp_password) {
      this.tempPassword = temp_password;
      this.onShowModalTempPassword = true;
    }

    const mergeSubjects = (to: any[] = [], from: any[] = []) =>
      [...to, ...from.filter(({ subject_id }) => !to.find((item) => subject_id === item.subject_id))].map((item) => ({
        ...item,
        role: [
          ...item.role,
          ...(from.find(({ subject_id }) => subject_id === item.subject_id)?.role ?? []).filter(
            ({ role_id }) => !item.role.find((role) => role.role_id === role_id)
          ),
        ],
      }));

    const user = {
      ...data,
      subject: mergeSubjects(data.subject, form.subject),
    };
    return this.updateRoles(user, subjects);
  }

  updateRoles(form, subjects: any[] = []) {
    const rolesStack = form.subject.map((subject, index) => {
      const isNewSubject = subject.role && !subjects[index];
      const isRolesChanged = !isEqual(subject.role, subjects[index]?.role);

      if (isNewSubject || isRolesChanged) {
        return this.$store.dispatch('userRoles/assignRole', {
          role_ids: (subject.role || []).map((role) => role.role_id),
          user_id: form.user_id,
          subject_id: subject.subject_id,
        });
      }

      return Promise.resolve();
    });
    return rolesStack.length ? Promise.allSettled(rolesStack) : Promise.resolve();
  }

  getSubjectIdList(subjects = []) {
    return subjects.map(({ subject_id }) => subject_id);
  }

  async editUser(form, subjects) {
    const check = {
      user: omit(this.editingUser, 'subject', 'subject_names'),
      form: omit(form, 'subject', 'subject_names'),
    };
    const isSubjectsChanged = !!xor(
      this.getSubjectIdList(this.editingUser.subject),
      this.getSubjectIdList(form.subject)
    ).length;

    if (!isEqual(check.user, check.form) || isSubjectsChanged) {
      await this.$store.dispatch('staff/updateStaff', form);
    }

    return this.updateRoles(form, subjects);
  }

  async saveUser(form, subjects) {
    this.isLoading = true;
    try {
      if (!this.editCard) {
        await this.createUser(form, subjects);
      } else if (!isEqual(this.editingUser, form)) {
        await this.editUser(form, subjects);
      }
      this.isLoading = false;
      this.closeModal();
      this.getStaffList();
    } catch (_error) {
      this.isLoading = false;
    }
  }

  getExportFilter() {
    const { filter, sort } = this.filter;
    return {
      filter,
      pageable: {
        ...this.pageable,
        sort,
      },
    };
  }

  sortColumn(e) {
    this.filter = {
      ...this.filter,
      sort: getSort(e, this.filter, this.sortMap),
    };
    this.getStaffList();
  }

  onOptionsChange(v) {
    this.pageable.pageNumber = v.page + 1;
    this.pageable.pageSize = v.size;
    this.getStaffList();
  }

  closeModal(user?: any) {
    this.onOpenAddModal = false;
    this.editCard = null;
    this.userId = null;

    if (user) {
      this.rows = this.rows.map((item) => {
        return item.user_id === user.user_id ? user : item;
      });
    }
  }

  closeSettingsModal() {
    this.onOpenSettings = !this.onOpenSettings;
  }

  closeTempPasswordModal() {
    this.onShowModalTempPassword = false;
  }

  activeUser() {
    this.closeModal();
    this.getStaffList();
  }

  async onActiveUser() {
    await this.$store.dispatch('staff/activation', {
      id: this.userId?.userId,
    });
    this.getStaffList();
    this.userId = null;
  }

  async onDeactiveUser() {
    await this.$store.dispatch('staff/deactivation', {
      id: this.userId?.userId,
    });
    this.getStaffList();
    this.userId = null;
  }

  handleCheck(userId, status) {
    if (this.userId?.userId === userId) {
      this.userId = null;
    } else {
      this.userId = { userId, status: Number(status) };
    }
  }

  deleteUser(id: number) {
    this.$service.notify.push('message', {
      text: '',
      params: {
        type: 'confirm-modal',
        title: 'Подтвердите удаление',
        text: 'Вы действительно хотите удалить этого пользователя?',
        buttons: [
          { type: 'decline' },
          {
            callback: async () => this.closeModal((await this.$axios.post('/api/security/user/delete', { id })).data),
          },
        ],
      },
    });
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.settings {
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
