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
        <UiControl name="inn" :value="form.inn">
          <InputComponent
            id="inn"
            key="inn"
            v-model="form.inn"
            placeholder="Введите текст"
            mask="##########"
            label="ИНН"
            :disabled="isEsia || isDisabled"
            required
          />
        </UiControl>
      </v-col>
      <v-col cols="12" md="6">
        <UiControl name="kpp" :value="form.kpp">
          <InputComponent
            id="kpp"
            key="kpp"
            v-model="form.kpp"
            placeholder="Введите текст"
            mask="#########"
            label="КПП"
            :disabled="isEsia || isDisabled"
            required
          />
        </UiControl>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="6">
        <UiControl name="nza" :value="form.nza">
          <InputComponent
            id="nza"
            v-model="form.nza"
            placeholder="Введите текст"
            label="Регистрационный номер в РАФП"
            mask="###########"
            :disabled="isEsia || isDisabled"
            required
          />
        </UiControl>
      </v-col>
      <v-col cols="12" md="6">
        <UiControl name="country" :value="form.country">
          <AutocompleteComponent
            v-model="form.country"
            return-object
            :items="countryList"
            item-value="country_id"
            item-text="name_full"
            label="Страна регистрации"
            :disabled="isEsia"
            name="country"
            required
            @change="changeCountry"
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
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import { IDictionaryNode } from '@/services/models/common';
import Form from '@/utils/global/mixins/form';

type CountryItem = {
  country_id: number;
  code: string;
  code_alpha3: string;
  global_id: number;
  name: string;
  name_full: string;
  startDate?: string;
  startTime?: string;
  start_date?: string;
  сode_alpha2?: string;
};

@Component({
  name: 'accredited-representative-office-form',
  components: {
    InputComponent,
    AutocompleteComponent,
    DefaultButton,
  },
})
export default class AccreditedRepresentativeOfficeForm extends Mixins(Form) {
  @Prop({ type: Boolean, default: false }) readonly isLoading?: boolean;
  @Prop({ type: Boolean, default: false }) public isDisabled!: boolean;

  countryList: IDictionaryNode[] = [];
  isValid = true;

  get rules() {
    return {
      name: 'required',
      inn: ['required', { size: 10 }],
      kpp: ['required', { size: 9 }],
      nza: 'required',
      country: 'required',
    };
  }

  get isEsia() {
    return this.form.created_by === 'ESIA';
  }

  created() {
    this.getCountry();
    this.$emit('update-rules', this.rules);
  }

  async getCountry(): Promise<CountryItem[]> {
    const data = await this.$store.dispatch('country/getListCountry');
    return (this.countryList = data.content);
  }

  changeCountry() {
    this.$store.commit('fias/changeAddress', []);
  }

  @Watch('rules')
  updateRules() {
    this.$emit('update-rules', this.rules);
  }
}
</script>

<style lang="scss" scoped>
.col {
  padding-bottom: 0;
  padding-top: 0;
}
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
