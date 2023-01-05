<template>
  <div>
    <v-row>
      <v-radio-group v-model="form.subject_type" column>
        <v-row>
          <v-col cols="6">
            <v-radio label="Российское юридическое лицо" value="UL" :disabled="isEdit" @click="onClearForm"></v-radio>
          </v-col>
          <v-col cols="6">
            <v-radio
              label="Индивидуальный предприниматель"
              value="IP"
              :disabled="isEdit"
              @click="onClearForm"
            ></v-radio>
          </v-col>
          <v-col cols="6">
            <v-radio
              label="Юридическое лицо, являющееся иностранным лицом"
              value="IR"
              :disabled="isEdit"
              @click="onClearForm"
            ></v-radio>
          </v-col>
          <v-col cols="6">
            <v-radio
              label="Аккредитованный филиал представительства иностранного юр. лица"
              value="IF"
              :disabled="isEdit"
              @click="onClearForm"
            ></v-radio>
          </v-col>
        </v-row>
      </v-radio-group>
    </v-row>

    <v-row v-if="form.subject_type !== 'IP'">
      <v-col cols="12">
        <label-component label="Наименование" />
        <InputComponent id="organizationName" v-model="form.name" placeholder="Введите текст" />
      </v-col>
    </v-row>

    <v-row v-if="form.subject_type === 'UL'">
      <v-col cols="12">
        <label-component label="Краткое наименование" />
        <InputComponent id="shotOrganizationName" v-model="form.short_name" placeholder="Введите текст" />
      </v-col>

      <v-col cols="12">
        <label-component label="Организационно-правовая форма" />
        <!-- <select-component v-model="form.opf" :items="opfList">
        </select-component> -->
        <autocomplete-component
          v-model="form.opf"
          return-object
          :items="opfList"
          item-value="name"
          item-text="name"
          multiple
          @searchInputUpdate="searchOpf"
        />
      </v-col>
    </v-row>

    <v-row v-if="form.subject_type === 'IP'">
      <v-col cols="12">
        <label-component label="Фамилия" />
        <InputComponent id="lastName" v-model="form.last_name" placeholder="Введите текст" />
      </v-col>
      <v-col cols="12">
        <label-component label="Имя" />
        <InputComponent id="firstName" v-model="form.first_name" placeholder="Введите текст" />
      </v-col>
      <v-col cols="12">
        <label-component label="Отчество (при наличии)" />
        <InputComponent id="secondName" v-model="form.second_name" placeholder="Введите текст" />
      </v-col>
    </v-row>

    <div>
      <v-row v-if="form.subject_type !== 'IR'">
        <v-col cols="12" md="4">
          <label-component label="ИНН" />
          <InputComponent
            id="inn"
            key="inn"
            v-model="form.inn"
            placeholder="Введите текст"
            :mask="innMask"
            :disabled="isEdit"
            @click="getMask"
          />
        </v-col>
        <v-col v-if="form.subject_type !== 'IP'" cols="12" md="4">
          <label-component label="КПП" />
          <InputComponent
            id="kpp"
            key="kpp"
            v-model="form.kpp"
            placeholder="Введите текст"
            mask="#########"
            :disabled="isEdit"
          />
        </v-col>

        <v-col v-if="form.subject_type !== 'IF'" cols="12" md="4">
          <label-component v-if="form.subject_type === 'UL'" label="ОГРН" />
          <label-component v-else label="ОГРНИП" />
          <InputComponent
            id="ogrn"
            key="ogrn"
            v-model="form.ogrn"
            placeholder="Введите текст"
            :mask="ogrnMask"
            @focus="getMaskOgrn"
          />
        </v-col>
      </v-row>
    </div>

    <v-row v-if="form.subject_type === 'UL' || form.subject_type === 'IP'">
      <v-col cols="12">
        <label-component label="Адрес" />
        <InputComponent
          v-if="form.address.address"
          id="address"
          disabled
          placeholder="Введите текст"
          :value="address(form.address)"
        />
      </v-col>

      <v-col v-if="form.address.additional_info" cols="12">
        <label-component label="Дополнительный адрес" />
        <InputComponent
          id="additional_info"
          v-model="form.address.additional_info"
          disabled
          placeholder="Введите текст"
        />
      </v-col>

      <v-col cols="12">
        <DefaultButton variant="primary" title="Указать адрес" @click="changeShowModal"> </DefaultButton>
      </v-col>
    </v-row>

    <v-row v-if="form.subject_type === 'IF'">
      <v-col cols="12">
        <label-component label="Регистрационный номер в РАФП" />
        <InputComponent
          id="nza"
          v-model="form.nza"
          placeholder="Введите текст"
          :mask="nzaMask"
          :disabled="isEdit"
          @focus="getMaskNza"
        />
      </v-col>
      <v-col cols="12">
        <label-component label="Код страны регистрации" />
        <InputComponent id="reg_country_code" v-model="form.reg_country_code" placeholder="Введите текст" />
      </v-col>
    </v-row>

    <v-row v-if="form.subject_type === 'IP'">
      <v-col cols="12">
        <label-component label="Вид документа" />
        <SelectComponent v-model="form.identity_doc_type" :items="documentList" item-value="code" item-text="name" />
      </v-col>
    </v-row>

    <v-row v-if="form.subject_type === 'IP'">
      <v-col cols="12" md="4">
        <label-component label="Серия" />
        <InputComponent
          id="identity_doc_series"
          v-model="form.identity_doc_series"
          placeholder="Введите текст"
          :rules="rulesSeries"
          :mask="maskSeries"
          type="number"
          @focus="getMaskSeries"
        />
      </v-col>
      <v-col cols="12" md="4">
        <label-component label="Номер" />
        <InputComponent
          id="identity_doc_num"
          v-model="form.identity_doc_num"
          placeholder="Введите текст"
          :rules="rulesPassortNumber"
          mask="######"
          type="number"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <span class="span-list">
          <span>
            <label class="checkbox">
              <input v-model="form.is_processor" type="checkbox" :value="form.is_processor" />
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

    <v-row justify="end">
      <v-col cols="12" class="col-exclude">
        <DefaultButton title="Отмена" @click="$emit('close')"> </DefaultButton>
        <DefaultButton variant="primary" title="Сохранить" @click="saveOrganization"> </DefaultButton>
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
        <Address v-model="form.address" :subject-type="form.subject_type" @close="closeModal" @saveAction="addFields" />
      </template>
    </Dialog-component>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import RadioGroupComponent from '@/components/common/inputs/RadioGroupComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import Address from '@/components/Address/Address.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import _ from 'lodash';
