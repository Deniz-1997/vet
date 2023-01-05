<template>
  <div class="nsi-card container">
    <v-row>
      <v-col cols="12">
        <div class="title">{{ actionTitle }}</div>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="6">
        <Input-component
          v-model="form.name"
          :disabled="!editMode && !createMode"
          required
          label="Наименование"
          placeholder="Введите наименование"
        />
      </v-col>

      <v-col v-if="fields.includes('measureName')" cols="12" md="6">
        <autocomplete-component
          v-model="form.unitOfMeasure"
          return-object
          :items="additionalData"
          item-value="id"
          item-text="name"
          label="Код единицы измерения"
          required
          :is-disabled="!editMode && !createMode"
        />
      </v-col>

      <v-col cols="6" md="3">
        <UiDateInput
          v-model="form.startDate"
          class="datePicker"
          :disabled="!editMode && !createMode"
          :format="'DD.MM.YYYY'"
          label="Действует с"
        />
      </v-col>

      <v-col cols="6" md="3">
        <UiDateInput
          v-model="form.endDate"
          class="datePicker"
          :disabled="!editMode && !createMode"
          label="Действует по"
          :format="'DD.MM.YYYY'"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <div class="title">{{ dataTitle }}</div>
      </v-col>
      <v-col v-if="(editMode === true || createMode === true) && NsiApi !== '/api/nci/qualityIndicators'" cols="12">
        <div class="add-note" @click="showModal = true">
          <img src="/icons/add.svg" alt="" />
          Добавить запись
        </div>
      </v-col>
    </v-row>

    <v-row justify="end">
      <DefaultButton title="Закрыть" @click="goBack" />
      <DefaultButton
        v-if="editMode || createMode"
        variant="primary"
        title="Сохранить"
        :disabled="isLoading"
        @click="saveAction"
      />
    </v-row>

    <Dialog-component
      v-model="showModal"
      :prompt="false"
      cancel-title=""
      confirm-title=""
      width="458px"
      with-close-icon
      controls-justify="justify-end"
    >
      <template #title>Добавить запись</template>
      <template #content>
        <ActionsNsiCard
          v-if="showModal"
          :okpd-api-url="okpdApiUrl"
          :tnved-api-url="tnvedApiUrl"
          :grain-url="grainUrl"
          :fields="fieldsModal"
          @close="closeModal"
          @save="addRow"
        />
      </template>
    </Dialog-component>

    <Dialog-component
      v-model="dublicatePopup"
      :prompt="false"
      cancel-title="Закрыть"
      confirm-title=""
      width="458px"
      with-close-icon
      controls-justify="justify-end"
    >
      <template #title> Ошибка добавления </template>
      <template #content> Такая запись уже существует </template>
    </Dialog-component>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import uniq from 'lodash/uniq';
import DataTable from '@/components/common/DataTable/DataTable.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import nsiList from '@/views/NSI/config';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import ActionsNsiCard from '@/views/NSI/components/Modal/ActionsNsiCard.vue';
import { numberMask } from '@/components/common/inputs/mask/number';
import { TableHeaders } from '@/components/common/DataTable/DataTable.types';
import { TForm, TTableItem } from './NsiCard.types';

@Component({
  name: 'nsi-card',
  components: {
    DataTable,
    InputComponent,
    LabelComponent,
    DefaultButton,
    SelectComponent,
    ActionsNsiCard,
    DialogComponent,
    AutocompleteComponent,
  },
  metaInfo(this: NsiCard) {
    return { title: this.NsiTitle };
  },
})
export default class NsiCard extends Vue {
  showModal = false;
  editMode = false;
  createMode = false;
  dublicatePopup = false;
  isLoading = false;

  NsiApi = '';
  dataTitle = '';
  activeNsi = '';
  itemNsi = '';
  NsiTitle = '';
  additionalData = [];
  additionalApiUrl = '';
  okpdApiUrl = '';
  tnvedApiUrl = '';
  grainUrl = '';
  additionalApiUrlSecond = '';

  headers: TableHeaders[] = [];
  form: TForm = {} as TForm;
  productAndOkpd2: TTableItem[] = [];
  limits: TTableItem[] = [];
  okpd2_array: TTableItem[] = [];
  tableCardUrl = [];
  fields: string[] = [];
  fieldsModal: string[] = [];
  mask = numberMask;
  rulesRange = [
    () => {
      if (this.form['valueFrom'] !== undefined) {
        return (
          +this.form['valueFrom'] < +this.form['valueTo'] ||
          +this.form['valueFrom'] == +this.form['valueTo'] ||
          "значение 'С' не должно превышать значение 'ПО'"
        );
      }
    },
  ];

  @Prop(Object) readonly item!: any;

  get purposesList() {
    return this.form?.purposes;
  }

  get actionTitle() {
    if (!this.editMode && !this.createMode) {
      return this.NsiTitle;
    }
    return this.editMode ? 'Создать новую версию' : 'Добавить запись';
  }

  get isInvalid() {
    return (
      this.NsiApi === '/api/nci/qualityIndicators' &&
      !(+this.form['valueFrom'] < +this.form['valueTo'] || +this.form['valueFrom'] === +this.form['valueTo']) &&
      this.form['valueFrom'] &&
      this.form['valueTo']
    );
  }

