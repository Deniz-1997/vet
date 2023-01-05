<template>
  <div class="address">
    <v-row>
      <v-col cols="12">
        <div class="inputContainer">
          <UiControl v-if="form.address" name="address" :value="form.address.addressText">
            <InputComponent
              :value="addressNew(form.address)"
              label="Адрес"
              :required="requiredAddress"
              class="input--large"
              disabled
              name="address"
            />
          </UiControl>
        </div>
      </v-col>
      <v-col cols="12">
        <div class="inputContainer" v-if="form.address">
          <InputComponent
            :value="form.address.additionalInfo"
            label="Дополнительный адрес"
            class="input--large"
            disabled
            name="address.additionalInfo"
          />
        </div>
      </v-col>
      <v-col cols="12">
        <DefaultButton
          variant="primary"
          :title="addressNew(form.address) ? 'Изменить адрес' : 'Указать адрес'"
          :disabled="isEsia"
          @click="showModal = true"
        />
      </v-col>
    </v-row>
    <Dialog-component
      v-model="showModal"
      :prompt="false"
      cancel-title=""
      confirm-title=""
      width="800"
      with-close-icon
      controls-justify="justify-end"
    >
      <template #title> Адрес </template>
      <template #content>
        <AddressNewComponent
          v-model="form.address"
          :subject-type="form.subjectType"
          @close="showModal = false"
          @saveAction="addFields"
        />
      </template>
    </Dialog-component>
  </div>
</template>

<script lang="ts">
import { Component, Mixins, Watch } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import { IDictionaryNode, TMapperPlain } from '@/services/models/common';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import { addressNew } from '@/utils/global/filters';
import AddressNewComponent from '@/components/AddressNewComponent/AddressNewComponent';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import { ESubjectType } from '@/services/enums/subject';
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
  name: 'card-address',
  components: { InputComponent, DialogComponent, DefaultButton, AddressNewComponent },
  methods: { addressNew },
})
export default class CardAddress extends Mixins(Form) {
  countryList: IDictionaryNode[] = [];
  showModal = false;
  innerForm: Partial<TMapperPlain<any>> = {
    address: {
      country: {},
      additionalInfo: '12312312',
      address: null,
      addressText: '',
    },
  };

  get requiredAddress() {
    return this.form.subjectType === ESubjectType.UL;
  }

  get isEsia() {
    return this.form.created_by === 'ESIA';
  }

  get rules() {
    return {
      address: this.requiredAddress && 'required',
    };
  }

  @Watch('form.address', { deep: true })
  updateForm() {
    this.$emit('change', this.form);
  }

  @Watch('rules')
  updateRules() {
    this.$emit('update-rules', this.rules);
  }

  created() {
    this.getCountry();
    this.$emit('update-rules', this.rules);
  }

  async getCountry(): Promise<CountryItem[]> {
    const data = await this.$store.dispatch('country/getListCountry');
    return (this.countryList = data.content);
  }

  addFields(data) {
    this.form.address = {
      ...data,
    };
    this.showModal = false;
  }

  changeCountry() {
    this.$store.commit('fias/changeAddress', []);
  }
}
</script>
