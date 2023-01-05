<template>
  <div>
    <span class="title">Роль </span>
    <v-row>
      <v-col cols="12" md="6">
        <label-component label="Наименование" />
        <InputComponent id="lastName" v-model="form.name" placeholder="Введите текст" :disabled="isShow" />
      </v-col>
      <v-col cols="12" md="6">
        <label-component label="Дата удаления" />
        <UiDateInput v-model="form.deleted_date" placeholder="Выберите дату удаления" :disabled="isEdit" />
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="6">
        <label-component label="Описание" />
        <InputComponent id="login" v-model="form.login" placeholder="Введите текст" :disabled="isShow" />
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <span class="title">Управление привилегиями роли </span>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <div class="inputContainer">
          <SelectComponent
            v-model="form.subject"
            return-object
            placeholder="Группа привилегий"
            :items="subjectList"
            item-value="code"
            item-text="name"
            :is-disabled="isShow"
          />
        </div>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="5">
        <label-component label="Доступные привелегии" />
        <InputComponent
          placeholder="Контекстный поиск по доступны..."
          :disabled="isEdit"
          @change="handleSearchAvailablePrivileges"
        />
        <DataTable
          :headers="headers"
          :items="rows.filter((item) => item.name.toLowerCase().includes(searchAvailablePrivileges.toLowerCase()))"
          :search="searchAvailablePrivileges"
          hide-footer
        >
          <template #[`item.check`]="{ item }">
            <label class="checkbox">
              <input v-model="item.check" type="checkbox" :checked="item.check" />
              <span class="checkbox__icon">
                <img src="/icons/checkbox/.svg" />
              </span>
            </label>
          </template>
        </DataTable>
      </v-col>
      <v-col cols="12" md="1" class="arrow">
        <span class="arrow__icon" @click="changeSnils">
          <img src="/icons/left-arrow.svg" />
        </span>
        <span class="arrow__icon" @click="changeSnils">
          <img src="/icons/right-arrow.svg" />
        </span>
      </v-col>

      <v-col cols="12" md="5">
        <label-component label="Назначенные привилегии" />
        <InputComponent
          placeholder="Контекстный поиск по назначенным..."
          :maxlength="12"
          :disabled="isEdit"
          @change="handleSearchAssignedPrivileges"
        />
        <DataTable
          :headers="headers"
          :items="rows1.filter((item) => item.name.toLowerCase().includes(searchAssignedPrivileges.toLowerCase()))"
          :search="searchAssignedPrivileges"
          hide-footer
        >
          <template #[`item.check`]="{ item }">
            <label class="checkbox">
              <input v-model="item.check" type="checkbox" :checked="item.check" />
              <span class="checkbox__icon">
                <img src="/icons/checkbox.svg" />
              </span>
            </label>
          </template>
        </DataTable>
      </v-col>
    </v-row>

    <v-row justify="end">
      <v-col cols="12" class="col-exclude">
        <DefaultButton title="Отмена" @click="$emit('close')"> </DefaultButton>
        <DefaultButton
          v-if="!isShow"
          variant="primary"
          title="Сохранить"
          :disabled="isLoading"
          @click="saveOrganization"
        >
        </DefaultButton>
      </v-col>
    </v-row>
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
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import EditableTable from '@/components/common/Table/index.vue';
import DataTable from '@/components/common/DataTable/DataTable.vue';

const mapForm = (data) => ({
  status: 1,
  ...data,
});

const mapInnerForm = (data) => ({
  ...data,
});

type Props = {
  idCard: number;
  isShow: boolean;
};

@Component({
  name: 'user-roles-card',
  components: {
    RadioGroupComponent,
    InputComponent,
    DefaultButton,
    SelectComponent,
    LabelComponent,
    DialogComponent,
    AutocompleteComponent,
    EditableTable,
    DataTable,
  },
})
export default class UserRolesCard extends Vue {
  @Prop({
    type: [Number],
    default: () => ({}),
  })
  readonly idCard: Props['idCard'] | undefined;
  @Prop({
    type: [Boolean],
    default: () => false,
  })
  readonly isShow: Props['isShow'] | undefined;
  form = {};
  subjectList: any = [];
  isShowModal = false;
  isLoading = false;
  isEdit = false;
  isShowcase = false;
  showCard = false;
  searchAvailablePrivileges = '';
  searchAssignedPrivileges = '';
  rows: any = [
    { id: 1, name: 'создать роль' },
    { id: 2, name: 'назначить роль' },
  ];
  rows1: any = [
    { id: 1, name: 'создать роль' },
    { id: 2, name: 'назначить роль' },
  ];
  headers = [
    {
      text: '',
      value: 'check',
    },
    {
      text: 'Привилегия',
      value: 'name',
    },
  ];

  mounted() {
    this.fetchListOrganizationApproval();
    if (this.idCard) {
      this.isEdit = true;
      this.getCardInfoById();
    }
  }

  async getCardInfoById() {
    const data: any = await this.$store.dispatch('userRoles/showInfoRole', this.idCard);
    this.form = mapInnerForm(data);
  }

  async fetchListOrganizationApproval() {
    const { content } = await this.$store.dispatch('templateApproval/getListApprovalOrganization');

    this.subjectList = content;
  }

  changeSnils() {
    // console.log('snils');
  }

  async saveOrganization() {
    this.isLoading = true;
    if (!this.idCard) {
      await this.$store.dispatch('staff/createStaff', mapForm(this.form));
    } else {
      await this.$store.dispatch('staff/updateStaff', mapForm(this.form));
    }
    this.isLoading = false;
    this.$emit('close');
  }

  async handleSearchAvailablePrivileges(value: string) {
    this.searchAvailablePrivileges = value;
  }

  async handleSearchAssignedPrivileges(value: string) {
    this.searchAssignedPrivileges = value;
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';

.name-orgnz {
  display: flex;
  flex-wrap: nowrap;
}

.flex-end {
  display: flex;
  margin-top: 10px;
  justify-content: flex-end;
}

.settingsSpan {
  padding-left: 16px;
  cursor: pointer;
}

.title {
  font-size: 16px;
  text-align: center;
  font-weight: 600;
  color: $black-color;
}

.arrow {
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.arrow__icon {
  display: flex;
  flex-direction: column;
  width: 20px;
  height: 20px;
  color: $medium-grey-color !important;
  border: 1px solid $light-grey-color;
  border-radius: 4px;
  padding: 2px;
  margin-top: 8px;
}
</style>
