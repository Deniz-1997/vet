<template>
  <rshn-buttons
    v-model="model"
    :edit="edit"
    :is-show="isShow"
    :create="create"
    :detail="detail"
    :loading="loading"
    delete-title="Вы действительно хотите удалить сведения о предписании?"
    @create="$emit('create')"
    @cancel="$emit('cancel')"
    @delete="$emit('delete')"
    @edit="$emit('edit')"
    @sign="$emit('sign')"
    @revoke="$emit('revoke')"
  >
    <template #extraReturnButton>
      <button-component
        v-if="detail && !edit && model.gw_id"
        title="Вернуться к изъятию"
        @click="$router.push({ name: withdrawalModel.detail_link, params: { id: model.gw_id } })"
      />
    </template>

    <template #extraButton>
      <button-component
        v-show="isStatusSubscribed"
        size="micro"
        title="Внести подтверждающие документы"
        @click="$emit('openDockModal')"
      />
    </template>
  </rshn-buttons>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import TextComponent from '@/components/common/Text/Text.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import ModalButton from '@/components/common/buttons/ModalButton.vue';
import RshnButtons from '@/views/rshn/subcomponents/Buttons/RshnButtons.vue';
import { RshnButtonsMix } from '@/utils/mixins/rshn/rshnButtons';
import { RshnWithdrawalData } from '@/models/Rshn/Withdrawal/RshnWithdrawalData.vue';

@Component({
  components: {
    RshnButtons,
    ModalButton,
    ButtonComponent,
    TextComponent,
  },
})
export default class RshnPrescriptionButtons extends Mixins(RshnButtonsMix) {
  withdrawalModel = new RshnWithdrawalData();
}
</script>

<style scoped></style>
