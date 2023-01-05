<template>
  <v-container class="cardRequest">
    <UiForm
      v-if="!showChanges && !isLoading"
      :rules="rules"
      :messages="messages"
      @validation="(v) => (isValid = v.isValid)"
      @submit="handleRequest"
    >
      <v-row>
        <v-col cols="12">
          <div class="title">
            <span>
              Заявление <span v-if="form.request_id">№ {{ form.request_id }}</span>
            </span>
          </div>
        </v-col>
      </v-row>
      <div class="baseInformation">
        <v-row>
          <v-col cols="12">
            <div class="inputContainer">
              <SubjectAutocomplete
                v-if="!isView && !isEditCard && isAdmin"
                v-model="initSubject.subject"
                label="Организация"
                is-return-object
                placeholder="Начните вводить наименование организации"
              />
              <InputComponent
                v-else
                disabled
                label="Организация"
                :value="companyName"
                @input="
                  (value) =>
                    $emit('change', {
                      name: 'subject.name',
                      value: subject.code,
                    })
                "
              />
            </div>
          </v-col>
        </v-row>
        <v-row v-if="!!form.subject">
          <v-col cols="12">
            <div class="inputContainer">
              <InputComponent disabled label="Вид заявления" :value="form.approval_request_type.name" />
            </div>
          </v-col>
        </v-row>

        <v-row v-if="form.approval_request_type && form.approval_request_type.code === '2'">
          <v-col cols="12">
            <div class="inputContainer">
              <InputComponent
                disabled
                label="Регистрационный номер организации в реестре элеваторов"
                :value="form.elevator_register_application.elevator_registration_number"
              />
            </div>
          </v-col>
        </v-row>

        <v-row v-if="isView">
          <v-col cols="12" md="6" lg="6" xl="6">
            <div class="inputContainer">
              <InputComponent
                label="Дата и время создания"
                disabled
                :value="form.request_date"
                @change="(value) => $emit('change', { name: 'request_date', value })"
              />
            </div>
          </v-col>

          <v-col v-if="form.statusId !== 1" cols="12" md="6" lg="6" xl="6">
            <div class="inputContainer">
              <InputComponent
                label="Дата и время отправки на рассмотрение"
                disabled
                :value="form.dispatch_date"
                @change="(value) => $emit('change', { name: 'request_date', value })"
              />
            </div>
          </v-col>
        </v-row>
        <v-row v-if="isView && !!requestId">
          <v-col cols="12">
            <ApprovalRequestLogShortTable :id="requestId" />
          </v-col>
        </v-row>

        <v-row v-if="form.approval_request_type ? form.approval_request_type.code === '2' : null">
          <v-col cols="12">
            <div class="inputContainer">
              <InputComponent
                v-model="form.elevator_register_application.basis_changes"
                :disabled="isView"
                label="Основания для внесения изменений"
              />
            </div>
          </v-col>
        </v-row>
      </div>

      <v-row>
        <v-col cols="12">
          <div class="containerTabs">
            <div class="tabs">
              <div :class="[{ active: tab === 'general' }, 'tab']" @click="clickTab('general')">Общие сведения</div>
              <div :class="[{ active: tab === 'location' }, 'tab']" @click="clickTab('location')">Места хранения</div>
            </div>
          </div>
        </v-col>
      </v-row>
      <div v-show="tab === 'general'" class="generalInformation">
        <CardGeneralInformation :form="form" :is-showcase="isView" :full-info="false" />
      </div>

      <div v-show="tab === 'location'" class="location">
        <CardLocationInformation
          :form="form"
          :is-showcase="isView"
          :status="form.request_status ? form.request_status.name : 'Черновик'"
          :max-capacity="totalCapacity"
          :information="form.elevator_register_application"
          :changes-data="changesData"
        />
      </div>

      <v-row>
        <v-col cols="12">
          <div class="buttons">
            <!-- <button
              class="button button--default"
              v-if="
                form.approval_request_type &&
                form.approval_request_type.code == 2 &&
                isView && canViewChanges

              "
              @click="showCompare(form.request_id)"
            >
              Посмотреть изменения
            </button> -->

            <a class="button button--default" @click="clickClose"> Закрыть </a>

            <!-- Условие для отображения canReject && isUserDivision-->
            <button
              v-if="canReject"
              type="button"
              class="button button--error"
              @click="openModalReasons('Отклонение задачи', 'reject')"
            >
              Отклонить
            </button>
            <!-- Условие для отображения canApprove && isUserDivision-->
            <button
              v-if="canApprove"
              type="button"
              class="button button--primary"
              @click="openModalConfirm('Рассмотрение', 'confirm')"
            >
              Согласовать
            </button>
            <button
              v-if="
                form.request_status &&
                form.request_status.code === 'DRAFT' &&
                isView &&
                (canSendRequest || canSendChangeRequest) &&
                !isClickSignatureModalOpen
              "
              type="button"
              class="button button--primary"
              @click="handleSignatureModalOpen"
            >
              Подписать и отправить на рассмотрение
            </button>
            <button
              v-else-if="!form.request_status || (form.request_status.code === 'DRAFT' && !isView)"
              :disabled="!isValid || isLoading"
              class="button button--primary"
              type="submit"
            >
              Сохранить
            </button>
          </div>
        </v-col>
      </v-row>
    </UiForm>

    <ConfirmModalDelete
      v-if="showConfirmModal"
      :text="'Вы действительно хотите удалить запись'"
      :name="selectItemName"
      @close="closeConfirmModal"
      @apply="handleRemoveStorage"
    />
    <AuthoritiesCardRequestChanges
      v-if="showChanges"
      :form="form"
      :is-showcase="isView"
      :changes-data="changesData"
      @hide="hideCompare"
      @close="clickClose"
    />
    <ModalConfirm
      v-if="isShowModalConfirm"
      :id="id"
      :title="title"
      :action="action"
      :item="selectTask"
      :request-id="requestId"
      @click-close="closeModalConfirm"
      @update-information="isAgreeModalConfirm = true"
    />
    <ModalReasons
      v-if="isShowModalReasons"
      :id="id"
      :title="title"
      :action="action"
      :item="selectTask"
      @click-close="closeModalReasons"
      @update-information="getInitParams"
    />
    <SignatureModal
      v-model="isSignatureModalOpen"
      service="requests"
      :measure-id="form.request_id"
      @approve="handleSignApprove"
    />

    <v-overlay :value="isLoading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import { AxiosError } from 'axios';
