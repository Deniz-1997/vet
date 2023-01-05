<template>
  <div>
    <v-radio-group v-model="form.subject_type" column>
      <v-row>
        <v-col cols="6">
          <v-radio label="Российское юридическое лицо" value="UL" :disabled="isEdit" @click="onClearForm"></v-radio>
        </v-col>
        <v-col cols="6">
          <v-radio label="Индивидуальный предприниматель" value="IP" :disabled="isEdit" @click="onClearForm"></v-radio>
        </v-col>
      </v-row>
    </v-radio-group>

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
      <v-col v-if="form.subject_type === 'UL'" cols="12" md="4">
        <label-component label="ИНН" />
        <InputComponent
          id="inn"
          key="inn"
          v-model="form.inn"
          placeholder="Введите текст"
          :disabled="isEdit"
          mask="##########"
        />
      </v-col>
      <v-col v-else cols="12" md="4">
        <label-component label="ИНН" />
        <InputComponent
          id="innIp"
          key="innIp"
          v-model="form.inn"
          placeholder="Введите текст"
          :disabled="isEdit"
          mask="############"
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

      <v-col v-if="form.subject_type === 'UL'" cols="12" md="4">
        <label-component label="ОГРН" />
        <InputComponent id="ogrn" key="ogrn" v-model="form.ogrn" placeholder="Введите текст" mask="#############" />
      </v-col>
      <v-col v-else cols="12" md="4">
        <label-component label="ОГРНИП" />
        <InputComponent
          id="ogrnIp"
          key="ogrnIp"
          v-model="form.ogrn"
          placeholder="Введите текст"
          mask="###############"
        />
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
          key="identity_doc_series"
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
          key="identity_doc_num"
          v-model="form.identity_doc_num"
          placeholder="Введите текст"
          mask="######"
          :rules="rulesPassortNumber"
        />
      </v-col>
    </v-row>

    <v-row>
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
        <label-component label="Доп адрес" />
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

    <v-row>
      <v-col cols="12">
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
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <span class="subtitle">Государственные контракты с агентом</span>
      </v-col>
      <v-col>
        <EditableTable
          v-model="form.contractList"
          :options="conservationHeader"
          :max="999"
          :is-can-edit="!isShowcase"
          :is-showcase="isShowcase"
        />
      </v-col>
    </v-row>

    <v-row justify="end">
      <v-col cols="12" class="col-exclude">
        <DefaultButton title="Отмена" @click="$emit('close')" />
        <DefaultButton variant="primary" title="Сохранить" @click="saveAgent" />
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import _ from 'lodash';
import moment from 'moment';
import { Component, Prop, Vue } from 'vue-property-decorator';
import RadioGroupComponent from '@/components/common/inputs/RadioGroupComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import Address from '@/components/Address/Address.vue';
import EditableTable from '@/components/common/Table/index.vue';

const mapInnerForm = (data) => {
  return {
    ...data,
    contractList: data.contractList.map((item) => ({
      ...item,
      doc_date: item.doc_date ? item.doc_date : '-',
      finish_date: item.finish_date ? item.finish_date : '-',
    })),
  };
};

// eslint-disable-next-line max-lines-per-function
const mapForm = (data) => {
  if (data.subject_type === 'UL') {
    return {
      ...data,
      name: data.name,
      short_name: data.name,
      inn: data.inn,
      kpp: data.kpp,
      ogrn: data.ogrn,
      subject_type: data.subject_type,
      address: {
        address: data.address.address,
        additional_info: data.address.additional_info,
        aoguid: data.address.aoguid,
        house_guid: data.address.house_guid,
        postcode: data.address.postcode,
      },
      contractList: data.contractList.map((item) => ({
        ...item,
        doc_date: moment(item.doc_date).format('DD.MM.YYYY'),
        finish_date: moment(item.finish_date).format('DD.MM.YYYY'),
      })),
    };
  }
  if (data.subject_type === 'IP') {
    return {
      ...data,
      identity_doc_type: {
        code: data.identity_doc_type.code,
      },
      last_name: data.last_name,
      first_name: data.first_name,
      second_name: data.second_name,
      inn: data.inn,
      ogrn: data.ogrn,
      subject_type: data.subject_type,
      identity_doc_series: data.identity_doc_series,
      identity_doc_num: data.identity_doc_num,
      contractList: data.contractList.map((item) => ({
        ...item,
        doc_date: moment(item.doc_date).format('DD.MM.YYYY'),
        finish_date: moment(item.finish_date).format('DD.MM.YYYY'),
      })),
    };
  }
};

type Props = {
  idCard: number;
};

@Component({
  name: 'app-agent',
  components: {
    RadioGroupComponent,
    InputComponent,
    DefaultButton,
    SelectComponent,
    LabelComponent,
    AutocompleteComponent,
    DialogComponent,
    Address,
    EditableTable,
  },
})
export default class Agent extends Vue {
  @Prop({ type: [Number], default: () => ({}) })
  readonly idCard!: Props['idCard'];

  form = {
    subject_type: 'UL',
    address: {
      address: '',
      additional_info: '',
      aoguid: '',
      house_guid: '',
      postcode: '',
    },
    contractList: [],
  };
  opfList: any = [];
  documentList = [];
  showModal = false;
  isShowcase = false;
  isEdit = false;
  rulesSeries = [(value) => (value && value.length === 4) || 'Серия должна состоять из 4 цифр.'];
  rulesPassortNumber = [(value) => (value && value.length === 6) || 'Номер должен состоять из 6 цифр.'];

  mounted() {
    this.getOpfList();
    this.getDocuments();
    if (this.idCard) {
      this.isEdit = true;
      this.getCardInfoById();
    }
  }

  get conservationHeader() {
    return [
      {
        label: 'Номер',
        name: 'doc_num',
        width: 200,
      },
      {
        label: 'Дата',
        name: 'doc_date',
        controlType: 'date',
        limitTo: new Date(),
        width: 250,
      },
      {
        label: 'Дата окончания действия',
        name: 'finish_date',
        controlType: 'date',
        width: 250,
      },
    ];
  }

  async getOpfList() {
    const { data } = await this.$store.dispatch('sdiz/getOPF');
    this.opfList = data;
  }

  closeModal() {
    this.showModal = !this.showModal;
  }

  onClearForm(): void {
    const nameField = ['kpp', 'inn', 'ogrn'];
    nameField.forEach((item) => {
      this.form[item] = '';
    });
  }

  async getCardInfoById() {
    const data: any = await this.$store.dispatch('agents/getListAgents', this.idCard);
    this.form = mapInnerForm(data);
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

  addFields(data) {
    this.form.address.address = data.address;
    this.form.address.additional_info = data.additional_info;
    this.form.address.aoguid = data.aoguid;
    this.form.address.house_guid = data.houseguid;
    this.form.address.postcode = data.postalcode;
  }

  async getDocuments() {
    const { data } = await this.$store.dispatch('organization/getSubjectDocument');
    this.documentList = data;
  }

  async saveAgent() {
    if (this.isEdit) {
      await this.$store.dispatch('agents/updateAgents', mapForm(this.form));
      this.$emit('close');
      this.isEdit = false;
    } else {
      await this.$store.dispatch('agents/createAgents', mapForm(this.form));
      this.$emit('close');
    }
  }
}
</script>

<style lang="scss" scoped>
.subtitle {
  font-weight: 700;
}
</style>
