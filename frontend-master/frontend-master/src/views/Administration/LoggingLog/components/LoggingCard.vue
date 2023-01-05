<template>
  <div class="logging-card">
    <v-row v-if="form.subject">
      <v-col cols="12">
        <label-component label="Организация" />
        <InputComponent id="lastName" v-model="form.subject.subject_data.name" :disabled="isShow" />
      </v-col>
    </v-row>
    <v-row v-if="form.user">
      <v-col cols="12">
        <label-component label="Фамилия" />
        <InputComponent id="lastName" v-model="form.user.last_name" :disabled="isShow" />
      </v-col>
    </v-row>

    <v-row v-if="form.user">
      <v-col cols="12">
        <label-component label="Имя" />
        <InputComponent id="first_name" v-model="form.user.first_name" :disabled="isShow" />
      </v-col>
      <v-col cols="12">
        <label-component label="Отчество" />
        <InputComponent id="secondName" v-model="form.user.second_name" :disabled="isShow" />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <label-component label="Бизнес область" />
        <InputComponent v-model="form.business_area.name" :disabled="isShow" />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <label-component label="Действие пользователя" />
        <InputComponent v-model="form.user_action.name" :disabled="isShow" />
      </v-col>
      <v-col cols="12" md="6">
        <label-component label="Результат действия" />
        <InputComponent id="password" v-model="form.action_result" :disabled="isShow" />
      </v-col>
      <v-col cols="12" md="6">
        <label-component label="Дата и время" />
        <InputComponent v-model="form.log_date" :disabled="isShow" />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <label-component label="Данные, запрашиваемые пользователем" />
        <TextAreaComponent id="password" v-model="form.action_data" :disabled="isShow" />
      </v-col>
    </v-row>

    <v-row justify="end">
      <v-col cols="12" class="col-exclude">
        <DefaultButton title="Закрыть" @click="$emit('close')"> </DefaultButton>
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import TextAreaComponent from '@/components/common/inputs/TextAreaComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';

type Props = {
  item: object;
  isShow: boolean;
};

@Component({
  name: 'logging-card',
  components: {
    InputComponent,
    TextAreaComponent,
    DefaultButton,
    LabelComponent,
    DialogComponent,
  },
})
export default class LoggingCard extends Vue {
  @Prop({
    type: [Object],
    default: () => ({}),
  })
  readonly item: Props['item'] | undefined;
  @Prop({
    type: [Boolean],
    default: () => false,
  })
  readonly isShow: Props['isShow'] | undefined;
  form = {};

  mounted() {
    if (this.item) {
      this.form = this.item;
      if (this.form['action_result']) {
        this.form['action_result'] = 'Успешно';
      } else {
        this.form['action_result'] = 'Не успешно';
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.logging-card {
  .name-orgnz {
    display: flex;
    flex-wrap: nowrap;
  }

  .flex-end {
    display: flex;
    margin-top: 10px;
    justify-content: flex-end;
  }

  .settingsSpan {
    padding-left: 16px;
    cursor: pointer;
  }

  .v-input.v-input--is-disabled {
    pointer-events: initial !important;
  }
}
</style>
