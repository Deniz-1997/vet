<template>
  <v-row>
    <v-col cols="12">
      <div class="title-h2">Аттестаты аккредитации</div>
    </v-col>

    <v-col cols="12">
      <EditableTable
        v-model="innerValue"
        :options="headers"
        :max="999"
        :is-showcase="isShow"
        :is-can-edit="!isShow"
        :is-custom-create="true"
      />
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Vue, Component, Prop } from 'vue-property-decorator';
import EditableTable from '@/components/common/Table/index.vue';

@Component({
  name: 'CertificatesTable',
  components: {
    EditableTable,
  },
})
export default class extends Vue {
  @Prop({ type: Array, default: () => [] }) readonly value!: any[];
  @Prop({ type: Boolean, default: () => true }) readonly isShow!: boolean;

  get headers() {
    return [
      {
        label: 'Номер аттестата',
        name: 'doc_num',
        width: 400,
      },
      {
        label: 'Дата начала действия',
        name: 'start_date',
        controlType: 'date',
        limitTo: this.$moment().add(1, 'd').toDate(),
        width: 250,
      },
      {
        label: 'Дата окончания действия',
        name: 'end_date',
        controlType: 'date',
        width: 250,
      },
    ];
  }

  get innerValue() {
    return this.value;
  }

  set innerValue(v) {
    this.$emit('input', v);
  }
}
</script>