import { yearMask } from '@/components/common/inputs/mask/years';
import { address } from '@/utils/global/filters';

type Props = {
  autoSelect?: boolean;
};

@Component({
  name: 'add-contragents',
  components: {
    RadioGroupComponent,
    InputComponent,
    DefaultButton,
    SelectComponent,
    LabelComponent,
    DialogComponent,
    Address,
    AutocompleteComponent,
  },
  methods: { address },
})
export default class AddContragents extends Vue {
  form = {
    subject_type: 'UL',
    address: {
      address: '',
      additional_info: '',
      aoguid: '',
      div_type: '',
      house_guid: '',
      postcode: '',
    },
    identity_doc_type: '',
    kpp: '',
    inn: '',
    ogrn: '',
    name: '',
    is_processor: '',
    //subject_role: "MANUFACTURER",
  };
  opfList: any = [];
  documentList = [];
  showModal = false;
  isEdit = false;
  maskYear = yearMask;
  innMask = '';
  ogrnMask = '';
  nzaMask = '';
  maskSeries = '';
  rulesSeries = [
    (value) => {
      if (value !== undefined && value !== '') {
        return (value && value.length === 4) || 'Серия должна состоять из 4 цифр.';
      } else {
        return true;
      }
    },
  ];
  rulesPassortNumber = [
    (value) => {
      if (value !== undefined && value !== '') {
        return (value && value.length === 6) || 'Номер должен состоять из 6 цифр.';
      } else {
        return true;
      }
    },
  ];

  @Prop({
    type: Boolean,
    default: false,
  })
  readonly autoSelect: Props['autoSelect'] | false;

  onClearForm(): void {
    const nameField = ['kpp', 'inn', 'ogrn', 'name', 'is_processor'];
    nameField.forEach((item) => {
      this.form[item] = '';
      if (item === 'is_processor') {
        this.form[item] = '';
      }
    });
  }

  changeShowModal(): boolean {
    return (this.showModal = true);
  }

  getMask() {
    if (this.form.subject_type === 'UL' || this.form.subject_type === 'IF') {
      this.innMask = '##########';
    } else {
      this.innMask = '############';
    }
  }

  getMaskOgrn() {
    if (this.form.subject_type === 'UL' || this.form.subject_type === 'IF') {
      this.ogrnMask = '#############';
    } else {
      this.ogrnMask = '###############';
    }
  }

  getMaskNza() {
    this.nzaMask = '###########';
  }

  getMaskSeries() {
    this.maskSeries = '####';
  }

  mounted() {
    this.getOpfList();
    this.getDocuments();
  }

  closeModal() {
    this.showModal = !this.showModal;
  }

  addFields(data) {
    this.form.address = {
      ...data,
    };
    // this.form.address.address = data.address;
    // this.form.address.additional_info = data.additional_info;
    // this.form.address.aoguid = data.aoguid;
    // this.form.address.house_guid = data.houseguid;
    // this.form.address.postcode = data.postalcode;
  }

  async saveOrganization() {
    this.$emit('autoSelect', this.form);
    this.$emit('close');
  }

  async searchOpf(value) {
    if (value === null) {
      return;
    }
    const itemIndex = this.opfList.findIndex((item: any) => item.name === value);
    if (itemIndex === -1) {
      const { data } = await this.$store.dispatch('sdiz/getOPF');
      this.opfList = data;
    }
  }

  async getOpfList() {
    const { data } = await this.$store.dispatch('sdiz/getOPF');
    this.opfList = data;
  }

  async getDocuments() {
    const { data } = await this.$store.dispatch('organization/getSubjectDocument');
    this.documentList = data;
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