import { date } from '@/utils/global/filters';
import { Component, Vue, Watch } from 'vue-property-decorator';
import CardGeneralInformation from '@/views/RegisterOrganizations/components/CardOrganization/components/GeneralInformation.vue';
import CardLocationInformation from '@/views/RegisterOrganizations/components/CardOrganization/components/LocationInformation.vue';
import ConfirmModalDelete from '@/views/Authorities/components/Modal/ConfirmModalDelete.vue';
import ModalConfirm from '@/views/Authorities/components/ModalConfirm.vue';
import ModalReasons from '@/views/Authorities/components/ModalReasons.vue';
import AuthoritiesCardRequestChanges from './CardRequestChanges.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import SignatureModal from '@/components/SignatureModal/SignatureModal.vue';
import ApprovalRequestLogShortTable from '@/components/ApprovalRequestLog/ApprovalRequestLogShortTable.vue';
import { EAction, mapAccessFlags } from '@/utils';
import { ERole } from '@/models/roles';
import { mapInnerFormFromOldRequest } from './utils';
import SubjectAutocomplete from '@/components/Subject/SubjectAutocomplete.vue';
import { cloneDeep, isEqual } from 'lodash';

const getInitialSubject = () => ({
  address: '',
  address_text: '',
  inn: '',
  kpp: '',
  name: '',
  nza: '',
  ogrn: '',
  opf: { code: '', name: '' },
  opf_name: '',
  short_name: '',
});

