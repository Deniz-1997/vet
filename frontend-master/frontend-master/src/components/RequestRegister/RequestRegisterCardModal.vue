<template>
  <DialogComponent v-model="isModalOpen" width="800" hide-actions>
    <template #title>
      <v-row>
        <v-col cols="7">
          <span v-if="mode.read" data-qa="role-card__title" class="title">{{ title }} №{{ details.id }}</span>
          <span v-else data-qa="role-card__title" class="title">{{
            mode.update ? 'Обработать запрос' : 'Добавить запрос'
          }}</span>
        </v-col>
      </v-row>
    </template>
    <template #content>
      <v-row class="definition-list">
        <v-col cols="12" md="12">
          <InputComponent v-model="form.created" label="Дата и время регистрации запроса" disabled />
        </v-col>
      </v-row>
      <v-row v-if="form.status_name" class="definition-list">
        <v-col cols="12" md="12">
          <InputComponent v-model="form.status_name" label="Статус" disabled />
        </v-col>
      </v-row>
      <!-- <v-row class="definition-list">
        <v-col cols="12" md="12">
          <InputComponent v-model="details.name" label="ФИО" :disabled="(!creating && isAnswer) || creating" />
        </v-col>
      </v-row> -->
      <v-row class="definition-list">
        <v-col cols="12" md="12">
          <ManufacturerAutocomplete
            v-if="mode.create && (isOGV || isOperator)"
            v-model="form.subject"
            label="Организация"
            placeholder="Начните вводить наименование организации"
          />
          <InputComponent v-else v-model="form.subject_name" label="Организация" disabled />
        </v-col>
      </v-row>
      <v-row class="definition-list">
        <v-col cols="12" md="12">
          <SelectComponent
            v-model="form.receiving"
            :items="options.receiving"
            label="Способ получения запроса"
            return-object
            item-value="code"
            item-text="name"
            disabled
          />
        </v-col>
      </v-row>
      <v-row class="definition-list">
        <v-col cols="12" md="12">
          <SelectComponent
            v-model="form.answering"
            :items="options.answering"
            label="Способ направления ответа"
            return-object
            item-value="code"
            item-text="name"
            :disabled="!mode.create"
          />
        </v-col>
      </v-row>
      <v-row v-if="form.answering && form.answering.code === 'EMAIL'" class="definition-list">
        <v-col cols="12" md="12">
          <InputComponent v-model="form.email" label="Адрес электронной почты" :disabled="mode.read || mode.update" />
        </v-col>
      </v-row>
      <v-row v-if="form.answering && form.answering.code === 'POST_OFFICE'" class="definition-list">
        <v-col cols="12" md="12">
          <InputComponent v-model="form.address" label="Адрес" :disabled="mode.read || mode.update" />
        </v-col>
      </v-row>
      <v-row v-if="form.updated && mode.read" class="definition-list">
        <v-col cols="12" md="12">
          <InputComponent v-model="form.updated" label="Дата и время отправки ответа" disabled />
        </v-col>
      </v-row>

      <v-row class="definition-list">
        <v-col cols="12" md="12">
          <TextareaComponent v-model="form.body" label="Запрос" :disabled="!mode.create" />
        </v-col>
      </v-row>
      <v-row v-if="(form.reject_reason && mode.read) || mode.update" class="definition-list">
        <v-col cols="12" md="12">
          <SelectComponent
            v-model="form.reject_reason"
            :items="reason_failure_list"
            label="Причина отказа"
            return-object
            item-value="code"
            item-text="name"
            :disabled="mode.read || form.fileAnswer"
          />
        </v-col>
      </v-row>
      <v-row v-if="details.answer || mode.update" class="definition-list">
        <v-col cols="12" md="12">
          <TextareaComponent v-model="form.answer" label="Ответ" :disabled="mode.read || form.fileAnswer" />
        </v-col>
      </v-row>
      <v-row class="definition-list">
        <v-col v-if="!mode.read && !form.answer" cols="12" md="3">
          <span v-if="mode.create" class="label">Файл запроса:</span>
          <span v-if="mode.update" class="label">Файл ответа:</span>

          <div v-if="form[fieldName]" class="d-flex align-center mb-2">
            <div class="file">{{ form[fieldName].name }}</div>
            <v-btn icon small class="ml-3" @click="onClearFile">
              <icon-component height="18" width="18">
                <delete-icon />
              </icon-component>
            </v-btn>
          </div>

          <label>
            <div class="input-wrapper">
              <div class="input-wrapper__text">Загрузить</div>
              <input
                :id="fieldName"
                ref="fileUpload"
                class="d-none"
                type="file"
                name="file"
                accept="application/pdf"
                @change="({ target }) => onInput(target.files)"
              />
            </div>
          </label>
        </v-col>
        <v-col v-if="form.file_id" cols="12">
          Файл запроса:
          <div class="link" @click="getFile('request')">Запрос.pdf</div>
        </v-col>
        <v-col v-if="form.answer_id" cols="12">
          Файл ответа:
          <div class="link" @click="getFile('response')">Ответ.pdf</div>
        </v-col>
      </v-row>
      <v-row justify="end">
        <v-col cols="12" class="col-exclude">
          <DefaultButton v-if="mode.update" title="Отложить" variant="primary" @click="toPostpone" />

          <DefaultButton v-if="mode.update" title="Отправить на доработку" variant="primary" @click="toRevision" />

          <DefaultButton :title="!mode.read ? 'Отменить' : 'Закрыть'" @click="isModalOpen = false" />
          <DefaultButton v-if="!mode.read" title="Отправить" variant="primary" @click="create" />
        </v-col>
      </v-row>

      <SignatureModal
        v-model="isSignatureModalOpen"
        service="requests-answer"
        :measure-id="form.id"
        @approve="handleSignApprove"
      />

      <v-overlay :value="isLoading" absolute>
        <v-progress-circular indeterminate size="64"></v-progress-circular>
      </v-overlay>
    </template>
  </DialogComponent>
