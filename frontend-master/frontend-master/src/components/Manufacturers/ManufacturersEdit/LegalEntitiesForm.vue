<template>
  <UiForm
    :rules="rules"
    data-qa="legal_entities__form"
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
        <v-col cols="12">
          <InputComponent
            id="shotOrganizationName"
            v-model="innerForm.shortName"
            placeholder="Введите текст"
            label="Краткое наименование"
            :disabled="innerForm.isEsia"
          />
        </v-col>

        <v-col cols="12">
          <UiControl name="opf" :value="innerForm.opf">
            <autocomplete-component
              v-model="innerForm.opf"
              return-object
              :items="opfList"
              label="Организационно-правовая форма"
              item-value="name"
              item-text="name"
              multiple
              :disabled="innerForm.isEsia"
              @searchInputUpdate="searchOpf"
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

        <v-col cols="12" md="4">
          <UiControl name="ogrn" :value="innerForm.ogrn">
            <InputComponent
              id="ogrn"
              key="ogrn"
              v-model="innerForm.ogrn"
              placeholder="Введите текст"
              label="ОГРН"
              mask="#############"
              :disabled="isEdit || innerForm.isEsia"
            />
          </UiControl>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="6">
          <UiControl name="phoneNumber" :value="innerForm.phoneNumber">
            <InputComponent
              id="phone_number"
              v-model="innerForm.phoneNumber"
              placeholder="Введите номер"
              label="Контактный номер телефона"
              type="tel"
              :disabled="innerForm.isEsia"
            />
          </UiControl>
        </v-col>
        <v-col cols="12" md="6">
          <UiControl name="email" :value="innerForm.email">
            <InputComponent
              id="email"
              v-model="innerForm.email"
              placeholder="Введите email"
              label="Адрес электронной почты"
              type="email"
              :disabled="innerForm.isEsia"
            />
          </UiControl>
        </v-col>
      </v-row>
    </div>
    <div v-if="!step || step === 'address'">
      <v-row>
        <v-col v-if="innerForm.address" cols="12">
          <UiControl name="address" :value="innerForm.address.addressText">
            <InputComponent
              id="address"
              disabled
              placeholder="Введите текст"
              label="Адрес"
              :value="addressNew(innerForm.address)"
            />
          </UiControl>
        </v-col>
        <v-col v-if="innerForm.address" cols="12">
          <InputComponent
            id="additional_info"
            :value="innerForm.address.additionalInfo"
            disabled
            placeholder="Введите текст"
            label="Дополнительный адрес"
          />
        </v-col>

        <v-col cols="12">
          <DefaultButton
            variant="primary"
            :title="innerForm.address && innerForm.address.addressText ? 'Изменить адрес' : 'Указать адрес'"
            @click="showModal = true"
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

    <Dialog-component
      v-model="showModal"
      :prompt="false"
      cancel-title=""
      confirm-title=""
      width="800"
      add-class="address-block"
      with-close-icon
      controls-justify="justify-end"
    >
      <template #title> Адрес </template>
      <template #content>
        <AddressNewComponent
          v-model="innerForm.address"
          :subject-type="innerForm.subjectType"
          @close="showModal = false"
        />
      </template>
    </Dialog-component>

    <v-row v-if="!step || step === 'register'" justify="end">
      <v-col cols="12" class="col-exclude">
        <DefaultButton variant="primary" type="submit" :disabled="isLoading" title="Сохранить" />
      </v-col>
    </v-row>
  </UiForm>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import AddressNewComponent from '@/components/AddressNewComponent/AddressNewComponent';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import { addressNew } from '@/utils/global/filters';
import { IDictionaryNode, TMapperPlain } from '@/services/models/common';
import cloneDeep from 'lodash/cloneDeep';
import { ManufacturerItemIn } from '@/services/mappers/manufacturer';
import { AddressMapperIn } from '@/services/mappers/address';

@Component({
  name: 'legal-entities-form',
  components: {
    InputComponent,
    DefaultButton,
    SelectComponent,
    LabelComponent,
    DialogComponent,
    AutocompleteComponent,
    AddressNewComponent,
  },
  methods: { addressNew },
})
export default class LegalEntitiesForm extends Vue {
  @Prop({ type: Object }) readonly form;
  @Prop({ type: Boolean }) readonly isEdit;
  @Prop({ type: Boolean, default: false }) readonly isLoading?: boolean;
  @Prop({ type: String }) readonly step?: string; /** Шаг формы. Если не задан, то отображается вся форма */
  opfList: IDictionaryNode[] = [];
  showModal = false;
  innerForm: Partial<TMapperPlain<ManufacturerItemIn>> = {};

  get rules() {
    return {
      name: 'required',
      inn: ['required', { size: 10 }],
      kpp: ['required', { size: 9 }],
      ogrn: ['required', { size: 13 }],
      opf: 'required',
      address: 'required',
      email: 'email',
    };
  }

  created() {
    this.innerForm = cloneDeep(this.form);
  }

  changeShowModal(): boolean {
    return (this.showModal = true);
  }

  mounted() {
    this.getOpfList();
  }

  addFields(address) {
    this.showModal = false;
    this.innerForm.address = new AddressMapperIn(address);
  }

  searchOpf(value) {
    if (value === null) {
      return;
    }
    const itemIndex = this.opfList.findIndex((item: any) => item.name === value);
    if (itemIndex === -1) {
      this.getOpfList();
    }
  }

  async getOpfList() {
    const { content } = await this.$store.dispatch('sdiz/getOPF');
    this.opfList = content;
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
