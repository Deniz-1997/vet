<template>
  <v-col cols="12">
    <sdiz-table
      v-model="innerValue"
      :is-create="false"
      :is-edit="false"
      :options-for-table="optionsForTable"
      class_="edit-table-extinguish"
      empty-text="Ничего не найдено"
    />
    <confirm-modal-delete
      :show-modal="isShowConfirmModal"
      :text="'Вы действительно хотите аннулировать запись'"
      name=""
      @apply="handleSignatureModalOpen"
      @close="onCloseConfirmModal"
    />
    <SignatureModal v-model="isSignatureModalOpen" :measure-id="measureId" @approve="handleSignApprove" />
    <v-overlay :value="isLoading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-col>
</template>

<script lang="ts">
import { Component, Model, Prop } from 'vue-property-decorator';
import { mixins } from 'vue-class-component';
import { AdditionalMix } from '@/utils/mixins/additional';
import SdizTable from '@/views/Sdiz/components/Subcomponents/Table/SdizTable.vue';
import { SdizExtinguishRefusalModel } from '@/models/Sdiz/SdizExtinguishRefusal';
import ConfirmModalDelete from '@/views/Authorities/components/Modal/ConfirmModalDelete.vue';
import SignatureModal from '@/components/SignatureModal/SignatureModal.vue';
import { SdizExtinguishVueModel } from '@/models/Sdiz/SdizExtinguish';
import { ActionsMix } from '@/utils/mixins/actions';
import { sdizCancelSign } from '@/utils/sdizCancelSign';

@Component({
  name: 'sdiz-history-return',
  components: { SignatureModal, ConfirmModalDelete, SdizTable },
})
export default class SdizHistoryReturn extends mixins(AdditionalMix, ActionsMix) {
  @Model('change', { type: Array, required: true }) value!: any[];
  @Prop({ type: String, required: true }) toLotLink!: string;
  @Prop({ type: String, required: true }) cancelSignService!: string;
  @Prop({ type: Boolean, default: false }) isActionsAccess!: boolean;

  isSignatureModalOpen = false;
  measureId = 0;
  rowValueAftClk: SdizExtinguishRefusalModel = new SdizExtinguishRefusalModel();
  isShowConfirmModal = false;
  isLoading = false;
  returnReasons: any[] = [];
  tableOptions = [
    {
      label: 'Дата отказа погашения',
      name: 'date',
    },
    {
      label: 'Сформированная партия',
      name: 'lot_number',
      customRenderValue: (model: SdizExtinguishRefusalModel): string | number => {
        const { gpb, lot } = model;

        if (gpb.id === null && lot.id === null) {
          return 'Партия не указана';
        }

        const num = lot.id === null ? gpb.gpb_number : lot.lot_number;

        return `<a href="${this.createdLotLink(model)}">${num ?? gpb.id ?? lot.id}</a>`;
      },
    },
    {
      label: 'Масса, кг',
      name: 'amount_kg',
    },
    {
      label: 'Причина',
      customRenderValue: (model: SdizExtinguishVueModel): string | number => {
        return this.returnReasonName(model.reason_id);
      },
    },
    {
      label: 'Примечание',
      name: 'comment',
    },
    {
      label: 'Статус',
      name: 'status',
      customRenderValue: (model: SdizExtinguishRefusalModel): string | number => {
        if (typeof model.is_canceled === 'undefined') {
          return '<span class="text-caption success--text">Не аннулированно</span>';
        }
        return model.is_canceled
          ? '<span class="text-caption error--text">Аннулированно</span>'
          : '<span class="text-caption success--text">Не аннулированно</span>';
      },
    },
    {
      label: 'Действия',
      name: 'is_canceled',
      onClick: (model: SdizExtinguishRefusalModel) => {
        if (this.isActionsAccess && !model.is_canceled) {
          this.setRowValue(model);
          this.openCancelModal();
        }
      },
      customRenderValue: (model: any): string | number => {
        const { gpb, lot, is_canceled } = model;

        const status = lot.id === null ? gpb.status : lot.status;

        if (status === 'CREATE') return '<span class="orange--text">Подпишите партию</span>';

        if (this.isActionsAccess && !is_canceled) {
          return '<span ref="btn_canceled" class="v-chip v-chip--label theme--light v-size--default btn-canceled-chip "><span  style="cursor: pointer" class="v-chip__content">Аннулировать</span></span>';
        }
        return '-';
      },
    },
  ];

  get innerValue(): any[] {
    return this.value;
  }

  get optionsForTable(): Array<object> {
    return this.tableOptions;
  }

  createdLotLink(model: SdizExtinguishRefusalModel) {
    const id = String(model.lot.id ?? model.gpb.id);

    let routeData = this.$router.resolve({
      name: this.toLotLink,
      params: { id: id },
    });

    return routeData.href || '#';
  }

  openCancelModal() {
    this.isShowConfirmModal = true;
  }

  onCloseConfirmModal() {
    this.isShowConfirmModal = false;
    this.rowValueAftClk = new SdizExtinguishRefusalModel();
  }

  async handleSignatureModalOpen() {
    this.isShowConfirmModal = false;
    await this.$store.dispatch('agreementDocument/getNewOrStoredDocument', {
      measureId: this.rowValueAftClk.id,
      service:
        this.rowValueAftClk.gpb.id !== null
          ? `sdiz/gpb/export/extinguish/refusal/canceled`
          : `sdiz/export/extinguish/refusal/canceled`,
    });
    this.isSignatureModalOpen = true;
  }

  setRowValue(model) {
    this.rowValueAftClk = model;
  }

  async handleSignApprove() {
    this.isSignatureModalOpen = false;
    await sdizCancelSign(this);
  }

  async fetchLotReturnReason(): Promise<void> {
    const nsiConfigSection = this.nsiConfigSectionByKey('lot-return-reason');
    const { content } = await this.$store.dispatch('nsi/getList', {
      url: nsiConfigSection.apiUrl,
      params: {
        actual: true,
        pageable: {
          sort: [
            {
              property: 'name',
              direction: 'ASC',
            },
          ],
        },
      },
    });

    this.returnReasons = content || [];
  }

  async created() {
    await this.fetchLotReturnReason();
  }

  returnReasonName(id) {
    if (!id) return '-';
    const reason = this.returnReasons.find((e) => e.id === id);
    return reason?.name || '-';
  }
}
</script>
<style lang="scss" scoped>
.edit-table-extinguish::v-deep {
  .tableHeader,
  .tableListRow {
    display: flex;
    flex-direction: row;
    align-content: center;
    justify-content: space-between;
  }

  .tableHeader {
    .theader {
      width: 33.33% !important;
      text-align: center !important;
    }
  }

  .tableListRow {
    .spanList {
      width: 33.33% !important;
      text-align: center !important;
    }
  }
}
</style>
