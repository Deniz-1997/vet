<template>
  <div class="approvalTemplates">
    <div v-if="!isShow">
      <div class="title">
        <span>Шаблоны рассмотрения заявлений</span>
      </div>

      <div v-if="canFilterRegister" class="search-block">
        <SearchComponent placeholder="Введите значение поиска" @search="handleSearch" />
        <v-col cols="12" md="4">
          <span class="span-list checkbox-elem">
            <span class="checkbox-filter">
              <label class="checkbox">
                <input v-model="showAllList" type="checkbox" @click="onShowAllList" />
                <span class="checkbox__icon">
                  <img src="/icons/checkbox.svg" />
                </span>
              </label>
            </span>
            <span class="checkbox-title"> Отображать все записи</span>
          </span>
        </v-col>
      </div>

      <div class="settings">
        <span v-if="canCreateTemplate" class="settingsSpan" @click="showCard('0')">
          <img src="/icons/add.svg" class="iconSettings" />
          Добавить
        </span>

        <button
          v-if="canEnableTemplate"
          class="settingsSpan"
          :class="{ 'settingsSpan--disabled': !isCanActivated }"
          :disabled="!isCanActivated"
          @click="() => (isProcessActivated = true)"
        >
          <span>
            <img src="/icons/confirm.svg" class="iconTable" />
            <span>Активировать шаблон</span>
          </span>
        </button>
      </div>

      <div class="options"></div>

      <DataTable
        :headers="headers"
        :items="rows"
        :items-length="total"
        :page="pageable.pageNumber"
        :per-page="pageable.pageSize"
        :search="searchValue"
        @handleSort="(e) => sortColumn(e)"
        @onOptionsChange="onOptionsChange"
      >
        <template #[`item.check`]="{ item }">
          <checkbox-component
            :id="item.id"
            :value="item.id === form.id"
            name="exclude"
            @change="(v) => handleCheck(v, item)"
          />
        </template>
        <template #[`item.actions`]="{ item }">
          <v-tooltip bottom>
            <template #activator="{ on, attrs }">
              <span v-bind="attrs" v-on="on">
                <img src="/icons/show.svg" class="iconTable" @click="showCard(item.id)" />
              </span>
            </template>
            <span>Просмотреть шаблон</span>
          </v-tooltip>
          <template v-if="canDeleteTemplate && item.status.id === 3">
            <!-- <img
              src="/icons/edit.svg"
              class="iconTable"
            > -->
            <v-tooltip bottom>
              <template #activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on">
                  <img src="/icons/deleteBasket.svg" class="iconTable" @click="confirmModal(item.id)" />
                </span>
              </template>
              <span>Удалить шаблон</span>
            </v-tooltip>
          </template>
        </template>
      </DataTable>
    </div>
    <AuthoritiesCardTemplate
      v-else
      v-model="information"
      :is-view="isView"
      @close="closeCard"
      @create="handleSubmitRequest"
    />
    <Dialog-component
      v-model="isProcessActivated"
      :prompt="false"
      cancel-title=""
      confirm-title=""
      with-close-icon
      controls-justify="justify-end"
      @close="closeModal"
    >
      <template #title>
        <span>Активировать шаблон «{{ selectTemplateForActivate.name }}»?</span>
      </template>

      <template #content>
        <v-row>
          <v-col cols="12">
            <UiDateInput
              v-model="form.start_date"
              class="datePicker"
              :format="'DD.MM.YYYY'"
              label="Действует с"
              title-format="MMMM YYYY"
            />
          </v-col>
        </v-row>

        <v-row justify="end">
          <div class="buttons">
            <DefaultButton class="cancel" title="Отменить" @click="closeModal" />
            <DefaultButton title="Активировать" variant="primary" :disabled="isLoading" @click="applyTemplate" />
          </div>
        </v-row>
      </template>
    </Dialog-component>
    <ConfirmModalDelete
      :show-modal="showConfirmModal"
      :text="`Вы действительно хотите удалить шаблон №${selectTemplate}`"
      @close="closeConfirmModal"
      @apply="handleDelete"
    />
    <v-overlay :value="isLoading" :absolute="true">
      <v-progress-circular indeterminate size="64" />
    </v-overlay>
  </div>
</template>

<script lang="ts">
// Рефакторинг обязателен!
import { Component, Vue } from 'vue-property-decorator';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import SearchComponent from '@/components/common/Search/Search.vue';
import AuthoritiesCardTemplate from '@/views/ApprovalTemplates/components/CardTemplate/CardTemplate.vue';
import ConfirmModalDelete from '@/views/Authorities/components/Modal/ConfirmModalDelete.vue';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import { mapAccessFlags, EAction } from '@/utils';

const defaultColumns = [
  {
    text: 'Действия',
    value: 'actions',
  },
  {
    text: 'Наименование',
    value: 'name',
  },
  {
    text: 'Объект рассмотрения',
    value: 'type.name',
  },
  {
    text: 'Действует с',
    value: 'start_date',
  },
  {
    text: 'Действует по',
    value: 'end_date',
  },
  {
    text: 'Статус',
    value: 'status.name',
  },
];

