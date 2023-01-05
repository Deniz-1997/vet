<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <div class="title mb-4">
          <span>Журнал информационного взаимодействия</span>
        </div>
        <div v-if="canFilterRegister" class="block-header mb-3">
          <div class="d-flex">
            <SearchComponent
              class="justify-start"
              placeholder="Введите значение поиска"
              @search="(filter) => onChangeFilter({ filter, pageable: { pageNumber: 0 } })"
            />
          </div>
        </div>
        <div class="d-flex mb-3">
          <UiViewSettingsModal
            v-if="canCustomizeRegister"
            id="interaction-log"
            v-model="filter"
            @apply-settings="fetchList"
          />
          <!-- TODO: Ожидает задачи по доработке асинхронного экспорта -->
          <!-- <span v-if="canExportRegister" class="settingsSpan" @click="exportAction">
            <img src="/icons/export.svg" class="iconSettings" />
            Экспорт списка
          </span> -->
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
                  <img alt="" class="iconTable" src="/icons/show.svg" @click="showDetails(item)" />
                </span>
              </template>
              <span>Просмотреть информацию</span>
            </v-tooltip>
          </template>
          <template #[`item.startDate`]="{ item }">
            {{ date(item.startDate, { outputFormat: 'DD.MM.YYYY HH:mm' }) }}
          </template>
          <template #[`item.endDate`]="{ item }">
            {{ date(item.endDate, { outputFormat: 'DD.MM.YYYY HH:mm' }) }}
          </template>
        </DataTable>
      </v-col>
    </v-row>
    <InteractionCardModal v-model="isModalShow.card" :details="details" />

    <v-overlay :value="isLoading" absolute>
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import omit from 'lodash/omit';

import { date } from '@/utils/global/filters';
import { TInnerFilter, TMapperPlain } from '@/services/models/common';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import SearchComponent from '@/components/common/Search/Search.vue';
import { EAction, mapAccessFlags } from '@/utils';
import { InteractionItem } from '@/services/mappers/interaction';
import InteractionCardModal from '@/components/InteractionLog/InteractionCardModal.vue';
import mergeWith from 'lodash/mergeWith';
import Exportable from '@/utils/global/mixins/exportable';

@Component({
  name: 'InteractionLogPage',
  components: {
    DataTable,
    SearchComponent,
    InteractionCardModal,
  },
  computed: {
    ...mapAccessFlags({
      canReadRegister: EAction.READ_INTERACTION_LOG_REGISTER,
      canFilterRegister: EAction.FILTER_INTERACTION_LOG_REGISTER,
      canCustomizeRegister: EAction.CUSTOMIZE_INTERACTION_LOG_REGISTER,
      canExportRegister: EAction.EXPORT_INTERACTION_LOG_REGISTER,
    }),
  },
})
export default class InteractionLogPage extends Mixins(Exportable('/api/security/log/interaction/export')) {
  readonly canReadRegister!: boolean;
  readonly canFilterRegister!: boolean;
  readonly canCustomizeRegister!: boolean;
  readonly canImport!: boolean;

  card: TMapperPlain<InteractionItem> | null = null;
  details = null;
  isLoading = true;
  list: TMapperPlain<InteractionItem>[] = [];
  total = 0;
  filter: TInnerFilter = {
    filter: '',
    pageable: {
      pageSize: 5,
      pageNumber: 0,
      sort: [{ direction: 'DESC', property: 'start_date' }],
    },
    actual: false,
    columns: [
      {
        value: 'actions',
        text: 'Действия',
      },
      {
        value: 'startDate',
        text: 'Дата и время начала',
        sortAs: 'start_date',
      },
      {
        value: 'endDate',
        text: 'Дата и время завершения',
        sortAs: 'end_date',
      },
      {
        value: 'initiator',
        text: 'Инициатор взаимодействия',
        sortAs: 'initiator',
      },
      {
        value: 'participant',
        text: 'Участник взаимодействия',
        sortAs: 'type.name',
      },
      {
        value: 'messageType',
        text: 'Тип сообщения',
        sortAs: 'message_type.name',
      },
      {
        value: 'requestName',
        text: 'Операция',
        sortAs: 'request_name',
      },
      {
        value: 'status',
        text: 'Статус',
        sortAs: 'status.name',
      },
      {
        value: 'result',
        text: 'Результат',
        sortAs: 'is_success',
      },
      {
        value: 'error',
        text: 'Примечание',
        sortable: false,
      },
    ],
  };
  isModalShow = {
    card: false,
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

  showDetails(item) {
    this.details = item;
    this.isModalShow.card = true;
  }

  async fetchList() {
    try {
      if (this.canReadRegister) {
        this.isLoading = true;
        const { data, filter } = await this.$service.interaction.find(this.filter);
        this.list = data;
        this.total = filter?.total ?? this.list.length;
      }
      this.isLoading = false;
    } catch (err) {
      this.isLoading = false;
      throw err;
    }
  }

  getExportFilter() {
    return omit(this.filter, ['columns', 'hash']);
  }
}
</script>

<style lang="scss" scoped>
.iconSettings {
  margin-right: 5px;

  &.reverse {
    transform: rotate(180deg);
  }
}

.settingsSpan {
  text-decoration: underline;
}
</style>
