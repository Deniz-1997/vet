<template>
  <v-container :class="$style.notifications">
    <v-row>
      <v-col cols="12" md="10" xl="9">
        <div class="title mb-4">
          <span>Уведомления</span>
        </div>
        <div v-if="canFilterRegister" class="block-header mb-3">
          <div class="d-flex">
            <SearchComponent
              class="justify-start"
              placeholder="Введите значение поиска"
              @search="(filter) => onChangeFilter({ filter, pageable: { pageNumber: 0 } })"
            />
            <UiCheckbox
              v-if="canFilterRegister"
              id="only-new"
              :value="filter.actual"
              class="notifications__only-new"
              name="only-new"
              label="Отображать прочитанные"
              @input="(actual) => onChangeFilter({ pageable: { pageNumber: 0 }, actual: !actual })"
            />
          </div>
        </div>
        <div class="d-flex justify-space-between mb-3">
          <UiViewSettingsModal
            v-if="canCustomizeRegister"
            id="notifications"
            v-model="filter"
            class="notifications__settings"
            @apply-settings="fetchList"
          />
        </div>

        <DataTable
          :headers="filter.columns"
          :items="list"
          :items-length="total"
          :item-class="paintRow"
          :page="filter.pageable.pageNumber"
          :per-page="filter.pageable.pageSize"
          :search="filter.filter"
          is-disable-sort
          @onOptionsChange="({ page, size }) => onChangeFilter({ pageable: { pageNumber: page + 1, pageSize: size } })"
        >
          <template v-if="canReadCard" #[`item.actions`]="{ item }">
            <div class="d-flex justify-space-between align-center">
              <v-tooltip v-if="item.object.type === 'VIOLATION'" bottom>
                <template #activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on">
                    <div class="notifications__item mr-2" @click="pickedItem = item">
                      <img src="/icons/show.svg" alt="Просмотреть запись" class="iconTable" />
                    </div>
                  </span>
                </template>

                <span>Просмотреть информацию</span>
              </v-tooltip>
              <v-tooltip v-else-if="isNew(item.status.code) && item.object && item.object.id" bottom>
                <template #activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on">
                    <a id="export" class="settingsSpan" @click="exportAction(item)">
                      <img src="/icons/export-grey.svg" alt="Скачать файл" class="iconTable" />
                    </a>
                  </span>
                </template>

                <span>Скачать файл</span>
              </v-tooltip>
              <v-tooltip v-if="isNew(item.status.code)" bottom>
                <template #activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on">
                    <div class="notifications__item notifications__item_check" @click="markAsRead(item.id)">
                      <v-icon small class="iconTable pb-1">mdi-check</v-icon>
                    </div>
                  </span>
                </template>

                <span>Пометить прочитанным</span>
              </v-tooltip>
            </div>
          </template>
          <template #[`item.created`]="{ item }">
            {{ date(item.created, { outputFormat: 'DD.MM.YYYY' }) }}
          </template>
        </DataTable>
      </v-col>
    </v-row>

    <NotificationPageCardModal v-model="isCardModalShow" :item="pickedItem" />

    <v-overlay :value="isLoading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import merge from 'lodash/merge';

import { date } from '@/utils/global/filters';
import { NotificationItem } from '@/services/mappers/notification';
import { TInnerFilter } from '@/services/models/common';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import SearchComponent from '@/components/common/Search/Search.vue';
import NotificationPageCardModal from '@/components/NotificationPage/NotificationPageCardModal.vue';
import { EAction, mapAccessFlags } from '@/utils';
import { ENotificationStatus } from '@/services/enums/notification';
import { showReport } from '@/utils/file';

@Component({
  name: 'NotificationPage',
  components: {
    DataTable,
    SearchComponent,
    NotificationPageCardModal,
  },
  computed: {
    ...mapAccessFlags({
      canReadRegister() {
        return [EAction.VIEW_NOTIFICATION_REGISTRY, EAction.VIEW_NOTIFICATION_USER_REGISTRY].some((action) =>
          this.$store.getters['auth/check'](action)
        );
      },
      canFilterRegister: EAction.FILTER_NOTIFICATION_REGISTRY,
      canCustomizeRegister: EAction.CUSTOMIZE_NOTIFICATION_REGISTRY,
      canReadCard: EAction.VIEW_NOTIFICATION,
    }),
  },
})
export default class NotificationPage extends Vue {
  readonly canReadRegister!: boolean;
  readonly canFilterRegister!: boolean;
  readonly canCustomizeRegister!: boolean;
  readonly canReadCard!: boolean;

  isLoading = true;
  list: NotificationItem[] = [];
  total = 0;
  filter: TInnerFilter = {
    filter: '',
    pageable: {
      pageSize: 5,
      pageNumber: 0,
      sort: [
        { direction: 'ASC', property: 'status.name' },
        { direction: 'DESC', property: 'start_date' },
      ],
    },
    actual: true,
    columns: [
      {
        value: 'actions',
        text: 'Действия',
        width: 32,
        sortable: false,
      },
      {
        value: 'status.name',
        text: 'Статус',
        sortAs: 'status.name',
        sortable: false,
      },
      {
        value: 'message',
        text: 'Сообщение',
        sortAs: 'message',
        sortable: false,
      },
      {
        value: 'object.name',
        text: 'Тип',
        sortAs: 'type.name',
        sortable: false,
      },
      {
        value: 'subject.name',
        text: 'Организация',
        sortAs: 'subject.name',
        sortable: false,
      },
      {
        value: 'created',
        text: 'Дата',
        width: 180,
        sortAs: 'start_date',
        sortable: false,
      },
    ],
  };
  pickedItem = null;

  get isCardModalShow() {
    return !!this.pickedItem;
  }

  set isCardModalShow(v) {
    if (!v) {
      this.pickedItem = null;
    }
  }

  onChangeFilter(filter: TInnerFilter) {
    this.filter = merge(this.filter, filter);
    this.fetchList();
  }

  date = date;

  async fetchList() {
    try {
      if (this.canReadRegister) {
        this.isLoading = true;
        const { data, filter } = await this.$service.notification.find(this.filter);
        this.list = data;
        this.total = filter?.total ?? this.list.length;
      }
      this.isLoading = false;
    } catch (err) {
      this.isLoading = false;
      throw err;
    }
  }

  async markAsRead(id) {
    this.isLoading = true;
    await this.$service.notification.markAsRead(id);
    this.fetchList();
  }

  isNew(code) {
    return code === ENotificationStatus.NEW;
  }

  paintRow({ status }) {
    if (status.code === ENotificationStatus.READ) {
      return 'notifications__table-row_read';
    }

    return '';
  }

  async exportAction({ id, object }) {
    this.isLoading = true;
    await showReport({
      path: `/api/elevator-request/file/${object.id}`,
      method: 'get',
      filter: {},
    });
    await this.markAsRead(id);
    this.isLoading = false;
  }
}
</script>

<style lang="scss" module>
@import '@/assets/styles/_variables';

.notifications {
  :global(.notifications__table-row_read) {
    opacity: 0.5;

    &:hover {
      opacity: 0.7;
    }
  }
}
</style>
