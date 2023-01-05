<template>
  <v-container fluid>
    <v-row class="withdrawal-find-dialog">
      <dialog-component
        :key="openModal"
        v-model="openModal"
        :prompt="false"
        :no-click-animation="true"
        cancel-title="Закрыть"
        confirm-title=""
        controls-justify="justify-end"
        @onCancel="onCancel"
      >
        <template #content>
          <rshn-list
            v-model="model"
            :is-show-additional-button="false"
            :use-filters-store="false"
            title="Поиск сведений об изъятии"
            show-select-button
            @onSelectItem="selectWithdrawal"
          >
            <template #[`filter`]="{ clear }">
              <withdrawal-filter-forms :key="clear" v-model="model">
                <template #[`status`]="{ model, rshn }">
                  <select-request-component
                    v-model="model.status_id"
                    :custom-items="rshn.statusList"
                    :disabled="target === 'prescription'"
                    label="Статус"
                    placeholder="Выберите статус"
                  />
                </template>
              </withdrawal-filter-forms>
            </template>
          </rshn-list>

          <div class="close-btn-container">
            <IconComponent :width="16" icon-color="#828286" @click="onCancel"><CloseIcon /></IconComponent>
          </div>
        </template>
      </dialog-component>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import SignatureModal from '@/components/SignatureModal/SignatureModal.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import RshnList from '@/views/rshn/components/List.vue';
import WithdrawalFilterForms from '@/views/rshn/subcomponents/FilterForms/WithdrawalFilterForms.vue';
import { RshnWithdrawalShort } from '@/models/Rshn/ShortModel/RshnWithdrawalShort.vue';
import TextComponent from '@/components/common/TextComponent.vue';
import IconComponent from '@/components/common/IconComponent/IconComponent.vue';
import CloseIcon from '@/components/common/IconComponent/icons/CloseIcon.vue';

@Component({
  components: {
    CloseIcon,
    IconComponent,
    TextComponent,
    WithdrawalFilterForms,
    RshnList,
    DialogComponent,
    SelectRequestComponent,
    SignatureModal,
  },
})
export default class WithdrawalFindDialog extends Vue {
  @Prop({ type: Boolean, default: false }) isOpen!: boolean;
  @Prop({ type: String, default: '' }) target!: string;

  model: RshnWithdrawalShort = new RshnWithdrawalShort();

  get openModal() {
    return this.isOpen;
  }

  set openModal(value: any) {
    this.$emit('isOpenFindWithdrawal', value);
  }

  onCancel() {
    this.openModal = !this.openModal;
  }

  selectWithdrawal(item) {
    this.onCancel();
    this.$emit('onSelect', item);
  }
}
</script>

<style lang="scss" scoped>
.close-btn-container {
  position: absolute;
  top: 20px;
  right: 20px;
}
</style>
