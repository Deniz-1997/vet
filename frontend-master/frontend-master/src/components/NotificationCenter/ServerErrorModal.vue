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
      <span>Что-то пошло не так</span>
    </template>

    <template #content>
      <div>
        <v-row>
          <v-col cols="12">
            <p class="mb-2">
              Сервер сейчас недоступен.<br />
              Попробуйте повторить действие позже.
            </p>
            <v-expansion-panels v-if="$config.isDev && error">
              <v-expansion-panel class="panel">
                <v-expansion-panel-header>Детали ошибки</v-expansion-panel-header>
                <v-expansion-panel-content>{{ error }}</v-expansion-panel-content>
              </v-expansion-panel>
            </v-expansion-panels>
          </v-col>
        </v-row>

        <v-row justify="end">
          <v-col cols="12" class="col-exclude">
            <DefaultButton title="На главную" @click="toHomePage" />
            <DefaultButton variant="primary" title="Обновить страницу" @click="refreshPage" />
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
  @Prop({ type: Error, default: () => null }) readonly error!: Error;
  isLoading = false;

  get innerValue() {
    return this.value;
  }

  set innerValue(v) {
    this.$emit('input', v);
  }

  toHomePage() {
    this.$router.push('/home');
    this.$emit('input', false);
  }

  async refreshPage() {
    window.location.reload();
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';

.panel {
  border-radius: 0;

  &::before {
    box-shadow: none;
  }

  button {
    min-height: 0;
    padding: 8px 0;
  }
}
</style>
