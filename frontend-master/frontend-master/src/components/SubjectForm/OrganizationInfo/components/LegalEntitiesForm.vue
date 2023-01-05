<template>
  <div>
    <v-row>
      <v-col cols="12">
        <UiControl name="name" :value="form.name">
          <InputComponent
            id="organizationName"
            v-model="form.name"
            placeholder="Введите текст"
            label="Наименование"
            :disabled="isEsia"
            required
          />
        </UiControl>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <UiControl name="shortName" :value="form.shortName">
          <InputComponent
            id="shotOrganizationName"
            v-model="form.shortName"
            placeholder="Введите текст"
            label="Краткое наименование"
            required
            :disabled="isEsia"
          />
        </UiControl>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <UiControl name="opf" :value="form.opf">
          <autocomplete-component
            v-model="form.opf"
            return-object
            :items="opfList"
            label="Организационно-правовая форма"
            item-value="name"
            item-text="name"
            multiple
            :disabled="isEsia"
            required
            @searchInputUpdate="searchOpf"
          />
        </UiControl>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="4">
        <UiControl name="inn" :value="form.inn">
          <InputComponent
            id="inn"
            key="inn"
            v-model="form.inn"
            placeholder="Введите текст"
            mask="##########"
            label="ИНН"
            :disabled="isEsia || isDisabled"
            required
          />
        </UiControl>
      </v-col>
      <v-col cols="12" md="4">
        <UiControl name="kpp" :value="form.kpp">
          <InputComponent
            id="kpp"
            key="kpp"
            v-model="form.kpp"
            placeholder="Введите текст"
            mask="#########"
            label="КПП"
            :disabled="isEsia || isDisabled"
            required
          />
        </UiControl>
      </v-col>

      <v-col cols="12" md="4">
        <UiControl name="ogrn" :value="form.ogrn">
          <InputComponent
            id="ogrn"
            key="ogrn"
            v-model="form.ogrn"
            placeholder="Введите текст"
            label="ОГРН"
            mask="#############"
            :disabled="isEsia || isDisabled"
            required
          />
        </UiControl>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="6">
        <UiControl name="phoneNumber" :value="form.phoneNumber">
          <InputComponent
            id="phone_number"
            v-model="form.phoneNumber"
            placeholder="Введите номер"
            label="Контактный номер телефона"
            type="tel"
            :disabled="isEsia"
          />
        </UiControl>
      </v-col>
      <v-col cols="12" md="6">
        <UiControl name="email" :value="form.email">
          <InputComponent
            id="email"
            v-model="form.email"
            placeholder="Введите email"
            label="Адрес электронной почты"
            type="email"
            :disabled="isEsia"
          />
        </UiControl>
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Watch, Mixins } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import AddressNewComponent from '@/components/AddressNewComponent/AddressNewComponent';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import { addressNew } from '@/utils/global/filters';
import { IDictionaryNode } from '@/services/models/common';
import Form from '@/utils/global/mixins/form';

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
export default class LegalEntitiesForm extends Mixins(Form) {
  @Prop({ type: Boolean, default: false }) readonly isLoading?: boolean;
  @Prop({ type: Boolean, default: false }) public isDisabled!: boolean;
  opfList: IDictionaryNode[] = [];
  showModal = false;

  get isEsia() {
    if (this.form.created_by) {
      return this.form.created_by === 'ESIA';
    }
    return false;
  }

  get rules() {
    return {
      name: 'required',
      shortName: 'required',
      inn: ['required', { size: 10 }],
      kpp: ['required', { size: 9 }],
      ogrn: ['required', { size: 13 }],
      opf: !this.isEsia ? 'required' : false,
      email: 'email',
    };
  }

  created() {
    this.$emit('update-rules', this.rules);
  }

  changeShowModal(): boolean {
    return (this.showModal = true);
  }

  mounted() {
    this.getOpfList();
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

  @Watch('rules')
  updateRules() {
    this.$emit('update-rules', this.rules);
  }
}
</script>

<style lang="scss" scoped>
.row {
  padding-bottom: 0;
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
