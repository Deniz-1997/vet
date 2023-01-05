<template>
  <v-container class="version">
    <v-row>
      <v-col cols="12">
        <div class="title">
          <span>{{ title }}</span>
        </div>
      </v-col>
    </v-row>
    <FileTable :items="config" :loading="isLoading" :directory="$route.params.type" />
  </v-container>
</template>

<script lang="ts">
import { Component, Vue, Watch } from 'vue-property-decorator';
import { TFileItem } from '@/views/Files/Files.types';
import FileTable from '@/views/Files/components/FileTable.vue';
import { Memoize } from '@/utils/global/decorators/method';

@Component({
  name: 'Files',
  components: { FileTable },
})
export default class FilesPage extends Vue {
  config: TFileItem[] = [];
  isLoading = true;

  get title() {
    return this.$route.meta?.breadcrumb.find(({ type }) => type === this.$route.params.type)?.name ?? 'Инструкции';
  }

  @Watch('$route.params.type', { immediate: true })
  async _handleChangeType(type) {
    this.config = await this.fetchList(type);
    this.isLoading = false;
  }

  @Memoize()
  async fetchList(type) {
    const { data } = await this.$axios(`/configs/files/${type}.json`);

    return data;
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';

.title {
  margin-bottom: 20px;
}

.documents-list__item {
  position: relative;
  font-size: 14px;
  padding: 0 40px 0 16px;
  color: $gold-dark-color;

  &::before {
    position: absolute;
    top: 3px;
    left: 0;
    height: 10px;
    width: 10px;
    background: url('/icons/arrow.svg') no-repeat;
    background-position: center;
    background-size: contain;
    content: '';
    display: block;
  }
}
</style>
