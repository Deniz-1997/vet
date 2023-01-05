<template>
  <div class="wrapper">
    <select-component
      v-model="selectedCertificate"
      :items="certificatesList"
      class="list"
      item-text="subjectName"
      label="Выберите сертификат"
      no-data-text="У вас нет валидных сертификатов"
      variant="micro"
      return-object
      hide-details
    />
    <default-button
      :disabled="!selectedCertificate || isLoading || loading"
      type="button"
      class="ml-2"
      size="micro"
      title="Подписать документ"
      variant="primary"
      @click="signFile"
    />
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import { CryptoProSignature } from './FileSignature.types';
import { Certificate, enlistCertificates, isPluginInstalled, signData } from '@/libs/cadesplugin';
import { Notification } from '@/components/common/Notification/Notification.types';

import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';

@Component({
  name: 'file-signature',
  components: {
    DefaultButton,
    SelectComponent,
  },
})
export default class FileSignature extends Vue {
  @Prop(String) fileData!: string;
  @Prop({ type: Object, default: () => ({}) }) signData!: any;
  @Prop({ type: Boolean, default: false }) readonly loading!: boolean;

  isLoading = false;
  certificatesList: Certificate[] = [];
  selectedCertificate: Certificate | null = null;

  errors = {
    objid: 'Вы не выбрали сертификат',
  };

  get file() {
    return this.$store.state.agreementDocument.agreementDocument.file;
  }

  async signFile(): Promise<void> {
    this.isLoading = true;
    try {
      const { thumbprint } = this.selectedCertificate as Certificate;
      const signature = this.file
        ? await this.$service.signature.signHash(this.file, thumbprint)
        : await signData(thumbprint, this.fileData);
      const signatureData: CryptoProSignature = {
        ...this.signData,
        certificate: this.selectedCertificate as Certificate,
        signature,
      };

      this.$emit('onSign', signatureData);
      this.isLoading = false;
    } catch (error) {
      const { message } = error as { message: string };

      this.showNotification({
        title: 'Ошибка',
        text: message.includes('objid') ? this.errors.objid : message,
      });
      this.isLoading = false;
    }
  }

  showNotification(notification: Notification): void {
    this.$emit('notification', notification);
  }

  init(): void {
    isPluginInstalled(
      async () => {
        try {
          this.certificatesList = await enlistCertificates();
        } catch (error) {
          const { message } = error as { message: string };
          this.showNotification({
            title: 'Ошибка получения сертификатов',
            text: message,
          });
        }
      },
      () => undefined
    );
  }

  created(): void {
    this.init();
  }
}
</script>

<style scoped lang="scss">
.wrapper {
  align-items: flex-end;
  display: flex;
  width: 600px;
}

.list {
  width: 300px !important;
}
</style>
