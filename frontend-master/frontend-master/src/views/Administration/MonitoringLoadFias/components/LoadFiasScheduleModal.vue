<template>
  <DialogComponent v-model="isModalOpen" width="800" hide-actions>
    <template #title>
      <span data-qa="load-fias-schedule__title" class="title">{{ title }}</span>
    </template>

    <template #content>
      <UiForm
        id="load-fias-schedule"
        ref="form"
        :rules="rules"
        :messages="messages"
        @validation="(v) => (isValid = v.isValid)"
        @submit="saveSchedule"
      >
        <v-row>
          <v-col cols="12">
            <UiControl name="days" :value="form.days">
              <autocomplete-component
                id="days"
                v-model="form.days"
                :items="dayList"
                label="Дни недели"
                is-multiple
                chips
                required
              />
            </UiControl>
          </v-col>
        </v-row>

        <v-row class="mt-0">
          <v-col cols="12">
            <UiControl name="time" :value="form.time">
              <InputComponent
                id="time"
                v-model="form.time"
                placeholder="ЧЧ:ММ"
                label="Время загрузки обновления"
                :mask="maskForTime"
                required
              />
            </UiControl>
          </v-col>
        </v-row>

        <v-row justify="end">
          <v-col cols="12" class="col-exclude">
            <DefaultButton title="Отменить" @click="isModalOpen = false" />
            <DefaultButton type="submit" variant="primary" title="Сохранить" :disabled="!isValid" />
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
import Modal from '@/utils/global/mixins/modal';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import AutocompleteComponent, { AutocompleteItem } from '@/components/common/inputs/AutocompleteComponent.vue';
import { timeMask } from '@/components/common/inputs/mask/time';

type FiasScheduleForm = {
  days: string[] | null;
  time: string | null;
};

/** Форма расписания загрузки обновлений ФИАС */
@Component({
  name: 'load-fias-schedule-modal',
  components: {
    AutocompleteComponent,
    DialogComponent,
    DefaultButton,
    InputComponent,
    SelectComponent,
  },
})
export default class LoadFiasScheduleModal extends Mixins(Modal) {
  @Prop({ type: String, required: false, default: 'Расписание загрузки обновлений ФИАС' }) readonly title!: string;
  @Prop({ type: String, required: false, default: '' }) readonly schedule!: string;

  form: FiasScheduleForm = this.getInnerForm();
  isValid = true;

  maskForTime = timeMask;
  dayList: AutocompleteItem[] = this.getDayList();

  get time() {
    if (this.form.time?.length === 5) {
      const timeTuples = this.form.time.split(':');

      return {
        hour: Number(timeTuples[0]),
        minute: Number(timeTuples[1]),
      };
    }
    return null;
  }

  get newSchedule() {
    //TODO важен ли порядок дней?
    return this.time && this.form.days?.length
      ? `${this.time.minute} ${this.time.hour} * * ${this.form.days.join(',')}`
      : null;
  }

  get rules() {
    return {
      days: 'required',
      time: ['required', { size: 5 }],
    };
  }

  get messages() {
    return {
      'size.time': 'Должен соблюдаться формат ЧЧ:ММ',
    };
  }

  getInnerForm() {
    if (!this.schedule) {
      return {
        days: [],
        time: null,
      };
    }

    const cron = this.schedule.split(' ');
    const minute = cron[0].padStart(2, '0');
    const hour = cron[1].padStart(2, '0');
    const days = cron[4].split(',');

    return {
      days,
      time: `${hour}:${minute}`,
    };
  }

  getDayList() {
    return [
      { value: '0', text: 'Воскресенье' },
      { value: '1', text: 'Понедельник' },
      { value: '2', text: 'Вторник' },
      { value: '3', text: 'Среда' },
      { value: '4', text: 'Четверг' },
      { value: '5', text: 'Пятница' },
      { value: '6', text: 'Суббота' },
    ];
  }

  onModalOpen() {
    this.isValid = true;
    this.form = this.getInnerForm();
  }

  saveSchedule() {
    if (this.newSchedule) {
      this.$emit('save-schedule', this.newSchedule);
    }
  }
}
</script>
