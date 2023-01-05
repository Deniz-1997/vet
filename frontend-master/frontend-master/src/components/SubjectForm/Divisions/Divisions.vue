<template>
  <div class="structure">
    <v-row>
      <v-col cols="12">
        <div class="title-h2">Организационная структура</div>
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        <EditableTable
          v-model="form"
          :options="headersStateAuthority"
          :max="999"
          :is-can-edit="isEditTable"
          is-custom-create
          :is-edit-action="isEditTable"
          :is-show-delete-button="isEditTable"
          :is-showcase="!isEditTable"
          is-delete-row
          :is-not-add-new-field="true"
          :is-show-card-button="false"
          edit-strategy="outer"
          @customCreate="showModalDialog"
          @edit="editDivisionRow"
        />
      </v-col>
    </v-row>

    <DivisionModal
      v-if="isShowModal"
      v-model="isShowModal"
      :form-item="tempForm"
      :division-list="divisions_list"
      @close="closeModalDivisionStaff"
      @save="saveDivision"
    />
  </div>
</template>

<script lang="ts">
import { Component, Mixins, Prop } from 'vue-property-decorator';
import EditableTable from '@/components/common/Table/index.vue';
import DivisionModal from './components/DivisionModal.vue';
import Form from '@/utils/global/mixins/form';

const initialTempForm = (): any => ({
  parent_division: null,
  name: '',
  division_staff: [],
});

@Component({
  name: 'card-register',
  components: { EditableTable, DivisionModal },
})
export default class CardRegister extends Mixins(Form) {
  @Prop({ type: Boolean, default: () => true }) readonly isEditTable!: boolean;

  isShowModal = false;
  isEditRow = false;
  tempForm: any = {
    parent_division: null,
    name: '',
    division_staff: [],
  };
  index: number | string = 'undefined';
  text =
    'При удалении подразделения будут удалены все нижестоящие объекты оргструктуры.\n Вы действительно хотите удалить подразделение?';

  get headersStateAuthority() {
    return [
      {
        label: 'Вышестоящее подразделение',
        name: 'parent_division.name',
        width: 250,
      },
      {
        label: 'Наименование подразделения или должности',
        name: 'name',
        width: 250,
      },
      {
        label: 'Сотрудники',
        name: 'division_staff_user_full_names',
        width: 250,
      },
    ];
  }

  get disabledField() {
    if (this.divisions_list.length === 0) {
      return true;
    }
    return false;
  }

  get divisions_list() {
    if (Array.isArray(this.form)) {
      const list = (this.form || []).map((item) => {
        if (item.subject_division_id) {
          return { subject_division_id: item.subject_division_id, name: item.name };
        } else {
          return {
            name: item.name,
            front_id: item.front_id,
          };
        }
      });
      return list;
    } else {
      return [];
    }
  }
  showModalDialog() {
    this.isEditRow = true;
    this.isShowModal = true;
  }

  closeModalDivisionStaff() {
    this.isShowModal = false;
    this.tempForm = initialTempForm();
  }

  async saveDivision(value) {
    if (value.indexRow || value.indexRow === 0) {
      this.form.splice(value.indexRow, 1, { ...value });
    } else {
      this.form = [
        ...this.form,
        {
          ...value,
          front_id: value?.front_id,
          parent_division: {
            ...value?.parent_division,
          },
        },
      ];
    }
    this.tempForm = initialTempForm();
    this.isShowModal = false;
  }

  async editDivisionRow(index: number) {
    this.showModalDialog();
    this.isShowModal = true;
    if (index !== undefined) {
      this.tempForm = this.form.find((item, i) => {
        if (i === index) {
          this.isEditRow = false;
          this.index = index;
          return item;
        }
      });

      this.tempForm = {
        ...this.tempForm,
        division_staff: (this.tempForm.division_staff || []).map((item) => ({
          ...item,
          start_date: item.start_date,
          end_date: item.end_date,
        })),
        indexRow: index,
      };
    }
  }
}
</script>

<style lang="scss" scoped>
.add-btn {
  display: flex;
  height: 40px;
  width: 40px;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  svg {
    fill: #828286;

    &:hover {
      fill: #d19b3f;
    }
  }
}
.table {
  &__head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    border-bottom: 1px solid #828286;
  }

  &__th {
    color: #828286;
    margin-right: 10px;
    padding: 20px 0;
    width: 300px;
    &:last-child {
      text-align: right;
    }
  }
}
</style>
