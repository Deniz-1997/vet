<template>
  <div v-if="hasContent">
    <text-component
        v-if="title"
        variant="h4"
        class="mb-4"
    >
      {{ title }}
    </text-component>
    <v-simple-table
        :fixed-header="fixedHeader"
        :dense="dense"
        :style="{ width }"
        :class="['table', { readOnly, underline, list, headerBorder }]"
    >
      <template #default>
        <thead>
        <tr>
          <th
              v-for="({ align, bold, colSpan, rowSpan, text }, headerIndex) in headers"
              :rowspan="rowSpan"
              :colspan="colSpan"
              :class="['th', align, bold]"
              :key="headerIndex"
          >
            {{ text }}
          </th>
        </tr>
        <tr v-if="!!subHeaders.length">
          <th
              v-for="({ align, bold, colSpan, rowSpan, text }, headerIndex) in subHeaders"
              :rowspan="rowSpan"
              :colspan="colSpan"
              :class="['th', align, bold]"
              :key="headerIndex"
          >
            {{ text }}
          </th>
        </tr>

        <tr v-if="loading">
          <th
              :colspan="headers.length"
              class="th loading"
          >
            <v-progress-linear
                class="progressbar"
                indeterminate
            />
          </th>
        </tr>
        </thead>
        <tbody>
        <tr v-if="noDataInTableView">
          <td
              :colspan="headers.length"
              class="no-data"
          >
            {{ noDataText }}
          </td>
        </tr>
        <tr
            v-for="(item, itemIndex) in items"
            :key="itemIndex"
        >
          <td
              v-for="(value, name) in item"
              class="td"
              :key="name"
          >
            <slot
                :value="value"
                :name="`item.${name}`"
            >
              {{ value }}
            </slot>
          </td>
        </tr>
        </tbody>
      </template>
    </v-simple-table>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import { SimpleHeader, SimpleItem } from './SimpleTable.types';
import TextComponent from '@/components/common/Text/Text.vue';
@Component({
  name: 'simple-table',
  components: {
    TextComponent,
  },
})
export default class SimpleTable extends Vue {
  @Prop({
    type: Array,
    default: () => [],
  }) readonly headers!: SimpleHeader[];

  @Prop({
    type: Array,
    default: () => [],
  }) readonly subHeaders!: SimpleHeader[];

  @Prop({
    type: Array,
    default: () => [],
  }) readonly items!: SimpleItem[];

  @Prop({
    type: String,
    default: 'Ничего не найдено.',
  }) readonly noDataText!: string;

  @Prop({
    type: Boolean,
    default: true,
  }) readonly fixedHeader!: boolean;

  @Prop({
    type: String,
    default: '800px',
  }) readonly height!: string;

  @Prop({
    type: Boolean,
    default: true,
  }) readonly readOnly!: boolean;

  @Prop({
    type: Boolean,
    default: true,
  }) readonly underline!: boolean;

  @Prop({
    type: String,
    default: '100%',
  }) readonly width!: string;

  @Prop(String) readonly title!: string;
  @Prop(Boolean) readonly loading!: boolean;
  @Prop(Boolean) readonly dense!: boolean;
  @Prop(Boolean) readonly list!: boolean;
  @Prop(Boolean) readonly headerBorder!: boolean;

  get noDataInTableView(): boolean {
    return !this.items.length && !this.list;
  }

  get hasContent(): boolean {
    return !!this.items.length || !!this.headers.length;
  }
}
</script>

<style scoped lang="scss">
@import "@/assets/styles/_variables.scss";

// $bright: map-get($map: $theme-colors, $key: "bright");
// $light: map-get($map: $theme-colors, $key: "light");
// $dark: map-get($map: $theme-colors, $key: "dark");
// $medium: map-get($map: $theme-colors, $key: "medium");
// $primary: map-get($map: $theme-colors, $key: "primary");

%cell {
  border: unset !important;
  line-height: 16px;
  padding: 4px 16px !important;
}

.progressbar::v-deep {
  height: 2px !important;

  .v-progress-linear__background,
  .v-progress-linear__indeterminate {
    background-color: $primary !important;
    border-color: $primary !important;
  }
}

.td {
  color: $dark !important;
  font-size: 13px !important;
  font-weight: normal;

  @extend %cell;
}

.th {
  color: $medium !important;
  font-size: 14px !important;
  font-weight: normal;

  @extend %cell;

  &.left {
    text-align: left;
  }

  &.center {
    text-align: center;
  }

  &.right {
    text-align: right;
  }

  &.bold {
    font-weight: bold;
  }

  &.loading {
    height: auto;
    padding: 0 !important;
  }
}

.table::v-deep {

  &.underline tr:last-child .th {
    border-bottom: 1px solid $bright !important;
  }

  &.headerBorder tr:last-child .th {
    border-bottom: 3px solid $medium !important;
  }

  &.list .td:first-child,
  &.list .th:first-child {
    color: $medium !important;
    font-weight: bold;
  }

  &.underline tr .td:not(:empty) {
    border-bottom: 1px solid $bright !important;
  }

  &.list:not(.underline) .td:first-child,
  &.list:not(.underline) .th:first-child {
    padding: 4px 0 !important;
  }

  &.readOnly tr {

    &:hover {
      background-color: initial !important;
    }
  }
}

.no-data {
  border: 1px solid $light;
  color: $light;
  font-size: 20px;
  font-weight: 500;
  line-height: 24px;
  padding: 33px 0;
  text-align: center;
}
</style>
