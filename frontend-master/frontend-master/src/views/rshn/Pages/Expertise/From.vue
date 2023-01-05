<template>
  <div>
    <rshn-form
      v-model="value"
      :detail="detail"
      :create="create"
      :update-link="updateLink"
      :is-show="isShow"
      :edit="edit"
      title-create="Внесение сведений об экспертизе"
      title-detail="Просмотр сведений об экспертизе"
      title-edit="Редактирование сведений об экспертизе"
      @edit="$emit('edit')"
    >
      <template #subHeader>
        <span class="text-subtitle-1 text--secondary">{{ subHeader }}</span>
      </template>
      <template #tabs>
        <tab-component v-if="create" v-model="model.expertise_type" :edit="edit" :detail="detail" :tab-list="tabList" />
      </template>
      <template #forms>
        <ExpertiseForms
          v-model="model"
          :type="formsType"
          :detail="detail"
          :create="create"
          :edit="edit"
          :sign-action-processed="signActionProcessed"
          @sign-action-processed="signActionProcessed = true"
        />
      </template>
      <template #buttons>
        <RshnExpertiseButtons
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
          @sign="initiateSign"
          @revoke="handleSignatureModalOpen(status.CANCELED, model.id)"
        />
      </template>
      <template #signature>
        <SignatureModal v-model="isSignatureModalOpen" :measure-id="measureId" @approve="handleSignApprove" />
      </template>
    </rshn-form>

    <DialogComponent
      v-model="isSignConfirmationDialog"
      :prompt="false"
      cancel-title="Отмена"
      confirm-title="Подтверждаю"
      controls-justify="justify-end"
      with-close-icon
      @onSuccess="handleSignatureModalOpen(status.SUBSCRIBED, model.id)"
    >
      <template #title>Подтверждение подписания</template>
      <template #content>
        <TextComponent variant="p" class="mt-3">
          Документ СДИЗ будет <b :class="{ 'red--text': !model.conformity_sign }">{{ sdizActionToConfirm }}</b
          >, данное действие необратимо.
        </TextComponent>
      </template>
    </DialogComponent>
  </div>
</template>

<script lang="ts">
import { Component, Watch, Mixins } from 'vue-property-decorator';
import TextComponent from '@/components/common/Text/Text.vue';
import SignatureModal from '@/components/SignatureModal/SignatureModal.vue';
import RshnForm from '@/views/rshn/components/From.vue';
import { ExpertiseEnum } from '@/utils/enums/RshnEnums';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import TabComponent from '@/views/rshn/subcomponents/TabComponent.vue';
import ExpertiseForms from '@/views/rshn/subcomponents/Forms/ExpertiseForms.vue';
import RshnExpertiseButtons from '@/views/rshn/subcomponents/Buttons/RshnExpertiseButtons.vue';
import { RshnFormMix } from '@/utils/mixins/rshn/rshnForm';
import { SdizShortVue } from '@/models/Rshn/ShortModel/SdizShort.vue';
import { QualityIndicatorsVueModel } from '@/models/Lot/QualityIndicators.vue';

@Component({
  name: 'rshn-expertise-form',
  components: {
    RshnExpertiseButtons,
    ExpertiseForms,
    TabComponent,
    RshnForm,
    SignatureModal,
    TextComponent,
    DialogComponent,
  },
})
export default class RshnExpertiseForm extends Mixins(RshnFormMix) {
  loadNestedDataAfterSignActions = true;

  isSignConfirmationDialog = false;

  initiateSign() {
    if (this.model.expertise_type === ExpertiseEnum.WITHDRAWAL) {
      this.handleSignatureModalOpen(this.status.SUBSCRIBED, this.model.id);
    } else {
      this.isSignConfirmationDialog = true;
    }
  }

  get sdizActionToConfirm() {
    return this.model.conformity_sign ? 'подтвержден' : 'аннулирован';
  }

  typeTab = ExpertiseEnum;
  get formsType() {
    if (this.create) return this.model.expertise_type;
    return this.model.expertise_type;
  }
  get subHeader() {
    return this.rshn.tabListExpertise.find((val) => val.type === this.model.expertise_type)?.name;
  }

  created() {
    if (this.create) this.model.expertise_type = this.typeTab.WITHDRAWAL;
  }

  get tabList() {
    const tabs = this.rshn.tabListExpertise;

    return tabs.map((e) => {
      (e as any).isDisabled = this.disabledTabs.includes(e.type);
      return e;
    });
  }

  get disabledTabs(): ExpertiseEnum[] {
    const tabs: ExpertiseEnum[] = [];

    const isSdizSet = !!this.model.sdiz_id;
    const isWithdrawalSet = !!this.model.gw_id;

    if (isSdizSet) tabs.push(ExpertiseEnum.WITHDRAWAL);
    if (isWithdrawalSet) tabs.push(ExpertiseEnum.IMPORT, ExpertiseEnum.EXPORT);

    return tabs;
  }

  @Watch('model.expertise_type')
  onExpertiseTypeChange(v) {
    if (v === ExpertiseEnum.WITHDRAWAL) {
      this.model.conformity_sign = null;
      this.model.is_not_conducted = null;
    }

    this.model.gw_id = null;
    this.model.sdiz_id = null;
    this.model.gpb_sdiz_id = null;
    this.model.sdiz_number = null;
    this.model.sdiz = new SdizShortVue();
    this.model.attachedDocumentEnterDate = null;
    this.model.quality_indicators = [] as QualityIndicatorsVueModel[];
  }
}
</script>
