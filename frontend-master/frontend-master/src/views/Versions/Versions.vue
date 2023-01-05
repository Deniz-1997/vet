<template>
  <v-container class="version">
    <v-row>
      <v-col cols="12">
        <div class="title">
          <span>Версии микросервисов</span>
        </div>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="9" xl="6">
        <DataTable
          :headers="headers"
          :items="items"
          :items-length="list.length"
          :page="pageable.pageNumber"
          :per-page="pageable.pageSize"
          @onOptionsChange="onOptionsChange"
        >
          <template #[`item.status`]="{ item }">
            <div :class="['status-badge', !item.active && 'status-badge_error']" />
          </template>
          <template #[`item.actions`]="{ item }">
            <v-tooltip v-if="!item.active" bottom>
              <template #activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on">
                  <div @click="refresh(item.id)">
                    <v-icon class="iconTable" small>mdi-refresh</v-icon>
                  </div>
                </span>
              </template>

              <span>Обновить</span>
            </v-tooltip>
          </template>
        </DataTable>
      </v-col>
    </v-row>

    <v-overlay :value="isLoading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import { TableHeaders } from '@/components/common/DataTable/DataTable.types';
import { TVersionItem } from '@/views/Versions/Versions.types';
import config from '@/views/Versions/config';

@Component({
  name: 'Versions',
  components: { DataTable },
})
export default class VersionsPage extends Vue {
  @Prop({ type: String, default: 'Версии микросервисов' }) public title!: string;
  isLoading = true;
  refreshing = new Set<string>();

  /** Отображаемый список сервисов. */
  list: TVersionItem[] = [];

  pageable = {
    pageSize: 50,
    pageNumber: 0,
  };

  get items() {
    const { pageSize, pageNumber } = this.pageable;
    const start = pageSize * pageNumber;
    const end = start + pageSize;
    return this.list.slice(start, end);
  }

  get headers(): TableHeaders[] {
    return [
      {
        value: 'status',
        width: 12,
      },
      {
        text: 'Название сервиса',
        value: 'name',
      },
      {
        text: 'Версия',
        value: 'version',
        width: 200,
      },
      {
        value: 'actions',
      },
    ];
  }

  async mounted() {
    // Получение данных о версиях.
    this.list = await this.$service.versions.getVersionList(config);
    this.isLoading = false;
  }

  async refresh(id) {
    if (!this.refreshing.has(id)) {
      this.refreshing.add(id);
      const update = config.find((service) => service.id === id);

      if (update) {
        const response = await this.$service.versions.getVersionItem(update);
        this.list = this.list.map((item) => (item.id === id ? response : item));
      }
      this.refreshing.delete(id);
    }
  }

  onOptionsChange(v) {
    this.pageable.pageNumber = v.page + 1;
    this.pageable.pageSize = v.size;
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';

.status-badge {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background-color: $success-color;

  &_error {
    background-color: $error-color;
  }
}
</style>