</template>

<script lang="ts">
import { Component, Prop, Mixins, Watch } from 'vue-property-decorator';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import TextareaComponent from '@/components/common/inputs/TextAreaComponent.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import Modal from '@/utils/global/mixins/modal';
import format from 'date-fns/format';
import locale from 'date-fns/locale/ru';
import { showFile } from '@/utils/file';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import IconComponent from '@/components/common/IconComponent/IconComponent.vue';
import DeleteIcon from '@/components/common/IconComponent/icons/DeleteIcon.vue';

import SignatureModal from '@/components/SignatureModal/SignatureModal.vue';
import { ERole } from '@/models/roles';

/** Карточка просмотра запроса (мои организации) */
@Component({
  name: 'RequestRegisterCardModal',
  components: {
    DialogComponent,
    DefaultButton,
    InputComponent,
    ManufacturerAutocomplete,
    TextareaComponent,
    SelectComponent,
    SignatureModal,
    IconComponent,
    DeleteIcon,
  },
})
export default class ComplaintCardModal extends Mixins(Modal) {
  @Prop({ type: String, default: 'Просмотр запроса' }) readonly title!: string;
  @Prop({ type: Object, default: '' }) readonly details!: any;
  @Prop({ type: String, default: null, validator: (v: string) => ['read', 'update', 'create'].includes(v) })
  readonly type!: string;

  form: any = this.getInnerForm();

  isLoading = false;

  getting_way_list: any = [];
  isSignatureModalOpen = false;

  sending_way_list: any = [];

  reason_failure_list: any = [];

  get userSubject() {
    return this.$store.getters['auth/getUserInfo']?.subject;
  }

  get mode() {
    return {
      read: this.type === 'read',
      update: this.type === 'update',
      create: this.type === 'create',
    };
  }

  get fieldName() {
    return this.type === 'create' ? 'fileRequest' : 'fileAnswer';
  }

  get isOGV() {
    return this.$store.getters['auth/roles'].includes(ERole.ROLE_GOVERMENT_USER);
  }

  get isOperator() {
    return this.$store.getters['auth/roles'].includes(ERole.ROLE_OPERATOR);
  }

  get options() {
    return {
      receiving: this.sending_way_list,
      answering: this.getting_way_list.filter(({ code }) => {
        return code !== 'EPGU' || code === this.form?.answering?.code;
      }),
    };
  }

  async mounted() {
    this.isLoading = true;
    this.getting_way_list = await this.$service.requests.findAnswerList();
    this.sending_way_list = await this.$service.requests.findReceivingList();
    this.reason_failure_list = await this.$service.requests.findRejectReasonList();

    this.isLoading = false;
  }

  async create() {
    this.isLoading = true;
    try {
      // Отправить
      if (this.form.id) {
        if (this.form.fileAnswer) {
          const { data } = await this.$service.requests.updload(this.form.fileAnswer);
          const file_id = data.id;
          this.form = {
            ...this.form,
            file_id: file_id,
          };
        }
        const data = await this.$service.requests.update(this.form);
        this.handleSignatureModalOpen(data);
      } else {
        // Добавить
        if (this.form.fileRequest) {
          const { data } = await this.$service.requests.updload(this.form.fileRequest);
          const file_id = data.id;
          this.form = {
            ...this.form,
            file_id: file_id,
          };
        }
        await this.$service.requests.create(this.form);
        this.$emit('close');
        this.isModalOpen = false;
      }
    } catch (_err) {
      this.isLoading = false;
    }

    this.isLoading = false;
  }

