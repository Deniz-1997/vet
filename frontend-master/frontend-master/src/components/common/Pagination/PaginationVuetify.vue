<template>
  <div class="pagination">
    <div class="pagination__pages">
      <div class="description">Страницы:</div>
      <v-pagination v-model="currentPage" class="pagination__nav" :length="total" :total-visible="visiblePages" />

      <ul class="navigation">
        <li class="navigation__btn">
          <v-btn :disabled="isFirstPage()" class="controls-button" text @click="handlePreviousClick">
            Предыдущая
          </v-btn>
        </li>
        <li class="navigation__btn">
          <v-btn v-show="false" :disabled="total === 1" class="controls-button" text @click="handleAllClick">
            Все
          </v-btn>
        </li>
        <li class="navigation__btn">
          <v-btn :disabled="isLastPage" class="controls-button" text @click="handleNextClick"> Следующая </v-btn>
        </li>
      </ul>
    </div>
    <div class="change-pages">
      <span class="text-center"> Записей на странице: </span>
      <select-component v-model="perPages.value" :items="perPages" @change="handleChange" :clearable="false">
      </select-component>
    </div>
  </div>
</template>

<script lang="ts">
import last from 'lodash/last';
import first from 'lodash/first';
import { Component, Prop, Vue, Watch } from 'vue-property-decorator';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import { SETTINGS_KEY } from './consts';

@Component({
  name: 'pagination-component',
  components: {
    SelectComponent,
    TextComponent,
  },
})
export default class Pagination extends Vue {
  @Prop({ type: Number, default: 7 }) readonly visiblePages!: number;
  @Prop(Number) readonly itemsLength!: number;
  @Prop(Number) readonly initialPerPage!: number;
  @Prop({ type: Number, default: 1 }) activePage!: number;

  currentPage = 1;
  page = 1;

  perPage = 5;
  perPages = [
    // { text: 'Все', value: 9999 },
    { text: 5, value: 5 },
    { text: 10, value: 10 },
    { text: 20, value: 20 },
    { text: 50, value: 50 },
    { text: 100, value: 100 },
  ];

  total = 0;
  showAll = false;

  pages: number[] = [];

  get isLastPage(): boolean {
    return this.currentPage === this.total;
  }

  get isFirstPages(): boolean {
    return this.firstPageOfPages === 0;
  }

  get isLastPages(): boolean {
    return this.lastPageOfPages === this.total - 1;
  }

  get firstPageOfPages(): number | undefined {
    return first(this.pages);
  }

  get lastPageOfPages(): number | undefined {
    return last(this.pages);
  }

  get isActivePage(): number | undefined {
    return this.currentPage;
  }

  mounted() {
    this.perPages['value'] = this.initialPerPage;
  }

  handleChange() {
    this.perPage = this.perPages['value'];
    this.$emit('perPages', this.perPages);
  }

  nextVisiblePages(): number {
    return this.visiblePages - this.pages.indexOf(this.currentPage);
  }

  previousVisiblePages(): number {
    return this.visiblePages + this.pages.indexOf(this.currentPage);
  }

  handleAllClick(): void {
    this.showAll = true;
    this.currentPage = 1;
    this.perPage = 9999;

    this.onPageChange();
  }

  isFirstPage() {
    return this.currentPage === 1;
  }

  handleNextClick(): void {
    this.showAll = false;

    if (this.lastPageOfPages === this.currentPage) {
      this.enlistNextPages();
    } else {
      this.currentPage += 1;
      this.onPageChange();
    }
  }

  handlePreviousClick(): void {
    this.showAll = false;

    if (this.currentPage === 2 && this.pages[0] !== 0) {
      this.pages.unshift(0);
    }
    if (this.firstPageOfPages === this.currentPage) {
      this.enlistPreviousPages();
    } else {
      this.currentPage -= 1;
      this.onPageChange();
    }
  }

  handleLastClick(): void {
    this.showAll = false;
    this.currentPage = this.total - 1;
    this.pages = this.calculatePages(this.currentPage);
    this.onPageChange();
  }

  enlistNextPages(): void {
    this.currentPage += 1;
    this.onPageChange();
  }