const getInitialForm = () => ({
  elevator_register_application: {
    basis_changes: '',
  },
  elevator_info: {
    elevator_info_insurance: [],
    elevator_info_service: [],
    elevator_info_processing: [],
    elevator_info_product: [],
    is_mechanized: true,
  },
  subject: getInitialSubject(),
  identity_doc: {
    type: {},
    series: '',
    id_number: '',
    doc_date: undefined,
  },
  approval_request_type: {
    code: '1',
    name: 'Заявление на регистрацию в реестре организаций, осуществляющих в качестве предпринимательской деятельности хранение зерна',
  },
  elevator_site: [],
  approval_request_logs: [],
});

Component.registerHooks(['beforeRouteLeave']);

@Component({
  name: 'card-request',
  components: {
    CardGeneralInformation,
    ConfirmModalDelete,
    ModalConfirm,
    ModalReasons,
    CardLocationInformation,
    AuthoritiesCardRequestChanges,
    InputComponent,
    AutocompleteComponent,
    SignatureModal,
    ApprovalRequestLogShortTable,
    SubjectAutocomplete,
  },
  computed: {
    ...mapAccessFlags({
      canViewChanges: EAction.READ_CHANGE_REQUEST_DATA,
      canSendRequest: EAction.SIGN_REQUEST,
      canSendChangeRequest: EAction.SIGN_CHANGE_REQUEST,
      canApproveTask: EAction.APPROVE_TASK,
      canRejectTask: EAction.REJECT_TASK,
    }),
  },
  methods: { date },
})
export default class AuthoritiesCardRequest extends Vue {
  readonly canViewChanges!: boolean;
  readonly canSendRequest!: boolean;
  readonly canSendChangeRequest!: boolean;
  readonly canApproveTask!: boolean;
  readonly canRejectTask!: boolean;

  $promiseId!: string;
  locationStorage = null;
  organizations = [];
  initialListOrganizations = [];
  requestTypesStorage = {};
  showModal = false;
  isEdit = false;
  storage = {};
  editMode = false;
  showConfirmModal = false;
  selectItemName = '';
  selectItemId = null;
  tab = 'general';
  information = {};
  subject = {};
  organizationInfo = {};
  changesData = {};
  loadQueue: Promise<any>[] = [];
  isValid = true;
  showChanges = false;
  isSignatureModalOpen = false;
  isEditCard = false;
  isCreate = true;
  form: any = getInitialForm();
  isView = false;
  taskInfo: any = {};
  isShowModalReasons = false;
  isShowModalConfirm = false;
  isAgreeModalConfirm = false;
  title = '';
  action = '';
  id: number | null = null;
  selectTask: any = {};
  formInitialState: any = null;
  isSaved = false;

  initSubject: any = {
    subject: {},
  };

  isClickSignatureModalOpen = false;

  get isUserDivision() {
    return !!this.form.divisions_correspond;
  }

  get isLoading() {
    return !!this.loadQueue.length;
  }

  get canApprove() {
    const { stage, status } = this.taskInfo || {};
    return this.canApproveTask && !stage?.automatic && [1, 5].includes(status?.id);
  }

  get canReject() {
    const { stage, status } = this.taskInfo || {};
    return this.canRejectTask && !stage?.automatic && [1, 5].includes(status?.id);
  }

  get commonCapacity() {
    return (this.form.elevator_site || []).reduce((sum, row) => sum + Number(row.capacity_tons || 0), 0);
  }

  get mothballedCapacity() {
    return this.form?.elevator_info?.capacity_mothballed || 0;
  }

  get totalCapacity() {
    this.form.elevator_info.capacity = Number((this.commonCapacity - this.mothballedCapacity).toFixed(3));
    return this.form.elevator_info.capacity;
  }

  get isPassportIdentity() {
    return this.form.identity_doc?.type?.code === 'RF_PASSPORT';
  }

