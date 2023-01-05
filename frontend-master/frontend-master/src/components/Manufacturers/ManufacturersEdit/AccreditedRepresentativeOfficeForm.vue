<template>
  <UiForm
    :rules="rules"
    data-qa="accredited_representative_office__form"
    @validation="(v) => (isValid = v.isValid)"
    @submit="$emit('save', innerForm)"
  >
    <div v-if="!step || step === 'general'">
      <v-row>
        <v-col cols="12">
          <UiControl name="name" :value="innerForm.name">
            <InputComponent
              id="organizationName"
              v-model="innerForm.name"
              placeholder="Введите текст"
              label="Наименование"
              :disabled="innerForm.isEsia"
            />
          </UiControl>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="4">
          <UiControl name="inn" :value="innerForm.inn">
            <InputComponent
              id="inn"
              key="inn"
              v-model="innerForm.inn"
              placeholder="Введите текст"
              mask="##########"
              label="ИНН"
              :disabled="isEdit || innerForm.isEsia"
            />
          </UiControl>
        </v-col>
        <v-col cols="12" md="4">
          <UiControl name="kpp" :value="innerForm.kpp">
            <InputComponent
              id="kpp"
              key="kpp"
              v-model="innerForm.kpp"
              placeholder="Введите текст"
              mask="#########"
              label="КПП"
              :disabled="isEdit || innerForm.isEsia"
            />
          </UiControl>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12">
          <UiControl name="nza" :value="innerForm.nza">
            <InputComponent
              id="nza"
              v-model="innerForm.nza"
              placeholder="Введите текст"
              label="Регистрационный номер в РАФП"
              mask="###########"
              :disabled="isEdit || innerForm.isEsia"
            />
          </UiControl>
        </v-col>
        <v-col cols="12">
          <AutocompleteComponent
            v-model="innerForm.country"
            return-object
            :items="countryList"
            item-value="country_id"
            item-text="name_full"
            label="Страна регистрации"
            :disabled="innerForm.isEsia"
            @change="changeCountry"
          />
        </v-col>
      </v-row>
    </div>
    <v-row v-if="!step || step === 'register'">
      <v-col cols="12">
        <span class="span-list">
          <span>
            <label class="checkbox">
              <input v-model="innerForm.isProcessor" type="checkbox" :value="innerForm.isProcessor" />
              <span class="checkbox__icon">
                <img src="/icons/checkbox.svg" />
              </span>
            </label>
          </span>
          <span class="checkbox-title">
            Организация, осуществляющая первичную и (или) последующую (промышленную) переработку зерна</span
          >
        </span>
      </v-col>
    </v-row>

    <v-row v-if="!step || step === 'register'" justify="end">
      <v-col cols="12" class="col-exclude">
        <DefaultButton variant="primary" type="submit" :disabled="isLoading" title="Сохранить" />
      </v-col>
    </v-row>
  </UiForm>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import cloneDeep from 'lodash/cloneDeep';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import { ManufacturerItemIn } from '@/services/mappers/manufacturer';
import { IDictionaryNode, TMapperPlain } from '@/services/models/common';

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
export default class AccreditedRepresentativeOfficeForm extends Vue {
  @Prop({ type: Object }) readonly form;
  @Prop({ type: Boolean }) readonly isEdit;
  @Prop({ type: Boolean, default: false }) readonly isLoading?: boolean;
  @Prop({ type: String }) readonly step?: string; /** Шаг формы. Если не задан, то отображается вся форма */

  countryList: IDictionaryNode[] = [];
  innerForm: Partial<TMapperPlain<ManufacturerItemIn>> = {};
  isValid = true;

  get rules() {
    return {
      name: 'required',
      inn: ['required', { size: 10 }],
      kpp: ['required', { size: 9 }],
      nza: 'required',
    };
  }

  created() {
    this.innerForm = cloneDeep(this.form);
    this.getCountry();
  }

  async getCountry(): Promise<CountryItem[]> {
    const data = await this.$store.dispatch('country/getListCountry');
    return (this.countryList = data.content);
  }

  changeCountry() {
    this.$store.commit('fias/changeAddress', []);
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
