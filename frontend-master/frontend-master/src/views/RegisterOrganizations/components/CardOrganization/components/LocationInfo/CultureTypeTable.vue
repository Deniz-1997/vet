<template>
  <div>
    <v-row>
      <v-col cols="12">
        <div class="title-h2">Вид сельскохозяйственной культуры</div>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="10" xl="8">
        <EditableTable
          v-model="innerValue"
          :options="headers"
          :max="999"
          :is-can-edit="!isShowcase"
          :is-showcase="isShowcase"
          id-table="culture_type_table"
        />
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from 'vue-property-decorator';
import EditableTable from '@/components/common/Table/index.vue';

@Component({
  name: 'CultureTypeTable',
  components: { EditableTable },
})
export default class extends Vue {
  @Prop({ type: Array, default: () => [] }) readonly value!: any[];
  @Prop({ type: Boolean, default: () => true }) readonly isShowcase!: boolean;

  productType: any[] = [];

  // eslint-disable-next-line max-lines-per-function
  get headers() {
    return [
      {
        label: 'ОКПД 2',
        name: 'okpd2.code',
        width: 125,
        useRestrictions: 'value',
        disabled: true,
      },
      {
        label: 'Наименование ОКПД 2',
        name: '%LIST%',
        controlType: 'autoComplete',
        restrictions: (list, selected) =>
          this.productType.filter(({ value: { tnved, okpd2 } }) => {
            return (
              ((!tnved.code || tnved.code.length === 10) &&
                !list.some(({ value }) => value?.okpd2?.code === okpd2?.code)) ||
              selected?.value?.okpd2?.code === okpd2?.code
            );
          }),
        itemValue: 'value',
        itemText: 'label',
        exclude: true,
        width: 450,
        idElement: 'select_OKPD'
      },
      {
        label: 'ТН ВЭД ЕАЭС (на уровне 10 разряда кодового обозначения)',
        name: 'tnved.code',
        width: 330,
        useRestrictions: 'value',
        disabled: true,
      },
      {
        label: 'Наименование ТН ВЭД ЕАЭС',
        name: 'tnved.name',
        useRestrictions: 'value',
        disabled: true,
        width: 450,
      },
    ];
  }

  get innerValue() {
    return this.value;
  }

  set innerValue(v) {
    this.$emit('input', v);
  }

  created() {
    this.fetchProductType();
  }

  async fetchProductType() {
    //ToDo: Разобраться с типизацией
    const { data }: any = await this.$axios.post('/api/nci/okpd2', {
      has_tnved: true,
      actual: true,
      pageable: {
        sort: [{ property: 'name', direction: 'DESC' }],
      },
    });
    this.productType = data.content.map((item) => ({
      label: item.name,
      value: {
        id: item.id,
        okpd2: {
          code: item.code,
          name: item.name,
          id: item.id,
        },
        tnved: {
          code: item.tnved?.[0]?.code,
          name: item.tnved?.[0]?.name,
          id: item.tnved?.[0]?.id,
        },
      },
    }));
  }
}
</script>
