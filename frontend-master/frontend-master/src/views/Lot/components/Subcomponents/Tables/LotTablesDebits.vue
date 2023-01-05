<template>
  <v-col cols="12" class="pa-0">
    <lot-table
      v-model="innerValue"
      :hide-action="false"
      :is-create="false"
      is-edit
      :is-edit-action="false"
      is-show-delete-button
      :max="0"
      :options-for-table="optionsForTable"
      show-case
      class_="edit-table-debits"
      empty-text="Не было списаний по партии"
      title="Списание по партии"
    />

    <confirm-modal-delete
      :show-modal="isShowConfirmModal"
      :text="'Вы действительно хотите аннулировать запись'"
      name=""
      @apply="handleCanceled"
      @close="onCloseConfirmModal"
    />
    <v-overlay :value="isLoading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-col>
</template>

<script lang="ts">
import { Component, Model, Prop } from 'vue-property-decorator';
import EditableTable from '@/components/common/Table/index.vue';
import { mixins } from 'vue-class-component';
import { AdditionalMix } from '@/utils/mixins/additional';
import LotTable from '@/views/Lot/components/Subcomponents/Tables/LotTable.vue';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import ConfirmModalDelete from '@/views/Authorities/components/Modal/ConfirmModalDelete.vue';
import { DebitVueModel } from '@/models/Lot/Debit.vue';
import nsiList from '@/views/NSI/config';

@Component({
  name: 'lot-tables-debits',
  components: { ConfirmModalDelete, ButtonComponent, DataTable, LotTable, EditableTable },
})
export default class LotTablesDebits extends mixins(AdditionalMix) {
  @Model('change', { type: Array, required: true }) value!: Array<any>;

  @Prop({ type: Boolean, default: false }) isEdit!: boolean;

  @Prop({ type: Boolean, default: false }) isCreate!: boolean;

  @Prop({ type: Boolean, default: false }) isActionsAccess!: boolean;

  isShowConfirmModal = false;
  isLoading = false;
  reason: any = [];

  rowValueAftClk: DebitVueModel | null = null;

  get innerValue(): DebitVueModel[] {
    return this.value;
  }

  set innerValue(value: DebitVueModel[]) {
    this.$emit('change', value);
  }

  get optionsForTable(): Array<object> {
    return [
      {
        label: 'Масса списания, кг',
        name: 'amount_kg_debit_mask',
        style: { width: '50%', textAlign: 'center' },
      },
      {
        label: 'Дата списания',
        name: 'create_date',
        style: { width: '50%', textAlign: 'center' },
      },
      this.reasonRenderValue(),
      this.noteRenderValue(),
      this.statusRenderValue(),
      this.actionRenderValue(),
    ];
  }
  reasonRenderValue() {
    return {
      label: 'Причина',
      name: 'reason_id',
      style: { width: '50%', textAlign: 'center' },
      customRenderValue: (model: any): string | undefined => {
        return this.getReasonName(model);
      },
    };
  }

  noteRenderValue() {
    return {
      label: 'Примечание',
      name: 'note',
      style: { width: '50%', textAlign: 'center' },
      customRenderValue: (model: DebitVueModel): string => {
        return model.note ?? '-';
      },
    };
  }

  statusRenderValue() {
    return {
      label: 'Статус',
      name: 'status',
      style: { width: '50%', textAlign: 'center' },
      customRenderValue: (model: any): string | number => {
        if (typeof model.is_canceled === 'undefined') {
          return '<span class="text-caption success--text">Оформлено</span>';
        }
        return model.is_canceled
          ? '<span class="text-caption error--text">Аннулировано</span>'
          : '<span class="text-caption success--text">Оформлено</span>';
      },
    };
  }

  actionRenderValue() {
    return {
      label: 'Действия',
      name: 'is_canceled',
      style: { width: '50%', textAlign: 'center' },
      onClick: (model: any) => {
        if (!(typeof model.is_canceled !== 'undefined' && !model.is_canceled && this.isActionsAccess)) {
          return;
        }

        this.rowValueAftClk = model;
        this.isShowConfirmModal = true;
      },
      customRenderValue: (model: any): string | number => {
        if (typeof model.is_canceled !== 'undefined' && !model.is_canceled && this.isActionsAccess) {
          return (
            '<span ref="btn_canceled" ' +
            'class="v-chip v-chip--label theme--light v-size--default" ' +
            'style="background: #d19b3f; height: 25px; color: white; cursor: pointer">' +
            '<span class="v-chip__content">Аннулировать</span></span>'
          );
        }
        return '-';
      },
    };
  }

  getReasonName(model: any): string {
    const reason = this.reason.find((e) => e.id === model.reason_id);

    return reason?.name || '';
  }

  async created() {
    await this.loadDataAndSetItems();
  }

  async loadDataAndSetItems() {
    const { content } = await this.$store.dispatch('nsi/getList', {
      params: {
        actual: true,
        pageable: {
          pageable: {
            pageNumber: 0,
            pageSize: 100,
          },
          sort: [
            {
              property: 'name',
              direction: 'ASC',
            },
          ],
        },
      },
      url: nsiList['reason-write-off'].apiUrl,
    });

    this.reason = content;
  }

  onCloseConfirmModal() {
    this.isShowConfirmModal = false;
    this.rowValueAftClk = null;
  }

  handleCanceled() {
    this.isShowConfirmModal = false;

    if (this.rowValueAftClk === null) {
      throw new Error('Error delete debit');
    }

    this.$emit('cancel-debit', this.rowValueAftClk);
  }
}
</script>
