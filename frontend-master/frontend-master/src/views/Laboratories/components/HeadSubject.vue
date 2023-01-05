<template>
  <div>
    <v-row>
      <v-col cols="12">
        <span class="head-subject">
          <label-component label="Головная организация" />
          <span v-if="!isShow" class="head-subject__btn" @click="isOpenModal = true">
            <img src="/icons/add.svg" class="head-subject-btn__icon" />
            <span v-if="form.headSubject && form.headSubject.subject_id">Изменить организацию</span>
            <span v-else>Добавить организацию</span>
          </span>
        </span>
        <template v-if="isHeadSubject">
          <div class="field">
            {{ form.headSubject.subject_data.name }}
          </div>
        </template>
        <SubjectAutocomplete v-else v-model="form.headSubject" is-return-object :is-disabled="true" />
      </v-col>
    </v-row>

    <Dialog-component
      v-model="isOpenModal"
      :prompt="false"
      cancel-title=""
      confirm-title=""
      width="800"
      with-close-icon
      controls-justify="justify-end"
    >
      <template #title>Выбор головной организации</template>
      <template #content>
        <v-row>
          <v-col cols="12">
            <SubjectAutocomplete
              v-model="temp.headSubject"
              is-return-object
              label="Выберите организацию"
              placeholder="Начните вводить наименование организации"
            />
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" class="flex-end">
            <DefaultButton variant="primary" title="Подтвердить" @click="onSaveHeadSubject" />
          </v-col>
        </v-row>
      </template>
    </Dialog-component>
  </div>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import FormMixin from './FormMixin.vue';
import SubjectAutocomplete from '@/components/Subject/SubjectAutocomplete.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import isEmpty from 'lodash/isEmpty';
import cloneDeep from 'lodash/cloneDeep';

@Component({
  name: 'head-subject',
  components: {
    DialogComponent,
    LabelComponent,
    DefaultButton,
    SubjectAutocomplete,
    InputComponent,
  },
})
export default class HeadSubject extends Mixins(FormMixin) {
  static cache = [];
  listLaboratories: any[] = [];
  isOpenModal = false;

  temp = {
    headSubject: null,
  };

  get isHeadSubject() {
    if (!isEmpty(this.form.headSubject)) {
      return true;
    } else return false;
  }

  created() {
    // this.getListLaboratory();
    this.temp.headSubject = cloneDeep(this.form.headSubject);
  }

  async getListLaboratory() {
    if (!HeadSubject.cache.length) {
      const { content } = await this.$store.dispatch('organization/searchOrganization', {
        filter: '',
        subjectType: 'UL',
        actual: true,
      });
      HeadSubject.cache = content;
    }
    return (this.listLaboratories = HeadSubject.cache);
  }

  addFields(data: any) {
    this.form.subject.address = { ...data };
    this.isOpenModal = false;
  }

  showModal() {
    this.isOpenModal = true;
  }

  closeModal() {
    this.isOpenModal = false;
  }

  onSaveHeadSubject() {
    this.isOpenModal = false;
    this.$emit('saveHeadSubject', this.temp.headSubject)
    console.log(this.temp)
    this.form.headSubject = this.temp.headSubject;
  }
}
</script>

<style lang="scss" scoped>
.flex-end {
  display: flex;
  justify-content: flex-end;
}

.head-subject {
  display: flex;
  flex-wrap: nowrap;

  &__btn {
    display: flex;
    margin-bottom: 8px;
    align-items: center;
    padding-left: 16px;
    cursor: pointer;
    transition: all 0.3s ease;

    &:hover {
      opacity: 0.8;
    }
  }
}

.field {
  min-height: 40px !important;
  background-color: #f8f8f8 !important;
  border: 1px solid #c1c1c1;
  border-radius: 3px;
  box-shadow: none !important;
  outline: none;
  height: 40px;
  color: rgba(0, 0, 0, 0.38);
  font-size: 14px;
  line-height: 16px;
  margin-bottom: 0;
  padding: 0 10px !important;
  z-index: 7;
  display: flex;
  align-items: center;
}

.head-subject-btn {
  &__icon {
    margin-right: 4px;
  }
}
</style>
