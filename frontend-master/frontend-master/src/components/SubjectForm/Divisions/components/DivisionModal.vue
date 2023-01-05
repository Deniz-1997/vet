<template>
  <Dialog-component
    v-model="innerValue"
    :prompt="false"
    cancel-title=""
    confirm-title=""
    width="800"
    with-close-icon
    controls-justify="justify-end"
  >
    <template #title> Подразделение </template>
    <template #content>
      <v-row>
        <v-col cols="12">
          <SelectComponent
            v-model="form.parent_division"
            label="Вышестоящее подразделение"
            return-object
            :items="filteredDivisionList"
            item-value="code"
            item-text="name"
            :is-disabled="filteredDivisionList.length === 0"
          />
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12">
          <InputComponent
            id="name"
            v-model="form.name"
            label="Наименование подразделения или должности"
            placeholder="Введите текст"
          />
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12">
          <EditableTable
            v-model="form.division_staff"
            title="Сотрудники"
            is-edit-action
            is-can-edit
            is-delete-row
            :options="headersStaff"
            :max="999"
          />
        </v-col>
      </v-row>
      <v-col cols="12" class="col-exclude">
        <DefaultButton title="Отмена" @click="close" />
        <DefaultButton variant="primary" title="Сохранить" @click="save" />
      </v-col>
    </template>
  </Dialog-component>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';
import EditableTable from '@/components/common/Table/index.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import cloneDeep from 'lodash/cloneDeep';

const getInitialForm = (): any => ({
  parent_division: null,
  name: '',
  division_staff: [],
});

@Component({
  name: 'row-divisions',
  components: { EditableTable, InputComponent, AutocompleteComponent, DialogComponent, DefaultButton, SelectComponent },
})
export default class RowDivisions extends Vue {
  @Prop({ type: Boolean }) readonly value;
  @Prop({ type: Object, default: {} }) readonly formItem;
  @Prop({ type: Array, default: [] }) readonly divisionList;

  form = getInitialForm();

  get filteredDivisionList() {
    if (this.form.front_id) {
      return this.divisionList.filter((division) =>
        division.front_id ? division.front_id !== this.form.front_id : true
      );
    }

    if (this.form.subject_division_id) {
      return this.divisionList.filter((division) =>
        division.subject_division_id ? division.subject_division_id !== this.form.subject_division_id : true
      );
    }

    return [...this.divisionList];
  }

  mounted() {
    if (this.formItem?.name) {
      return (this.form = cloneDeep(this.formItem));
    }
    this.form = getInitialForm();
  }

  get innerValue() {
    return this.value;
  }

  set innerValue(value) {
    this.$emit('input', value);
    if (!value) {
      this.$emit('close');
      this.form = getInitialForm();
    }
  }

  get headersStaff() {
    return [
      {
        label: 'ФИО',
        name: 'staff',
        controlType: 'autoUserComplete',
        exclude: true,
        width: 250,
      },
      {
        label: 'Дата назначения',
        name: 'start_date',
        controlType: 'date',
        limitTo: this.$moment().add(1, 'd').toDate(),
        width: 250,
      },
      {
        label: 'Дата снятия',
        name: 'end_date',
        controlType: 'date',
        width: 250,
      },
    ];
  }

  close() {
    this.form = getInitialForm();
    this.$emit('close');
  }

  listStaffUser(list) {
    return (list || []).map((item) => item.full_name || item.user_full_name).join(', ');
  }

  save() {
    const mapItemDivision = {
      ...this.form,
      front_id: `front-${Date.now()}`,
      division_staff: (this.form.division_staff || []).map((item) => ({
        ...item.staff,
        start_date: item.start_date,
        end_date: item.end_date,
        name: item.name,
        subject_division_id: item.subject_division_id,
        user_id: item.staff ? item.staff.user_id : item.user_id,
        subject_division_staff_id: item.subject_division_staff_id,
      })),
    };
    const tempForm = {
      ...mapItemDivision,
      division_staff_user_full_names: this.listStaffUser(mapItemDivision.division_staff),
    };
    this.$emit('save', tempForm);
  }
}
</script>

<style lang="scss" scoped></style>
