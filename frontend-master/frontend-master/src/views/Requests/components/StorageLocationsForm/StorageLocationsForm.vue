<template>
  <UiForm :rules="rules" :messages="messages" @validation="(v) => (isValid = v.isValid)" @submit="saveStorage">
    <div class="overlay" @click="clickClose" />
    <v-container class="storage-locations">
      <v-row>
        <v-col cols="10">
          <div class="title">
            <span>Места хранения услуги</span>
          </div>
        </v-col>
        <v-col cols="1"> </v-col>
        <v-col cols="1">
          <div class="close">
            <img src="/icons/cross.svg" class="cross" @click="clickClose" />
          </div>
        </v-col>
      </v-row>
      <div class="baseInformation">
        <v-row>
          <v-col cols="12" md="12" lg="6" xl="6">
            <div class="inputContainer">
              <InputComponent
                class="input--large"
                label="Организация"
                :value="
                  (storage.form.subjectName && storage.form.subjectName.name) ||
                  (storage.form.subject_name && storage.form.subject_name) ||
                  (storage.form.subject && storage.form.subject.subject_name) ||
                  (storage.form.subject && storage.form.subject.subject_data.name)
                "
                disabled
                name="organization"
              />
            </div>
          </v-col>

          <v-col cols="12" md="12" lg="6" xl="6">
            <div class="inputContainer">
              <UiControl name="cadastral_number" :value="tempForm.cadastral_number">
                <v-tooltip bottom>
                  <template #activator="{ on, attrs }">
                    <span v-bind="attrs" v-on="on">
                      <InputComponent
                        v-model="tempForm.cadastral_number"
                        class="input--large"
                        label="Кадастровый номер"
                        :edit="changesItemData && changesItemData.cadastral_number.editCode === 'EDIT'"
                        :disabled="isViewType"
                        :mask="maskCadastral"
                        name="cadastral_number"
                      />
                    </span>
                  </template>
                  <span
                    >Кадастровый номер должен содержать от 11 до 16 цифр. Если третий разряд вашего кадастрового номера
                    (кадастровый квартал) состоит из 6 цифр, вам необходимо самостоятельно ввести разделитель разряда
                    (двоеточие).</span
                  >
                </v-tooltip>
                <div v-if="changesItemData && changesItemData.cadastral_number.editCode !== 'NONE'" class="pop-up">
                  Старое значение:
                  {{ changesItemData && changesItemData.cadastral_number.oldValue }}
                  <span v-if="changesItemData && !changesItemData.cadastral_number.oldValue">-</span>
                </div>
              </UiControl>
            </div>
          </v-col>
        </v-row>

        <v-row>
          <v-col v-if="tempForm.address.address" cols="12">
            <div class="inputContainer">
              <InputComponent
                :value="address(tempForm.address)"
                label="Адрес"
                class="input--large"
                :edit="changesItemData && changesItemData.address.editCode === 'EDIT'"
                disabled
                name="address"
              />
              <div v-if="changesItemData && changesItemData.address_name.editCode !== 'NONE'" class="pop-up">
                Старое значение:
                {{ changesItemData && changesItemData.address_name.oldValue }}
                <span v-if="changesItemData && !changesItemData.address_name.oldValue">-</span>
              </div>
            </div>
          </v-col>

          <v-col v-if="tempForm.address.additional_info" cols="12">
            <div class="inputContainer">
              <InputComponent
                :value="tempForm.address.additional_info"
                label="Дополнительный адрес"
                :edit="changesItemData && changesItemData.additional_info.editCode === 'EDIT'"
                class="input--large"
                disabled
                name="address.additional_info"
              />
              <div v-if="changesItemData && changesItemData.address_add.editCode !== 'NONE'" class="pop-up">
                Старое значение:
                {{ changesItemData && changesItemData.address_add.oldValue }}
                <span v-if="changesItemData && !changesItemData.address_add.oldValue">-</span>
              </div>
            </div>
          </v-col>
          <v-col v-if="!isViewType" cols="12">
            <UiControl name="address" :value="address(tempForm.address)">
              <DefaultButton
                variant="primary"
                :title="address(tempForm.address) ? 'Изменить адрес' : 'Указать адрес'"
                @click="showModal = true"
              />
            </UiControl>
          </v-col>
        </v-row>

        <v-row class="block">
          <v-col cols="12" md="12" lg="4" xl="3">
            <div class="inputContainer">
              <UiControl name="own_rent" :value="tempForm.own_rent.code">
                <SelectComponent
                  v-if="!isViewType"
                  v-model="tempForm.own_rent"
                  label="Принадлежность"
                  return-object
                  item-value="code"
                  item-text="name"
                  :items="ownRentList"
                  :is-disabled="isViewType"
                  clearable
                  @change="clearOwnRent"
                  name="own_rent"
                />
                <InputComponent
                  v-else
                  label="Принадлежность"
                  class="input--large"
                  :edit="changesItemData && changesItemData.own_rent.editCode === 'EDIT'"
                  :value="tempForm.own_rent ? tempForm.own_rent.label : '-'"
                  disabled
                  name="own_rent"
                />
                <div v-if="changesItemData && changesItemData.own_rent.editCode !== 'NONE'" class="pop-up">
                  Старое значение:
                  {{ changesItemData && changesItemData.own_rent.oldValue }}
                  <span v-if="changesItemData && !changesItemData.own_rent.oldValue">-</span>
                </div>
              </UiControl>
            </div>
          </v-col>

          <v-col v-if="!!tempForm.own_rent.code" cols="12" md="12" lg="3" xl="3">
            <div class="inputContainer">
              <UiControl name="doc_num_rent" :value="tempForm.own_rent_document.doc_num">
                <InputComponent
                  v-model="tempForm.own_rent_document.doc_num"
                  label="Номер документа"
                  :edit="changesItemData && changesItemData.own_rent_doc_num.editCode === 'EDIT'"
                  class="input--large"
                  :disabled="isViewType"
                  name="own_rent_document.doc_num"
                />
              </UiControl>
              <div v-if="changesItemData && changesItemData.own_rent_doc_num.editCode !== 'NONE'" class="pop-up">
                Старое значение:
                {{ changesItemData && changesItemData.own_rent_doc_num.oldValue }}
                <span v-if="changesItemData && !changesItemData.own_rent_doc_num.oldValue">-</span>
              </div>
            </div>
          </v-col>

          <v-col v-if="!!tempForm.own_rent.code" cols="12" md="12" lg="5" xl="6">
            <div
              class="inputContainer"
              :class="{
                'edit-elem': changesItemData && changesItemData.own_rent_doc_date.editCode === 'EDIT',
              }"
            >
              <UiControl name="doc_date_rent" :value="tempForm.own_rent_document.doc_date">
                <UiDateInput
                  v-model="tempForm.own_rent_document.doc_date"
                  class="datePicker"
                  :edit="changesItemData && changesItemData.own_rent_doc_date.editCode === 'EDIT'"
                  :format="'DD.MM.YYYY'"
                  :label="tempForm.own_rent.code === 'RENT' ? 'Дата документа аренды' : 'Дата документа собственности'"
                  :limit-to="$moment().add(1, 'd').toDate()"
                  title-format="MMMM YYYY"
                  :disabled="isViewType"
                  name="own_rent_document.doc_date"
                />
              </UiControl>
              <div v-if="changesItemData && changesItemData.own_rent_doc_date.editCode !== 'NONE'" class="pop-up">
                Старое значение:
                {{ changesItemData && changesItemData.own_rent_doc_date.oldValue }}
                <span v-if="changesItemData && !changesItemData.own_rent_doc_date.oldValue">-</span>
              </div>
            </div>
          </v-col>
        </v-row>

        <v-row>
          <v-col cols="12" md="12" lg="6" xl="6">
            <div class="inputContainer">
              <UiControl name="granary_type" :value="tempForm.granary_type">
                <SelectComponent
                  v-if="!isViewType"
                  v-model="tempForm.granary_type"
                  return-object
                  :items="services"
                  item-value="code"
                  item-text="name"
                  label="Вид зернохранилища"
                  :is-disabled="isViewType"
                  name="granary_type"
                />
                <InputComponent
                  v-else
                  label="Вид зернохранилища"
                  :edit="changesItemData && changesItemData.granary_type.editCode === 'EDIT'"
                  class="input--large"
                  :value="tempForm.granary_type ? tempForm.granary_type.name : '-'"
                  disabled
                  name="granary_type"
                />
                <div v-if="changesItemData && changesItemData.granary_type.editCode !== 'NONE'" class="pop-up">
                  Старое значение:
                  {{ changesItemData && changesItemData.granary_type.oldValue }}
                  <span v-if="changesItemData && !changesItemData.granary_type.oldValue">-</span>
                </div>
              </UiControl>
            </div>
          </v-col>
          <v-col cols="12" md="12" lg="6" xl="6">
            <div class="inputContainer">
              <UiControl name="elevator_site_storage" :value="tempForm.elevator_site_storage">
                <SelectComponent
                  v-if="!isViewType"
                  v-model="tempForm.elevator_site_storage"
                  return-object
                  :items="storageLocationsList"
                  :is-disabled="isViewType"
                  item-value="code"
                  item-text="name"
                  label="Способ хранения"
                  name="elevator_site_storage"
                />

                <InputComponent
                  v-else
                  label="Способ хранения"
                  :edit="changesItemData && changesItemData.elevator_site_storage.editCode === 'EDIT'"
                  class="input--large"
                  :value="tempForm.elevator_site_storage ? tempForm.elevator_site_storage.name : '-'"
                  disabled
                  name="elevator_site_storage"
                />
                <div v-if="changesItemData && changesItemData.elevator_site_storage.editCode !== 'NONE'" class="pop-up">
                  Старое значение:
                  {{ changesItemData && changesItemData.elevator_site_storage.oldValue }}
                  <span v-if="changesItemData && !changesItemData.elevator_site_storage.oldValue">-</span>
                </div>
              </UiControl>
            </div>
          </v-col>
        </v-row>

        <v-row>
          <v-col cols="12" md="12" lg="6" xl="6">
            <div class="inputContainer">
              <UiControl name="capacity_tons" :value="tempForm.capacity_tons">
                <InputComponent
                  v-model="tempForm.capacity_tons"
                  class="input--large"
                  label="Вместимость, тонны"
                  :disabled="isViewType"
                  :maxlength="12"
                  :mask="maskFractionalNumber"
                  :edit="changesItemData && changesItemData.capacity_tons.editCode === 'EDIT'"
                  name="capacity_tons"
                />
                <div v-if="changesItemData && changesItemData.capacity_tons.editCode !== 'NONE'" class="pop-up">
                  Старое значение:
                  {{ changesItemData && changesItemData.capacity_tons.oldValue }}
                  <span v-if="changesItemData && !changesItemData.capacity_tons.oldValue">-</span>
                </div>
              </UiControl>
            </div>
          </v-col>
        </v-row>

        <v-row>
          <v-col cols="12" md="12" lg="6" xl="6">
            <div class="inputContainer">
              <UiControl name="doc_num_act_commissioning" :value="tempForm.act_commissioning.doc_num">
                <InputComponent
                  v-model="tempForm.act_commissioning.doc_num"
                  class="input--large"
                  label="Акт ввода в эксплуатацию (номер)"
                  :disabled="isViewType"
                  :edit="changesItemData && changesItemData.act_commissioning_num.editCode === 'EDIT'"
                  name="act_commissioning.doc_num"
                />
                <div v-if="changesItemData && changesItemData.act_commissioning_num.editCode !== 'NONE'" class="pop-up">
                  Старое значение:
                  {{ changesItemData && changesItemData.act_commissioning_num.oldValue }}
                  <span v-if="changesItemData && !changesItemData.act_commissioning_num.oldValue">-</span>
                </div>
              </UiControl>
            </div>
          </v-col>

          <v-col cols="12" md="12" lg="6" xl="6">
            <div class="inputContainer">
              <UiControl
                name="doc_date_act_commissioning"
                :value="+$moment(tempForm.act_commissioning.doc_date, 'DD.MM.YYYY').toDate()"
              >
                <UiDateInput
                  v-model="tempForm.act_commissioning.doc_date"
                  class="datePicker"
                  label="Акт ввода в эксплуатацию (дата)"
                  :edit="changesItemData && changesItemData.own_rent_doc_date.editCode === 'EDIT'"
                  format="DD.MM.YYYY"
                  title-format="MMMM YYYY"
                  :disabled="isViewType"
                  limit-from="01.01.1800"
                  :limit-to="$moment().add(1, 'd').toDate()"
                  name="act_commissioning.doc_date"
                />
                <div v-if="changesItemData && changesItemData.own_rent_doc_date.editCode !== 'NONE'" class="pop-up">
                  Старое значение:
                  {{ changesItemData && changesItemData.own_rent_doc_date.oldValue }}
                  <span v-if="changesItemData && !changesItemData.own_rent_doc_date.oldValue">-</span>
                </div>
              </UiControl>
            </div>
          </v-col>
        </v-row>

        <v-row>
          <v-col cols="12" md="12" lg="6" xl="6">
            <div class="inputContainer">
              <UiControl
                name="year_construction"
                :value="tempForm.year_construction && +$moment(tempForm.year_construction, 'YYYY').toDate()"
              >
                <Year
                  v-model="tempForm.year_construction"
                  :disabled="isViewType"
                  label="Год постройки"
                  :starting-year="1800"
                  :limit-to="currentDate"
                  :edit="changesItemData && changesItemData.year_construction.editCode === 'EDIT'"
                  name="year_construction"
                />
              </UiControl>
              <div v-if="changesItemData && changesItemData.year_construction.editCode !== 'NONE'" class="pop-up">
                Старое значение:
                {{ changesItemData && changesItemData.year_construction.oldValue }}
                <span v-if="changesItemData && !changesItemData.year_construction.oldValue">-</span>
              </div>
            </div>
          </v-col>
          <v-col cols="12" md="12" lg="6" xl="6">
            <div class="inputContainer">
              <UiControl
                name="year_last_reconstruction"
                :value="
                  tempForm.year_last_reconstruction && +$moment(tempForm.year_last_reconstruction, 'YYYY').toDate()
                "
              >
                <Year
                  v-model="tempForm.year_last_reconstruction"
                  :disabled="isViewType"
                  :limit-to="currentDate"
                  label="Год реконструкции"
                  :starting-year="1800"
                  :edit="changesItemData && changesItemData.year_last_reconstruction.editCode === 'EDIT'"
                  name="year_last_reconstruction"
                />
              </UiControl>
              <div
                v-if="changesItemData && changesItemData.year_last_reconstruction.editCode !== 'NONE'"
                class="pop-up"
              >
                Старое значение:
                {{ changesItemData && changesItemData.year_last_reconstruction.oldValue }}
                <span v-if="changesItemData && !changesItemData.oldValue"></span>
              </div>
            </div>
          </v-col>

          <v-col cols="12" md="12" lg="6" xl="6">
            <div class="inputContainer">
              <UiControl
                name="year_overhaul"
                :value="tempForm.year_overhaul && +$moment(tempForm.year_overhaul, 'YYYY').toDate()"
              >
                <Year
                  v-model="tempForm.year_overhaul"
                  :disabled="isViewType"
                  :limit-to="currentDate"
                  label="Год последнего капитального ремонта"
                  :starting-year="1800"
                  :edit="changesItemData && changesItemData.year_overhaul.editCode === 'EDIT'"
                  name="year_overhaul"
                />
              </UiControl>
              <div v-if="changesItemData && changesItemData.year_overhaul.editCode !== 'NONE'" class="pop-up">
                Старое значение:
                {{ changesItemData && changesItemData.year_overhaul.oldValue }}
                <span v-if="changesItemData && !changesItemData.oldValue"></span>
              </div>
            </div>
          </v-col>
        </v-row>
      </div>

      <v-row>
        <v-col cols="12">
          <div class="buttons">
            <DefaultButton title="Закрыть" @click="clickClose" />
            <DefaultButton v-if="!isViewType" title="Сохранить" variant="primary" type="submit" :disabled="!isValid">
            </DefaultButton>
          </div>
        </v-col>
      </v-row>
    </v-container>

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
          v-model="tempForm.address"
          :subject-type="storage.form.subject && storage.form.subject.subject_type"
          @close="closeModal"
          @saveAction="addFields"
        />
      </template>
    </Dialog-component>
  </UiForm>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import 'vue2-datepicker/locale/ru';
