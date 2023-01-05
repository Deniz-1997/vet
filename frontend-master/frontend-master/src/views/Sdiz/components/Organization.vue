<template>
  <div>
    <v-row v-if="!onOpenAddModal">
      <v-col cols="12">
        <span class="header">Выберите организацию</span>
        <autocomplete-component
          v-model="form.name"
          return-object
          :items="listOrganizations"
          item-value="name"
          item-text="name"
          :is-disabled="onOpenAddModal"
          multiple
          @searchInputUpdate="searchOrganization"
        />
        <span class="header"> Если нужная организация не найдена, то добавьте сведения по новой организации </span>

        <span class="btnSpan" @click="onOpenAddModal = true">
          <img src="/icons/add.svg" class="iconSettings" />
          Добавить
        </span>
      </v-col>
    </v-row>
    <div v-if="onOpenAddModal">
      <div class="header">Укажите данные организации</div>
      <v-row>
        <v-radio-group v-model="form.subject_type" column>
          <v-row>
            <v-col cols="6">
              <v-radio label="Российское юридическое лицо" value="UL"></v-radio>
            </v-col>
            <v-col cols="6">
              <v-radio label="Индивидуальный предприниматель" value="IP"></v-radio>
            </v-col>
            <v-col cols="6">
              <v-radio label="Юридическое лицо, являющееся иностранным лицом" value="IR"></v-radio>
            </v-col>
            <v-col cols="6">
              <v-radio label="Аккредитованный филиал представительства иностранного юр. лица" value="IF"></v-radio>
            </v-col>
          </v-row>
        </v-radio-group>
      </v-row>

      <v-row v-if="form.subject_type !== 'IP'">
        <v-col v-if="form.subject_type !== 'IP'" cols="12">
          <label-component label="Наименование" />
          <InputComponent id="organizationName" v-model="form.name.name" placeholder="Наименование" />
        </v-col>
      </v-row>

      <v-row v-if="form.subject_type === 'UL'">
        <v-col cols="12">
          <label-component label="Краткое наименование" />
          <InputComponent id="shotOrganizationName" v-model="form.short_name" placeholder="Введите текст" />
        </v-col>

        <v-col cols="12">
          <label-component label="Организационно-правовая форма" />

          <!--   <select-component v-model="form.opf" :items="opfList">
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

      <v-row>
        <v-col v-if="form.subject_type !== 'IR'" cols="12" md="4">
          <label-component label="ИНН" />
          <InputComponent id="inn" v-model="form.inn" :mask="innMask" placeholder="Введите текст" @click="getMask" />
        </v-col>
        <v-col v-if="form.subject_type !== 'IR' && form.subject_type !== 'IP'" cols="12" md="4">
          <label-component label="КПП" />
          <InputComponent id="kpp" v-model="form.kpp" v-mask="'#########'" placeholder="Введите текст" />
        </v-col>
        <v-col v-if="form.subject_type === 'UL' || form.subject_type === 'IP'" cols="12" md="4">
          <label-component v-if="form.subject_type === 'UL'" label="ОГРН" />
          <label-component v-if="form.subject_type === 'IP'" label="ОГРНИП" />
          <InputComponent
            id="ogrn"
            v-model="form.ogrn"
            placeholder="Введите текст"
            :mask="ogrnMask"
            @click="getMaskOgrn"
          />
        </v-col>
      </v-row>

      <v-row v-if="form.subject_type === 'UL'">
        <v-col cols="12">
          <label-component label="Адрес" />
          <InputComponent
            v-if="form.address.address"
            id="address"
            v-model="form.address.address"
            disabled
            placeholder="Введите текст"
          />
        </v-col>

        <v-col v-if="form.address.additional_info" cols="12">
          <label-component label="Доп. адрес" />
          <InputComponent
            id="additional_info"
            v-model="form.address.additional_info"
            disabled
            placeholder="Введите текст"
          />
        </v-col>

        <v-col cols="12">
          <DefaultButton variant="primary" title="Указать адрес" @click="showModal = true"> </DefaultButton>
        </v-col>
      </v-row>

      <v-row v-if="form.subject_type === 'IF'">
        <v-col cols="12">
          <label-component label="Регистрационный номер РАФП" />
          <InputComponent id="nza" v-model="form.nza" placeholder="Введите текст" mask="###########" />
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
            mask="####"
            :rules="rulesSeries"
          />
        </v-col>
        <v-col cols="12" md="4">
          <label-component label="Номер" />
          <InputComponent
            id="identity_doc_num"
            v-model="form.identity_doc_num"
            placeholder="Введите текст"
            mask="######"
            :rules="rulesPassortNumber"
          />
        </v-col>
      </v-row>

      <v-row justify="end">
        <v-col cols="12" class="col-exclude">
          <DefaultButton title="Отменa" @click="$emit('close')"> </DefaultButton>
          <DefaultButton variant="primary" title="Сохранить" @click="saveOrganization()"> </DefaultButton>
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
          <Address
            v-model="form.address"
            :subject-type="form.subject_type"
            @close="closeModal"
            @saveAction="addFields"
          />
        </template>
      </Dialog-component>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Watch } from 'vue-property-decorator';
import RadioGroupComponent from '@/components/common/inputs/RadioGroupComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import Address from '@/components/Address/Address.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import { ESubjectType } from '@/services/enums/subject';

