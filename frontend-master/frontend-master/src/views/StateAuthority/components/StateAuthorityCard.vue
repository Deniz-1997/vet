<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <div class="title">
          <span> Карточка Органа государственной власти </span>
        </div>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <InputComponent
          id="stateAuthorityName"
          v-model="form.subject.subject_data.name"
          placeholder="Введите текст"
          label="Наименование"
          :disabled="isShow || editCard"
        />
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <InputComponent
          id="shotStateAuthorityName"
          v-model="form.subject.subject_data.short_name"
          placeholder="Введите текст"
          label="Краткое наименование"
          :disabled="isShow || editCard"
        />
      </v-col>

      <v-col cols="12">
        <autocomplete-component
          v-model="form.subject.subject_data.opf"
          return-object
          :items="opfList"
          label="Организационно-правовая форма"
          item-value="code"
          item-text="name"
          :is-disabled="isShow || editCard"
          @searchInputUpdate="searchOpf"
        />
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <div class="containerTabs">ОБЩИЕ СВЕДЕНИЯ</div>
      </v-col>
    </v-row>

    <div class="generalInformation">
      <v-row>
        <v-col cols="12" md="4">
          <InputComponent
            id="inn"
            key="inn"
            v-model="form.subject.subject_data.inn"
            placeholder="Введите текст"
            :mask="'##########'"
            label="ИНН"
            :disabled="isShow || editCard"
          />
        </v-col>
        <v-col cols="12" md="4">
          <InputComponent
            id="kpp"
            key="kpp"
            v-model="form.subject.subject_data.kpp"
            placeholder="Введите текст"
            label="КПП"
            mask="#########"
            :disabled="isShow || editCard"
          />
        </v-col>

        <v-col cols="12" md="4">
          <InputComponent
            id="ogrn"
            key="ogrn"
            v-model="form.subject.subject_data.ogrn"
            placeholder="Введите текст"
            label="ОГРН"
            :mask="'#############'"
            :disabled="isShow || editCard"
          />
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12">
          <InputComponent
            v-if="form.subject.address.address"
            id="address"
            :value="address(form.subject.address)"
            label="Адрес"
            disabled
            placeholder="Введите текст"
          />
        </v-col>

        <v-col v-if="form.subject.address.additional_info" cols="12">
          <InputComponent
            id="additional_info"
            v-model="form.subject.address.additional_info"
            disabled
            label="Дополнительный адрес"
            placeholder="Введите текст"
          />
        </v-col>

        <v-col cols="12">
          <DefaultButton
            v-if="!isShow && !editCard"
            variant="primary"
            :title="address(form.subject.address) ? 'Изменить адрес' : 'Указать адрес'"
            @click="onOpenModal = true"
          />
        </v-col>
      </v-row>
    </div>
    <v-row v-if="isShow || isEdit">
      <v-col cols="12">
        <div class="containerTabs">Организационная структура</div>
      </v-col>
    </v-row>

    <div v-if="isShow || isEdit" class="generalInformation">
      <v-row>
        <v-col cols="12">
          <EditableTable
            v-model="form.divisions"
            :options="headersStateAuthority"
            :max="999"
            :is-showcase="isShow"
            :is-can-edit="!isShow"
            is-delete-row
            :is-custom-create="true"
            :is-edit-action="!isShow"
            :is-not-add-new-field="true"
            :is-show-card-button="true"
            edit-strategy="outer"
            @customCreate="showModalDialod"
            @edit="editDivisionRow"
            @deleteItem="deleteRow"
          />
        </v-col>
      </v-row>
    </div>
    <v-row justify="end">
      <v-col cols="12" class="col-exclude">
        <DefaultButton
          v-if="isShow"
          class="button button--default"
          title="Закрыть"
          @click="
            () => {
              closeModalDivisionStaff = false;
              $router.push('/stateAuthority');
            }
          "
        />
        <DefaultButton
          v-if="!isShow"
          class="button button--default"
          title="Отмена"
          @click="$router.push('/stateAuthority')"
        />
        <DefaultButton v-if="!isShow" class="buttons" variant="primary" title="Сохранить" @click="saveStateAuthority" />
      </v-col>
    </v-row>
    <Dialog-component
      v-model="isShowModal"
      :prompt="false"
      cancel-title=""
      confirm-title=""
      width="800"
      with-close-icon
      controls-justify="justify-end"
    >
      <template #title> Подразделение </template>
      <template #content>
        <v-row>
          <v-col cols="12">
            <label-component label="Вышестоящее подразделение" />
            <SelectComponent
              v-model="divisionsForm.root_division"
              return-object
              :items="divisionList"
              item-value="code"
              item-text="name"
              :is-disabled="divisionList.length === 0 || isShow || !isEditRow"
            />
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12">
            <label-component label="Наименование подразделения или должности" />
            <InputComponent id="name" v-model="divisionsForm.name" placeholder="Введите текст" :disabled="isShow" />
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12">
            <EditableTable
              v-model="divisionsForm.division_staff"
              title="Сотрудники"
              :is-showcase="isShow"
              :is-edit-action="!isShow"
              :is-can-edit="!isShow"
              :options="headersStaff"
              :max="999"
            />
          </v-col>
        </v-row>
        <v-col cols="12" class="col-exclude">
          <DefaultButton :title="isShow ? 'Закрыть' : 'Отмена'" @click="closeModalDivisionStaff" />
          <DefaultButton v-if="!isShow" variant="primary" title="Сохранить" @click="saveDivision" />
        </v-col>
      </template>
    </Dialog-component>

    <Dialog-component
      v-model="onOpenModal"
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
          v-model="form.subject.address"
          :subject-type="form.subject.subject_type"
          @close="closeModal"
          @saveAction="addFields"
        />
      </template>
    </Dialog-component>
    <v-overlay :value="isLoading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>
