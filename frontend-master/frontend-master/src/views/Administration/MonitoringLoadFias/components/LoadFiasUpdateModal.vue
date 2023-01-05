<template>
  <DialogComponent v-model="isModalOpen" width="800" hide-actions>
    <template #title>
      <span data-qa="role-card__title" class="title">{{ title }}</span>
    </template>

    <template #content>
      <UiForm
        id="load-fias-schedule"
        ref="form"
        :rules="rules"
        @validation="(v) => (isValid = v.isValid)"
        @submit="updateFias"
      >
        <v-row>
          <v-col cols="12">
            <v-radio-group v-model="form.mode">
              <v-row>
                <v-col cols="12">
                  <v-radio label="Загрузить ФИАС полностью" :value="modeList.FULL" />
                </v-col>
                <v-col cols="12">
                  <v-radio label="Загрузить последнее обновление" :value="modeList.LAST_UPDATE" />
                </v-col>
                <v-col cols="12">
                  <v-radio label="Загрузить выбранное обновление" :value="modeList.SELECTED_UPDATE" />
                </v-col>
                <v-col cols="12">
                  <v-radio label="Загрузить данные по конкретному региону" :value="modeList.REGION" />
                </v-col>
              </v-row>
            </v-radio-group>
          </v-col>
        </v-row>

        <div class="area-field">
          <v-row v-if="form.mode === modeList.SELECTED_UPDATE">
            <v-col cols="12">
              <UiControl name="file" :value="form.file">
                <InputComponent v-model="form.file" label="Ссылка на файл обновления" />
              </UiControl>
            </v-col>
          </v-row>

          <v-row v-if="form.mode === modeList.REGION">
            <v-col cols="6">
              <UiControl name="region" :value="form.region">
                <InputComponent v-model="form.region" label="Номер региона по ГАР" />
              </UiControl>
            </v-col>
          </v-row>
        </div>

        <v-row justify="end">
          <v-col cols="12" class="col-exclude">
            <DefaultButton title="Отменить" @click="isModalOpen = false" />
            <DefaultButton title="Запустить" type="submit" variant="primary" />
          </v-col>
        </v-row>
      </UiForm>
    </template>
  </DialogComponent>
</template>

<script lang="ts">
import { Component, Prop, Mixins } from 'vue-property-decorator';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import TextareaComponent from '@/components/common/inputs/TextAreaComponent.vue';
import Modal from '@/utils/global/mixins/modal';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import { UpdateFiasMode } from '@/views/Administration/MonitoringLoadFias/MonitoringLoadFias.types';

type FiasUpdateForm = {
  mode: UpdateFiasMode;
  file: string | null;
  region: string | null;
};

/** Форма загрузки обновления ФИАС */
@Component({
  name: 'load-fias-update-modal',
  components: { DialogComponent, DefaultButton, InputComponent, TextareaComponent },
})
export default class LoadFiasUpdateModal extends Mixins(Modal) {
  @Prop({ type: String, default: 'Загрузка обновления ФИАС' }) readonly title!: string;

  form: FiasUpdateForm = this.getInnerForm();
  isValid = true;
  modeList = UpdateFiasMode;

  get rules() {
    return {
      file: this.form.mode === this.modeList.SELECTED_UPDATE && 'required',
      region: this.form.mode === this.modeList.REGION && 'required',
    };
  }

  getInnerForm(): FiasUpdateForm {
    return {
      mode: UpdateFiasMode.LAST_UPDATE,
      file: null,
      region: null,
    };
  }

  onModalOpen() {
    this.isValid = true;
    this.form = this.getInnerForm();
  }

  async updateFias() {
    switch (this.form.mode) {
      case 'FULL':
        await this.$axios.post('/api/fias/loadDatabase', { full: true } );
        break;
      case 'LAST_UPDATE':
        await this.$axios.post('/api/fias/loadDatabase', { save_archive: true } );
        break;
      case 'SELECTED_UPDATE':
        await this.$axios.post('/api/fias/loadDatabase', { url: this.form.file, full: true } );
        break;
      case 'REGION':
        await this.$axios.post('/api/fias/loadDatabase', { region: this.form.region, save_archive: true } );
        break;
    }
    this.isModalOpen = false;
  }
}
</script>

<style lang="scss" scoped>
.area-field {
  min-height: 95px;
}
</style>