import 'vue2-datepicker/index.css';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import { numberMask } from '@/components/common/inputs/mask/number';
import { fractionalNumberMask } from '@/components/common/inputs/mask/fractionalNumber';
import { cadastralMask } from '@/components/common/inputs/mask/cadastralNumber';
import Address from '@/components/Address/Address.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import Year from '@/components/common/Year/Year.vue';
import { address } from '@/utils/global/filters';

type ListItem = {
  code: number;
  name: string;
};

type Props = {
  storage: any;
  isViewType?: boolean;
  changesData?: any;
};

@Component({
  name: 'authorities-card-request',
  components: {
    DefaultButton,
    InputComponent,
    Address,
    DialogComponent,
    SelectComponent,
    Year,
  },
  methods: { address },
})
export default class AuthoritiesCardRequest extends Vue {
  @Prop({
    type: Object,
    default: {},
  })
  readonly storage: Props['storage'] | undefined;
  @Prop({
    type: Boolean,
    default: false,
  })
  readonly isViewType: Props['isViewType'] | undefined;
  @Prop({
    type: Object,
    default: {},
  })
  readonly changesData: Props['changesData'] | undefined;

  services: ListItem[] = [];
  storageLocationsList: ListItem[] = [];
  editTable = false;
  locationStorage = null;
  isEdit = false;
  isShowcase = false;
  isValid = true;
  tempForm = {
    address: {},
    act_commissioning: {
      doc_num: '',
      doc_date: '',
    },
    own_rent_document: {
      doc_num: '',
      doc_date: '',
    },
    own_rent: {
      code: '',
    },
    year_last_reconstruction: '',
    year_overhaul: '',
    year_construction: '',
  };
  ownRentList = [];
  mask = numberMask;
  maskFractionalNumber = fractionalNumberMask;
  maskCadastral = cadastralMask;
  showModal = false;