  get rules() {
    const { type } = this.form.identity_doc || {};

    const result: any = {
      capacity_mothballed: ['numeric', { min: 0 }, { max: this.commonCapacity }],
      service: ['required', { min: 1 }],
      conversion: this.form.subject?.is_processor && ['required', { min: 1 }],
    };

    if (this.form.subject?.subject_type === 'IP') {
      result.identitySeries = [this.isPassportIdentity && { size: 4 }, { required: !!type?.name }];
      result.identityNumber = [this.isPassportIdentity && { size: 6 }, { required: !!type?.name }];
      result.identityDate = !!type && 'required';
    }

    if (this.form.elevator_info.is_mechanized) {
      result.hazardous_object = 'required';
    }

    return result;
  }

  get messages() {
    return {
      'min.service': 'Обязательно для заполнения',
      'min.conversion': 'Обязательно для заполнения',
      'numeric.capacity_mothballed': 'Некорректный формат',
      'min.capacity_mothballed': 'Мощность не может быть отрицательной',
      'max.capacity_mothballed': 'Мощность законсервированных зернохранилищ не может превышать общую мощность',
      'size.identitySeries': 'Серия должна состоять из 4 цифр',
      'size.identityNumber': 'Номер должен состоять из 6 цифр',
    };
  }

  get companyName() {
    return this.form?.subject?.subject_data?.name
      ? this.form?.subject?.subject_data?.name
      : this.initSubject.subject.name;
  }

  get isAdmin() {
    return this.$store.getters['auth/roles'].includes(ERole.ROLE_ADMIN);
  }

  get hasUnsavedData() {
    return (this.isCreate || this.isEditCard) && !(this.isSaved || isEqual(this.form, this.formInitialState));
  }

  async mounted() {
    this.form = getInitialForm();
    await this.getInitParams();
    // await this.pushLoader(this.fetchOrganizations());

    if (!this.isAdmin) {
      await this.pushLoader(this.handleNonadminInteractions());
    }
  }

  get requestTypes() {
    return this.requestTypesStorage[this.form?.subjectName?.code] || [];
  }

  get agreementDocument() {
    return this.$store.state.agreementDocument;
  }

  get measureId(): number {
    return Number(this.form.request_id);
  }

  get personalInfo(): any {
    return this.$store.state.auth;
  }

  get requestId(): number | undefined {
    return this.form.request_id ? Number(this.form.request_id) : undefined;
  }

  async handleNonadminInteractions() {
    this.formInitialState = null;

    this.initSubject.subject = this.form.subject.subject_id
      ? { ...this.form.subject }
      : {
          subject_id: this.$store.state.auth.user.subject.subject_id,
          ...this.$store.state.auth.user.subject,
        };

    if (this.initSubject.subject?.subject_id) {
      this.getInfoOrganization(this.initSubject.subject?.subject_id);
    }
    if (
      this.initSubject.subject?.subject_id &&
      !this.requestTypesStorage[this.initSubject.subject?.subject_id] &&
      !this.isView
    ) {
      await this.pushLoader(this.fetchRequestTypes(this.initSubject.subject?.subject_id));
      if (!this.form.code) {
        this.handleChange({
          name: 'typeName',
          value: this.requestTypes[0],
        });
      }
      if (this.requestTypes.length && this.requestTypes[0].value == 2 && !this.form.request_id) {
        this.$emit('enrich', this.requestTypes[0]);
      }
    }
  }

  openModalReasons(title: string, action: string) {
    this.selectTask = {};
    this.isShowModalReasons = true;
    this.title = title;
    this.action = action;
    this.selectTask = { ...this.form, ...this.taskInfo };
    this.id = Number(this.$route.params.id);
  }

  openModalConfirm(title: string, action: string) {
    this.selectTask = {};
    this.isShowModalConfirm = true;
    this.title = title;
    this.action = action;
    this.id = Number(this.$route.params.id);
    this.selectTask = { ...this.form, ...this.taskInfo };
  }

  async closeModalReasons() {
    await this.pushLoader(this.getInitParams());
    this.isShowModalReasons = false;
  }

