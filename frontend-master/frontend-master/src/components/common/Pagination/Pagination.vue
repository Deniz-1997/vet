<template>
  <div class="wrapper">
    <div class="navigation">
      <slot
        name="pages"
        :props="{
          activePage,
          enlistNextPages,
          enlistPreviousPages,
          isFirstPages,
          isLastPages,
          pages
        }"
      >
        <div class="pages">
          Страницы:
          <text-component
            v-if="!isFirstPages"
            @click.native="enlistPreviousPages"
            class="mr-1 text-primary pointer"
          >
            ...
          </text-component>
          <text-component
            v-for="page in pages"
            @click.native="handlePageClick(page)"
            :class="['mr-1', 'text-primary', 'pointer', { active: activePage === page }]"
            :key="page"
          >
            {{ page }}
          </text-component>
          <text-component
            v-if="!isLastPages"
            @click.native="enlistNextPages"
            class="mr-1 text-primary pointer"
          >
            ...
          </text-component>
          <text-component
            v-if="!isLastPages"
            @click.native="handleLastClick"
            :class="['mr-1', 'text-primary', 'pointer', { active: activePage === total }]"
          >
            {{ total }}
          </text-component>
        </div>
      </slot>
      <slot
        name="controls"
        :props="{
          handleAllClick,
          handleNextClick,
          handlePreviousClick,
          isFirstPage,
          isLastPage,
          total,
        }"
      >
        <ul class="controls">
          <!-- <li class="arrow">
            <v-btn
              @click="handlePreviousClick"
              :disabled="isFirstPage"
              class="arrow-button"
              text
              fab
            >
              <v-icon>mdi-chevron-left</v-icon>
            </v-btn>
          </li> -->
          
          <li class="item">
            <v-btn
              @click="handlePreviousClick"
              :disabled="isFirstPage"
              class="controls-button"
              text
            >
              Предыдущая
            </v-btn>
          </li>
          <li class="item">
            <v-btn
              v-show="false"
              @click="handleAllClick"
              :disabled="total === 1"
              class="controls-button"
              text
            >
              Все
            </v-btn>
          </li>
          <li class="item">
            <v-btn
              @click="handleNextClick"
              :disabled="isLastPage"
              class="controls-button"
              text
            >
              Следующая
            </v-btn>
          </li>
          <!-- <li class="arrow">
            <v-btn
              @click="handleNextClick"
              :disabled="isLastPage"
              class="arrow-button"
              text
              fab
            >
              <v-icon>mdi-chevron-right</v-icon>
            </v-btn>
          </li> -->
        </ul>
      </slot>
    </div>
    
  </div>
</template>

<script lang="ts">
import last from 'lodash/last';
import first from 'lodash/first';
import { Component, Prop, Vue, Watch } from 'vue-property-decorator';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import TextComponent from '@/components/common/Text/Text.vue';

@Component({
  name: 'pagination-component',
  components: {
    SelectComponent,
    TextComponent,
  },
})
export default class Pagination extends Vue {
  @Prop({ type: Number, default: 5 }) readonly visiblePages!: number;
  @Prop(Number) readonly itemsLength!: number;
  @Prop(Number) readonly initialPerPage!: number;
  @Prop({ type: Number, default: 1 }) activePage!: number;

  perPage = 5;
  perPages = [
    { text: 'Все', value: 9999 },
    { text: 10, value: 10 },
    { text: 20, value: 20 },
    { text: 50, value: 50 },
    { text: 100, value: 100 },
  ];

  total = 0;
  showAll = false;

  pages: number[] = [];

  get isFirstPage(): boolean {
    return this.activePage === 1;
  }

  get isLastPage(): boolean {
    return this.activePage === this.total;
  }

  get isFirstPages(): boolean {
    return this.firstPageOfPages === 1;
  }

  get isLastPages(): boolean {
    return this.lastPageOfPages === this.total;
  }

  get firstPageOfPages(): number | undefined {
    return first(this.pages);
  }

  get lastPageOfPages(): number | undefined {
    return last(this.pages);
  }