<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import mergeWith from 'lodash/mergeWith';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import Address from '@/components/Address/Address.vue';
import EditableTable from '@/components/common/Table/index.vue';
import { address } from '@/utils/global/filters';

type StaffItem = {
  value: number;
  label: string;
};

@Component({
  name: 'stateAuthority-card',
  components: {
    DataTable,
    DefaultButton,
    InputComponent,
    LabelComponent,
    SelectComponent,
    DialogComponent,
    AutocompleteComponent,
    Address,
    EditableTable,
  },
})
export default class StateAuthorityCard extends Vue {
  address = address;
  form: any = {
    divisions: [],
    subject: {
      subject_type: 'UL',
      subject_data: {},
      address: {
        address: '',
        additional_info: '',
        aoguid: '',
        div_type: '',
        house_guid: '',
        postcode: '',
      },
    },
  };

  divisionsForm: any = {
    division_staff: [],
    root_division: null,
  };
  isLoading = false;
  staffList: StaffItem[] = [];
  editCard = false;
  isEdit = false;
  isShow = false;
  subject_id: number | null = null;
  isEditRow = false;
  divisionList: any = [];
  opfList: any = [];
  isShowModal = false;
  tab = 'general';
  onOpenModal = false;
  index: number | string = 'undefined';
  isError = false;
  innMask = '############';
  isShowcase = false;
  users: any = [];
  stafflist: any = [];
  text =
    'При удалении подразделения будут удалены все нижестоящие объекты оргструктуры.\n Вы действительно хотите удалить подразделение?';

  get headersStaff() {
    return [
      {
        label: 'ФИО',
        name: 'staff',
        controlType: 'autoComplete',
        itemValue: 'value',
        itemText: 'label',
        restrictions: this.staffList,
        exclude: true,
        width: 250,
      },
      {
        label: 'Дата назначения',
        name: 'start_date',
        controlType: 'date',
        limitTo: this.$moment().add(1, 'd').toDate(),
        width: 250,
      },
      {
        label: 'Дата снятия',
        name: 'end_date',
        controlType: 'date',
        width: 250,
      },
    ];
  }

  get headersStateAuthority() {
    return [
      {
        label: 'Вышестоящее подразделение',
        name: 'root_division_name',
        width: 250,
      },
      {
        label: 'Наименование подразделения или должности',
        name: 'name',
        width: 250,
      },
      {
        label: 'Сотрудники',
        name: 'division_staff_user_full_names',
        width: 250,
      },
    ];
  }

