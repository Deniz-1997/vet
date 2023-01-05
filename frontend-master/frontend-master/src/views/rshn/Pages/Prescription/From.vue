<template>
  <rshn-form
    v-model="value"
    :detail="detail"
    :create="create"
    :update-link="updateLink"
    :is-show="isShow"
    :edit="edit"
    title-create="Внесение сведений об предписании"
    title-detail="Просмотр сведений об предписании"
    title-edit="Редактирование сведений об предписании"
    @edit="$emit('edit')"
  >
    <template #forms>
      <PrescriptionFroms
        v-model="model"
        :detail="detail"
        :edit="edit"
        :create="create"
        :sign-action-processed="signActionProcessed"
        @sign-action-processed="signActionProcessed = true"
      />
    </template>
    <template #activities>
      <ActivitiesPrescription
        v-if="model.status_id === status.SUBSCRIBED"
        v-model="model"
        :detail="detail"
        :edit="edit"
        :tab-list="rshn.tabListPrescriptionActivities"
        @edit="handleDocEdit"
      />
    </template>
    <template #buttons>
      <RshnPrescriptionButtons
        v-model="model"
        :edit="edit"
        :is-show="isShow"
        :create="create"
        :detail="detail"
        :loading="loading"
        @create="handleCreate"
        @cancel="cancel"
        @delete="handleDelete"
        @edit="handleEdit"
        @sign="handleSignatureModalOpen(status.SUBSCRIBED, model.id)"
        @revoke="handleSignatureModalOpen(status.CANCELED, model.id)"
        @openDockModal="openDockDialog"
      />
    </template>
    <template #signature>
      <SignatureModal v-model="isSignatureModalOpen" :measure-id="measureId" @approve="handleSignApprove" />
    </template>
    <template #dialog>
      <PrescriptionDialog
        :value="selectedDoc"
        :is-open="isOpenModal"
        @isOpen="onShowModal"
        @createDock="createDock"
        @updateDock="updateDock"
      />
    </template>
  </rshn-form>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import TextComponent from '@/components/common/Text/Text.vue';
import SignatureModal from '@/components/SignatureModal/SignatureModal.vue';
import PrescriptionFroms from '@/views/rshn/subcomponents/Forms/PrescriptionFroms.vue';
import RshnPrescriptionButtons from '@/views/rshn/subcomponents/Buttons/RshnPrescriptionButtons.vue';
import RshnForm from '@/views/rshn/components/From.vue';
import ActivitiesPrescription from '@/views/rshn/subcomponents/Tables/ActivitiesPrescription.vue';
import PrescriptionDialog from '@/views/rshn/subcomponents/Dialog/PrescriptionDialog.vue';
import { PrescriptionDocData } from '@/models/Rshn/Prescription/PrescriptionDocData';
import { RshnFormMix } from '@/utils/mixins/rshn/rshnForm';

@Component({
  name: 'rshn-prescription-form',
  components: {
    PrescriptionDialog,
    ActivitiesPrescription,
    RshnForm,
    RshnPrescriptionButtons,
    PrescriptionFroms,
    SignatureModal,
    TextComponent,
  },
})
export default class RshnPrescriptionForm extends Mixins(RshnFormMix) {
  isOpenModal = false;

  selectedDocId: number | null = null;

  newDoc = new PrescriptionDocData();

  loadNestedDataAfterSignActions = true;

  openDockDialog() {
    this.isOpenModal = !this.isOpenModal;
  }

  prepareDocData(event: any) {
    const { enter_date, gpd_number, content, gpd_type } = event;

    const data = {
      enter_date,
      gpd_number,
      gpd_type,
      content,
      gp_id: this.model.id,
      operator_id: this.subjectId,
    };

    return data;
  }

  async createDock(event) {
    const data = this.prepareDocData(event);

    const { response, status } = await this.$store.dispatch('rshn/createPrescriptionDoc', data);
    if (status) this.model.docs.push(new PrescriptionDocData(response));
  }

  async updateDock(event) {
    const data = this.prepareDocData(event);

    const { response, status } = await this.$store.dispatch('rshn/updatePrescriptionDoc', { id: event.id, data });

    if (status) {
      const idx = this.model.docs.findIndex((e) => e.id === event.id);
      this.$set(this.model.docs, idx, new PrescriptionDocData(response));
    }
  }

  onShowModal(value: boolean) {
    this.isOpenModal = value;
    if (!value) this.selectedDocId = null;
  }

  handleDocEdit(model: PrescriptionDocData) {
    this.selectedDocId = model.id;
    this.onShowModal(true);
  }

  get selectedDoc() {
    if (!this.selectedDocId) return this.newDoc;
    const idx = this.model.docs.findIndex((e) => e.id === this.selectedDocId);
    return idx !== -1 ? this.model.docs[idx] : this.newDoc;
  }
}
</script>
