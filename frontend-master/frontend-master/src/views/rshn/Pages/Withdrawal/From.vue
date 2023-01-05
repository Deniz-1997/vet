<template>
  <rshn-form
    v-model="value"
    :detail="detail"
    :create="create"
    :update-link="updateLink"
    :is-show="isShow"
    :edit="edit"
    title-create="Внесение сведений об изъятии"
    title-detail="Просмотр  сведений об изъятии"
    title-edit="Редактирование сведений об изъятии"
    @edit="$emit('edit')"
  >
    <template #subHeader>
      <span class="text-subtitle-1 text--secondary">{{ subHeader }}</span>
    </template>
    <template #tabs>
      <tab-component v-if="create" v-model="model.gw_type" :edit="edit" :detail="detail" :tab-list="rshn.tabList" />
    </template>
    <template #forms>
      <withdrawal-forms v-model="model" :type="model.gw_type" :detail="detail" :edit="edit" />
    </template>
    <template #activities>
      <ActivitiesWithdrawal
        v-if="model.status_id === status.SUBSCRIBED"
        v-model="model"
        :edit="edit"
        :detail="detail"
        :tab-list="rshn.tabListWithdrawalActivities"
        @edit="handleRestrictionEdit"
      />
    </template>
    <template #buttons>
      <rshn-withdrawal-buttons
        v-model="model"
        :edit="edit"
        :is-show="isShow"
        :create="create"
        :detail="detail"
        :loading="loading"
        @create="handleCreate"
        @cancel="cancel"
        @delete="handleDelete"
        @edit="handleWithdrawalEdit"
        @sign="handleSignatureModalOpen(status.SUBSCRIBED, model.id)"
        @revoke="handleSignatureModalOpen(status.CANCELED, model.id)"
        @openRestrictionModal="openRestrictionModal"
        @createPrescription="createPrescription"
        @createExcerpt="createExcerpt"
      />
    </template>
    <template #signature>
      <SignatureModal v-model="isSignatureModalOpen" :measure-id="measureId" @approve="handleSignApprove" />
    </template>
    <template #dialog>
      <restrictions-dialog
        v-if="model.status_id === status.SUBSCRIBED"
        :value="selectedRestriction"
        :is-open="isOpenDialog"
        :action="restrictionAction"
        @is-open="setIsOpenDialog"
        @on-success="handleRestrictionAction"
      />
    </template>
  </rshn-form>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import TextComponent from '@/components/common/Text/Text.vue';
import SignatureModal from '@/components/SignatureModal/SignatureModal.vue';
import WithdrawalForms from '@/views/rshn/subcomponents/Forms/WithdrawalFroms.vue';
import TabComponent from '@/views/rshn/subcomponents/TabComponent.vue';
import RestrictionsDialog, { RestrictionAction } from '@/views/rshn/subcomponents/Dialog/RestrictionsDialog.vue';
import { StatusEnum, WithdrawalTypeEnum } from '@/utils/enums/RshnEnums';
import { RestrictionsData } from '@/models/Rshn/Withdrawal/RestrictionsData';
import ActivitiesWithdrawal from '@/views/rshn/subcomponents/Tables/ActivitiesWithdrawal.vue';
import RshnWithdrawalButtons from '@/views/rshn/subcomponents/Buttons/RshnWithdrawalButtons.vue';
import RshnForm from '@/views/rshn/components/From.vue';
import { rshnConsts } from '@/utils/consts/rshnConsts';
import { RshnFormMix } from '@/utils/mixins/rshn/rshnForm';
import { GetDocumentForSignMix } from '@/utils/mixins/getDocumentForSign';
import { RshnWidthdrawalValidation } from '@/utils/mixins/rshn/rshnWidthdrawalValidation';

@Component({
  name: 'rshn-withdrawal-form',
  components: {
    RshnForm,
    RshnWithdrawalButtons,
    ActivitiesWithdrawal,
    RestrictionsDialog,
    TabComponent,
    WithdrawalForms,
    SignatureModal,
    TextComponent,
  },
})
export default class RshnWithdrawalForm extends Mixins(RshnFormMix, GetDocumentForSignMix, RshnWidthdrawalValidation) {
  typeTab = WithdrawalTypeEnum;
  selectedRestrictionId: number | null = null;
  restriction = new RestrictionsData();
  restrictionAction: RestrictionAction = RestrictionAction.CREATE;

  get subHeader() {
    return rshnConsts.typeWithdrawalList.find((val) => val.id === this.model.gw_type)?.name;
  }

  get signedRestrictionId() {
    return this.model.restrictions.find((val) => val.status_id === StatusEnum.SUBSCRIBED)?.id;
  }

  get isSubscribedRestriction() {
    return this.model.restrictions.find((val) => val.status_id === StatusEnum.SUBSCRIBED);
  }

