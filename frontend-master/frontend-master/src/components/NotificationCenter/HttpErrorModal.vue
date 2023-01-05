<template>
  <Dialog-component
    :value="innerValue"
    :prompt="false"
    cancel-title=""
    confirm-title=""
    width="420"
    :persistent="!$config.isDev"
    controls-justify="justify-end"
    hide-actions
  >
    <template #title>
      <span>Ошибка интернет-соединения</span>
    </template>

    <template #content>
      <div>
        <v-row>
          <v-col cols="12">
            <p class="mb-0">
              Не удаётся установить связь с сервером.<br /><br />
              Пожалуйста, проверьте своё интернет-соединение, после чего перезагрузите страницу.
            </p>
          </v-col>
        </v-row>

        <v-row justify="end">
          <v-col cols="12" class="col-exclude">
            <DefaultButton variant="primary" title="Перезагрузить" @click="refreshPage" />
          </v-col>
        </v-row>
      </div>
    </template>
  </Dialog-component>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';

@Component({
  name: 'HttpErrorModal',
  components: {
    DefaultButton,
    DialogComponent,
  },
})
export default class HttpErrorModal extends Vue {
  @Prop({ type: Boolean, default: false }) readonly value!: boolean;

  get innerValue() {
    return this.value;
  }

  set innerValue(v) {
    this.$emit('input', v);
  }

  async refreshPage() {
    window.location.reload();
  }
}
</script>
