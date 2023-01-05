<template>
  <div class="wrapper">
    <div class="navigation">
      <slot
        name="pages"
        :props="{
          isActivePage,
          enlistNextPages,
          enlistPreviousPages,
          isFirstPages,
          isLastPages,
          initialPerPage,
          pages,
        }"
      >
        <div class="pages">
          Страницы:
          <text-component v-if="!isFirstPages" @click.native="enlistPreviousPages" class="mr-1 text-primary pointer">
            ...
          </text-component>
          <text-component
            v-for="page in pages"
            @click.native="handlePageClick(page)"
            :class="['mr-1', 'text-primary', 'pointer', { active: isActivePage === page }]"
            :key="page"
          >
            {{ page + 1 }}
          </text-component>
          <text-component v-if="!isLastPages" @click.native="enlistNextPages" class="mr-1 text-primary pointer">
            ...
          </text-component>
          <text-component
            v-if="!isLastPages"
            @click.native="handleLastClick"
            :class="['mr-1', 'text-primary', 'pointer', { active: isActivePage === total }]"
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
            <v-btn @click="handlePreviousClick" :disabled="isFirstPage()" class="controls-button" text>
              Предыдущая
            </v-btn>
          </li>
          <li class="item">
            <v-btn v-show="false" @click="handleAllClick" :disabled="total === 1" class="controls-button" text>
              Все
            </v-btn>
          </li>
          <li class="item">
            <v-btn @click="handleNextClick" :disabled="isLastPage" class="controls-button" text> Следующая </v-btn>
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
    <div class="change-pages">
      <span class="description"> Записей на странице: </span>
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

  currentPage = 1;

  perPage = 5;
  perPages = [
    // { text: 'Все', value: 9999 },
    { text: 10, value: 10 },
    { text: 20, value: 20 },
    { text: 50, value: 50 },
    { text: 100, value: 100 },
  ];

  total = 0;
  showAll = false;

  pages: number[] = [];

  get isLastPage(): boolean {
    return this.currentPage === this.total - 1;
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
    this.perPages['value'] = 10;
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
    return this.currentPage === 0;
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

  handlePageClick(page: number): void {
    this.currentPage = page;
    this.onPageChange();
  }

  enlistNextPages(): void {
    this.currentPage += this.nextVisiblePages();
    this.pages = this.calculatePages(this.currentPage);
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

  onPageChange(): void {
    this.$emit('onPageChange', {
      page: this.currentPage,
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
    this.pages = this.calculatePages(this.currentPage);
  }

  @Watch('initialPerPage')
  onInitialPerPage(initialPerPage: number) {
    this.perPages['value'] = initialPerPage;
  }

  @Watch('perPage')
  onPerPageChange(perPage: number): void {
    this.currentPage = 0;
    this.total = this.calculateTotalPerPage(perPage);
    this.pages = this.calculatePages(this.currentPage);

    this.showAll = perPage === 9999;
    this.onPageChange();
  }

  @Watch('itemsLength')
  onItemsLengthChange(): void {
    this.currentPage = 0;
    this.total = this.calculateTotalPerPage(this.perPage);
    this.pages = this.calculatePages(this.currentPage);
    this.isFirstPage();
  }
}
</script>

<style scoped lang="scss">
@import '@/assets/styles/_variables.scss';

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

.change-pages {
  align-items: center;
  display: flex;
  z-index: 0;
}

.description {
  font-weight: normal;
  font-size: 11px;
  line-height: 12px;
  padding-right: 12px;
  color: $medium-grey-color;
}

.pages {
  font-size: 11px;
  line-height: 16px;
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
