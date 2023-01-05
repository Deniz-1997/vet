<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <div class="title">
          <span>Журнал действий пользователя</span>
        </div>
      </v-col>
      <v-col cols="12" md="4">
        <SearchComponent placeholder="Введите значение поиска" @search="handleSearch" />
      </v-col>

      <v-col cols="12">
        <div class="settings">
          <span class="settingsSpan" @click="onOpenSettings = true">
            <img src="/icons/settings.svg" class="iconSettings" />
            Настроить вид
          </span>
        </div>
      </v-col>
      <v-col cols="12">
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
            <v-tooltip bottom>
              <template #activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on">
                  <img src="/icons/show.svg" class="iconTable" @click="showModal(item, 'show')" />
                </span>
              </template>
              <span>Просмотреть информацию</span>
            </v-tooltip>
          </template>
          <template #[`item.action_result`]="{ item }">
            <span v-if="item.action_result" class="processor"> успешно </span>
            <span v-if="!item.action_result" class="processor"> не успешно </span>
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
        <span>Просмотр события</span>
      </template>

      <template #content>
        <LoggingCard v-if="onOpenAddModal" :item="showItem" :is-show="typeAction === 'show'" @close="closeModal" />
      </template>
    </DialogComponent>

    <ViewSettingsModal
      id="logging"
      v-model="onOpenSettings"
      :headers="headers"
      :sort-map="sortMap"
      @close="closeSettingsModal"
      @apply-settings="onApplySettings"
    />
    <v-overlay :value="isLoading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import SearchComponent from '@/components/common/Search/Search.vue';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import LoggingCard from '@/views/Administration/LoggingLog/components/LoggingCard.vue';
import ViewSettingsModal, { TFilter } from '@/components/common/ViewSettings/ViewSettingsModal.vue';

import { SETTINGS_KEY } from '@/components/common/ViewSettings/consts';

const defaultPageable = {
  pageSize: 5,
  pageNumber: 0,
};

@Component({
  name: 'logging-list',
  components: {
    SearchComponent,
    DataTable,
    DialogComponent,
    LoggingCard,
    ViewSettingsModal,
  },
})
export default class LoggingList extends Vue {
  rows: any = [];
  isLoading = true;
  showItem = {};
  onOpenAddModal = false;
  onOpenSettings = false;
  pageable = defaultPageable;

  applyHeaders: any[] = [];
  searchResult = '';
  typeAction = 'view';
  total = 0;
  filter: Partial<TFilter> = {};

  headers = [
    {
      text: 'Действия',
      value: 'actions',
    },
    {
      text: '№',
      value: 'action_log_id',
    },
    {
      text: 'Фамилия',
      value: 'user.last_name',
    },
    {
      text: 'Имя',
      value: 'user.first_name',
    },
    {
      text: 'Отчество',
      value: 'user.second_name',
    },
    {
      text: 'Организация',
      value: 'subject.subject_data.name',
      width: 200,
    },
    {
      text: 'Логин',
      value: 'login',
    },
    {
      text: 'Действие пользователя',
      value: 'user_action.name',
    },
    {
      text: 'Результат действия',
      value: 'action_result',
    },
    {
      text: 'Бизнес область',
      value: 'business_area.name',
    },
    {
      text: 'Дата и время',
      value: 'log_date',
    },
  ];

  get sortMap() {
    return {
      'user.last_name': 'full_name',
    };
  }

  mounted() {
    this.initSettings();
  }

  get settings(): { [key: string]: { [key: string]: any } } {
    return JSON.parse(localStorage.getItem(SETTINGS_KEY) || '{}');
  }

  initSettings() {
    const { logging } = this.settings;
    this.applyHeaders = logging.columns;
  }

  async getLoggingList() {
    const { filter, sort } = this.filter;
    const { content, totalElements } = await this.$store.dispatch('activityLog/getList', {
      filter,
      pageable: {
        ...this.pageable,
        sort,
      },
    });
    this.total = totalElements;
    this.rows = content;
    this.isLoading = false;
  }

  onApplySettings(data) {
    this.applyHeaders = data.columns;
    this.filter.sort = data.sort;
    this.getLoggingList();
  }

  sortColumn(e) {
    this.filter = {
      ...this.filter,
      sort: [
        {
          direction: this.filter.sort && this.filter.sort[0].direction === 'ASC' ? 'DESC' : 'ASC',
          property: !this.sortMap ? e.sortBy[0] : this.sortMap[e.sortBy[0]] ? this.sortMap[e.sortBy[0]] : e.sortBy[0],
        },
      ],
    };
    this.getLoggingList();
  }

  onOptionsChange(data) {
    if (data.page !== undefined) {
      this.pageable.pageNumber = data.page + 1;
      this.pageable.pageSize = data.size;
      this.getLoggingList();
    }
  }

  async handleSearch(filter: string) {
    this.filter.filter = filter;
    this.pageable.pageNumber = 0;
    this.getLoggingList();
  }

  showModal(item: string, typeAction: string) {
    this.onOpenAddModal = true;
    this.showItem = item;
    this.typeAction = typeAction;
  }

  closeModal() {
    this.onOpenAddModal = false;
    this.showItem = '';
    this.getLoggingList();
  }

  closeSettingsModal() {
    this.onOpenSettings = !this.onOpenSettings;
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