  get rules() {
    return {
      year_construction: [
        this.tempForm.year_last_reconstruction && 'before_or_equal:year_last_reconstruction',
        this.tempForm.year_overhaul && 'before_or_equal:year_overhaul',
        'required',
      ],
      doc_num_rent: [!!this.tempForm.own_rent.code && 'required'],
      doc_date_rent: [!!this.tempForm.own_rent.code && 'required'],
      cadastral_number: ['required', { between: [14, 19], regex: '/^\\d{2}:\\d{2}:\\d{6,7}:\\d{1,5}$/' }],
      address: 'required',
      own_rent: 'required',
      granary_type: 'required',
      elevator_site_storage: 'required',
      capacity_tons: 'required',
      doc_num_act_commissioning: [!!this.tempForm.act_commissioning.doc_date && 'required'],
      doc_date_act_commissioning: [
        !!this.tempForm.act_commissioning.doc_num && 'required',
        !!this.tempForm.act_commissioning.doc_date &&
          !!this.tempForm.year_construction &&
          'after_or_equal:year_construction',
      ],
    };
  }

  get messages() {
    return {
      'before_or_equal.year_construction': 'Год постройки не может быть больше года реконструкции и кап. ремонта',
      'after_or_equal.doc_date_act_commissioning':
        'Значение параметра в поле "Акт ввода в эксплуатацию (дата)" не может быть меньше значения параметра в поле "Год постройки"',
      'between.cadastral_number': 'Кадастровый номер должен содержать от 11 до 16 символов',
      'regex.cadastral_number': 'Кадастровый номер не соответствует формату',
    };
  }