  enlistPreviousPages(): void {
    if (this.pages[0] < this.previousVisiblePages()) {
      this.pages = this.calculatePages(this.currentPage - this.currentPage);
    } else {
      this.pages = this.calculatePages(this.currentPage - this.previousVisiblePages());
    }
    this.currentPage = this.pages[this.pages.length - 1];
    this.onPageChange();
  }

  setPageFromRoute({ path } = this.$route) {
    const { [path]: savedPage = 1 } = JSON.parse(localStorage.getItem(SETTINGS_KEY) || '{}');
    const page = savedPage && savedPage <= this.total ? savedPage : 1;

    if (page !== this.currentPage) {
      this.currentPage = page;
    }
  }

  onPageChange(): void {
    localStorage.setItem(
      SETTINGS_KEY,
      JSON.stringify({
        ...JSON.parse(localStorage.getItem(SETTINGS_KEY) || '{}'),
        [this.$route.path]: this.currentPage,
      })
    );
    this.$emit('onPageChange', {
      page: this.currentPage - 1,
      perPage: this.perPage,
    });
  }

  calculateTotalPerPage(perPage: number): number {
    return Math.ceil(this.itemsLength / perPage);
  }

  calculatePages(currentPage: number): number[] {
    const pages = [...Array(this.total).keys()];
    return pages.slice(currentPage, currentPage + this.visiblePages);
  }

  created(): void {
    this.perPage = this.initialPerPage || this.perPage;
    this.total = this.calculateTotalPerPage(this.perPage);
    this.setPageFromRoute();
    this.pages = this.calculatePages(this.currentPage);
    this.perPages['value'] = this.initialPerPage;
  }

  @Watch('currentPage')
  handlePageClick(page: number, prev: number): void {
    if (page !== prev) {
      this.onPageChange();
    }
  }

  @Watch('initialPerPage')
  onInitialPerPage() {
    this.perPages['value'] = this.initialPerPage;
  }

  @Watch('perPage')
  onPerPageChange(perPage: number): void {
    this.currentPage = 1;
    this.total = this.calculateTotalPerPage(perPage);
    this.pages = this.calculatePages(this.currentPage);
    this.showAll = perPage === 9999;
    this.onPageChange();
  }

  @Watch('itemsLength', { immediate: true })
  onItemsLengthChange(): void {
    this.total = this.calculateTotalPerPage(this.perPage);
    this.setPageFromRoute();
    this.pages = this.calculatePages(this.currentPage);
    this.isFirstPage();
  }
}
</script>

<style lang="scss">
@import '@/assets/styles/_variables';

.pagination {
  width: 100%;
  flex: 1;
  justify-content: space-between;

  &__pages {
    width: 100;
    flex-wrap: nowrap;
    display: flex;
  }

  .pagination__nav {
    width: 100;
    align-items: center;
    display: flex;

    .v-pagination {
      padding-left: 4px !important;

      li {
        &:last-child {
          display: none;
        }

        &:first-child {
          display: none;
        }
      }

      .v-pagination__item {
        box-shadow: none;
        padding: 0;
        min-width: initial;
        height: auto;
        color: $gold-dark-color;
        font-size: 11px;

        &--active {
          background: none !important;
          color: $medium-grey-color;
        }
      }
    }
  }

  .v-application ul {
    padding-left: 2px !important;
  }

  .navigation__btn {
    .v-btn {
      color: $gold-dark-color;
      font-size: 11px;
      line-height: 16px;
      padding-right: 16px !important;
      padding-left: 4px !important;

      &--disabled {
        color: $input-border-color;
      }
    }
  }

  .change-pages {
    align-items: center;
    display: flex;
    z-index: 0;
  }

  .text-center {
    font-size: 11px;
    line-height: 16px;
    color: $medium-grey-color;
    margin-right: 8px;
    text-transform: uppercase;
    display: flex;
  }

  .description {
    font-size: 11px;
    line-height: 16px;
    font-weight: normal;
    color: $medium-grey-color;
    text-transform: uppercase;
  }

  .navigation {
    align-items: center;
    display: flex;
    list-style: none;
    color: $gold-dark-color !important;
  }
}
</style>
