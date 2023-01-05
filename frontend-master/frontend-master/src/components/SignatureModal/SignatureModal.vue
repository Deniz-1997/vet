<template>
  <dialog-component
    v-model="innerShow"
    :prompt="false"
    cancel-title=""
    confirm-title=""
    controls-justify="justify-end"
    width="800px"
    with-close-icon
    with-fullscreen
    :is-loading="isLoading"
    @onSuccess="isSignsDialogShow = true"
  >
    <template #title_close> Закрыть </template>
    <template #title>
      <text-component variant="h4"> Подписать ЭЦП </text-component>
    </template>
    <template #content>
      <pdf-view :error-message="fileError" :file-data="fileData" :loading="fileLoading" width="100%" />
      <div v-if="fileData" class="mt-4">
        <file-signature
          :file-data="fileData"
          :measure-id="measureId"
          :service="service"
          :loading="isLoading"
          @notification="onError"
          @onSign="handleSignDocument"
        />
      </div>
    </template>
  </dialog-component>
</template>

<script lang="ts">
import { Component, Model, Prop, Vue } from 'vue-property-decorator';

import { CryptoProSignature } from '@/components/common/FileSignature/FileSignature.types';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import FileSignature from '@/components/common/FileSignature/FileSignature.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import PdfView from '@/components/common/PdfView/PdfView.vue';

@Component({
  components: {
    DialogComponent,
    FileSignature,
    TextComponent,
    PdfView,
  },
})
export default class SignatureModal extends Vue {
  @Model('change') show!: boolean;

  @Prop(Boolean) fileLoading!: boolean;
  @Prop(String) fileError!: string;
  @Prop(Number) readonly measureId!: number;
  @Prop(String) service!: string;
  @Prop({
    type: Boolean,
    default: () => false,
  })
  readonly isRequest!: boolean;

  isLoading = false;

  get agreementDocument() {
    return this.$store.state.agreementDocument;
  }

  get fileData() {
    return this.agreementDocument.agreementDocument.data || null;
  }

  get fileBase64() {
    return this.agreementDocument.agreementDocument.base64 || null;
  }

  get signData() {
    return this.agreementDocument.agreementDocumentSign.data || null;
  }

  get innerShow(): boolean {
    return this.show;
  }

  set innerShow(show: boolean) {
    this.$emit('change', show);

    if (!show) {
      this.$emit('close');
    }
  }

  async handleSignDocument(cryptoProSignature: CryptoProSignature): Promise<void> {
    const { signature, certificate } = cryptoProSignature;

    try {
      this.isLoading = true;

      await this.$store.dispatch('agreementDocument/signDocument', {
        measureId: this.measureId,
        certificate,
        signature,
        pdf_image: this.fileBase64,
        service: this.service,
      });

      this.isLoading = false;

      this.$emit('approve', this.measureId);
    } catch (err: any) {
      const text = err?.response?.data?.message ?? err?.message ?? 'Ошибка при подписании документа';
      this.$service.notify.push('error', { text, params: {} });
      this.isLoading = false;
    }
  }

  onError({ text }) {
    const title = 'Невозможно использовать сертификат для электронной подписи документов:';
    this.$service.notify.push('error', { text: `${title}\n${text}`, params: {} });
  }
}
</script>
