<template>
  <div class="generalInformation">
    <div class="baseInformation">
      <v-row v-if="!isForeign">
        <v-col cols="12" md="4" xl="4">
          <div class="inputContainer">
            <InputComponent
              disabled
              label="ИНН"
              :value="form.subject && form.subject.subject_data && form.subject.subject_data.inn"
            />
          </div>
        </v-col>
        <v-col v-if="form.subject && form.subject.subject_type !== 'IP'" cols="12" md="4" xl="4">
          <div class="inputContainer">
            <InputComponent
              disabled
              label="КПП"
              :value="form.subject && form.subject.subject_data && form.subject.subject_data.kpp"
            />
          </div>
        </v-col>
        <v-col v-if="!isForeignBranch" cols="12" md="4" xl="4">
          <div class="inputContainer">
            <InputComponent
              disabled
              label="ОГРН/ОГРНИП"
              :value="form.subject && form.subject.subject_data && form.subject.subject_data.ogrn"
            />
          </div>
        </v-col>
        <v-col v-else-if="form.subject.subject_data.nza" cols="12" md="4">
          <UiControl name="nza" :value="form.subject.subject_data.nza">
            <InputComponent
              id="nza"
              :value="form.subject.subject_data.nza"
              placeholder="Введите текст"
              label="Регистрационный номер в РАФП"
              mask="###########"
              disabled
            />
          </UiControl>
        </v-col>
      </v-row>

      <v-row v-if="isSubjectTypeIP && typeCard !== 'organization'">
        <v-col cols="12" md="12" xl="12">
          <UiControl tag="div" class="inputContainer" name="identityType" :value="form.identity_doc.type">
            <SelectComponent
              v-model="form.identity_doc.type"
              :items="documentList"
              label="Вид документа"
              item-value="code"
              item-text="name"
              return-object
              :is-disabled="isShowcase"
              name="identity_doc.type"
              @onChange="onIdentityChange"
            />
          </UiControl>
        </v-col>
        <v-col cols="12" md="4" xl="4">
          <UiControl tag="div" class="inputContainer" name="identitySeries" :value="form.identity_doc.series">
            <InputComponent
              v-model="form.identity_doc.series"
              label="Серия"
              :disabled="isShowcase"
              :mask="isPassportIdentity ? '####' : undefined"
              name="identity_doc.series"
            />
          </UiControl>
        </v-col>
        <v-col cols="12" md="4" xl="4">
          <UiControl tag="div" class="inputContainer" name="identityNumber" :value="form.identity_doc.id_number">
            <InputComponent
              v-model="form.identity_doc.id_number"
              label="Номер"
              :disabled="isShowcase"
              :mask="isPassportIdentity ? '######' : undefined"
              name="identity_doc.id_number"
            />
          </UiControl>
        </v-col>
        <v-col cols="12" md="4" xl="4">
          <UiControl tag="div" class="inputContainer" name="identityDate" :value="form.identity_doc.doc_date">
            <UiDateInput
              v-model="form.identity_doc.doc_date"
              class="datePicker"
              label="Дата выдачи документа"
              :limit-to="$moment().add(1, 'd').toDate()"
              :disabled="isShowcase"
              name="identity_doc.doc_date"
            />
          </UiControl>
        </v-col>
      </v-row>

      <v-row v-if="!isForeign">
        <v-col cols="12" md="10" xl="6">
          <div class="inputContainer">
            <div class="element">
              <InputComponent label="Регион" disabled :value="region.name" name="region.name" />
            </div>
          </div>
        </v-col>
        <v-col cols="12" md="2" xl="3">
          <div class="inputContainer">
            <div class="element">
              <InputComponent label="Код региона" disabled :value="region.code" name="region.code" />
            </div>
          </div>
        </v-col>
      </v-row>

      <v-row v-if="!isForeign">
        <v-col cols="12" md="12" xl="12">
          <div class="inputContainer">
            <div class="element">
              <InputComponent label="Адрес" disabled :value="addressOrganization" name="subject.address" />
            </div>
          </div>
        </v-col>
      </v-row>

      <v-row v-if="!isForeign && form.subject && form.subject.additional_info">
        <v-col cols="12" md="12" xl="12">
          <div class="inputContainer">
            <div class="element">
              <InputComponent
                label="Дополнительный адрес"
                disabled
                :value="form.subject && form.subject.additional_info"
                name="subject.additional_info"
              />
            </div>
          </div>
        </v-col>
      </v-row>
    </div>

    <v-row>
      <v-col cols="12">
        <UiCheckbox
          id="is_mechanized"
          v-model="form.elevator_info.is_mechanized"
          name="is_mechanized"
          label="Механизированный элеватор"
          class="mt-6"
          :disabled="isShowcase"
          @input="onMechanizedToggle"
        />
      </v-col>
    </v-row>

    <v-row v-if="form.elevator_info.is_mechanized">
      <v-col cols="12" md="12" lg="6" xl="6">
        <UiControl name="hazardous_object" :value="form.elevator_info.elevator_info_hazardous_object">
          <EditableTable
            v-model="form.elevator_info.elevator_info_hazardous_object"
            title="Свидетельства о регистрации опасных производственных объектов, используемых организацией"
            :options="headersCertificateRegistration"
            :max="999"
            :is-can-edit="!isShowcase"
            :is-showcase="isShowcase"
            id-table="elevator_info_hazardous_object"
          />
        </UiControl>
      </v-col>
    </v-row>
    <v-row v-if="form.elevator_info.is_mechanized">
      <v-col cols="12" md="10" xl="9">
        <EditableTable
          v-model="form.elevator_info.elevator_info_insurance"
          title="Страхование гражданской ответственности организации"
          :options="headersCivilLiabilityInsurance"
          :max="999"
          :is-can-edit="!isShowcase"
          :is-showcase="isShowcase"
          :rules="civilLiabilityRules"
          :messages="civilLiabilityMessages"
          id-table="elevator_info_insurance"
          @validate="(evt) => $emit('validate', evt)"
        />
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';
import EditableTable from '@/components/common/Table/index.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import { ESubjectType } from '@/services/enums/subject';
import { IDictionaryNode } from '@/services/models/common';

