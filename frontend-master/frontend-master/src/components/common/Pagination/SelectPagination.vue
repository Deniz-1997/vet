<template>
  <pagination
    @onPageChange="handlePageChange"
    :items-length="itemsLength"
    :initial-per-page="perPage"
  >
    <template #total="{ props: { activePage, total } }">
      <text-component class="base-micro">
        {{ activePage }}/{{ total }}
      </text-component>
    </template>
    <template #pages>
      <div />
    </template>
    <template
      #controls="{
        props: {
          handlePreviousClick,
          isFirstPage,
          handleNextClick,
          isLastPage
        }
      }"
    >
      <v-btn
        @click="handlePreviousClick"
        :disabled="isFirstPage"
        class="arrow-button"
        text
        fab
      >
        <v-icon>mdi-chevron-left</v-icon>
      </v-btn>
      <v-btn
        @click="handleNextClick"
        :disabled="isLastPage"
        class="arrow-button"
        text
        fab
      >
        <v-icon>mdi-chevron-right</v-icon>
      </v-btn>
    </template>
    <template #per-pages>
      <div />
    </template>
  </pagination>
</template>

<script lang="ts">
/* eslint-disable max-len */
import { Component, Prop, Vue } from 'vue-property-decorator';
import { PageChange } from '@/components/common/Pagination/Pagination.types';
import TextComponent from '@/components/common/Text/Text.vue';

@Component({
  name: 'select-pagination',
  components: {
    // vue edge case: escape circular loop
    Pagination: () => import('@/components/common/Pagination/Pagination.vue'),
    TextComponent,
  },
})
export default class SelectComponent extends Vue {
  @Prop({ type: Number, default: 200 }) readonly perPage!: number;
  @Prop(Number) readonly itemsLength!: number;

  handlePageChange(pageChange: PageChange): void {
    const { page, perPage } = pageChange;

    this.$emit('onPageChange', { page, perPage });
  }
}
</script>
