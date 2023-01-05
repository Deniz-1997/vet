<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <div class="title">
          <span>Реестр ролей</span>
        </div>
      </v-col>

      <v-col v-if="canFilterRegister" cols="12">
        <div class="d-flex">
          <SearchComponent
            class="justify-start"
            placeholder="Введите значение поиска"
            @search="(filter) => onChangeFilter({ filter, pageable: { pageNumber: 0 } })"
          />
        </div>
      </v-col>
      <div class="d-flex justify-space-between mb-3">
        <UiViewSettingsModal
          v-show="canCustomizeRegister"
          id="roles"
          v-model="filter"
          class="roles__settings"
          @apply-settings="fetchList"
        />
      </div>

      <v-col cols="12">
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
          <template v-if="canReadCard" #[`item.actions`]="{ item }">
            <v-tooltip bottom>
              <template #activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on">
                  <div class="roles__item mr-2" @click="showCard(item.id)">
                    <img src="/icons/show.svg" alt="Просмотреть запись" class="iconTable" />
                  </div>
                </span>
              </template>

              <span>Просмотреть запись</span>
            </v-tooltip>
          </template>
          <template #[`item.endDate`]="{ item }">
            {{ date(item.endDate, { outputFormat: 'DD.MM.YYYY' }) }}
          </template>
        </DataTable>
      </v-col>
    </v-row>
    <RoleCardModal :id="roleId" v-model="isModalShow.roles" />
    <v-overlay :value="isLoading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import merge from 'lodash/merge';
import { date } from '@/utils/global/filters';
import { Component, Vue } from 'vue-property-decorator';
import SearchComponent from '@/components/common/Search/Search.vue';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import RoleCardModal from '@/components/Administration/Roles/RoleCardModal.vue';
import { EAction, mapAccessFlags } from '@/utils';
import { TInnerFilter } from '@/services/models/common';
import { RoleItem } from '@/services/mappers/roles';

@Component({
  name: 'user-roles-list',
  components: {
    SearchComponent,
    DataTable,
    RoleCardModal,
  },
  computed: {
    ...mapAccessFlags({
      canReadRegister: EAction.VIEW_ROLE,
      canFilterRegister: EAction.FILTER_ROLE,
      canCustomizeRegister: EAction.CUSTOMIZE_ROLE,
      canReadCard: EAction.VIEW_USER_ACCOUNT,
    }),
  },
})
export default class UserRolesList extends Vue {
  readonly canReadRegister!: boolean;
  isLoading = true;
  isModalShow = {
    roles: false,
  };
  roleId: number | null = null;
  list: RoleItem[] = [];
  total = 0;
  filter: TInnerFilter = {
    filter: '',
    pageable: {
      pageSize: 5,
      pageNumber: 0,
      sort: [],
    },
    actual: true,
    columns: [
      {
        text: 'Действия',
        value: 'actions',
        sortable: false,
      },
      {
        text: 'Роль',
        value: 'code',
        sortAs: 'role',
        sortable: false,
      },
      {
        text: 'Описание',
        value: 'name',
        sortAs: 'description',
        sortable: false,
      },
      {
        text: 'Дата удаления',
        value: 'endDate',
        sortAs: 'deleted_date',
        sortable: false,
      },
    ],
  };

  showCard(id: number) {
    this.roleId = id;
    this.isModalShow.roles = true;
  }

  onChangeFilter(filter: TInnerFilter) {
    this.filter = merge(this.filter, filter);
    this.fetchList();
  }

  async fetchList() {
    try {
      if (this.canReadRegister) {
        const { data, filter } = await this.$service.roles.find(this.filter);
        this.list = data;
        this.total = filter?.total ?? this.list.length;
      }
      this.isLoading = false;
    } catch (err) {
      this.isLoading = false;
      throw err;
    }
  }

  date = date;
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
