<template>
  <div class="tableHeader">
    <span v-if="!hideActions" class="theader" />
    <span
      v-for="item in options"
      :key="item.name"
      class="theader"
      :style="typeof item.style === 'undefined' ? { width: item.width + 'px' } : item.style"
      @click="$emit('click', item.name)"
    >
      {{ item.label }}
    </span>
  </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from 'vue-property-decorator';

type Options = {
  name: string;
  label: string;
  width: number;
};

type Props = {
  options: Options[];
  hideActions: Boolean;
};

@Component({
  name: 'TableHeader',
})
export default class TableHeader extends Vue {
  @Prop({
    type: Array,
    default: () => [],
  })
  readonly options: Props['options'] | undefined;
  @Prop({
    type: Boolean,
    default: () => false,
  })
  readonly hideActions: Props['hideActions'] | undefined;
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.tableHeader {
  display: flex;
  flex-direction: row;
  width: 100%;
  align-items: center;
  align-content: center;
  color: $medium-grey-color;
  min-height: 24px;

  .theader:first-child {
    text-align: left;
    width: 120px;
  }

  .theader:last-child {
    text-align: right;
    width: calc(100% - 120px);
  }

  @include respond-to('medium') {
    font-size: 15px;
  }

  @include respond-to('small') {
    font-size: 12px;
  }
}

.tableListRow {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-content: center;
  align-items: center;
  padding-top: 5px;
  min-height: 40px;
  box-sizing: border-box;
  padding-bottom: 5px;
  border-bottom: 1px solid $input-border-color;

  .spanList:first-child {
    text-align: left;
    width: 120px;
  }

  .spanList:last-child {
    text-align: right;
    width: calc(100% - 120px);
  }
}

.theader {
  width: 66.6%;
  display: inline-table;
  table-layout: fixed;
  margin-right: 15px;
  font-size: 0.75rem !important;
}

.spanList {
  display: inline-table;
  table-layout: fixed;
  color: $footer-color;
  margin-right: 15px;
  font-size: 0.75rem;

  &:nth-child(2) {
    word-break: break-word;
  }
}

.iconTable {
  width: 20px;
  height: 20px;
  margin-left: 8px;
  cursor: pointer;
}

.tableList {
  overflow-y: auto;
  width: 100%;

  @include respond-to('small') {
    font-size: 12px;
  }
}

.tableInfo {
  display: flex;
  flex-direction: row;
  margin-top: 25px;
}
</style>