  async closeModalConfirm() {
    this.isShowModalConfirm = false;

    if (this.isAgreeModalConfirm) {
      this.$router.push({
        name: 'approval',
        params: { showNotifyMsg: `Заявление №${this.selectTask.request_id} успешно согласовано` },
      });
    } else {
      await this.pushLoader(this.getInitParams());
    }
  }

  clickTab(tab: string) {
    this.tab = tab;
  }

  async searchOrganization(value) {
    if (value === null) {
      return;
    }
    if (this.initSubject.subject?.subject_id) {
      this.getInfoOrganization(this.initSubject.subject.subject_id);
    }
  }

  handleChange({ name, value }: any) {
    this.form = {
      ...this.form,
      [name]: value,
    };
  }

  async getInitParams() {
    let id = this.$route.params.id;
    this.isView = this.$route.params.type === 'view';
    this.isEditCard = this.$route.params.type === 'edit';
    this.isCreate = !this.isView && !this.isEditCard;
    this.formInitialState = null;
    this.isSaved = false;

    if (this.$route.path.startsWith('/tasks-for-approval')) {
      const { data } = await this.pushLoader(this.$axios.get(`/api/approval-task/tasks/${id}`));
      id = data.request_id;
      this.taskInfo = data;
    }

    if (id) {
      const data = await this.pushLoader(this.$store.dispatch('elevator/getInfoElevator', id));
      this.form = data;
      this.form.identity_doc = data.identity_doc;

      if (!this.form.identity_doc) {
        this.form.identity_doc = {
          type: {},
          series: '',
          id_number: '',
          doc_date: undefined,
        };
      }
    }
  }

  saveFormInitialState() {
    this.formInitialState = cloneDeep(this.form);
  }

  async handleSubmitRequest(type: string) {
    let data;
    try {
      if (type === 'create') {
        data = await this.pushLoader(this.$store.dispatch('elevator/create', this.form));
      } else {
        data = await this.pushLoader(this.$store.dispatch('elevator/update', this.form));
      }
      this.isSaved = true;
      this.$router.push({
        name: 'card-requests',
        params: { id: data.request_id, type: 'view' },
      });
      this.getInitParams();
    } catch (err) {
      console.error(err);

      const message = (err as unknown as AxiosError)?.response?.data?.message ?? 'Что-то пошло не так';
      this.emitError(message);
    }
  }

  emitError(message: string) {
    this.$store.commit('errors/clearErrorList');
    this.$store.commit('errors/setErrorsList', message);
  }

  handleRequest() {
    if (this.form.loading_capacity_train_tons > 32000) {
      return this.emitError("Значение поля 'Мощность погрузки (тонн в сутки)' превышает допустимое значение 32000");
    }
    if (this.form.railway_capacity_wagons > 32000) {
      return this.emitError(
        "Значение поля 'Вместимость ж/д путей в вагонах (собственных)' превышает допустимое значение 32000"
      );
    }
    if (this.form.railway_capacity_wagons_rent > 32000) {
      return this.emitError(
        "Значение поля 'Вместимость ж/д путей в вагонах (аренда)' превышает допустимое значение 32000"
      );
    }
    if (this.form.railway_length > 32000) {
      return this.emitError(
        "Значение поля 'Протяженность ж/д путей, м (собственных)' превышает допустимое значение 32000"
      );
    }
    if (this.form.railway_length_rent > 32000) {
      return this.emitError("Значение поля 'Протяженность ж/д путей, м (аренда)' превышает допустимое значение 32000");
    }
    if (this.form.loading_capacity_wagons > 32000) {
      return this.emitError("Значение поля 'Мощность погрузки (вагонов в сутки)' превышает допустимое значение 32000");
    }

    if (!this.initSubject.subject) {
      return this.emitError("Поле 'Организация' не заполнено");
    }
    if (this.form.request_id && this.form.request_status && this.form.request_status.code === 'DRAFT' && !this.isView) {
      return this.handleSubmitRequest('update');
    }
    return this.handleSubmitRequest('create');
  }

