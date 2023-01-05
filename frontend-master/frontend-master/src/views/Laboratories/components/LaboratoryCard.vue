<template>
  <Dialog-component
    v-model="innerValue"
    :prompt="false"
    cancel-title=""
    confirm-title=""
    width="800"
    with-close-icon
    controls-justify="justify-end"
  >
    <template #title>
      <span v-if="id">Данные лаборатории</span>
      <span v-else>Добавление лаборатории</span>
    </template>

    <template #content>
      <UiForm :rules="rules" @submit="saveOrganization" @validation="(v) => (isValid = v.isValid)">
        <HeadSubject :form="form" :is-show="isShow" :is-edit="isEdit" />
        <SubjectInfo :form="form" :is-show="isShow" :is-edit="isEdit" />
        <LocationsTable
          v-model="form.locations"
          :subject-type="form.head_subject && form.head_subject.subject_type"
          :is-show="isShow"
        />
        <CertificatesTable v-model="form.certificates" :is-show="isShow" />

        <v-row justify="end">
          <v-col cols="12" class="col-exclude">
            <DefaultButton v-if="isShow" title="Закрыть" @click="closeCard" />
            <DefaultButton v-if="!isShow" title="Отмена" @click="closeCard" />
            <DefaultButton
              v-if="!isShow"
              variant="primary"
              title="Сохранить"
              :disabled="!isValid || isLoading"
              type="submit"
            />
          </v-col>
        </v-row>
      </UiForm>
    </template>
    <v-overlay :value="isLoading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </Dialog-component>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import HeadSubject from './HeadSubject.vue';
import SubjectInfo from './SubjectInfo.vue';
import LocationsTable from './LocationsTable.vue';
import CertificatesTable from './CertificatesTable.vue';

const getInitialForm = (): any => ({
  head_subject: {},
  subject: {
    subject_data: {}
  },
  locations: [],
  certificates: [],
});

@Component({
  name: 'laboratory-card',
  components: {
    DialogComponent,
    DefaultButton,
    HeadSubject,
    SubjectInfo,
    LocationsTable,
    CertificatesTable,
  },
})
export default class LaboratoryCard extends Vue {
  /** Флаг открытия модального окна. */
  @Prop({ type: Boolean }) readonly value;
  @Prop({ type: [String, Number] }) readonly id;
  @Prop({ type: Boolean }) readonly isShow;

  isLoading = false;
  isValid = true;
  isError = false;
  isEdit = false;

  form: any = getInitialForm();

  get innerValue() {
    return this.value;
  }

  set innerValue(value) {
    this.$emit('input', value);
    if (!value) {
      this.closeCard();
    }
  }

  get rules() {
    return {
      subject_address: [{ subject_country: this.form?.head_subject?.subject_type }],
    };
  }

  mapForm(form) {
    return {
      ...form,
      head_subject_id: form.head_subject?.subject_id,
      certificates: form.certificates.map((item) => ({
        ...item,
        document: {
          doc_num: item.doc_num,
          doc_date: item.doc_date,
        },
      })),
    };
  }

  @Watch('id')
  async getInfo() {
    this.isLoading = true;
    if (this.id) {
      this.isEdit = true;
      this.form = await this.$store.dispatch('laboratories/getInfoLaboratory', {
        id: this.id,
      });

      this.form = {
        ...this.form,
        certificates: this.form.certificates
          ? this.form.certificates.map((item) => ({
              ...item,
              doc_num: item.document.doc_num,
              doc_date: item.document.doc_date,
            }))
          : [],
        locations: this.form.locations || [],
      };
    } else {
      this.isEdit = false;
    }
    this.isLoading = false;
  }

  async saveOrganization() {
    this.isError = false;

    if (this.form['locations'].length === 0) {
      this.$store.commit('errors/clearErrorList');
      this.$store.commit('errors/setErrorsList', 'Данные "Адреса осуществления деятельности" не заполнены');
      return;
    }
    if (!this.form['certificates']) {
      this.$store.commit('errors/clearErrorList');
      this.$store.commit('errors/setErrorsList', 'Данные "Аттестаты аккредитации" не заполнены');
      this.isError = true;
      return;
    }
    try {
      this.isLoading = true;
      if (!this.isError) {
        if (this.form.id) {
          this.$store.dispatch('laboratories/updateLaboratories', this.mapForm(this.form)).then(() => {
            this.closeCard();
          });
        } else {
          await this.$store.dispatch('laboratories/createLaboratories', this.mapForm(this.form)).then(() => {
            this.closeCard();
          });
        }
      }
    } catch (_error) {
      this.isLoading = false;
    }
  }

  closeCard() {
    this.$emit('close');
    this.form = getInitialForm();
  }
}
</script>
