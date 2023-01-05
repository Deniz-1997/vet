<template>
  <v-container :class="$style['approval-request-logs']">
    <v-row>
      <v-col cols="12">
        <div class="title mb-4">
          <span>Журнал согласования заявлений</span>
        </div>
        <div v-if="canFilterRegister" class="block-header mb-3">
          <div class="d-flex">
            <SearchComponent
              class="justify-start"
              placeholder="Введите значение поиска"
              @search="(filter) => onChangeFilter({ filter, pageable: { pageNumber: 0 } })"
            />
            <!-- TODO: Пока убираем. Если появится обработка на беке, вернуть. <UiCheckbox
              id="only-new"
              :value="filter.actual"
              class="approval-request-logs__only-new"
              name="only-new"
              label="Отображать все записи"
              @input="(actual) => onChangeFilter({ pageable: { pageNumber: 0 }, actual: !actual })"
            /> -->
          </div>
        </div>
        <div class="d-flex justify-space-between mb-3">
          <UiViewSettingsModal
            v-show="canCustomizeRegister"
            id="approval-request-logs"
            v-model="filter"
            class="approval-request-logs__settings"
            @apply-settings="fetchList"
          />
        </div>

        <DataTable
          :headers="filter.columns"
          :items="list"
          :items-length="total"
          :page="filter.pageable.pageNumber"
          :per-page="filter.pageable.pageSize"
          :search="filter.filter"
          :filter="filter"
          multi-sort
          @onOptionsChange="
            ({ page, size, sort }) => onChangeFilter({ pageable: { pageNumber: page + 1, pageSize: size, sort } })
          "
        >
          <template #[`item.actions`]="{ item }">
            <v-tooltip bottom>
              <template #activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on">
                  <img src="/icons/show.svg" class="iconTable" @click="showModal(item.recordNumber)" />
                </span>
              </template>
              <span>Просмотреть информацию</span>
            </v-tooltip>
          </template>
          <template #[`item.dispatchDate`]="{ item }">
            {{ date(item.dispatchDate, { outputFormat: 'DD.MM.YYYY' }) }}
          </template>
          <template #[`item.approvalDate`]="{ item }">
            {{ date(item.approvalDate, { outputFormat: 'DD.MM.YYYY' }) }}
          </template>
          <template #[`item.expectedDate`]="{ item }">
            {{ date(item.expectedDate, { outputFormat: 'DD.MM.YYYY' }) }}
          </template>
        </DataTable>
      </v-col>
    </v-row>

    <ApprovalRequestLogCardModal v-model="isModalShow.card" :info="card" />

    <v-overlay :value="isLoading" absolute>
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import merge from 'lodash/merge';

import { date } from '@/utils/global/filters';
import { TInnerFilter, TMapperPlain } from '@/services/models/common';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import SearchComponent from '@/components/common/Search/Search.vue';
import ApprovalRequestLogCardModal from '@/components/ApprovalRequestLog/ApprovalRequestLogCardModal.vue';
import { ApprovalRequestLogItem } from '@/services/mappers/approvalRequestLog';
import { EAction, mapAccessFlags } from '@/utils';
import { TApprovalRequestInnerFilter } from '@/services/models/approvalRequestLog';
import mergeWith from 'lodash/mergeWith';

@Component({
  name: 'ApprovalRequestLogPage',
  components: {
    DataTable,
    SearchComponent,
    ApprovalRequestLogCardModal,
  },
  computed: {
    ...mapAccessFlags({
      canReadRegister: EAction.READ_APPROVAL_REQUEST_LOG_REGISTRY,
      canFilterRegister: EAction.FILTER_APPROVAL_REQUEST_LOG_REGISTRY,
      canCustomizeRegister: EAction.CUSTOMIZE_APPROVAL_REQUEST_LOG_REGISTRY,
    }),
  },
})
export default class ApprovalRequestLogPage extends Vue {
  readonly canReadRegister!: boolean;
  readonly canFilterRegister!: boolean;
  readonly canCustomizeRegister!: boolean;

  isModalShow = {
    card: false,
  };
  card: TMapperPlain<ApprovalRequestLogItem> | null = null;
  isLoading = true;
  list: TMapperPlain<ApprovalRequestLogItem>[] = [];
  total = 0;
  filter: TApprovalRequestInnerFilter = {
    filter: '',
    excludedStatuses: [1],
    pageable: {
      pageSize: 5,
      pageNumber: 0,
      sort: [
        { direction: 'ASC', property: 'request.request_status.name' },
        { direction: 'DESC', property: 'event_date' },
      ],
    },
    actual: true,
    columns: [
      {
        text: 'Действия',
        value: 'actions',
      },
      {
        value: 'recordNumber',
        text: '№',
        sortAs: 'id',
      },
      {
        value: 'requestNumber',
        text: 'Номер заявления',
        sortAs: 'request_id',
      },
      {
        value: 'requestType',
        text: 'Тип',
        width: 300,
        sortAs: 'request.approval_request_type.name',
      },
      {
        value: 'username',
        text: 'Пользователь',
        sortAs: 'request.created_by',
      },
      {
        value: 'subjectName',
        text: 'Организация',
        width: 200,
        sortAs: 'request.subject.short_name',
      },
      {
        value: 'dispatchDate',
        text: 'Начало согласования',
        sortAs: 'event_date',
      },
      {
        value: 'assignee',
        text: 'Ответственный',
        sortAs: 'changed_by',
      },
      {
        value: 'stage',
        text: 'Этап согласования',
        sortAs: 'approval_task.stage.name',
        sortable: false,
        width: 100,
      },
      {
        value: 'status',
        text: 'Статус',
        sortAs: 'request.request_status.name',
      },
      {
        value: 'division',
        text: 'Подразделение',
        sortAs: 'approval_task.subject_division.name',
      },
      {
        value: 'approvalDate',
        text: 'Дата принятия решения',
        sortAs: 'event_date',
      },
      {
        value: 'expectedDate',
        text: 'Плановая дата принятия решения',
        sortAs: 'approval_task.decision_date_plan',
      },
      {
        value: 'notes',
        text: 'Замечания',
        sortAs: 'notes',
        sortable: false,
      },
    ],
  };

  onChangeFilter(filter: TInnerFilter) {
    this.filter = mergeWith(this.filter, filter, (_, from) => {
      if (Array.isArray(from)) {
        return from;
      }
    });
    this.fetchList();
  }

  date = date;

  async fetchList() {
    try {
      if (this.canReadRegister) {
        this.isLoading = true;
        const { data, filter } = await this.$service.approvalRequestLog.find(this.filter);
        this.list = data;
        this.total = filter?.total ?? this.list.length;
      }
      this.isLoading = false;
    } catch (err) {
      this.isLoading = false;
      throw err;
    }
  }

  async showModal(id: number) {
    this.card = null;
    this.isLoading = true;
    const { data } = await this.$service.approvalRequestLog.findOne(id);
    this.isLoading = false;
    this.isModalShow.card = true;
    this.card = data;
  }
}
</script>

<style lang="scss" module>
@import '@/assets/styles/_variables';
</style>
