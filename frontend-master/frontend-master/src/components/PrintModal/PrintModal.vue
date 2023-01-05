<template>
  <dialog-component
    v-model="isModalOpen"
    :prompt="false"
    cancel-title=""
    confirm-title=""
    controls-justify="justify-end"
    with-close-icon
    width="100%"
    :is-loading="isLoading"
  >
    <template #content>
      <div>
        <pdf-view
          v-if="fileData || fileError"
          :file-data="fileData"
          :loading="isLoading"
          :error-message="fileError"
          width="100%"
          height="760px"
        />
        <div class="print-modal__loader" v-else>
          <v-progress-circular indeterminate size="32"></v-progress-circular>
        </div>
      </div>
    </template>
  </dialog-component>
</template>

<script lang="ts">
import { Component, Prop, } from 'vue-property-decorator';
import { mixins } from "vue-class-component";
import { PropType } from 'vue';
import TextComponent from '@/components/common/Text/Text.vue';
import PdfView from '@/components/common/PdfView/PdfView.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import Modal from '@/utils/global/mixins/modal';

@Component({
  components: {
    DialogComponent,
    TextComponent,
    PdfView,
  },
})
export default class PrintModal extends mixins(Modal) {
  @Prop({ type: Number as PropType<number | null>, default: null }) readonly measureId!: number | null;
  @Prop({ type: String, required: true }) service!: string;

  get isLoading() {
    return this.$store.state.printDocument.printDocument.loading;
  }

  get fileData() {
    return this.$store.state.printDocument.printDocument.data || null;
  }

  get fileError() {
    return this.$store.state.printDocument.printDocument.error;
  }

  async onModalOpen() {
    await this.$store.dispatch('printDocument/getDocument', { measureId: this.measureId, service: this.service });
  }

  onModalClose() {
    this.$store.dispatch('printDocument/clearDocument');
  }
}
</script>

<style lang="scss">
.print-modal {
  &__loader {
    display: flex;
    justify-content: center;
    padding: 25vh 0;
  }
}
</style>
