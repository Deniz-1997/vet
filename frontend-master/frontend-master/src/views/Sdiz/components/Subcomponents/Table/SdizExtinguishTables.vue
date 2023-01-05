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
    <v-overlay :value="isLoading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>

    <SignatureModal v-model="isSignatureModalOpen" :measure-id="measureId" @approve="handleSignApprove" />
  </v-col>
</template>

<script lang="ts">
import { Component, Model, Prop } from 'vue-property-decorator';
import { mixins } from 'vue-class-component';
import { AdditionalMix } from '@/utils/mixins/additional';
import SdizTable from '@/views/Sdiz/components/Subcomponents/Table/SdizTable.vue';
import { SdizExtinguishVueModel } from '@/models/Sdiz/SdizExtinguish';
import ConfirmModalDelete from '@/views/Authorities/components/Modal/ConfirmModalDelete.vue';
import SignatureModal from '@/components/SignatureModal/SignatureModal.vue';
import { sdizCancelSign } from '@/utils/sdizCancelSign';

@Component({
  name: 'sdiz-extinguish-tables',
  components: { SignatureModal, ConfirmModalDelete, SdizTable },
})
export default class SdizExtinguishTables extends mixins(AdditionalMix) {
  @Model('change', { type: Array, required: true }) value!: SdizExtinguishVueModel[];
  @Prop({ type: String, required: true }) cancelSignService!: string;
  @Prop({ type: String, required: true }) toLotLink!: string;

  isSignatureModalOpen = false;
  measureId = 0;
  rowValueAftClk: SdizExtinguishVueModel = new SdizExtinguishVueModel();
  isShowConfirmModal = false;
  isLoading = false;
  discrepancyCauses: any = [];

  tableOptions = [
    {
      label: 'Дата погашения',
      name: 'operation_date',
    },
    {
      label: 'Сформированная партия',
      name: 'lot_number',
      customRenderValue: (model: SdizExtinguishVueModel): string | number => {
        const { gpb, lot } = model;

        if (gpb.id === null && lot.id === null) {
          return 'Партия не указана';
        }

        const num = lot.id === null ? gpb.gpb_number : lot.lot_number;

        return `<a href="${this.createdLotLink(model)}">${num ?? gpb.id ?? lot.id}</a>`;
      },
    },
    {
      label: 'Масса погашения, кг',
      name: 'amount_kg',
    },
    {
      label: 'Полное погашение',
      customRenderValue: (model: SdizExtinguishVueModel): string | number => {
        return model.full_use ? 'Да' : 'Нет';
      },
    },
    {
      label: 'Причина',
      customRenderValue: (model: SdizExtinguishVueModel): string | number => {
        return this.weightDisperancyCauseName(model.reason_id);
      },
    },
    {
      label: 'Примечание',
      name: 'comment',
    },
    {
      label: 'Статус',
      name: 'status',
      customRenderValue: (model: SdizExtinguishVueModel): string | number => {
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
      onClick: (model: SdizExtinguishVueModel) => {
        if (
          this.isCreatedByUser(model.created_by_id) &&
          !model.is_canceled &&
          (!this.fullUseExtinguish || this.fullUseExtinguishId === model.id)
        ) {
          this.setRowValue(model);
          this.openCancelModal();
        }
      },
      customRenderValue: (model: any): string | number => {
        const { gpb, lot } = model;

        const status = lot.id === null ? gpb.status : lot.status;

        if (status === 'CREATE') return '<span class="orange--text">Подпишите партию</span>';

        if (this.isCreatedByUser(model.created_by_id) && !model.is_canceled) {
          if (this.fullUseExtinguish) {
            const isFullExtinguishEntry = this.fullUseExtinguishId === model.id;

            const cancelButton =
              '<span ref="btn_canceled" class="v-chip v-chip--label theme--light v-size--default btn-canceled-chip "><span  style="cursor: pointer" class="v-chip__content">Аннулировать</span></span>';
            const text = '<span class="orange--text">Аннулируйте погашение с признаком полное погашение</span>';

            return isFullExtinguishEntry ? cancelButton : text;
          } else {
            return '<span ref="btn_canceled" class="v-chip v-chip--label theme--light v-size--default btn-canceled-chip "><span  style="cursor: pointer" class="v-chip__content">Аннулировать</span></span>';
          }
        }
        return '-';
      },
    },
  ];

  get fullUseExtinguish() {
    const data = this.innerValue.filter((e) => e.full_use && !e.is_canceled);
    return data.length ? data[0] : null;
  }

  get fullUseExtinguishId() {
    return this.fullUseExtinguish ? this.fullUseExtinguish.id : null;
  }

  get innerValue(): any[] {
    return this.value;
  }

  get optionsForTable(): Array<object> {
    return this.tableOptions;
  }

  isCreatedByUser(creator) {
    return creator === this.$store.state.auth.user.subject.subject_id;
  }

  createdLotLink(model: SdizExtinguishVueModel) {
    const id = String(model.lot.id ?? model.gpb.id);

    let routeData = this.$router.resolve({
      name: this.toLotLink,
      params: { id: id },
    });

    return routeData.href || '#';
  }

  onCloseConfirmModal() {
    this.isShowConfirmModal = false;
    this.isSignatureModalOpen = false;
    this.rowValueAftClk = new SdizExtinguishVueModel();
  }

  openCancelModal() {
    this.isShowConfirmModal = true;
  }

  setRowValue(model) {
    this.rowValueAftClk = model;
  }

  async handleSignatureModalOpen() {
    this.isShowConfirmModal = false;
    await this.$store.dispatch('agreementDocument/getNewOrStoredDocument', {
      measureId: this.rowValueAftClk.id,
      service:
        this.rowValueAftClk.gpb.id !== null ? `sdiz/gpb/export/extinguish/canceled` : `sdiz/export/extinguish/canceled`,
    });
    this.isSignatureModalOpen = true;
  }

  async handleSignApprove() {
    this.isSignatureModalOpen = false;
    await sdizCancelSign(this);
  }

  async fetchWeightDisperancyCauses(): Promise<void> {
    const nsiConfigSection = this.nsiConfigSectionByKey('weight-disperancy-cause');
    const { content } = await this.$store.dispatch('nsi/getList', {
      url: nsiConfigSection.apiUrl,
      params: {
        actual: false,
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

    this.discrepancyCauses = content || [];
  }

  async created() {
    await this.fetchWeightDisperancyCauses();
  }

  weightDisperancyCauseName(id) {
    if (!id) return '-';

    const reason = this.discrepancyCauses.find((e) => e.id === id);
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