  handleEnrich() {
    if (this.isView) {
      this.$router.push({
        name: 'card-requests',
        params: { id: this.form.request_id, type: 'view' },
      });
      this.getInitParams();
      return;
    }
    this.$router.push({
      name: 'card-requests',
      params: { id: this.form.request_id, type: 'edit' },
    });
    this.getInitParams();
  }

  async handleSignatureModalOpen(): Promise<void> {
    await this.pushLoader(
      this.$store.dispatch('agreementDocument/getNewOrStoredDocument', {
        measureId: this.form.request_id,
        service: 'elevator-request',
      })
    );
    this.isSignatureModalOpen = true;
    this.isClickSignatureModalOpen = true;
  }

  async handleSignApprove(id) {
    await this.pushLoader(this.$store.dispatch('approvalTask/applyRequest', id));
    this.isSignatureModalOpen = false;
    this.$router.push(this.$route.meta?.backRoute || '/requests');
  }

  handleShowConfirmModal(name, index) {
    this.selectItemName = name;
    this.selectItemId = index;
    this.showConfirmModal = true;
  }

  closeConfirmModal() {
    this.showConfirmModal = false;
  }

  handleRemoveStorage() {
    const { requestSiteList } = this.form;
    this.$emit('change', {
      name: 'requestSiteList',
      value: (requestSiteList || []).filter((_, i) => i !== this.selectItemId),
    });
    this.showConfirmModal = false;
  }

  handleCloseForm() {
    this.editMode = false;
    this.showModal = false;
  }

  clickClose() {
    this.$emit('click-close');
    this.$router.push(this.$route.meta?.backRoute || '/requests').catch(() => {
      // ловлю исключение, которое выбрасывается при отмене перехода в ModalRouteLeave
    });
  }

  async showCompare(id) {
    this.showChanges = true;
    const data = await this.pushLoader(this.$store.dispatch('elevator/getChangesRequest', id));
    this.changesData = data;
  }

  hideCompare() {
    this.showChanges = false;
    this.changesData = {};
  }

  // eslint-disable-next-line max-lines-per-function
  async fetchOrganizations() {
    const { content } = await this.pushLoader(this.$store.dispatch('organization/getElevatorSubject', {}));
    this.organizations = content.map((item) => ({
      name: item.subject_data.name,
      code: item.subject_data.subject_id,
    }));

    const data = await this.pushLoader(this.$store.dispatch('user/getInfo'));
    this.$store.commit('auth/setUserInfo', data);
    const userInfo = { ...this.personalInfo };
    const { roles, subject } = userInfo.user;
    const roleUser = roles.filter((item) => {
      if (roles.length === 1) {
        if (item.authority === 'ROLE_SUBJECT_USER') {
          return item;
        }
      }
    });
    if (!roles.find((item) => ['ROLE_MCX_USER', 'ROLE_AUDITOR', 'ROLE_REQUEST'].includes(item.authority))) {
      await this.pushLoader(this.getInfoOrganization(subject.subject_id));
    }
    if (roleUser.length > 0 && subject && subject.inn !== '7710035913') {
      this.initialListOrganizations = content.filter((item: any) => item.label === subject.name);
      this.organizations = content
        .filter((item: any) => item.subject_data.name === subject.name)
        .map((item) => ({
          name: item.subject_data.name,
          code: item.subject_data.subject_id,
        }));
    } else {
      this.initialListOrganizations = content;
      this.organizations = content.map((item) => ({
        name: item.subject_data.name,
        code: item.subject_data.subject_id,
      }));
    }
  }

  async fetchRequestTypes(id) {
    const data = await this.pushLoader(this.$store.dispatch('elevator/getRequestType', id));
    this.form.approval_request_type = data;
  }

  async getInfoOrganization(id) {
    const data = await this.pushLoader(this.$store.dispatch('organization/getInfoOrganization', id));
    this.organizationInfo = data;
    this.form.subject = data;
  }

  async getInfoRequest() {
    const data = await this.pushLoader(
      this.$store.dispatch('elevator/getActualInfoElevator', this.initSubject.subject.subject_id)
    );
    this.form = {
      approval_request_type: this.form.approval_request_type,
      ...data,
    };
  }

