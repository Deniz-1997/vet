<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="10" xl="9">
        <div class="title mb-4">
          <span>Жалобы</span>
        </div>
        <div v-if="canFilterRegister" class="block-header mb-3">
          <div class="d-flex">
            <SearchComponent
              class="justify-start"
              placeholder="Поиск жалобы по организации"
              @search="(filter) => onChangeFilter({ filter, pageable: { pageNumber: 0 } })"
            />
            <UiCheckbox
              id="all_list"
              name="all_list"
              :value="!showActualList"
              class="notifications__only-new"
              label="Отображать все записи"
              @input="(v) => onShowAllList()"
            />
          </div>
        </div>
        <div class="d-flex mb-3">
          <UiViewSettingsModal
            id="complaint"
            v-model="filter"
            class="notifications__settings"
            @apply-settings="fetchList"
          />
          <div class="settings">
            <!-- TODO: Ожидает задачи по доработке асинхронного экспорта -->
            <!-- <span v-if="canExportRegister" class="settingsSpan" @click="exportAction">
              <img src="/icons/export.svg" class="iconSettings" />
              Экспорт списка
            </span> -->
          </div>
        </div>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <DataTable
          :headers="filter.columns"
          :items="list"
          :items-length="total"
          :page="filter.pageable.pageNumber"
          :per-page="filter.pageable.pageSize"
          :filter="filter"
          :search="filter.filter"
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
        </DataTable>
      </v-col>
    </v-row>
    <ComplaintCardModal v-model="isModalShow.card" :details="details" />

    <v-overlay :value="isLoading" absolute>
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import mergeWith from 'lodash/mergeWith';

import { TInnerFilter, TMapperPlain } from '@/services/models/common';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import SearchComponent from '@/components/common/Search/Search.vue';
import { EAction, mapAccessFlags } from '@/utils';
import { ComplaintItem } from '@/services/mappers/complaint';
import ComplaintCardModal from '@/components/Complaint/ComplaintCardModal.vue';
import Exportable from '@/utils/global/mixins/exportable';

@Component({
  name: 'ComplaintPage',
  components: {
    DataTable,
    SearchComponent,
    ComplaintCardModal,
  },
  computed: {
    ...mapAccessFlags({
      canReadRegister: EAction.READ_COMPLAINT_REGISTER,
      canExportRegister: EAction.EXPORT_COMPLAINT_REGISTER,
      canFilterRegister: EAction.FILTER_COMPLAINT_REGISTER,
    }),
  },
})
export default class InteractionLogPage extends Mixins(Exportable('/api/elevator-request/complaint/exportReport')) {
  readonly canReadRegister!: boolean;
  readonly canExportRegister!: boolean;
  readonly canFilterRegister!: boolean;

  card: TMapperPlain<ComplaintItem> | null = null;
  details = {};
  isLoading = true;
  list: TMapperPlain<ComplaintItem>[] = [];
  total = 0;
  showActualList = false;

  filter: TInnerFilter = {
    filter: '',
    pageable: {
      pageSize: 5,
      pageNumber: 0,
      sort: [{ property: 'created', direction: 'DESC' }],
    },
    actual: false,
    columns: [
      {
        value: 'actions',
        text: 'Действия',
      },
      {
        value: 'id',
        text: 'Номер жалобы',
        sortAs: 'complaint_id',
      },
      {
        value: 'complaint_name',
        text: 'Тип жалобы',
        sortAs: 'complaint_type.name',
      },
      {
        value: 'created_by',
        text: 'Пользователь',
        sortAs: 'subject.created_by',
      },
      {
        value: 'subject_name',
        text: 'Организация',
        sortAs: 'subject.short_name',
      },
      {
        value: 'created',
        text: 'Дата и время получения',
        sortAs: 'created',
      },
    ],
  };
  isModalShow = {
    card: false,
  };

  created() {
    this.fetchList();
  }

  onChangeFilter(filter: TInnerFilter) {
    this.filter = mergeWith(this.filter, filter, (_, from) => {
      if (Array.isArray(from)) {
        return from;
      }
    });
    this.fetchList();
  }

  async onShowAllList() {
    this.showActualList = !this.showActualList;
    this.fetchList();
  }

  async showDetails(item) {
    const { data } = await this.$service.complaint.findOne(item.id);
    this.details = { ...data };
    this.isModalShow.card = true;
  }

  async fetchList() {
    try {
      if (this.canReadRegister) {
        this.isLoading = true;
        const { data, filter } = await this.$service.complaint.find({
          ...this.filter,
          actual: this.showActualList,
        });
        this.list = data;
        this.total = filter?.total ?? this.list.length;
      }
      this.isLoading = false;
    } catch (err) {
      this.isLoading = false;
      throw err;
    }
    this.isLoading = false;
  }

  getExportFilter() {
    const { filter, sort, pageable } = this.filter;
    return {
      filter,
      actual: this.showActualList,
      pageable: { ...pageable, sort },
    };
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
