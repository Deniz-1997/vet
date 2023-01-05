<template>
  <div class="RequestInformation">
    <DataTable
      :headers="headers"
      :items="rows"
      :items-length="rows.length"
      :hide-footer="true"
    >
      <template v-slot:[`item.actions`]="{ item }">
        <img
          src="/icons/show.svg"
          class="iconTable"
          @click="showCard(item.id)"
        >
        <img
          src="/icons/info.svg"
          class="iconTable"
          @click="showChanges(item.id)"
          v-if="item.type_id === 2"
        >
      </template>
    </DataTable>
    <dialog-component
      v-model="isShowCard"
      :prompt="false"
      cancel-title=""
      confirm-title=""
      width="auto"
      with-close-icon
    >
      <template #title>
        <div />
      </template>
      <template #content>
        <AuthoritiesCardRequest
          :isShowcase="isShowCard"
          :isView="true"
          :form="form"
          @click-close="closeCard"
        />
      </template>
    </dialog-component>
    <dialog-component
      v-model="isShowChange"
      :prompt="false"
      controls-justify="justify-end"
      cancel-title="Закрыть"
      confirm-title=""
      width="1200"
      with-close-icon
    >
      <template #title>
        Сведения, подлежащие изменению в рамках заявления №{{userChanger.id}}
      </template>
      <template #content>
        <div class="label" v-if="userChanger.basis_changes">Основание для внесения изменений: {{userChanger.basis_changes}}</div>
        <div class="label" v-if="userChanger.user_name">Ответственный: {{userChanger.user_name}}</div>
        <div style="width: 100%">
        <DataTable
          :headers="headersChange"
          :items="changeData"
          :items-length="changeData.length"
          :hide-footer="true"
        />
        </div>
      </template>
    </dialog-component>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator'
import DataTable from "@/components/common/DataTable/DataTable.vue";
import AuthoritiesCardRequest from "@/views/Requests/components/CardRequest/CardRequest.vue";
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import { mapInnerForm } from '@/views/Requests/utils';

type Props = {
  information: any,
  status: string,
  isShowcase: boolean,
  changesData: any
}

@Component({
  name: 'card-request-information',
  components: { DataTable, AuthoritiesCardRequest, DialogComponent },
})

export default class CardRequestInformation extends Vue {
  @Prop({
    type: Boolean,
    default: () => false,
  })
  readonly isShowcase!: Props["isShowcase"];
  @Prop({
    type: Object,
    default: () => {},
  })
  readonly information!: Props["information"];
  @Prop({
    type: Object,
    default: () => {},
  })
  readonly changesData!: Props["changesData"];
  @Prop({
    type: String,
    default: () => '',
  })
  readonly status!: Props["status"];

  isShowCard = false;
  isShowChange = false;
  form: any = {}
  changeData=[];
  userChanger: any = {}
  rows =[];

  headers = [
    {
      text: 'Действия',
      value: 'actions'
    },
    {
      text: 'Наименование',
      value: 'type_name',
    },
    {
      text: 'Номер заявления',
      value: 'id',
    },
    {
      text: 'Дата подачи',
      value: 'request_date'
    },
    {
      text: 'Ответственный',
      value: 'user_name'
    },
    {
      text: 'Дата рассмотрения',
      value: 'approval_date'
    },
    {
      text: 'Статус',
      value: 'status_name'
    },
    {
      text: 'Комментарий',
      value: 'reject_reason'
    }
  ];

  headersChange = [
    {
      text: 'Сведения подлежащие изменению',
      value: 'item'
    },
    {
      text: 'Суть изменения',
      value: 'change_name',
    },
    {
      text: 'Старое значение',
      value: 'old_valuew'
    },
    {
      text: 'Новое значение',
      value: 'new_value'
    }
  ];

  mounted() {
    this.getListRequests(this.information.elevator_id);
  }

  async getListRequests(id) {
    const { elevator_request } = await this.$store.dispatch('organization/getListRequests', id);
    return this.rows = elevator_request;
  }

  closeCard () {
    this.isShowCard = false;
  }

  async showCard(id: string | null) {
    this.isShowCard = true;
    if (id) {
      const data = await this.$store.dispatch('elevator/getInfoElevator', id);
      this.form = {
        ...mapInnerForm(data),
      };

    }
  }

  async showChanges(id: string | null) {
    this.isShowChange = true;
    if (id) {
      const data = await this.$store.dispatch('elevator/getRequestChangesList', id);
      this.changeData = data;

      this.userChanger = this.rows.find((item: any) => {
        if(item.id === id) {
          return item
        }
      })
    }
  }

}
</script>

<style lang="scss" scoped>
  .iconTable {
    width: 16px;
    height: 16px;
    cursor: pointer;
  }
</style>