const mappers = {
  [ESubjectType.IP]: (data) => ({
    subject_type: data.subject_type,
    inn: data.inn,
    ogrn: data.ogrn,
    last_name: data.last_name,
    first_name: data.first_name,
    second_name: data.second_name,
    subject_role: data.subject_role,
    identity_doc_type: {
      id: data.identity_doc_type,
    },
    identity_doc_series: data.identity_doc_series,
    identity_doc_num: data.identity_doc_num,
  }),
  [ESubjectType.UL]: (data) => ({
    inn: data.inn,
    kpp: data.kpp,
    ogrn: data.ogrn,
    name: data.name.name,
    short_name: data.short_name,
    subject_role: data.subject_role,
    opf: {
      code: data.opf.code,
    },
    address: {
      address: data.address.address,
      additional_info: data.address.additional_info,
      aoguid: data.address.aoguid,
      house_guid: data.address.house_guid,
      postcode: data.address.postcode,
      div_type: data.div_type,
    },
    subject_type: data.subject_type,
  }),
  [ESubjectType.IR]: (data) => ({
    name: data.name.name,
    subject_type: data.subject_type,
  }),
  [ESubjectType.IF]: (data) => ({
    name: data.name.name,
    nza: data.nza,
    inn: data.inn,
    kpp: data.kpp,
    reg_country_code: data.reg_country_code,
    subject_type: data.subject_type,
  }),
};

const mapForm = (data) => {
  if (!mappers[data.subject_type]) {
    return {};
  }

  return mappers[data.subject_type](data);
};

@Component({
  name: 'sdiz-organization',
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
})
export default class SdizOrganization extends Vue {
  form: any = {
    subject_type: 'UL',
    opf: {
      label: '',
      value: '',
    },
    address: {
      address: '',
      additional_info: '',
      postcode: '',
      aoguid: '',
      house_guid: '',
      div_type: '',
    },
    name: {
      name: '',
      code: '',
    },
  };
  listOrganizations: any[] = [];
  opfList: any[] = [];
  documentList: any[] = [];
  showModal = false;
  showField = false;
  onOpenAddModal = false;
  innMask = '';
  ogrnMask = '';
  rulesSeries = [(value) => (value && value.length === 4) || 'Серия должна состоять из 4 цифр.'];
  rulesPassortNumber = [(value) => (value && value.length === 6) || 'Номер должен состоять из 6 цифр.'];

  mounted() {
    //this.getOpfList();
    this.getDocuments();
  }

  @Watch('form.subject_type')
  onClear(value) {
    let subject_type;
    if (subject_type !== value) {
      subject_type = value;
      this.form['inn'] = '';
      this.form['kpp'] = '';
      this.form['ogrn'] = '';
      this.form.name.name = '';
    }
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

  closeModal() {
    this.showModal = false;
  }

  addFields(data) {
    this.form.address.address = data.address;
    this.form.address.additional_info = data.additional_info;
    this.form.address.aoguid = data.aoguid;
    this.form.address.div_type = data.div_type;
    this.form.address.house_guid = data.houseguid;
    this.form.address.postcode = data.postalcode;
  }

  async saveOrganization() {
    await this.$store.dispatch('sdiz/createOrganizaton', mapForm(this.form));
    this.$emit('close');
  }

  /*ToDO не работает с debounce, решить после показа*/

  async searchOrganization(value) {
    if (value === null) {
      return;
    }
    const itemIndex = this.listOrganizations.findIndex((item: any) => item.name === value);
    if (itemIndex === -1) {
      let { data } = await this.$store.dispatch('organization/searchOrganization', {
        query: value,
        subjectType: this.form.subject_type,
      });
      this.listOrganizations = data;
    } else {
      let data = await this.$store.dispatch('organization/organizationField', this.form.name.code);
      this.form = {
        ...this.form,
        ...data,
        name: this.form.name,
      };
      if (data.opf)
        this.form.opf = {
          label: data.opf.name,
          value: data.opf.code,
        };
      await setTimeout(() => this.$emit('close'), 1000);
    }
  }

  async searchOpf(value) {
    if (value === null) {
      return;
    }
    const itemIndex = this.opfList.findIndex((item: any) => item.name === value);
    if (itemIndex === -1) {
      let { data } = await this.$store.dispatch('sdiz/getOPF');
      this.opfList = data.map((item) => ({
        label: item.name,
        value: item.code,
      }));
      this.opfList = data;
    } else {
      let data = await this.$store.dispatch('sdiz/getOPF', this.form.name.code);
      this.form = {
        ...this.form,
        ...data,
        name: this.form.name,
      };
      if (data.opf)
        this.form.opf = {
          label: data.opf.name,
          value: data.opf.code,
        };
    }
  }

  async getOpfList() {
    const { data } = await this.$store.dispatch('sdiz/getOPF');
    this.opfList = data.map((item) => ({
      label: item.name,
      value: item.code,
    }));
  }

  async getDocuments() {
    const { data } = await this.$store.dispatch('organization/getSubjectDocument');
    this.documentList = data;
  }
}
</script>

<style lang="scss">
.col-exclude {
  display: flex;
  justify-content: flex-end;
}

.header {
  font-weight: 600;
  color: black;
  padding-bottom: 16px;
}

.btnSpan {
  float: right;
}
</style>
