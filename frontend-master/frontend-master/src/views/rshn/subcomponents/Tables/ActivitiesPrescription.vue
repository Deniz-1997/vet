<template>
  <v-container fluid class="mt-8">
    <v-row>
      <tab-component v-if="detail" v-model="tab" :edit="edit" :detail="detail" :tab-list="tabList" @click.prevent />
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
import TabComponent from '@/views/rshn/subcomponents/TabComponent.vue';
import SignatureModal from '@/components/SignatureModal/SignatureModal.vue';
import { ActivitiesPrescriptionEnum, StatusEnum } from '@/utils/enums/RshnEnums';
import { RshnActivity } from '@/utils/mixins/rshn/rshnActivity';
import ActivitiesTable from '@/views/rshn/subcomponents/Tables/ActivitiesTable.vue';

@Component({
  components: { ActivitiesTable, SignatureModal, TabComponent },
})
export default class ActivitiesPrescription extends Mixins(RshnActivity) {
  updateLink = 'rshn/updatePrescriptionDoc';
  typeTab = ActivitiesPrescriptionEnum;
  tab = this.typeTab.DOCKS;
  status = StatusEnum;

  header_docks = [
    {
      label: 'Номер документа',
      name: 'gpd_number',
      width: 500,
    },
    {
      label: 'Дата',
      name: 'enter_date',
      width: 500,
    },
    {
      label: 'Вид документа',
      name: 'gpd_type_translate',
      width: 500,
    },
    {
      label: 'Статус',
      name: 'status_translate',
      width: 500,
    },
    this.getActionButtons(this, { isEditButton: true, isDetailButton: false }),
  ];

  get optionsForTable(): Array<any> {
    switch (this.tab) {
      default:
        return this.header_docks;
    }
  }

  get modelName() {
    switch (this.tab) {
      default:
        return this.model.docs;
    }
  }
}
</script>

<style lang="scss" scoped></style>