  async mounted() {
    this.isShow = this.$route.params.type === 'show';
    this.isEdit = this.$route.params.type === 'edit';
    if (this.$route.params.id) {
      if (this.isEdit) {
        this.editCard = true;
      }
      await this.getCardInfoById();
      await this.getOpfList();
    }
  }

  async getCardInfoById() {
    this.isLoading = true;
    this.staffList = [];
    if (this.$route.params.id) {
      const data = await this.$store.dispatch('stateAuthority/getStateAuthorityById', this.$route.params.id);
      this.form = mergeWith(this.form, data, (to, from) => {
        if (from === null) {
          return to;
        }
      });
      this.subject_id = this.form.subject.subject_id;
    }
    if (this.subject_id) {
      await Promise.all([this.getDivisionBySubjectId(), this.getStaff()]);
    }
    this.isLoading = false;
  }

  async getDivisionBySubjectId() {
    this.isLoading = true;
    this.divisionList = [];
    const content = await this.$store.dispatch('stateAuthority/getHierarchyDivisions', { subject_id: this.subject_id });
    if (content) {
      this.form.divisions = content;
      this.isLoading = false;
    }

    if (this.form.divisions) {
      this.form.divisions.map((item) => {
        const rootDivision = {
          code: item.subject_division_id,
          name: item.name,
        };
        this.divisionList.push(rootDivision);
      });
    }
  }

  async getStaff() {
    this.isLoading = true;
    this.staffList = [];
    if (!this.isShow) {
      const staffList: any = await this.$store.dispatch('staff/getStaffDivision', { subject_id: this.subject_id });
      staffList.forEach((item) => {
        const user = {
          label: item.full_name,
          value: item.user_id,
        };
        this.staffList.push(user);
      });
    }
    this.isLoading = false;
  }

  async searchOpf(value) {
    const itemIndex = this.opfList.findIndex((item: any) => item.code === value);
    if (itemIndex === -1) {
      const { content } = await this.$store.dispatch('sdiz/getOPF');
      this.opfList = content;
    }
  }

  async getOpfList() {
    this.isLoading = true;
    const { content } = await this.$store.dispatch('sdiz/getOPF');
    this.opfList = content;
    this.isLoading = false;
  }

  addFields(data) {
    this.form.subject.address.country = data.country;
    this.form.subject.address.address = data.address;
    this.form.subject.address.additional_info = data.additional_info;
    this.form.subject.address.aoguid = data.aoguid;
    this.form.subject.address.house_guid = data.houseguid;
    this.form.subject.address.postcode = data.postalcode;
    this.form.subject.address.div_type = data.div_type;
    this.closeModal();
  }

  reportError(text: string) {
    this.$store.commit('errors/clearErrorList');
    this.$store.commit('errors/setErrorsList', text);
    this.isError = true;
    return false;
  }

  checkDivisionIsValid() {
    const { division_staff = [], name } = this.divisionsForm || {};
    let { isValid, staff } = division_staff.reduce(
      (result, item) => {
        const { staff, start_date, end_date } = item;
        const isDateRangeValid = this.$moment(start_date, 'DD.MM.YYYY').isBefore(this.$moment(end_date, 'DD.MM.YYYY'));

        if (!staff) {
          return { ...result, isValid: this.reportError('Поле "ФИО" не заполнено') };
        }

        if (!start_date) {
          return { ...result, isValid: this.reportError('Поле "Дата назначения" не заполнено') };
        }

        if (start_date && end_date && !isDateRangeValid) {
          return { ...result, isValid: this.reportError('"Дата снятия" не может быть меньше "Даты назначения"') };
        }

        result.staff.add(staff.value);
        return result;
      },
      { isValid: true, staff: new Set() }
    );

    if (isValid && staff.size !== division_staff.length) {
      isValid = this.reportError('Один пользователь не может быть добавлен несколько раз');
    }

    if (!name) {
      isValid = this.reportError('Поле "Наименование подразделения или должности" не заполнено');
    }

    return isValid;
  }