  created() {
    if (this.create) this.model.gw_type = this.typeTab.STORAGE;
  }

  async handleRestrictionAction(restriction: RestrictionsData, action: RestrictionAction) {
    switch (action) {
      case RestrictionAction.CREATE: {
        await this.createRestriction(restriction);
        break;
      }
      case RestrictionAction.EDIT: {
        await this.updateRestriction(restriction);
        break;
      }
      case RestrictionAction.TAKEOFF: {
        await this.initiateUnbanRestriction(restriction);
        break;
      }
    }
  }

  async handleSignApprove(): Promise<void> {
    this.isSignatureModalOpen = false;

    switch (this.typeSignature) {
      case this.status.SUBSCRIBED:
        await this.handleSignFromDescription(this.model.id, this.model.subscribe_service);
        break;
      case this.status.CANCELED:
        await this.handleSignFromDescription(this.model.id, this.model.cancel_service);
        break;
      case this.status.TAKEOFF:
        await this.handleSignFromDescription(this.signedRestrictionId, this.restriction.takeoff_service);
        break;
    }
  }

  createPrescription() {
    this.goToCreatePage('rshn_prescription_create');
  }

  createExcerpt() {
    this.goToCreatePage('rshn_expertise_create');
  }

  goToCreatePage(name) {
    this.$router.push({ name: name, query: { withdrawal_id: String(this.model.id) } });
  }

  setIsOpenDialog(value: boolean) {
    this.isOpenDialog = value;
    if (!value) this.selectedRestrictionId = null;
  }

  get selectedRestriction() {
    if (!this.selectedRestrictionId) return this.restriction;
    const idx = this.model.restrictions.findIndex((e) => e.id === this.selectedRestrictionId);
    return idx !== -1 ? this.model.restrictions[idx] : this.restriction;
  }

  openRestrictionModal(action: RestrictionAction) {
    this.restrictionAction = action;
    this.isOpenDialog = !this.isOpenDialog;
  }

  async createRestriction(model: RestrictionsData) {
    model.operator_id = this.subjectId;
    model.gw_id = this.model.id;
    const { response, status } = await this.$store.dispatch(
      'rshn/createWithdrawalRestriction',
      model.getDataForCreateOrUpdate()
    );
    if (status) this.model.restrictions.push(new RestrictionsData(response));
  }

  handleRestrictionEdit(model: RestrictionsData) {
    this.selectedRestrictionId = model.id;
    this.openRestrictionModal(RestrictionAction.EDIT);
  }

  async updateRestriction(model: RestrictionsData) {
    const { response, status } = await this.$store.dispatch('rshn/updateWithdrawalRestriction', {
      id: model.id,
      data: model.getDataForCreateOrUpdate(),
    });

    if (status) {
      const idx = this.model.restrictions.findIndex((e) => e.id === model.id);
      this.$set(this.model.restrictions, idx, new RestrictionsData(response));
    }
  }

  async initiateUnbanRestriction(data: RestrictionsData) {
    try {
      this.typeSignature = StatusEnum.TAKEOFF;
      this.measureId = this.signedRestrictionId;
      await this.getNewOrStoredDocument(this.signedRestrictionId, data.export_takeoff_apiendpoint);
      this.isSignatureModalOpen = true;
    } catch (_e) {
      this.$service.notify.push('error', { text: 'Ошибка при получении документа для подписания' });
    }
  }

  async handleCreate(): Promise<void> {
    try {
      const isValid = await this.isWithdrawalValid(this);
      if (!isValid) return;

      this.model.operator_id = this.subjectId;
      const { response, status } = await this.$store.dispatch(this.model.create_apiendpoint, {
        data: this.model.getDataForCreateOrUpdate(),
      });
      if (status) await this.$router.push({ name: this.model.detail_link, params: { id: response.id } });
    } catch (e) {
      this.$notify({ group: 'rshn', type: 'warning', title: e as string });
    }
  }

  async handleWithdrawalEdit(): Promise<void> {
    try {
      this.loading = true;
      if (!this.edit) {
        this.$emit('edit', true);
      } else {
        const isValid = await this.isWithdrawalValid(this);
        if (!isValid) return;

        const { status, response } = await this.$store.dispatch(this.model.update_apiendpoint, {
          data: this.model.getDataForCreateOrUpdate(),
          id: this.model.id,
        });
        if (status) {
          this.$emit('edit', false);
          this.model = this.model.createNewModel(response);
          this.$notify({ group: 'rshn', type: 'success', title: 'Операция выполнена успешно' });
        }
      }
    } catch (e) {
      this.$notify({ group: 'rshn', type: 'warning', title: e as string });
    } finally {
      this.loading = false;
    }
  }
}
</script>
