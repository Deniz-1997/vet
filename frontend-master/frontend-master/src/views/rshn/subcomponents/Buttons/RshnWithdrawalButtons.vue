<template>
  <rshn-buttons
    v-model="model"
    :edit="edit"
    :is-show="isShow"
    :create="create"
    :detail="detail"
    :loading="loading"
    delete-title="Вы действительно хотите удалить сведения об изъятии?"
    @create="$emit('create')"
    @cancel="$emit('cancel')"
    @delete="$emit('delete')"
    @edit="$emit('edit')"
    @sign="$emit('sign')"
    @revoke="$emit('revoke')"
  >
    <template #extraButton>
      <button-component
        v-show="showRestrictionButton"
        size="micro"
        :title="titleRestriction"
        @click="$emit('openRestrictionModal', restrictionAction)"
      />
      <button-component
        v-show="isStatusSubscribed"
        size="micro"
        title="Создать экспертизу"
        @click="$emit('createExcerpt')"
      />
      <button-component
        v-show="isStatusSubscribed"
        size="micro"
        title="Создать предписание"
        @click="$emit('createPrescription')"
      />
    </template>
  </rshn-buttons>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import TextComponent from '@/components/common/Text/Text.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import ModalButton from '@/components/common/buttons/ModalButton.vue';
import { RshnButtonsMix } from '@/utils/mixins/rshn/rshnButtons';
import RshnButtons from '@/views/rshn/subcomponents/Buttons/RshnButtons.vue';
import { RestrictionAction } from '@/views/rshn/subcomponents/Dialog/RestrictionsDialog.vue';

@Component({
  components: {
    RshnButtons,
    ModalButton,
    ButtonComponent,
    TextComponent,
  },
})
export default class RshnWithdrawalButtons extends Mixins(RshnButtonsMix) {
  get showRestrictionButton() {
    const isSignedPrescription = this.model.prescriptions.find((e) => e.status_id === this.status.SUBSCRIBED);

    const createdRestriction = !!this.model.restrictions.find((val) => val.status_id === this.status.CREATE);

    if (this.isSubscribedRestriction) {
      return true;
    } else {
      return this.model.status_id === this.status.SUBSCRIBED && isSignedPrescription && !createdRestriction;
    }
  }

  get isSubscribedRestriction() {
    return !!this.model.restrictions.find((val) => val.status_id === this.status.SUBSCRIBED);
  }

  get titleRestriction() {
    return this.isSubscribedRestriction ? 'Снять запрет' : 'Установить запрет';
  }

  get restrictionAction() {
    return this.isSubscribedRestriction ? RestrictionAction.TAKEOFF : RestrictionAction.CREATE;
  }
}
</script>

<style scoped></style>