  //сохранение подразделения
  async saveDivision() {
    try {
      if (this.checkDivisionIsValid()) {
        this.isLoading = true;

        if (this.index === 'undefined') {
          await this.addDivision();
        } else {
          await this.editDivision();
        }

        this.index = 'undefined';
        this.divisionsForm = {};
        this.isShowModal = !this.isShowModal;
        this.users = [];
        this.isEditRow = false;
        this.getDivisionBySubjectId();
        this.closeModalDivisionStaff();
        this.isLoading = false;
      }
    } catch (err) {
      this.isLoading = false;
      throw err;
    }
  }

  // добавление подразделения
  async addDivision() {
    if (this.divisionsForm.division_staff && this.divisionsForm.division_staff.length > 0) {
      this.divisionsForm.division_staff.map((item) => {
        this.users.push({
          user_id: item.staff.value,
          start_date: item.start_date,
          end_date: item.end_date,
        });
      });
    }
    let division = {
      subject_id: this.subject_id,
      parent_division_id: this.divisionsForm.root_division ? this.divisionsForm.root_division.code : null,
      name: this.divisionsForm.name,
      type: 'D',
      division_staff: this.users,
    };
    await this.$store.dispatch('stateAuthority/createDivisionId', division);
  }

  //редактирование подразделения
  async editDivision() {
    if (this.divisionsForm.division_staff.length > 0) {
      this.divisionsForm.division_staff.map((item) => {
        this.users.push({
          subject_division_staff_id: item.subject_division_staff_id,
          user_id: item.staff.value,
          start_date: item.start_date,
          end_date: item.end_date,
        });
      });
    }
    let division = {
      subject_id: this.subject_id,
      subject_division_id: this.divisionsForm.subject_division_id,
      name: this.divisionsForm.name,
      type: 'D',
      division_staff: this.users,
    };
    await this.$store.dispatch('stateAuthority/editDivision', division);
  }

  async deleteRow(value) {
    this.isLoading = true;
    let id = null;
    value.map((item) => {
      id = item.subject_division_id;
    });
    await this.$store.dispatch('stateAuthority/deleteDivision', { id });
    this.getDivisionBySubjectId();
  }

  async editDivisionRow(index: number) {
    this.showModalDialod();
    this.isShowModal = true;
    if (index !== undefined) {
      this.divisionsForm = this.form.divisions.find((item, i) => {
        if (i === index) {
          this.isEditRow = false;
          this.index = index;
          return item;
        }
      });
    }
  }

  clickTab(tab: string) {
    this.tab = tab;
  }

  showModalDialod() {
    this.isEditRow = true;
    this.isShowModal = true;
  }

  closeModalDivisionStaff() {
    this.divisionList = [];
    this.divisionsForm = {};
    this.getDivisionBySubjectId();
    this.isShowModal = false;
    this.isEditRow = true;
  }

  closeModal() {
    this.divisionList = [];
    this.divisionsForm = {};
    this.onOpenModal = false;
  }

  async saveStateAuthority() {
    if (!this.$route.params.id) {
      const res = await this.$store.dispatch('stateAuthority/createStateAuthority', this.form);
      this.subject_id = res.subject_id;
    } else {
      await this.$store.dispatch('stateAuthority/updateStateAuthority', this.form);
    }
    this.editCard = false;
    this.$router.push('/stateAuthority');
  }
}
</script>
<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.generalInformation {
  margin-top: 16px;
}

.containerTabs {
  margin-top: 16px;
  font-weight: bold;
  border-bottom: 1px solid $light-grey-color;
}

.tabs {
  border-bottom: 1px solid $light-grey-color;
  width: 100%;
  display: flex;
  flex-direction: row;
  text-transform: uppercase;
}

.tab {
  display: flex;
  font-weight: bold;
  font-size: 13px;
  color: $footer-color;
  cursor: pointer;
  padding-bottom: 8px;
  margin-right: 18px;

  &.active {
    color: $gold-light-color;
    border-bottom: 1px solid $gold-light-color;
  }
}

.buttons {
  margin-left: 20px;
}

.button {
  background-color: $white-color;
  color: $medium-grey-color;
  padding: 15px 35px;
  justify-content: center;
  text-decoration: none;
  display: flex;
  font-size: 16px;
  align-items: center;
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
    box-shadow: 1px solid $input-border-color;
  }

  &--primary {
    border-color: $button-primary-background;
    background-color: $button-primary-background;
    color: $white-color;
  }
}
</style>
