<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <div class="title mb-4">
          <span>Реестр деклараций ФТС</span>
        </div>
        <div v-if="canFilterRegister || canFilterRegisterOther" class="block-header mb-3">
          <div class="d-flex">
            <SearchComponent
              class="justify-start"
              placeholder="Введите номер декларации"
              mask="########/######/#######"
              message="Введите полный номер таможенной декларации"
              :disabled="isDisabled"
              @input="(search) => (filter.filter = search)"
              @search="(filter) => onChangeFilter({ filter, pageable: { pageNumber: 0 } })"
            />
          </div>
        </div>
        <div class="d-flex mb-3">
          <UiViewSettingsModal
            v-if="canCustomizeRegister || canCustomizeRegisterOther"
            id="declaration"
            v-model="filter"
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
          is-disable-sort
          @onOptionsChange="({ page, size }) => onChangeFilter({ pageable: { pageNumber: page + 1, pageSize: size } })"
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
          <template #[`item.exportDate`]="{ item }">
            {{ date(item.exportDate, { outputFormat: 'DD.MM.YYYY HH:mm' }) }}
          </template>
        </DataTable>
      </v-col>
    </v-row>
    <DeclarationCardModal v-model="isModalShow.card" :details="details" />

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
import { DeclarationItem } from '@/services/mappers/declaration';
import DeclarationCardModal from '@/components/Declaration/DeclarationCardModal.vue';

@Component({
  name: 'DeclarationPage',
  components: {
    DataTable,
    SearchComponent,
    DeclarationCardModal,
  },
  computed: {
    ...mapAccessFlags({
      canReadRegister: EAction.READ_DECLARATION_REGISTER_SDIZ,
      canReadRegisterOther: EAction.READ_DECLARATION_REGISTER,
      canFilterRegister: EAction.FILTER_DECLARATION_REGISTER_SDIZ,
      canFilterRegisterOther: EAction.FILTER_DECLARATION_REGISTER,
      canCustomizeRegister: EAction.CUSTOMIZE_DECLARATION_REGISTER_SDIZ,
      canCustomizeRegisterOther: EAction.CUSTOMIZE_DECLARATION_REGISTER,
    }),
  },
})
export default class InteractionLogPage extends Vue {
  readonly canReadRegister!: boolean;
  readonly canFilterRegister!: boolean;
  readonly canCustomizeRegister!: boolean;
  readonly canFilterRegisterOther!: boolean;
  readonly canReadRegisterOther!: boolean;
  readonly canCustomizeRegisterOther!: boolean;
  readonly canImport!: boolean;

  card: TMapperPlain<DeclarationItem> | null = null;
  details = null;
  isLoading = false;
  list: TMapperPlain<DeclarationItem>[] = [];
  total = 0;
  filter: TInnerFilter = {
    filter: '',
    pageable: {
      pageSize: 5,
      pageNumber: 0,
      sort: [],
    },
    actual: false,
    columns: [
      {
        value: 'actions',
        text: 'Действия',
        sortable: false,
      },
      {
        value: 'number',
        text: 'Номер декларации',
        sortable: false,
      },
      {
        value: 'inn',
        text: 'ИНН',
        sortable: false,
      },
      {
        value: 'status',
        text: 'Статус',
        sortable: false,
      },
      {
        value: 'type',
        text: 'Тип декларации',
        sortable: false,
      },
      {
        value: 'exportDate',
        text: 'Дата и время экспорта',
        sortable: false,
      },
    ],
  };
  isModalShow = {
    card: false,
  };

  get isDisabled() {
    return (this.filter.filter?.length || 0) < 23;
  }

  onChangeFilter(filter: TInnerFilter) {
    this.filter = merge(this.filter, filter);
    this.fetchList();
  }

  date = date;

  showDetails(item) {
    this.details = item;
    this.isModalShow.card = true;
  }

  async fetchList() {
    if (!this.filter.filter || this.isDisabled) {
      if (this.list.length) {
        this.list = [];
      }

      return;
    }
    try {
      if (this.canReadRegister || this.canReadRegisterOther) {
        this.isLoading = true;
        const { data, filter } = await this.$service.declaration.find(this.filter);
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