type DocumentType = {
  label: string;
  value: number;
};

/**ToDo: У форм прописать полную типизацию */
type Props = {
  form: any;
  fullInfo: boolean;
  typeCard: string;
  isShowcase: boolean;
  organizationInfo: any;
};

@Component({
  name: 'card-general-information',
  components: { EditableTable, InputComponent, SelectComponent },
})
export default class CardGeneralInformation extends Vue {
  @Prop({ type: Object, default: () => ({}) }) readonly form!: Props['form'];
  @Prop({ type: Object, default: () => ({}) }) readonly organizationInfo!: Props['organizationInfo'];
  @Prop({ type: Boolean, default: () => false }) readonly fullInfo!: Props['fullInfo'];
  @Prop({ type: String, default: () => 'request' }) readonly typeCard!: Props['typeCard'];
  @Prop({ type: Boolean, default: () => false }) readonly isShowcase!: Props['isShowcase'];
  typeDocument: DocumentType[] = [];
  documentList: IDictionaryNode[] = [];
  headersCertificateRegistration = [
    {
      label: 'Регистрационный номер',
      name: 'doc_num',
      width: 350,
    },
    {
      label: 'Дата',
      name: 'doc_date',
      controlType: 'date',
      limitTo: this.$moment().add(1, 'd').toDate(),
      width: 220,
    },
  ];

  get headersCivilLiabilityInsurance(): any {
    return [
      {
        label: 'Тип договора',
        name: 'document_type',
        controlType: 'select',
        restrictions: this.typeDocument,
        exclude: true,
        width: 550,
      },
      {
        label: 'Номер',
        name: 'doc_num',
        width: 200,
      },
      {
        label: 'Дата начала действия',
        name: 'doc_date',
        controlType: 'date',
        width: 250,
      },
      {
        label: 'Дата окончания действия',
        name: 'validity_date',
        controlType: 'date',
        limitTo: '31.12.2027',
        width: 250,
      },
    ];
  }

  get isPassportIdentity() {
    return this.form?.identity_doc?.type.code === 'RF_PASSPORT';
  }

  get isForeign() {
    return [ESubjectType.IR, ESubjectType.UNKNOWN].includes(this.form?.subject?.subject_type);
  }

  get isForeignBranch() {
    return this.form?.subject?.subject_type === ESubjectType.IF;
  }

  get isSubjectTypeIP() {
    return this.form?.subject?.subject_type === ESubjectType.IP;
  }

  async created() {
    await this.fetchTypeDocument();
  }

  get civilLiabilityRules() {
    return {
      doc_date: ['required', 'before:validity_date'],
      validity_date: ['required', 'after:doc_date'],
    };
  }

  get civilLiabilityMessages() {
    return {
      before: 'Дата начала действия должна быть меньше даты окончания',
      after: 'Дата начала действия должна быть меньше даты окончания',
      'required.doc_date': 'Поле «Дата начала» обязательное для заполнения',
      'required.validity_date': 'Поле «Дата окончания» обязательное для заполнения',
    };
  }

  get region() {
    const { code_okato, name_okato } = this.form?.subject?.oker ?? {};

    return {
      name: name_okato,
      code: code_okato,
    };
  }

  get addressOrganization(): string {
    if (this.form.subject) {
      if (this.form.subject.address_text) {
        return this.form.subject.address_text;
      }
      return '';
    }
    return '';
  }

  get addressAdditionalInfo(): string {
    if (this.form.subject) {
      return this.form.subject.address ? this.form.subject.address.additional_info : '-';
    } else if (this.organizationInfo) {
      return this.organizationInfo.address ? this.organizationInfo.address.additional_info : '-';
    } else {
      return '';
    }
  }

  async fetchTypeDocument() {
    const [typeDocument, documentList] = await Promise.all([
      this.$store.dispatch('elevator/getListInsuranceDocument', { actual: true }),
      this.$store.dispatch('organization/getSubjectDocument'),
    ]);
    this.typeDocument = typeDocument.content.map((item) => ({
      ...item,
      label: item.name,
      value: item.code,
    }));
    this.documentList = documentList.content;
  }

  onIdentityChange() {
    this.form.identity_doc = {
      ...this.form.identity_doc,
      series: '',
      id_number: '',
      doc_date: undefined,
    };
  }

  onMechanizedToggle(value: boolean) {
    if (!value) {
      this.form.elevator_info.elevator_info_hazardous_object = [];
      this.form.elevator_info.elevator_info_insurance = [];
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.baseInformation {
  margin-top: 25px;
}

.label {
  color: $medium-grey-color;
  font-size: 13px;
  line-height: 16px;
  margin-bottom: 5px;

  @include respond-to('medium') {
    font-size: 13px;
  }

  @include respond-to('small') {
    font-size: 14px;
  }
}

.input {
  border: 1px solid $input-border-color;
  border-radius: 3px;
  background: $white-color;
  outline: none;
  width: 100%;
  height: 40px;
  color: $black-color;
  font-size: 14px;
  line-height: 16px;
  padding: 0 10px;
  z-index: 9;
  max-width: 955px;

  &--disabled {
    background-color: $input-disable-background;
    color: $input-disabled-color;
  }

  &--small {
    width: 308px;
  }
}

.element {
  display: flex;
  flex-direction: column;
  width: 100%;
}
</style>