  get nextVisiblePages(): number {
    return this.visiblePages - this.pages.indexOf(this.activePage);
  }

  get previousVisiblePages(): number {
    return this.visiblePages + this.pages.indexOf(this.activePage);
  }

  handleAllClick(): void {
    this.showAll = true;
    this.activePage = 1;
    this.perPage = 9999;

    this.onPageChange();
  }

  handleNextClick(): void {
    this.showAll = false;

    if (this.lastPageOfPages === this.activePage) {
      this.enlistNextPages();
    } else {
      this.activePage += 1;
      this.onPageChange();
    }
  }

  handlePreviousClick(): void {
    this.showAll = false;

    if (this.firstPageOfPages === this.activePage) {
      this.enlistPreviousPages();
    } else {
      this.activePage -= 1;
      this.onPageChange();
    }
  }

  handleLastClick(): void {
    this.showAll = false;

    this.activePage = this.total;
    this.pages = this.calculatePages(this.activePage);

    this.onPageChange();
  }

  handlePageClick(page: number): void {
    this.activePage = page;
    this.onPageChange();
  }

  enlistNextPages(): void {
    this.activePage += this.nextVisiblePages;
    this.pages = this.calculatePages(this.activePage);
    this.onPageChange();
  }

  enlistPreviousPages(): void {
    this.pages = this.calculatePages(this.activePage - this.previousVisiblePages);
    this.activePage = --this.activePage;
    this.onPageChange();
  }

  onPageChange(): void {
    this.$emit('onPageChange', { page: this.activePage, perPage: this.perPage });
  }

  calculateTotalPerPage(perPage: number): number {
    return Math.ceil(this.itemsLength / perPage);
  }

  calculatePages(activePage: number): number[] {
    const pages = [...Array(this.total + 1).keys()];

    return pages.slice(activePage, activePage + this.visiblePages);
  }

  created(): void {
    this.perPage = this.initialPerPage || this.perPage;
    this.total = this.calculateTotalPerPage(this.perPage);
    this.pages = this.calculatePages(this.activePage);
  }

  @Watch('perPage')
  onPerPageChange(perPage: number): void {
    this.activePage = 1;
    this.total = this.calculateTotalPerPage(perPage);
    this.pages = this.calculatePages(this.activePage);

    this.showAll = perPage === 9999;

    this.onPageChange();
  }

  @Watch('itemsLength')
  onItemsLengthChange(): void {
    this.total = this.calculateTotalPerPage(this.perPage);
    this.pages = this.calculatePages(this.activePage);
  }
}
</script>

<style scoped lang="scss">
  @import "@/assets/styles/_variables.scss";

  .wrapper {
    align-items: center;
    display: flex;
    font-size: 12px !important;
    font-weight: normal;
    justify-content: space-between;
    line-height: 16px !important;
    text-transform: uppercase !important;
    width: 100%;
  }

  .total {
    color: $medium-grey-color;
  }

  .navigation {
    align-items: center;
    display: flex;
    flex: 0 1 80%;
  }

  .pages {
    font-size: 11px;
    line-height: 16px;;
    color: $medium-grey-color;

    .pointer {
      color: $gold-dark-color;
    }

    .active {
      color: $footer-color;
    }
  }

  .page {
    cursor: pointer;
  }

  .per-pages {
    align-items: center;
    display: flex;
    flex: 0 1 20%;
    justify-content: flex-end;
  }

  .per-page {
    color: $medium-grey-color;
    margin-right: 6px;
  }

  .controls {
    align-items: center;
    display: flex;
    list-style: none;
    margin-left: 14px;
  }

  .item {

    &:first-child {
      margin-left: 15px;
    }

    &:last-child {
      margin-right: 15px;
    }


    .v-btn {
      color: $gold-dark-color;
      font-size: 11px;
      line-height: 16px;

      &--disabled {
        color: $input-border-color;
      }
    }
  }

  .arrow-button {
    color: $light-grey-color;
    height: 20px;
    width: 20px;
  }

  .controls-button {
    color: $light-grey-color;
    padding: 4px calc(9px / 2) !important;
  }
</style>