  mounted() {
    this.activeNsi = this.$route.params.mask;
    this.itemNsi = this.$route.params.id;
    this.NsiApi = nsiList[this.activeNsi].apiUrl;
    this.NsiTitle = nsiList[this.activeNsi].title;
    this.additionalApiUrl = nsiList[this.activeNsi].additionalApiUrl;
    this.okpdApiUrl = nsiList[this.activeNsi].okpdApiUrl;
    this.tnvedApiUrl = nsiList[this.activeNsi].tnvedApiUrl;
    this.grainUrl = 'api/nci/okpd2';
    this.additionalApiUrlSecond = nsiList[this.activeNsi].additionalApiUrlSecond;
    this.tableCardUrl = nsiList[this.activeNsi].tableCardUrl;
    this.fields = nsiList[this.activeNsi].card;
    this.fieldsModal = nsiList[this.activeNsi].cardModal;
    this.headers = nsiList[this.activeNsi].extraHeaders?.common ?? nsiList[this.activeNsi].extraHeaders;
    this.dataTitle = nsiList[this.activeNsi].tableTitle;

    if (this.$route.fullPath.includes('edit')) {
      this.editMode = true;
    }

    if (this.$route.fullPath.includes('create')) {
      this.createMode = true;
    }

    if (this.createMode === false) {
      this.getItem(this.itemNsi);
    }

    if (this.additionalApiUrl) {
      this.getNsiList();
    }
  }

  goBack() {
    this.$router.go(-1);
  }

  async getItem(id: string) {
    this.form = await this.$store.dispatch('nsi/getItem', {
      url: this.NsiApi,
      data: { id },
    });
    this.limits = this.form.limits.map((item) => ({
      ...item,
      purposes: item.purpose ? item.purpose.name : '-',
    }));
    const types = uniq(this.limits.map(({ type }: any) => type)).reduce((result, type) => {
      const columns = nsiList[this.activeNsi].extraHeaders[type] || nsiList[this.activeNsi].extraHeaders.default;
      return [...result, ...columns];
    }, []);
    this.headers = [...this.headers, ...types];
  }

  async getNsiList() {
    const { content } = await this.$store.dispatch('nsi/getList', {
      url: this.additionalApiUrl,
      params: { actual: true },
    });
    this.additionalData = content;
  }

  closeModal() {
    this.showModal = false;
  }

  // eslint-disable-next-line max-lines-per-function
  async saveAction() {
    if (this.isInvalid) {
      this.$store.commit('errors/clearErrorList');
      this.$store.commit('errors/setErrorsList', 'Ошибка ввода диапазона значений');
      return;
    }

    this.form.okpd2_array = this.okpd2_array;
    this.form.limits = this.limits;

    if (this.NsiApi === '/api/nci/qualityIndicators') {
      this.form['valueFrom'] = +this.form['valueFrom'];
      this.form['valueTo'] = +this.form['valueTo'];
    }

    this.isLoading = true;
    if (this.editMode === true) {
      try {
        await this.$store.dispatch('nsi/update', {
          url: this.NsiApi,
          data: this.form,
        });
        this.isLoading = false;
        this.goBack();
      } catch (e) {
        this.isLoading = false;
      }
    } else if (this.createMode === true) {
      try {
        await this.$store.dispatch('nsi/create', {
          url: this.NsiApi,
          data: this.form,
        });
        this.isLoading = false;
        this.goBack();
      } catch (e) {
        this.isLoading = false;
      }
    }
  }

  addRow(data: any) {
    this.limits.push(data);
  }

  deleteRow(id: number) {
    if (this.limits && this.limits.length !== 0) {
      this.limits.splice(id, 1);
    }

    if (this.okpd2_array && this.okpd2_array.length !== 0) {
      this.okpd2_array.splice(id, 1);
    }
  }

  canDeleteItem(): boolean {
    return this.editMode || this.createMode;
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.add-note {
  align-items: center;
  display: flex;
  color: $medium-grey-color;
  cursor: pointer;
  text-decoration-line: underline;
  max-width: 150px;

  img {
    margin-right: 6px;
  }
}

.tnved-item {
  cursor: pointer;
  position: relative;

  &_popup {
    background: $white-color;
    border: 1px solid $light-grey-color;
    display: none;
    position: absolute;
    top: -40px;
    left: 0;
    padding: 5px;
    z-index: 10;
    min-width: 100px;
  }
}

.tnved-item:hover {
  .tnved-item_popup {
    display: block;
  }
}

.checkbox-block {
  align-items: center;
  // height: 61px;
  display: flex;
  margin-bottom: 28px;

  .label {
    margin-bottom: 0;
    margin-left: 5px;
  }
}

.checkbox {
  cursor: pointer;
  width: 16px;
  height: 16px;
  position: relative;

  [type='checkbox'] {
    position: absolute;
    opacity: 0;
  }

  &__icon {
    align-items: center;
    justify-content: center;
    background: $check-bg;
    display: flex;
    height: 16px;
    width: 16px;
    border: 1px solid $input-border-color;
    border-radius: 4px;

    img {
      width: 9px;
      display: block;
      opacity: 0;
    }
  }

  [type='checkbox']:checked {
    & + .checkbox__icon {
      background: $gold-light-color;
      border-color: $gold-light-color;

      img {
        opacity: 1;
      }
    }
  }
}

.label {
  color: $medium-grey-color;
  font-size: 13px;
  line-height: 16px;
  margin-bottom: 5px;
  display: block;

  &--strong {
    color: $black-color;
    font-weight: 700;
  }
}
</style>
