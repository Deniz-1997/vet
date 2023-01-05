<template>
  <Dialog-component
    v-model="innerValue"
    :prompt="false"
    cancel-title=""
    confirm-title=""
    width="590"
    with-close-icon
    controls-justify="justify-end"
  >
    <template #title>
      <v-row>
        <v-col cols="12">
          <div class="title">
            <span class="spanTitle">Подтвердите действие</span>
          </div>
        </v-col>
      </v-row>
    </template>
    <template #content>
      <v-row>
        <v-col cols="12">
          <div class="title">
            <span class="spanTitle"></span>
          </div>
          <div class="description">Исключение лаборатории {{ name }}</div>
        </v-col>
        <v-col cols="12">
          <input-component v-model="reason" label="Основание" />
        </v-col>
      </v-row>
      <v-row justify="end">
        <v-col cols="12" class="col-exclude">
          <DefaultButton title="Отменить" @click="$emit('close')" />
          <DefaultButton variant="primary" title="Сохранить" :disabled="isLoading" @click="excludeAction" />
        </v-col>
      </v-row>
    </template>
  </Dialog-component>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import Datepicker from '@/components/common/Datepicker/Datepicker.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import moment from 'moment';

type Props = {
  id: number;
  name: string;
  value: boolean;
};

@Component({
  name: 'exclude-laboratories',
  components: {
    DefaultButton,
    InputComponent,
    LabelComponent,
    Datepicker,
    SelectComponent,
    DialogComponent,
  },
})
export default class ExcludeLaboratories extends Vue {
  /** Флаг открытия модального окна. */
  @Prop({ type: Boolean }) readonly value;
  @Prop({ type: Number }) readonly id: Props['id'] | undefined;
  @Prop({ type: String }) readonly name: Props['name'] | undefined;

  isLoading = false;
  reasonList: any = [];
  selectDate = '';
  selectTime = '';
  selectReason: any = {};
  reason = '';

  get innerValue() {
    return this.value;
  }

  set innerValue(value) {
    this.$emit('input', value);
  }

  async excludeAction() {
    if (!this.reason) {
      this.$store.commit('errors/clearErrorList');
      this.$store.commit('errors/setErrorsList', "Поле 'Причина' не заполнено");
      return;
    }
    this.isLoading = true;
    const data = {
      id: this.id,
      reason: this.reason,
      date: moment(new Date()).format('DD.MM.YYYY HH:MM'),
    };
    await this.$store.dispatch('laboratories/excludeLaboratories', data);
    this.$emit('excludeAction');
    this.$emit('close');
    this.isLoading = false;
  }

  @Watch('selectDate')
  async onChangeSelectDate() {
    this.selectTime = moment().format('HH:mm');
  }
}
</script>

<style lang="scss">
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.col-exclude {
  display: flex;
  justify-content: flex-end;
}

.description {
  color: $black-color;
  padding: 20px 0;
  text-align: center;
  font-size: 16px;
  font-weight: 700;
}

.spanTitle {
  text-transform: uppercase;
  font-size: 18px;
  font-weight: bold;
  color: $footer-color;
}
</style>
