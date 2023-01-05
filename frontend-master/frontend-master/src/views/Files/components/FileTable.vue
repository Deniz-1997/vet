<template>
  <v-row>
    <v-col cols="12" lg="9" xl="8">
      <DataTable
        :headers="headers"
        :items="list"
        :items-length="items.length"
        :page="pageable.pageNumber"
        :per-page="pageable.pageSize"
        @onOptionsChange="onOptionsChange"
      >
        <template #[`item.actions`]="{ item }">
          <a
            v-if="item"
            :href="item.href || safe(item.name)"
            :download="item.name"
            target="_blank"
            class="documents-list__item"
          >
            <v-tooltip bottom>
              <template #activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on">
                  <icon-component v-if="!item.href">
                    <download-icon />
                  </icon-component>
                  <span v-else class="link">ссылка</span>
                </span>
              </template>
              <span>{{ item.href ? 'Открыть в новой вкладке' : 'Скачать' }}</span>
            </v-tooltip>
          </a>
        </template>
      </DataTable>
    </v-col>

    <v-overlay :value="loading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-row>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import IconComponent from '@/components/common/IconComponent/IconComponent.vue';
import DownloadIcon from '@/components/common/IconComponent/icons/DownloadIcon.vue';
import { TFileItem, TFileDirectory } from '@/views/Files/Files.types';

@Component({
  name: 'file-table',
  components: { DataTable, IconComponent, DownloadIcon },
})
export default class StaffList extends Vue {
  @Prop({ type: Array, default: () => [] }) readonly items!: TFileItem[];
  @Prop({ type: String, default: 'documents' }) readonly directory!: TFileDirectory;
  @Prop({ type: Boolean, default: true }) readonly loading!: boolean;

  get list() {
    const { pageSize, pageNumber } = this.pageable;
    const start = pageSize * pageNumber;
    const end = start + pageSize;
    return this.items.slice(start, end);
  }

  pageable = {
    pageSize: 5,
    pageNumber: 0,
  };

  headers = [
    {
      text: 'Наименование',
      value: 'label',
      sortable: false,
    },
    {
      text: 'Дата публикации',
      value: 'date',
      width: 200,
      sortable: false,
    },
    {
      text: 'Действия',
      value: 'actions',
      width: 200,
      sortable: false,
    },
  ];

  onOptionsChange(v) {
    this.pageable.pageNumber = v.page + 1;
    this.pageable.pageSize = v.size;
  }

  safe(path) {
    return `/files/${this.directory}/${encodeURIComponent(path)}`;
  }
}
</script>
