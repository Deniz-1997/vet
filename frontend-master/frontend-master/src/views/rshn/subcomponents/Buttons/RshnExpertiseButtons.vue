<template>
  <rshn-buttons
    v-model="model"
    :edit="edit"
    :is-show="isShow"
    :create="create"
    :detail="detail"
    :loading="loading"
    delete-title="Вы действительно хотите удалить сведения об экспертизе?"
    @create="$emit('create')"
    @cancel="$emit('cancel')"
    @delete="$emit('delete')"
    @edit="$emit('edit')"
    @sign="$emit('sign')"
    @revoke="$emit('revoke')"
  >
    <template #revokeButton>
      <ButtonComponent
        v-show="isStatusSubscribed && canBeRevoked"
        size="micro"
        title="Аннулировать"
        @click="$emit('revoke')"
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
import { ExpertiseEnum } from '@/utils/enums/RshnEnums';

@Component({
  components: {
    RshnButtons,
    ModalButton,
    ButtonComponent,
    TextComponent,
  },
})
export default class RshnExpertiseButtons extends Mixins(RshnButtonsMix) {
  get canBeRevoked() {
    return this.model.expertise_type === ExpertiseEnum.WITHDRAWAL;
  }
}
</script>
