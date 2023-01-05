<template>
  <div class="modalConfirm">
    <div class="overlay" @click="$emit('close')" />
    <div class="container">
      <div class="title">
        <span class="spanTitle">Подтвердите действие</span>
      </div>
      <div class="description">
        {{ getDescriptionAction() }}
      </div>

      <div class="reason">
        <SelectComponent
          v-model="selectReason"
          :items="reasonList"
          label="Основание"
          item-value="id"
          item-text="name"
        />
      </div>

      <div v-if="actionType !== 'exclude'" class="reason">
        <input-component v-model="note" label="Комментарий" />
      </div>
      <div class="period" data-app>
        <UiDateInput v-model="selectDate" :limit-from="limitFrom" class="datePicker" label="Дата" />
      </div>
      <div class="buttons">
        <DefaultButton title="Отменить" @click="$emit('close')" />
        <DefaultButton title="Сохранить" variant="primary" :disabled="isLoading" @click="handleClick" />
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import moment from 'moment';
import { Component, Vue, Prop } from 'vue-property-decorator';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import 'vue2-datepicker/locale/ru';
import 'vue2-datepicker/index.css';

type Props = {
  name: string;
  elevatorId: number;
  actionType: string;
};

@Component({
  name: 'modal-reasons',
  components: { SelectComponent, DefaultButton, InputComponent },
})
export default class ModalConfirmDelete extends Vue {
  @Prop({ type: String }) readonly name!: Props['name'];
  @Prop({ type: Number }) readonly elevatorId!: Props['elevatorId'];
  @Prop({ type: String }) readonly actionType!: Props['actionType'];
  @Prop({ type: [Date, Number, String], required: false }) readonly startDate?: Date | number | string;

  isLoading = false;
  reasonList: any = [];
  selectDate = '';
  selectReason: any = {};
  note = '';

  get limitFrom() {
    if (!this.startDate) return this.startDate;
    const format = typeof this.startDate === 'string' ? 'DD.MM.YYYY' : undefined;
    return this.$moment(this.startDate, format).add(1, 'd').toDate();
  }

  created() {
    this.fetchReasonList();
  }

  getDescriptionAction() {
    switch (this.actionType) {
      case 'stop':
        return `Приостановить деятельность организации ${this.name}`;
      case 'exclude':
        return `Исключение из реестра организации ${this.name}`;
      case 'resumption':
        return `Возобновить деятельность организации ${this.name}`;
      default:
        return;
    }
  }

  async handleClick() {
    if (typeof this.selectReason === 'object') {
      this.$store.commit('errors/clearErrorList');
      this.$store.commit('errors/setErrorsList', "Поле 'Причина' не заполнено");
      return;
    }

    this.isLoading = true;
    const handler = this[this.actionType];

    if (handler) {
      await handler();
    }

    this.$emit('close');
    this.$emit('reset');
    this.isLoading = false;
  }

  async fetchReasonList() {
    const { content } = await this.$store.dispatch('elevator/fetchReasonList');
    this.reasonList = content;
  }

  async exclude() {
    const date = moment(this.selectDate, 'DD.MM.YYYY').format('DD.MM.YYYY');
    const reasonId = this.selectReason || '';
    const params = {
      date,
      notes: this.note,
      elevator_id: this.elevatorId,
      elevator_status_change_reason_id: Number(reasonId),
    };
    await this.$store.dispatch('elevator/excludeOrganization', { params });
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.overlay {
  background-color: rgba($black-color, 0.3);
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 8;
}

.label {
  color: $input-border-color;
  margin-bottom: 5px;
  font-size: 13px;
  line-height: 16px;
}

.container {
  position: fixed;
  left: 50%;
  width: 590px;
  top: 50%;
  z-index: 10;
  background: $white-color;
  border: 1px solid $light-grey-color;
  box-sizing: border-box;
  box-shadow: 0 16px 32px rgba($black-color, 0.1);
  border-radius: 4px;
  z-index: 9;
  padding: 20px;
  transform: translate(-50%, -50%);
}

.title {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}

.spanTitle {
  text-transform: uppercase;
  font-size: 18px;
  font-weight: bold;
  color: $footer-color;
}

.lineChoose {
  margin-top: 20px;
}

.spanChoose {
  text-decoration: underline;
  margin-right: 15px;
  font-size: 16px;
  color: $medium-grey-color;
  cursor: pointer;
}

.baseInformation {
  margin-top: 25px;

  @include respond-to('medium') {
    margin-top: 15px;
  }

  @include respond-to('small') {
    margin-top: 10px;
  }
}

.buttons {
  display: flex;
  margin-top: 20px;
  justify-content: flex-end;
}

.cancel {
  background-color: $white-color;
  border: 1px solid $input-border-color;
  border-radius: 4px;
  color: $medium-grey-color;
  padding: 9px 25px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin-right: 10px;
  cursor: pointer;
  outline: none;

  @include respond-to('medium') {
    padding: 9px 15px;
    font-size: 14px;
  }

  @include respond-to('small') {
    font-size: 12px;
  }

  &:hover {
    box-shadow: 0 0 5px rgba($black-color, 0.5);
  }
}

.description {
  color: $black-color;
  padding: 20px 0;
  text-align: center;
  font-size: 16px;
  font-weight: 700;
}

.reason {
  margin-bottom: 18px;
}

.apply {
  background-color: $gold-dark-color;
  border: none;
  border-radius: 4px;
  color: $white-color;
  padding: 9px 25px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  cursor: pointer;
  outline: none;

  @include respond-to('medium') {
    padding: 9px 15px;
    font-size: 14px;
  }

  @include respond-to('small') {
    font-size: 12px;
  }

  &:hover {
    box-shadow: 0 0 5px rgba($black-color, 0.5);
  }
}

.cross {
  cursor: pointer;
}
</style>
