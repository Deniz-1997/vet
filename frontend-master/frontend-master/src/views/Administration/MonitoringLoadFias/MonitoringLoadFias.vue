<template>
  <v-container>
    <v-row>
      <v-col cols="6">
        <div class="title">
          <span>Мониторинг загрузки ФИАС</span>
        </div>
      </v-col>

      <v-col cols="6" class="d-flex justify-end">
        <DefaultButtonNew
          title="Загрузить обновление"
          variant="primary"
          :prepend-icon="'mdi-upload'"
          @click="showUpdateModal"
        />
<!--        <DefaultButtonNew title="Расписание" :prepend-icon="'mdi-alarm'" @click="showScheduleModal" />-->

<!--        <div class="settings">-->
<!--          <UiViewSettingsModal id="monitoring-load-fias" v-model="filter" type="button" @apply-settings="() => {}" />-->
<!--        </div>-->
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <DataTableNew
          :headers="filteredColumns"
          :items="list"
          :items-length="total"
          :page="filter.pageable.pageNumber"
          :per-page="filter.pageable.pageSize"
          :search="filter.filter"
          class="lastcol_fixed"
          @handleSort="(e) => sortColumn(e)"
          @onOptionsChange="({ page, size }) => onChangeFilter({ pageable: { pageNumber: page + 1, pageSize: size } })"
        >
          <template #[`item.actions`]="{ item }">
            <span class="wrap_content_actions">
              <v-tooltip bottom>
                <template #activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on">
                    <img src="/icons/show.svg" class="iconTable" alt="" @click="showDetails(item)" />
                  </span>
                </template>
                <span>Просмотреть информацию</span>
              </v-tooltip>
              <v-tooltip v-if="item.status === 'В процессе'" bottom>
                <template #activator="{ on, attrs }">
                  <span v-bind="attrs" v-on="on">
                    <img src="/icons/cancel.svg" class="iconTable" alt="" @click="stopProcess" />
                  </span>
                </template>
                <span>Остановить загрузку</span>
              </v-tooltip>
            </span>
          </template>
        </DataTableNew>
      </v-col>
    </v-row>

    <load-fias-card-modal v-model="isModalShow.card" :details="details" />
    <load-fias-update-modal v-model="isModalShow.update" />
    <load-fias-schedule-modal v-model="isModalShow.schedule" :schedule="schedule" @save-schedule="saveSchedule" />

    <v-overlay :value="isLoading">
      <v-progress-circular indeterminate size="64" />
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import SearchNewComponent from '@/components/common/SearchNew/Search.vue';
import DataTableNew from '@/components/common/DataTableNew/DataTable.vue';
import DefaultButtonNew from '@/components/common/buttons/DefaultButtonNew.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import LoadFiasCardModal from '@/views/Administration/MonitoringLoadFias/components/LoadFiasCardModal.vue';
import LoadFiasUpdateModal from '@/views/Administration/MonitoringLoadFias/components/LoadFiasUpdateModal.vue';
import LoadFiasScheduleModal from '@/views/Administration/MonitoringLoadFias/components/LoadFiasScheduleModal.vue';
import { mapAccessFlags } from '@/utils';
import { TInnerFilter, TMapperPlain } from '@/services/models/common';
import { merge } from 'lodash';
import { MonitoringLoadFiasItem } from "@/services/mappers/monitoringLoadFias";

@Component({
  name: 'monitoring-load-fias',
  components: {
    SearchNewComponent,
    DataTableNew,
    DefaultButtonNew,
    DefaultButton,
    DialogComponent,
    LoadFiasCardModal,
    LoadFiasUpdateModal,
    LoadFiasScheduleModal,
  },
  computed: {
    ...mapAccessFlags({
      //TODO использовать корректные привилегии
    }),
  },
})
export default class MonitoringLoadFiasList extends Vue {
  isLoading = false;
  list: TMapperPlain<MonitoringLoadFiasItem>[] = [];
  total = 0;
  details: TMapperPlain<MonitoringLoadFiasItem> | null = null;
  schedule = '';

  isModalShow = {
    card: false,
    update: false,
    schedule: false,
  };

  filter: TInnerFilter = {
    filter: '',
    pageable: {
      pageSize: 5,
      pageNumber: 0,
      sort: [{ direction: 'ASC', property: 'started_at' }],
    },
    actual: true,
    columns: [
      {
        text: 'Дата и время начала',
        value: 'started_at',
        sortAs: 'started_at',
        isShow: true,
      },
      {
        text: 'Дата и время окончания',
        value: 'finished_at',
        sortAs: 'finished_at',
        isShow: true,
      },
      {
        text: 'Наименование файла',
        value: 'file_name',
        sortAs: 'file_name',
        sortable: false,
        isShow: true,
      },
      {
        text: 'Наименование справочника',
        value: 'dictionary_name',
        sortAs: 'dictionary_name',
        isShow: true,
      },
      {
        text: 'Результат',
        value: 'result',
        sortAs: 'result',
        isShow: true,
      },
      {
        text: 'Действия',
        value: 'actions',
        class: 'th_actions fixed fixed--right',
        sortable: false,
        isShow: true,
      },
    ],
  };

  get filteredColumns() {
    return this.filter?.columns?.filter((item) => !!item.isShow);
  }

  mounted() {
    this.fetchSchedule();
  }

  sortColumn(e) {
    this.filter.pageable = {
      ...this.filter.pageable,
      sort: [
        {
          direction: this.filter.pageable?.sort && this.filter.pageable?.sort[0].direction === 'ASC' ? 'DESC' : 'ASC',
          property: e.sortBy[0],
        },
      ],
    };
    this.fetchList();
  }

  onChangeFilter(filter: TInnerFilter) {
    this.filter = merge(this.filter, filter);
    this.fetchList();
  }

  async fetchList() {
    try {
      this.isLoading = true;
      const { data, filter } = await this.$axios.post('/api/fias/findLog', {
        ...this.filter,
      });

      this.list = data.content;
      this.total = filter?.total ?? this.list.length;
      this.isLoading = false;
    } catch (err) {
      this.isLoading = false;
      throw err;
    }
  }

  showDetails(item) {
    this.details = item;
    this.isModalShow.card = true;
  }

  showUpdateModal() {
    this.isModalShow.update = true;
  }

  showScheduleModal() {
    this.isModalShow.schedule = true;
  }

  async fetchSchedule() {
    //TODO получение текущего расписания
    this.schedule = '9 5 * * 1,3,5';
  }

  saveSchedule(newSchedule) {
    console.log('save schedule', newSchedule);

    this.isLoading = true;
    try {
      this.isModalShow.schedule = false;
      //TODO сохранение расписания
      //TODO сообщение об успешном сохранении
      //TODO не забыть обновить this.schedule
      this.isLoading = false;
    } catch (_error) {
      this.isLoading = false;
    }
  }

  async stopProcess() {
    await this.$axios.get('/api/fias/stop');
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';

.settings {
  display: inline-block;
  vertical-align: middle;
  background-color: #fff;
  color: $medium-grey-color;
  text-align: center;
  text-decoration: none;
  font-size: 15px;
  border-radius: 4px;
  margin-right: 0;
  margin-left: 0;
  border: 1px solid $input-border-color;
  width: 40px;
  height: 40px;
  line-height: 3.1;
  cursor: pointer;
}

.iconTable {
  cursor: pointer;
  padding-right: 8px;
}
</style>
