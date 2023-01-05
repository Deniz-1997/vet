<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <div class="title mb-4">
          <span>Запросы</span>
        </div>

        <div class="block-header mb-3">
          <div class="d-flex">
            <SearchComponent
              class="justify-start"
              placeholder="Поиск запроса"
              @search="(filter) => onChangeFilter({ filter, pageable: { pageNumber: 0 } })"
            />
            <UiCheckbox
              id="all_list"
              name="all_list"
              :value="!showActualList"
              label="Все записи"
              @input="(v) => onShowAllList()"
            />
          </div>
        </div>

        <div v-if="canCreateRequest" class="d-flex mb-3">
          <span class="settings">
            <UiViewSettingsModal
              id="requestRegister"
              v-model="filter"
              @apply-settings="fetchList"
            />
          </span>

          <span class="settingsSpan" @click="createRequest()">
            <img src="/icons/add.svg" class="iconSettings" alt="" />
            Добавить
          </span>
        </div>

        <DataTable
          :headers="filter.columns"
          :items="list"
          :items-length="total"
          :page="filter.pageable.pageNumber"
          :per-page="filter.pageable.pageSize"
          :search="filter.filter"
          :filter="filter"
          @handleSort="(e) => sortColumn(e)"
          @onOptionsChange="({ page, size }) => onChangeFilter({ pageable: { pageNumber: page + 1, pageSize: size } })"
        >
          <template #[`item.actions`]="{ item }">
            <v-tooltip v-if="canReadCard" bottom>
              <template #activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on">
                  <img alt="" class="iconTable" src="/icons/show.svg" @click="showDetails(item)" />
                </span>
              </template>
              <span>Просмотреть информацию</span>
            </v-tooltip>
            <v-tooltip
              v-if="item.status.code !== 'DONE' && item.status.code !== 'RETURNED_FOR_REVISION' && canAnswerRequest"
              bottom
            >
              <template #activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on">
                  <img alt="" class="iconTable" src="/icons/edit.svg" @click="editDetails(item)" />
                </span>
              </template>
              <span>Обработать запрос</span>
            </v-tooltip>
          </template>
        </DataTable>
      </v-col>
    </v-row>
    <RequestRegisterCardModal v-model="isModalShow.card" :details="details" :type="type" @close="close" />

    <v-overlay :value="isLoading" absolute>
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import merge from 'lodash/merge';

import { TInnerFilter, TMapperPlain } from '@/services/models/common';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import SearchComponent from '@/components/common/Search/Search.vue';
import { RequestRegisterItem } from '@/services/mappers/requestRegister';
import RequestRegisterCardModal from '@/components/RequestRegister/RequestRegisterCardModal.vue';
import { EAction } from '@/models/roles';
import { mapAccessFlags } from '@/utils';

@Component({
  name: 'RequestRegisterPage',
  components: {
    RequestRegisterCardModal,
    DataTable,
    SearchComponent,
  },
  computed: {
    ...mapAccessFlags({
      canAnswerRequest: EAction.ANSWER_FREE_FORM,
      canReadCard: EAction.READ_FREE_FORM,
      canCreateRequest: EAction.CREATE_FREE_FORM,
    }),
  },
})
export default class RequestRegisterPage extends Vue {
  readonly canAnswerRequest!: boolean;
  readonly canReadCard!: boolean;

  card: TMapperPlain<RequestRegisterItem> | null = null;
  details = {};
  isLoading = false;
  total = 1;
  list: any = [];
  showActualList = true;

  filter: TInnerFilter = {
    filter: '',
    pageable: {
      pageSize: 5,
      pageNumber: 0,
      sort: [{ direction: 'DESC', property: 'id' }],
    },
    actual: false,
    columns: [
      {
        value: 'actions',
        text: 'Действия',
        sortable: false,
      },
      {
        value: 'id',
        text: 'Номер запроса',
      },
      {
        value: 'subject_name',
        text: 'Организация',
        sortAs: 'subject.name',
      },
      {
        value: 'receiving_type',
        text: 'Способ получения запроса',
        sortAs: 'means_of_receiving.name',
      },
      {
        value: 'answering_type',
        text: 'Способ направления ответа',
        sortAs: 'means_of_answering.name',
      },
      {
        value: 'created',
        text: 'Дата и время получения запроса',
      },
      {
        value: 'answer_date',
        text: 'Дата и время направления ответа',
        sortable: false,
      },
      {
        value: 'status_name',
        text: 'Статус',
        sortAs: 'status.name',
      },
    ],
  };
  isModalCard = false;
  isModalShow = {
    card: false,
  };
  type: string | null = null;

  created() {
    this.fetchList();
  }

  close() {
    this.isModalCard = false;
    this.fetchList();
  }

  onChangeFilter(filter: TInnerFilter) {
    this.filter = merge(this.filter, filter);
    this.fetchList();
  }

  onShowAllList() {
    this.showActualList = !this.showActualList;
    this.fetchList();
  }

  sortColumn(e) {
    this.filter.pageable = {
      ...this.filter.pageable,
      sort: [
        {
          direction: this.filter.pageable?.sort && this.filter.pageable?.sort[0].direction === 'ASC' ? 'DESC' : 'ASC',
          property: this.getProperty(e) || '',
        },
      ],
    };
    this.fetchList();
  }

  getProperty(e) {
    const property = this.filter?.columns?.filter((v) => v.value === e.sortBy[0])[0];

    return property?.sortAs ? property?.sortAs : property?.value;
  }

  async fetchList() {
    try {
      this.isLoading = true;
      const { data, filter } = await this.$service.requests.find({
        ...this.filter,
        actual: this.showActualList,
      });
      this.list = data;
      this.total = filter?.total ?? this.list.length;
      this.isLoading = false;
    } catch (err) {
      this.isLoading = false;
      throw err;
    }
    this.isLoading = false;
  }

  async showDetails(item) {
    this.isModalShow.card = true;
    this.type = 'read';
    this.details = {
      ...item,
    };
  }

  async editDetails(item) {
    if (item.status?.code === 'NEW' || item.status?.code === 'POSTPONED') {
      await this.$service.requests.inWork(item.id);
      this.fetchList();
    }
    this.isModalShow.card = true;
    this.type = 'update';
    this.details = {
      ...item,
    };
  }

  createRequest() {
    this.isModalShow.card = true;
    this.type = 'create';
    this.details = {};
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
