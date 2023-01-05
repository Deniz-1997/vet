<template>
  <v-container v-if="model" fluid class="mt-8">
    <v-row>
      <tab-component v-if="detail" v-model="tab" :edit="edit" :detail="detail" :tab-list="tabList" />
      <v-col cols="12">
        <ActivitiesTable
          v-model="modelName"
          :is-create="false"
          :is-edit="false"
          :options-for-table="optionsForTable"
          class_="edit-table-extinguish"
          title=""
          empty-text="Ничего не найдено"
        />
        <SignatureModal
          v-model="isSignatureModalOpen"
          :measure-id="measureId"
          @approve="handleSignApprove"
          @close="handleSignatureModalClose"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import { RshnActivity } from '@/utils/mixins/rshn/rshnActivity';
import TabComponent from '@/views/rshn/subcomponents/TabComponent.vue';
import ActivitiesTable from '@/views/rshn/subcomponents/Tables/ActivitiesTable.vue';
import SignatureModal from '@/components/SignatureModal/SignatureModal.vue';
import { ActivitiesWithdrawalEnum, StatusEnum } from '@/utils/enums/RshnEnums';

@Component({
  components: { ActivitiesTable, SignatureModal, TabComponent },
})
export default class ActivitiesWithdrawal extends Mixins(RshnActivity) {
  updateLink = 'rshn/updateWithdrawalRestriction';
  typeTab = ActivitiesWithdrawalEnum;
  status = StatusEnum;

  tab = this.typeTab.PRESCRIPTIONS;

  get exportPdfEndpoint() {
    switch (this.tab) {
      case this.typeTab.EXPERTISES:
        return this.header_expertises;
      case this.typeTab.PRESCRIPTIONS:
        return this.header_prescription;
      default:
        return this.header_restrictions;
    }
  }

  header_prescription = [
    { label: 'Номер предписания', name: 'id', width: 500 },
    { label: 'Номер документа', name: 'gp_row_number', width: 500 },
    { label: 'Изолированное хранение', name: 'restrictions_bin_convert', width: 500 },
    { label: 'Сведения об ограничениях действия с партией', name: 'restrictions_text_convert', width: 500 },
    {
      label: 'Статус',
      name: 'status_translate',
      width: 500,
    },
    this.getActionButtons(this, { isDetailButton: true, isEditButton: false }),
  ];

  header_expertises = [
    { label: 'Номер экспертизы', name: 'expertise_number', width: 500 },
    { label: 'Номер пробы', name: 'selection_number', width: 500 },
    { label: 'Тип экспертизы', name: 'expertise_type_translate', width: 500 },
    { label: 'Статус', name: 'status_translate', width: 500 },
    this.getActionButtons(this, { isDetailButton: true, isEditButton: false }),
  ];

  header_restrictions = [
    {
      label: 'Дата',
      name: 'enter_date',
      width: 500,
    },
    {
      label: 'Тип запрета',
      name: 'restriction_type_translate',
      width: 500,
    },
    {
      label: 'Должностное лицо',
      name: 'operator.full_name',
      width: 500,
    },
    {
      label: 'Статус',
      name: 'status_translate',
      width: 500,
    },
    this.getActionButtons(this, { isDetailButton: false, isEditButton: true }),
  ];

  get optionsForTable(): Array<any> {
    switch (this.tab) {
      case this.typeTab.EXPERTISES:
        return this.header_expertises;
      case this.typeTab.PRESCRIPTIONS:
        return this.header_prescription;
      default:
        return this.header_restrictions;
    }
  }

  get modelName() {
    switch (this.tab) {
      case ActivitiesWithdrawalEnum.EXPERTISES:
        return this.model.expertises;
      case ActivitiesWithdrawalEnum.PRESCRIPTIONS:
        return this.model.prescriptions;
      default:
        return this.model.restrictions;
    }
  }
}
</script>

<style lang="scss" scoped></style>
