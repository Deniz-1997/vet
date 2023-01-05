<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <div class="title">
          <span>Государственные контракты с агентами</span>
        </div>
      </v-col>
      <v-col v-if="canFilterAgents" cols="12" md="4">
        <SearchComponent
          placeholder="Введите значение поиска"
          @search="(filter) => onChangeFilter({ filter, pageable: { pageNumber: 0 } })"
        />
      </v-col>
      <v-col cols="12">
        <div class="settings">
          <UiViewSettingsModal id="agents" v-model="filter" class="agents__settings" @apply-settings="fetchList" />
          <!-- TODO: Ожидает задачи по доработке асинхронного экспорта -->
          <!-- <span v-if="canExportRegister" class="settingsSpan" @click="exportAction">
            <img src="/icons/export.svg" class="iconSettings" />
            Экспорт списка
          </span> -->
          <span v-if="canAddNewAgent" class="settingsSpan" @click="onOpenAddModal = true" id="addNewAgent">
            <img src="/icons/add.svg" class="iconSettings" />
            Добавить
          </span>
        </div>
      </v-col>

      <v-col cols="12">
        <DataTable
          :headers="filter.columns"
          :items="rows"
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
            <div class="d-flex align-center justify-space-between">
              <v-tooltip v-if="canViewCard" bottom>
                <template #activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on">
                    <img src="/icons/show.svg" class="iconTable" @click="canEdit(item.contract_id, 'read')" />
                  </span>
                </template>
                <span>Просмотреть информацию</span>
              </v-tooltip>

              <v-tooltip v-if="canEditCard" bottom>
                <template #activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on">
                    <img
                      src="/icons/edit.svg"
                      class="iconTable"
                      :disabled="item.canEdit"
                      @click="canEdit(item.contract_id, 'edit')"
                    />
                  </span>
                </template>
                <span>Редактировать информацию</span>
              </v-tooltip>

              <v-tooltip v-if="canDeleteCard" bottom>
                <template #activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on">
                    <img src="/icons/deleteBasket.svg" class="iconTable" @click="showConfirmDeleteModal(item)" />
                  </span>
                </template>
                <span>Удалить информацию</span>
              </v-tooltip>
            </div>
          </template>
        </DataTable>
      </v-col>
    </v-row>
    <ConfirmModalDelete
      :show-modal="showConfirmModal"
      :text="textDeleteModal"
      @close="closeConfirmModal"
      @apply="handleDeleteDraft(selectRequest)"
    />

    <ContractCard
      v-model="onOpenAddModal"
      :id-card="idEditCard"
      :readonly="actionType === 'read'"
      @close="closeOrganizationModal"
    />

    <v-overlay :value="isLoading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import SearchComponent from '@/components/common/Search/Search.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import ContractCard from '@/views/Agents/components/ContractCard.vue';
import ConfirmModalDelete from '@/views/Authorities/components/Modal/ConfirmModalDelete.vue';
import { EAction, mapAccessFlags } from '@/utils';
import { TInnerFilter } from '@/services/models/common';
import { Debounce } from '@/utils/global/decorators/method';
import mergeWith from 'lodash/mergeWith';
import omit from 'lodash/omit';
import Exportable from '@/utils/global/mixins/exportable';

const mapForm = (data) => ({
  ...data,
  doc: {
    doc_id: data.doc_id,
    doc_num: data.doc_num,
  },
  subject: {
    subject_id: data.subject_id,
  },
});