  //Вернуть на доработку
  async toRevision() {
    this.isLoading = true;
    try {
      if (this.form.id) {
        if (this.form.fileAnswer) {
          const { data } = await this.$service.requests.updload(this.form.fileAnswer);
          const file_id = data.id;
          this.form = {
            ...this.form,
            file_id: file_id,
          };
        }

        const data = await this.$service.requests.toRevision(this.form);
        this.handleSignatureModalOpen(data);
      }
    } catch (_err) {
      this.isLoading = false;
    }

    this.isLoading = false;
  }

  // Отложить
  async toPostpone() {
    this.isLoading = true;
    try {
      await this.$service.requests.toPostpone(this.form.id);
      this.$emit('close');
      this.isModalOpen = false;
    } catch (_err) {
      this.isLoading = false;
    }

    this.isLoading = false;
  }

  @Watch('form.answering')
  filterAnswer() {
    if (this.form.answering?.code !== 'POST_OFFICE') {
      this.form.address = '';
    }
    if (this.form.answering?.code !== 'EMAIL') {
      this.form.email = '';
    }
  }

  getInnerForm() {
    return {
      status_name: '',
      receiving: null,
      fileRequest: null,
      fileAnswer: null,
      organizations: null,
      updated: '',
      email: '',
      answering: null,
      body: '',
      reject_reason: null,
      answer: '',
      created: '',
      subject: null,
    };
  }

  async onModalOpen() {
    this.isLoading = true;
    if (this.details.id) {
      const { data } = await this.$service.requests.findOne(this.details.id);
      this.form = {
        ...this.form,
        ...this.details,
        ...data,
      };
    } else {
      this.form.receiving =
        !this.isOGV || !this.isOperator
          ? this.sending_way_list.find(({ code }) => code === 'ARM')
          : this.sending_way_list.find(({ code }) => code === 'PAPER');
      this.form.created = format(new Date(), 'dd.MM.yyyy hh:mm', { locale });
      this.form.subject_name =
        !this.isOGV || !this.isOperator
          ? this.form.subject_name || this.userSubject?.short_name || this.userSubject?.name
          : this.form.subject_name;
      this.form.subject =
        !this.isOGV || !this.isOperator ? this.form.subject || this.userSubject?.subject_id : this.form.subject;
    }
    this.isLoading = false;
  }

  onModalClose() {
    this.form = this.getInnerForm();
  }

  async handleSignatureModalOpen(data?: any): Promise<void> {
    await this.$store.dispatch('agreementDocument/getNewOrStoredDocument', {
      measureId: this.form.id,
      service: 'requests-answer',
      file: data,
    });
    this.isSignatureModalOpen = true;
  }

  async handleSignApprove() {
    this.isSignatureModalOpen = false;
    this.isModalOpen = false;
  }

  async getFile(type: 'request' | 'response') {
    if (type === 'response') {
      await showFile({
        method: 'get',
        path: `/api/elevator-request/free/form/request/answer/get/${this.form.answer_id}`,
      });
    } else {
      await showFile({
        method: 'get',
        path: `/api/elevator-request/file/${this.form.file_id}`,
      });
    }
  }

  async onInput(files: FileList) {
    const file = files.item(0);

    if (file) {
      this.form[this.fieldName] = new File([await file.arrayBuffer()], file.name, {
        lastModified: file.lastModified,
        type: file.type,
      });
    }
  }

  onClearFile() {
    this.form[this.fieldName] = null;
    (this.$refs.fileUpload as HTMLInputElement).value = '';
  }
}
</script>

<style lang="scss">
@import '@/assets/styles/_variables';

.definition-list strong {
  font-weight: bolder !important;
}

.input-wrapper {
  width: 100%;
  padding: 5px 5px;
  border: 1px dashed $gold-light-color;
  display: flex;
  flex-direction: column;
  align-items: center;
  transition: 0.2s all ease-out;
  cursor: pointer;

  &__text {
    color: $gold-light-color;
  }
}

.link {
  color: $gold-light-color !important;
  text-decoration: underline;
  cursor: pointer;
}

.label {
  color: $medium-grey-color;
  font-size: 14px;
  font-weight: normal;
  line-height: 16px;
  margin-bottom: 5px;
}

.file {
  padding: 5px 0;
  text-decoration: underline;
}
</style>