  get changesItemData() {
    if (this.changesData?.elevator_site_change?.length > 0) {
      return this.changesData.elevator_site_change[this.storage.index];
    }
    return null;
  }

  get currentDate() {
    return new Date();
  }

  async created() {
    this.fetchServices();
    this.fetchStorageLocationList();
    this.fetchUsageRights();
    if (this.storage) {
      this.tempForm = { ...this.tempForm, ...(this.storage.data || {}) };
    }

    await this.$store.dispatch('country/getListCountry');
  }

  clickClose() {
    this.$emit('click-close');
  }

  closeModal() {
    this.showModal = !this.showModal;
  }

  saveStorage() {
    this.$emit('submit', this.tempForm);
  }

  addFields(data) {
    this.tempForm.address = {
      ...data,
    };
    this.closeModal();
  }

  async fetchStorageLocationList() {
    const { content } = await this.$store.dispatch('elevator/getStorageMethodList', { actual: true });
    this.storageLocationsList = content;
  }

  async fetchUsageRights() {
    const { content } = await this.$store.dispatch('elevator/getListUsageRights');
    this.ownRentList = content;
  }

  async fetchServices() {
    const { content } = await this.$store.dispatch('elevator/getListGranaryType', { actual: true });
    this.services = content;
  }

  clearOwnRent() {
    if (!this.tempForm.own_rent) {
      this.tempForm.own_rent_document = {
        doc_num: '',
        doc_date: '',
      };
      this.tempForm.own_rent = {
        code: '',
      };
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.storage-locations {
  position: fixed;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  z-index: 100;
  background: $white-color;
  border: 1px solid $input-border-color;
  box-sizing: border-box;
  box-shadow: 0 16px 32px rgba($black-color, 0.1);
  border-radius: 4px;
  max-width: 800px;
  width: 100%;
  max-height: 800px;
  overflow-y: auto;
  padding: 20px;
}

.close {
  display: flex;
  justify-content: flex-end;
  cursor: pointer;
}

.overlay {
  background-color: rgba($black-color, 0.3);
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 8;
}

.container {
  overflow-y: auto;
}

.title {
  font-style: normal;
  font-weight: 500;
  font-size: 24px;
  line-height: 24px;
  color: $black-color;

  @include respond-to('medium') {
    font-size: 22px;
  }

  @include respond-to('small') {
    font-size: 18px;
  }
}

.baseInformation {
  margin-top: 25px;

  @include respond-to('medium') {
    margin-top: 15px;
  }

  @include respond-to('small') {
    margin-top: 10px;
  }
}

.label {
  color: $input-border-color;
  font-size: 16px;
  line-height: 16px;
  margin-bottom: 5px;
  display: block;
}

.block {
  padding-top: 36px;
}

.input {
  border: 1px solid $input-border-color;
  border-radius: 3px;
  background: $white-color;
  outline: none;
  height: 40px;
  color: $black-color;
  font-size: 14px;
  line-height: 16px;
  margin: 0;
  padding: 0 10px;
  z-index: 5;
  width: 100%;

  @include respond-to('medium') {
    height: 40px;
    font-size: 16px;
  }

  @include respond-to('small') {
    height: 40px;
    font-size: 14px;
  }

  &--disabled {
    background-color: $input-disable-background;
    color: $input-disabled-color;
  }

  &--small {
    flex: 1 1 150px;
    margin-right: 15px;
    max-width: 150px;
  }

  &--large {
    flex: 1 1 100%;
  }
}

.buttons {
  display: flex;
  margin-top: 20px;
  justify-content: flex-end;
}

.button {
  background-color: $white-color;
  color: $medium-grey-color;
  padding: 15px 35px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  border-radius: 4px;
  margin-left: 15px;
  border: 1px solid $input-border-color;
  cursor: pointer;
  outline: none;

  @include respond-to('medium') {
    padding: 9px 25px;
  }

  @include respond-to('small') {
    padding: 5px 20px;
  }

  &:hover {
    box-shadow: 0 0 5px rgb(0 0 0 / 50%);
  }

  &--primary {
    border-color: $button-primary-background;
    background-color: $button-primary-background;
    color: $white-color;
  }
}

.select {
  height: 40px;
  width: 320px;

  &--lg {
    width: 100%;
  }

  &--small {
    width: 100%;
  }
}

.pop-up {
  background: $white-color;
  border: 1px solid $light-grey-color;
  border-radius: 5px;
  display: none;
  padding: 5px 15px;
  position: absolute;
  left: 0;
  top: -44px;
  z-index: 15;
}

.inputContainer {
  position: relative;
  padding-top: -2px;
}

.inputContainer:hover {
  .pop-up {
    display: block;
  }
}
</style>
