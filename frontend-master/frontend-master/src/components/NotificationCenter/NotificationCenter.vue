<template>
  <div>
    <v-snackbar v-model="isShow" :timeout="7000" :color="color" vertical top right>
      <!-- eslint-disable-next-line vue/no-v-html -->
      <span v-for="(item, index) in content.text.split('<br>')" :key="index">{{ item }}</span>

      <template #action="{ attrs }">
        <v-btn text v-bind="attrs" @click="isShow = false">ОК</v-btn>
      </template>
    </v-snackbar>
    <HttpErrorModal v-model="isNetworkErrorModalShow" />
    <ServerErrorModal v-model="isServerErrorModalShow" :error="serverError" />
    <ConfirmModal
      v-for="(item, index) in queue"
      v-bind="item.params"
      :key="index"
      v-model="item.open"
      :loading="item.isConfirmLoading"
      @apply="(v) => item.onConfirm(v)"
      @close="() => item.onClose()"
    />
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { MapperError } from '@/utils/global/mapper/errors';
import HttpErrorModal from './HttpErrorModal.vue';
import ServerErrorModal from './ServerErrorModal.vue';
import ConfirmModal from './ConfirmModal.vue';

class Confirmation {
  constructor(protected params, protected onClose) {}

  isConfirmLoading = false;
  open = true;

  async onConfirm(callback) {
    this.isConfirmLoading = true;
    if (callback) {
      await callback();
    }
    this.onClose();
    this.isConfirmLoading = false;
  }
}

@Component({
  name: 'notification-center',
  components: { HttpErrorModal, ServerErrorModal, ConfirmModal },
})
export default class NotificationCenter extends Vue {
  isNetworkErrorModalShow = false;
  isServerErrorModalShow = false;
  confirmationQueue: any = {};
  isShow = false;
  serverError = null;
  content = { type: '', text: '' };

  get queue() {
    return Object.values(this.confirmationQueue);
  }

  get color() {
    const result = ['accent-2'];

    result.push(this.content.type === 'error' ? 'red' : 'success');

    return result.join(' ');
  }

  created() {
    this.$service.notify.on('error', this.onError);
    this.$service.notify.on('message', this.onMessage);
    this.$service.notify.on('flush', this.onFlush);
  }

  beforeDestroy() {
    this.$service.notify.off('error', this.onError);
    this.$service.notify.off('message', this.onMessage);
    this.$service.notify.off('flush', this.onFlush);
  }

  showServerError(error) {
    this.serverError = error;
    this.isServerErrorModalShow = true;
  }

  onError({ text, params }: any) {
    if (params?.error instanceof MapperError || params?.error?.response?.status >= 500) {
      this.showServerError(params?.error);
    } else if (!this.isShow) {
      this.isShow = true;
      this.content = {
        type: 'error',
        text,
      };
    }
  }

  onMessage({ text, params }: any) {
    if (params?.type) {
      switch (params.type) {
        case 'network-error':
          this.isNetworkErrorModalShow = true;
          break;
        case 'confirm-modal':
          this.addConfirmation(params);
          break;
      }
    } else if (!this.isShow) {
      this.isShow = true;
      this.content = {
        type: 'message',
        text,
      };
    }
  }

  onFlush() {
    this.isShow = false;
    this.content = {
      type: '',
      text: '',
    };
  }

  addConfirmation(params) {
    const id = Math.random().toString(36).slice(2);
    this.confirmationQueue = {
      ...this.confirmationQueue,
      [id]: new Confirmation(params, () => {
        const { [id]: _, ...rest } = this.confirmationQueue;
        this.confirmationQueue = rest;
      }),
    };
  }
}
</script>