@Component({
  name: 'approval-templates',
  components: {
    AuthoritiesCardTemplate,
    DataTable,
    SearchComponent,
    ConfirmModalDelete,
    CheckboxComponent,
    DialogComponent,
    DefaultButton,
  },
  computed: {
    ...mapAccessFlags({
      canReadRegister: EAction.READ_APPROVAL_TEMPLATE_REGISTER,
      canFilterRegister: EAction.FILTER_APPROVAL_TEMPLATE_REGISTER,
      canCreateTemplate: EAction.CREATE_APPROVAL_TEMPLATE,
      canEnableTemplate: EAction.ENABLE_APPROVAL_TEMPLATE,
      canDeleteTemplate: EAction.DELETE_APPROVAL_TEMPLATE,
    }),
  },
})
export default class AuthoritiesApprovalTemplates extends Vue {
  readonly canReadRegister!: boolean;
  readonly canFilterRegister!: boolean;
  readonly canCreateTemplate!: boolean;
  readonly canEnableTemplate!: boolean;
  readonly canDeleteTemplate!: boolean;

  isShow = false;
  showConfirmModal = false;
  isLoading = false;
  information: any = {};
  isView = false;
  selectTemplate: number | null = null;
  showAllList = false;
  showActual = true;
  filter = '';
  rows: any[] = [];
  pageable: any = {
    pageSize: 5,
    pageNumber: 0,
    sort: [{ direction: 'ASC', property: 'name' }],
  };
  searchValue = '';
  total = 0;
  selectTemplateForActivate: any = {};
  isProcessActivated = false;
  isCanActivated = false;
  form: any = { id: undefined };

  get headers() {
    const result = [...defaultColumns];

    if (this.canEnableTemplate || this.canDeleteTemplate) {
      return [
        {
          text: '',
          value: 'check',
        },
        ...result,
      ]
    }

    return result;
  };

  mounted() {
    this.getTemplates();
  }

  onShowAllList() {
    this.showActual = !this.showActual;
    this.pageable.pageNumber = 0;
    this.getTemplates();
  }

  onOptionsChange(data) {
    this.isCanActivated = false;
    if (data.page !== undefined) {
      this.pageable.pageNumber = data.page + 1;
      this.pageable.pageSize = data.size;
    }
    this.getTemplates();
  }

  handleCheck(v, item) {
    if (v) {
      if (item.status.id !== 1 && item.status.id !== 2) {
        this.isCanActivated = true;
        this.selectTemplateForActivate = item;
      } else {
        this.isCanActivated = false;
      }
      this.form.id = item.id;
    } else {
      this.form.id = null;
      this.selectTemplateForActivate = null;
      this.isCanActivated = false;
    }
  }

  closeModal() {
    this.isProcessActivated = false;
  }

  getSort(e, filter) {
    if (e.sortBy[0]) {
      return [
        {
          direction: filter.sort && filter.sort[0].direction === 'ASC' ? 'DESC' : 'ASC',
          property:  e.sortBy[0],
        }
      ];
    }

    if (!!filter?.sort?.length) {
      return filter.sort;
    }

    return [];
  }

  sortColumn(e) {
    this.pageable = {sort: this.getSort(e, this.pageable)}
    this.getTemplates();
  }

  async applyTemplate() {
    this.isLoading = true;
    try {
      await this.$store.dispatch('templateApproval/activetedTemplate', this.form);
      this.getTemplates();
      this.form.id = null;
      this.selectTemplateForActivate = null;
      this.isCanActivated = false;
      this.isProcessActivated = false;
      this.isLoading = false;
    } catch (_err) {
      this.isLoading = false;
    }
  }

  async handleSearch(value: string) {
    this.searchValue = value;
    this.pageable.pageNumber = 0;
    this.getTemplates(value);
  }

  async getTemplates(value?: string) {
    if (this.canReadRegister) {
      this.isLoading = true;
      const data = await this.$store.dispatch('templateApproval/getList', {
        filter: value ? value : '',
        pageable: { ...this.pageable },
        actual: this.showActual,
      });
      this.total = data.totalElements;
      this.isLoading = false;
      this.rows = data.content;
    }
  }

  async showCard(id: string | null) {
    if (id && id !== '0') {
      const data = await this.$store.dispatch('templateApproval/getInfoTemplate', id);
      this.information = data ? data : {};
      this.isShow = true;
      this.isView = true;
    } else {
      this.information = {};
      this.isView = false;
      this.isShow = true;
    }
  }

  confirmModal(id: number) {
    this.selectTemplate = id;
    this.showConfirmModal = true;
  }

  closeConfirmModal() {
    this.showConfirmModal = false;
  }

  async handleDelete() {
    await this.$store.dispatch('templateApproval/removeTemplate', this.selectTemplate);
    await this.getTemplates();
    this.rows = this.rows.filter((item: any) => item.id !== this.selectTemplate);
    this.showConfirmModal = false;
  }

  async handleSubmitRequest() {
    await this.$store.dispatch('templateApproval/createTemplate', this.information);
    this.closeCard();
    this.getTemplates();
  }

  closeCard() {
    this.isShow = false;
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

.settings {
  margin: 20px 0;
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
}

.search-block {
  display: flex;
  flex-direction: row;
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

.checkbox-elem {
  display: flex;
  padding-top: 24px;
  padding-left: 12px;
}

.checkbox-title {
  font-size: 14px;
  padding-bottom: 2px;
  margin-left: 4px;
  color: $medium-grey-color;
}

.iconTable {
  width: 16px;
  height: 16px;
  margin-left: 3px;
  cursor: pointer;
}

.iconSettings {
  margin-right: 5px;
}

.search {
  margin-top: 20px;
  display: flex;
  flex-direction: row;
  justify-content: flex-start;

  @include respond-to('small') {
    margin-top: 10px;
  }
}
</style>
