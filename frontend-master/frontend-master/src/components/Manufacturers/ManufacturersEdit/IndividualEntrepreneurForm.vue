<template>
  <UiForm
    :rules="rules"
    :messages="messages"
    data-qa="individual_entrepreneur__form"
    @validation="(v) => (isValid = v.isValid)"
    @submit="$emit('save', innerForm)"
  >
    <div v-if="!step || step === 'general'">
      <div class="subtitle">Заполнение сведений об ИП</div>
      <v-row>
        <v-col cols="12">
          <UiControl name="lastName" :value="innerForm.lastName">
            <InputComponent id="lastName" v-model="innerForm.lastName" placeholder="Введите текст" label="Фамилия" />
          </UiControl>
        </v-col>
        <v-col cols="12">
          <UiControl name="firstName" :value="innerForm.firstName">
            <InputComponent id="firstName" v-model="innerForm.firstName" placeholder="Введите текст" label="Имя" />
          </UiControl>
        </v-col>
        <v-col cols="12">
          <InputComponent
            id="secondName"
            v-model="innerForm.secondName"
            placeholder="Введите текст"
            label="Отчество (при наличии)"
            :disabled="!isNameDifferent || innerForm.isEsia"
          />
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12">
          <UiControl name="name" :value="innerForm.name">
            <InputComponent
              id="name"
              v-model="fieldName"
              placeholder="Введите текст"
              label="Наименование ИП"
              :disabled="!isNameDifferent || innerForm.isEsia"
              @input="changeFieldName"
            />
          </UiControl>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12">
          <span class="span-list">
            <span>
              <label class="checkbox">
                <input
                  v-model="isNameDifferent"
                  type="checkbox"
                  :value="isNameDifferent"
                  :disabled="innerForm.isEsia"
                />
                <span class="checkbox__icon">
                  <img src="/icons/checkbox.svg" />
                </span>
              </label>
            </span>
            <span class="checkbox-title"> Наименование ИП отличается от ФИО индивидуального предпринимателя</span>
          </span>
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
              mask="############"
              label="ИНН"
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
              label="ОГРНИП"
              mask="###############"
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
      <div class="subtitle">Адрес регистрации ИП</div>
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
            v-if="innerForm.address && innerForm.address.addressText"
            variant="primary"
            title="Изменить адрес"
            @click="showModal = true"
          />
          <DefaultButton v-else variant="primary" title="Указать адрес" @click="showModal = true" />
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
  </UiForm>
</template>

<script lang="ts">
import { Component, Prop, Watch, Vue } from 'vue-property-decorator';
import { AddressMapperIn } from '@/services/mappers/address';
import AddressNewComponent from '@/components/AddressNewComponent/AddressNewComponent';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import cloneDeep from 'lodash/cloneDeep';
import { TMapperPlain } from '@/services/models/common';
import { ManufacturerItemIn } from '@/services/mappers/manufacturer';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import { addressNew } from '@/utils/global/filters';
import { ESubjectType } from '@/services/enums/subject';

@Component({
  name: 'individual-entrepreneur-form',
  components: {
    InputComponent,
    AddressNewComponent,
    DefaultButton,
    DialogComponent,
    SelectComponent,
  },
  methods: { addressNew },
})
export default class IndividualEntrepreneurForm extends Vue {
  @Prop({ type: Object }) readonly form;
  @Prop({ type: Boolean }) readonly isEdit;
  @Prop({ type: Boolean, default: false }) readonly isLoading?: boolean;
  @Prop({ type: String }) readonly step?: string; /** Шаг формы. Если не задан, то отображается вся форма */

  showModal = false;
  isNameDifferent = false;
  innerForm: Partial<TMapperPlain<ManufacturerItemIn>> = { name: '' };

  get rules() {
    return {
      lastName: 'required',
      firstName: 'required',
      inn: ['required', { size: 12 }],
      ogrn: ['required', { size: 15 }],
      docDate: this.innerForm.identityType && 'required',
      email: 'email',
      name: this.isNameDifferent && 'required',
    };
  }

  get messages() {
    return {
      required: 'Поле обязательно для заполнения',
    };
  }

  get fullName() {
    return [this.innerForm.lastName, this.innerForm.firstName, this.innerForm.secondName].join(' ').trim();
  }

  get limit() {
    return this.$moment().add(1, 'd').toDate();
  }

  changeFieldName(value) {
    if (!this.isNameDifferent) this.innerForm.name = value;
  }

  created() {
    if (this.isEdit) {
      this.innerForm = cloneDeep(this.form);
    } else {
      this.innerForm.subjectType = ESubjectType.IP;
    }
    this.isNameDifferent = !!this.innerForm.name && this.innerForm.name !== this.fullName;
  }

  addFields(address) {
    this.showModal = false;
    this.innerForm.address = new AddressMapperIn(address);
  }

  @Watch('innerForm.firstName')
  changeFirstName() {
    this.changeFieldName(this.fullName);
  }

  @Watch('innerForm.lastName')
  changeLastName() {
    this.changeFieldName(this.fullName);
  }

  @Watch('innerForm.secondName')
  changeSecondName() {
    this.changeFieldName(this.fullName);
  }

  @Watch('isNameDifferent')
  changeIsNameDifferent() {
    this.changeFieldName(this.fullName);
  }
}
</script>

<style lang="scss" scoped>
.subtitle {
  font-size: 18px;
  font-weight: 500;
  margin-bottom: 20px;
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
