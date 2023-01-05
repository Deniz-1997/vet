<template>
  <div v-show="items.length">
    <h4 v-if="title" class="label">{{ title }}</h4>
    <DataTable :headers="headers" :items="items" :items-length="total" hide-footer is-disable-sort>
      <template #[`item.approvalDate`]="{ item }">
        {{ date(item.approvalDate, { outputFormat: 'DD.MM.YYYY HH:mm' }) }}
      </template>
      <template #[`item.expectedDate`]="{ item }">
        {{ date(item.expectedDate, { outputFormat: 'DD.MM.YYYY HH:mm' }) }}
      </template>
    </DataTable>
  </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from 'vue-property-decorator';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import { ApprovalRequestLogItem } from '@/services/mappers/approvalRequestLog';
import { TMapperPlain } from '@/services/models/common';
import { date } from '@/utils/global/filters';

@Component({
  name: 'ApprovalRequestLogShortTable',
  components: { DataTable },
})
export default class ApprovalRequestLogShortTable extends Vue {
  @Prop({ type: Number, required: true }) readonly id!: number;
  @Prop({ type: String, default: 'Согласование заявления' }) readonly title?: string;

  items: TMapperPlain<ApprovalRequestLogItem>[] = [];
  total = 0;

  get headers() {
    return [
      {
        value: 'stage',
        text: 'Этап согласования',
      },
      {
        value: 'approvalDate',
        text: 'Дата и время принятия решения',
      },
      {
        value: 'expectedDate',
        text: 'Плановая дата и время принятия решения',
      },
      {
        value: 'status',
        text: 'Статус',
      },
      {
        value: 'action',
        text: 'Действие',
      },
      {
        value: 'notes',
        text: 'Замечания',
      },
    ];
  }

  created() {
    this.fetch();
  }

  async fetch() {
    const { data, filter } = await this.$service.approvalRequestLog.find({
      actual: true,
      requestId: this.id,
      excludedStatuses: [1],
    });

    this.items = data.reverse();
    this.total = filter?.total ?? this.total;
  }

  date = date;
}
</script>

<style lang="scss" scoped>
.label {
  color: #828286;
  font-size: 14px;
  font-weight: normal;
  line-height: 16px;
  margin-bottom: 13px;
  display: block;
}
</style>
