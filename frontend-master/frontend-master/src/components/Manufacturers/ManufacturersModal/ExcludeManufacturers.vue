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
      <UiForm :rules="rules" @validation="(v) => (isValid = v.isValid)" @submit="onSubmit">
        <v-row>
          <v-col cols="12">
            <div class="description">Аннулирование регистрации товаропроизводителя {{ name }}</div>
          </v-col>
          <v-col cols="12">
            <UiControl name="exclusion_reason" :value="exclusion_reason">
              <SelectComponent
                v-model="exclusion_reason"
                label="Причина"
                :items="reasonList"
                item-value="id"
                item-text="name"
              />
            </UiControl>
          </v-col>
          <v-col cols="12">
            <div class="period" data-app>
              <UiControl name="registry_exclusion_date" :value="registry_exclusion_date">
                <UiDateInput v-model="registry_exclusion_date" :limit-from="limitFrom" class="datePicker" label="Дата" />
              </UiControl>
            </div>
          </v-col>
        </v-row>
        <v-row justify="end">
          <v-col cols="12" class="col-exclude">
            <DefaultButton title="Отменить" @click="$emit('close')" />
            <DefaultButton
              variant="primary"
              title="Сохранить"
              type="submit"
              :disabled="!isValid || isLoading"
              :loading="isLoading"
            />
          </v-col>
        </v-row>
      </UiForm>
    </template>
  </Dialog-component>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import moment from 'moment';
import { IDictionaryNode } from '@/services/models/common';

type Props = {
  id: number;
  name: string;
};

@Component({
  name: 'exclude-manufacturers',
  components: {
    DefaultButton,
    InputComponent,
    LabelComponent,
    SelectComponent,
    DialogComponent,
  },
})
export default class ExcludeManufacturers extends Vue {
  @Prop({ type: Number }) readonly id!: Props['id'];
  @Prop({ type: String }) readonly name!: Props['name'];
  @Prop({ type: Boolean }) readonly value;
  @Prop({ type: [Date, Number, String], required: false }) readonly startDate?: Date | number | string;

  reasonList: IDictionaryNode[] = [];
  registry_exclusion_date = '';
  selectTime = '';
  exclusion_reason: any = null;
  isLoading = false;
  isValid = true;

  get limitFrom() {
    if (!this.startDate) return this.startDate;
    const format = typeof this.startDate === 'string' ? 'DD.MM.YYYY' : undefined;
    return this.$moment(this.startDate, format).add(1, 'd').toDate();
  }

  get rules() {
    return {
      exclusion_reason: 'required',
      registry_exclusion_date: 'required',
    };
  }
  get innerValue() {
    return this.value;
  }

  set innerValue(value) {
    this.$emit('input', value);
  }

  created() {
    this.getReasonList();
  }

  async getReasonList() {
    const { data } = await this.$service.manufacturer.getReasonList();
    this.reasonList = data;
  }

  async onSubmit() {
    this.isLoading = true;
    await this.$service.manufacturer.exclude({
      id: this.id,
      exclusion_reason: {
        id: this.exclusion_reason,
      },
      registry_exclusion_date: this.registry_exclusion_date,
    });
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
