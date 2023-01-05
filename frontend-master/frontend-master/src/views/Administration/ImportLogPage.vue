<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <div class="title mb-4">
          <span>Загрузка данных об организациях и пользователях</span>
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
            id="import-log"
            v-model="filter"
            @apply-settings="fetchList"
          />
          <div class="settings">
            <span v-if="canImport" class="settingsSpan" @click="isModalShow.import = true">
              <img src="/icons/export.svg" class="iconSettings reverse" />
              Импорт
            </span>
          </div>
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
          <template #[`item.uploadDate`]="{ item }">
            {{ date(item.uploadDate, { outputFormat: 'DD.MM.YYYY HH:mm' }) }}
          </template>
        </DataTable>
      </v-col>
    </v-row>
    <ImportFileModal v-model="isModalShow.import" @close="fetchList" />
    <ImportFileCardModal v-model="isModalShow.card" :details="details" />

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
import { EAction, mapAccessFlags } from '@/utils';
import { TImportInnerFilter } from '@/services/models/import';
import { ImportItem } from '@/services/mappers/import';
import { EImportProcessType } from '@/services/enums/import';
import ImportFileModal from '@/components/Import/ImportFileModal.vue';
import ImportFileCardModal from '@/components/Import/ImportFileCardModal.vue';
import mergeWith from 'lodash/mergeWith';

@Component({
  name: 'ImportLogPage',
  components: {
    DataTable,
    SearchComponent,
    ImportFileModal,
    ImportFileCardModal,
  },
  computed: {
    ...mapAccessFlags({
      canReadRegister: EAction.READ_IMPORT_LOG_REGISTRY,
      canFilterRegister: EAction.FILTER_IMPORT_LOG_REGISTRY,
      canCustomizeRegister: EAction.CUSTOMIZE_IMPORT_LOG_REGISTRY,
      canImport: EAction.CREATE_IMPORT_RECORD,
    }),
  },
})
export default class ImportLogPage extends Vue {
  readonly canReadRegister!: boolean;
  readonly canFilterRegister!: boolean;
  readonly canCustomizeRegister!: boolean;
  readonly canImport!: boolean;

  card: TMapperPlain<ImportItem> | null = null;
  details = null;
  isLoading = true;
  list: TMapperPlain<ImportItem>[] = [];
  total = 0;
  filter: TImportInnerFilter = {
    filter: '',
    processName: EImportProcessType.USERS,
    pageable: {
      pageSize: 5,
      pageNumber: 0,
      sort: [{ direction: 'DESC', property: 'created' }],
    },
    actual: false,
    columns: [
      {
        value: 'actions',
        text: 'Действия',
      },
      {
        value: 'uploadDate',
        text: 'Дата и время загрузки',
        sortAs: 'created',
      },
      {
        value: 'name',
        text: 'Наименование файла',
        sortAs: 'fileName',
      },
      {
        value: 'status',
        text: 'Статус загрузки',
        sortAs: 'status',
      },
    ],
  };
  isModalShow = {
    import: false,
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
    this.details = item.errors?.length ? item.errors : item.result;
    this.isModalShow.card = true;
  }

  async fetchList() {
    try {
      if (this.canReadRegister) {
        this.isLoading = true;
        const { data, filter } = await this.$service.import.find(this.filter);
        this.list = data;
        this.total = filter?.total ?? this.list.length;
      }
      this.isLoading = false;
    } catch (err) {
      this.isLoading = false;
      throw err;
    }
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