@Component({
  name: 'agents-list',
  components: {
    DataTable,
    SearchComponent,
    DefaultButton,
    ContractCard,
    ConfirmModalDelete,
  },

  computed: {
    ...mapAccessFlags({
      canFilterAgents: EAction.FILTER_AGENT_CONTRACT_REGISTER,
      canViewCard: EAction.READ_AGENT_CONTRACT,
      canEditCard: EAction.UPDATE_AGENT_CONTRACT,
      canDeleteCard: EAction.DELETE_AGENT_CONTRACT,
      canAddNewAgent: EAction.UPDATE_AGENT_CONTRACT_REGISTER,
      canExportRegister: EAction.EXPORT_AGENT_CONTRACT_REGISTER,
    }),
  },
})
export default class AgentsList extends Mixins(Exportable('/api/agent/contract/exportReport')) {
  readonly canFilterAgents!: boolean;
  readonly canViewCard!: boolean;
  readonly canEditCard!: boolean;
  readonly canDeleteCard!: boolean;
  readonly canAddNewAgent!: boolean;
  readonly canExportRegister!: boolean;
  statusButton = false;
  isLoading = false;
  rows: any = [];
  checked = false;
  isShowActionModal = false;
  onOpenAddModal = false;
  idEditCard = null;
  showProcessor = false;
  selectRequest = null;
  nameAgent = null;
  item;
  textDeleteModal = '';
  searchResult = '';
  showConfirmModal = false;
  total = 0;
  actionType = '';
  filter: TInnerFilter = {
    filter: '',
    pageable: {
      pageSize: 5,
      pageNumber: 0,
      sort: [ { direction: 'DESC', property: 'subject.name' },],
    },
    actual: true,
    columns: [
      {
        text: 'Действия',
        value: 'actions',
        sortable: false,
      },
      {
        text: 'Наименование',
        value: 'subject.subject_data.name',
        sortAs: 'subject.name',
      },
      {
        text: 'ИНН/КПП',
        value: 'subject.subject_data.inn_kpp',
        sortAs: 'subject.inn_kpp',
        sortable: false,
      },
      {
        text: 'Номер контракта',
        value: 'document.doc_num',
        sortAs: 'document.doc_num',
      },
      {
        text: 'Дата начала',
        value: 'contract_date_start',
        sortAs: 'contract_date_start',
      },
      {
        text: 'Дата окончания',
        value: 'contract_date_end',
        sortAs: 'contract_date_end',
      },
    ],
  };

  @Debounce()
  async fetchList() {
    this.isLoading = true;
    const { content, totalElements } = await this.$store.dispatch('contracts/getList', {
      pageable: this.filter.pageable,
      filter: this.filter.filter,
      actual: true,
    });
    this.total = totalElements;
    this.rows = content;
    this.isLoading = false;
  }

  canEdit(id, actionType: string) {
    this.onOpenAddModal = true;
    this.idEditCard = id;
    this.actionType = actionType;
  }

  /*   async deleteItem(item) {
    await this.$store.dispatch('contracts/deleteContract', mapForm(item));
    this.getContracts();
  } */

  async handleDeleteDraft() {
    //ToDO: Переписать логику удаления(срочно).
    // eslint-disable-next-line no-useless-catch
    try {
      const data = await this.$store.dispatch('contracts/deleteContract', mapForm(this.item));
      this.fetchList();
      if (data === 'OK') {
        this.rows = this.rows.filter((item) => item['id'] !== this.selectRequest);
      }
    } catch (err) {
      throw err;
    }
    this.showConfirmModal = false;
  }

  showConfirmDeleteModal(item: any) {
    this.selectRequest = item.document.doc_num;
    this.nameAgent = item.subject.subject_data.name;
    this.textDeleteModal = `Вы собираетесь удалить запись о государственном контракте ${this.selectRequest} с агентом ${this.nameAgent}`;
    this.item = item;
    this.showConfirmModal = true;
  }

  closeConfirmModal() {
    this.showConfirmModal = false;
  }

  handleCheck() {
    if (this.checked) {
      this.checked = false;
      this.statusButton = false;
      return;
    }
    this.checked = true;
  }

  onChangeFilter(filter: TInnerFilter) {
    this.filter = mergeWith(this.filter, filter, (_, from) => {
      if (Array.isArray(from)) {
        return from;
      }
    });
    this.fetchList();
  }

  getExportFilter() {
    return omit(this.filter, ['columns', 'hash'])
  }

  closeOrganizationModal() {
    if (this.actionType !== 'read') {
      this.fetchList();
    }
    this.idEditCard = null;
    this.actionType = '';
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.iconTable {
  cursor: pointer;
  padding-right: 8px;
}

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

.processor {
  padding-left: 40px;
}

.checkbox-filter {
  padding-top: 8px;
}

.checkbox-title {
  font-size: 14px;
  color: $medium-grey-color;
}
</style>
