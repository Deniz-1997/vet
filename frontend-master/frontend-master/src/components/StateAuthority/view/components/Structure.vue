<template>
  <div class="structure">
    <v-row>
      <v-col>
        <EditableTable
          v-model="form"
          :options="headersStateAuthority"
          :max="999"
          is-showcase
          is-delete-row
          :is-not-add-new-field="true"
          :is-show-card-button="false"
        />
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';
import EditableTable from '@/components/common/Table/index.vue';
import { TMapperPlain } from '@/services/models/common';
import { DivisionsItemIn } from '@/services/mappers/divisions';

@Component({
  name: 'card-register',
  components: { EditableTable },
})
export default class CardRegister extends Vue {
  @Prop({ type: Array, default: () => ({}) }) readonly form!: TMapperPlain<DivisionsItemIn>[];
  get headersStateAuthority() {
    return [
      {
        label: 'Вышестоящее подразделение',
        name: 'parent_division.name',
        width: 250,
        sortAs: 'parent_division',
      },
      {
        label: 'Наименование подразделения или должности',
        name: 'name',
        width: 250,
        sortAs: 'root_division_name',
      },
      {
        label: 'Сотрудники',
        name: 'division_staff_user_full_names',
        width: 250,
        sortAs: 'division_staff_user_full_names',
      },
    ];
  }
}
</script>
