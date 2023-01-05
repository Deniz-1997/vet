<template>
  <div>
    <v-row>
      <v-col cols="12">
        <UiControl name="name" :value="form.name">
          <InputComponent
            id="organizationName"
            v-model="form.name"
            placeholder="Введите текст"
            label="Наименование"
            :disabled="isEsia"
            required
          />
        </UiControl>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="6">
        <UiControl name="phoneNumber" :value="form.phoneNumber">
          <InputComponent
            id="phone_number"
            v-model="form.phoneNumber"
            placeholder="Введите номер"
            label="Контактный номер телефона"
            type="tel"
            :disabled="isEsia"
          />
        </UiControl>
      </v-col>
      <v-col cols="12" md="6">
        <UiControl name="email" :value="form.email">
          <InputComponent
            id="email"
            v-model="form.email"
            placeholder="Введите email"
            label="Адрес электронной почты"
            type="email"
            :disabled="isEsia"
          />
        </UiControl>
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Watch, Mixins } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import Form from '@/utils/global/mixins/form';

@Component({
  name: 'legal-entities-person-form',
  components: {
    InputComponent,
    DefaultButton,
  },
})
export default class LegalEntitiesPersonForm extends Mixins(Form) {
  @Prop({ type: Boolean, default: false }) readonly isLoading?: boolean;

  get isEsia() {
    return this.form.created_by === 'ESIA';
  }

  get rules() {
    return {
      name: 'required',
    };
  }

  created() {
    this.$emit('update-rules', this.rules);
  }

  @Watch('rules')
  updateRules() {
    this.$emit('update-rules', this.rules);
  }
}
</script>

<style lang="scss" scoped>
.col-exclude {
  display: flex;
  justify-content: flex-end;
}

.ul-radio {
  padding-top: 8px;
}

.span-list {
  display: flex;
  flex-wrap: nowrap;
}

.checkbox {
  padding-top: 4px;
}

.checkbox-title {
  padding-left: 4px;
}
</style>