  pushLoader(promise) {
    this.loadQueue.push(promise);
    const id = Math.random().toString(36).slice(0, 6);
    this.$promiseId = id;

    Promise.all(this.loadQueue).finally(() => {
      if (this.$promiseId === id) {
        this.loadQueue.splice(0, this.loadQueue.length);

        if (!this.formInitialState) {
          this.saveFormInitialState();
        }
      }
    });

    return promise;
  }

  getStage(item) {
    return (
      item?.approval_template_stage?.automatic_stage?.name ??
      item?.approval_template_stage?.name ??
      item.request_status.name
    );
  }

  async fetchListRequest() {
    const { content } = await await this.$store.dispatch('elevator/getListRequests', {
      subject_id: this.initSubject.subject.subject_id,
      actual: true,
      request_status: {
        id: 4,
      },
    });

    if (content.length > 0) {
      const request_id = content[0].request_id;

      const data = await this.$store.dispatch('elevator/getInfoElevator', request_id);
      this.form = mapInnerFormFromOldRequest({
        ...data,
        approval_request_type: this.form.approval_request_type,
        request_status: undefined,
      });
    }
  }

  beforeRouteLeave(to, from, next) {
    if (this.hasUnsavedData) {
      this.$service.notify.push('message', {
        text: '',
        params: {
          type: 'confirm-modal',
          title: 'Подтвердите действие',
          text: 'При переходе в другой раздел системы все несохраненные изменения будут потеряны. Продолжить?',
          buttons: [
            {
              type: 'decline',
              label: 'Продолжить',
              callback: () => {
                next(true);
              },
            },
            {
              label: 'Остаться',
              callback: () => {
                next(false);
              },
            },
          ],
        },
      });
    } else {
      next(true);
    }
  }

  @Watch('initSubject.subject', { immediate: true })
  async handleUpdateRequestTypes() {
    if (this.initSubject.subject?.code) {
      this.initSubject.subject.subject_id = this.initSubject.subject.code;
    }
    if (this.$route.params.id) {
      return;
    }
    if (this.initSubject.subject.subject_id && !this.isView) {
      await this.pushLoader(this.fetchRequestTypes(this.initSubject.subject.subject_id));
      if (this.form.approval_request_type.id === 2) {
        this.getInfoRequest();
        await this.pushLoader(this.getInfoOrganization(this.initSubject.subject.subject_id));
      }
      this.fetchListRequest();
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.containerTabs {
  margin-top: 30px;
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

.elementsInput {
  margin-bottom: 15px;
  z-index: -1;
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

//ToDo: Исправить цвета

.label {
  color: #828286;
  font-size: 14px;
  font-weight: normal;
  line-height: 16px;
  margin-bottom: 13px;
  display: block;
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

.input {
  &--disabled {
    background-color: $input-disable-background;
    color: $input-disabled-color;
  }

  &--small {
    flex: 1 1 150px;
    margin-right: 15px;
    max-width: 150px;
  }

  &--big {
    height: auto;
    padding: 10px;
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
    box-shadow: 0 0 5px rgba($black-color, 0.5);
  }

  &--primary {
    border-color: $button-primary-background;
    background-color: $button-primary-background;
    color: $white-color;
  }
}

.select {
  height: 40px;
  width: 100%;

  &--lg {
    flex: 1 1 100%;
  }
}

.input {
  border: 1px solid $input-border-color;
  border-radius: 3px;
  background: $white-color;
  box-shadow: none !important;
  outline: none;
  min-height: 40px;
  height: auto;
  color: $black-color;
  margin-bottom: 0;
  padding: 10px !important;
  z-index: 7;
  font-size: 0.875rem;
  font-weight: 400;
  line-height: 1.375rem;
  letter-spacing: 0.0071em;

  &--disabled {
    background-color: $input-disable-background !important;
    color: $input-disabled-color;
  }
}
</style>
