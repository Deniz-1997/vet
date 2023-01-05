<template>
  <v-col cols="12">
    <v-row>
      <v-col cols="12">
        <text-component class="font-weight-bold d-flex align-center" variant="span">
          <span>Сведения при изъятии</span>
        </text-component>
      </v-col>
      <v-col v-if="create || edit" cols="12">
        <button-component
          class="ml-0 btn-custom"
          size="micro"
          title="Выбрать"
          variant="primary"
          @click="isOpenModal = !isOpenModal"
        />
      </v-col>
      <template v-if="!!model.withdrawal.id">
        <v-col cols="12" md="6">
          <label-component label="Номер" />
          <text-component>
            {{ model.withdrawal.gw_number }}
          </text-component>
        </v-col>
        <v-col cols="12" md="6">
          <label-component label="Собственник" />
          <text-component>
            {{ model.withdrawal.owner.name }}
          </text-component>
        </v-col>

        <v-col cols="12" md="6">
          <label-component label="Дата формирования" />
          <text-component>
            {{ model.withdrawal.enter_date }}
          </text-component>
        </v-col>
        <v-col cols="12" md="6">
          <label-component label="Масса нетто, кг." />
          <text-component>
            {{ model.withdrawal.amount_kg }}
          </text-component>
        </v-col>

        <v-col cols="12" md="6">
          <label-component label="Вид с\х культуры" />
          <text-component>
            {{ model.withdrawal.okpd2.product_name_convert }}
          </text-component>
        </v-col>
        <v-col cols="12" md="6">
          <label-component label="Признак отсутствия документов" />
          <text-component>
            {{ model.withdrawal.is_not_doc ? 'Да' : 'Нет' }}
          </text-component>
        </v-col>

        <template v-if="!model.withdrawal.is_not_doc">
          <v-col v-if="model.withdrawal.sdiz_number" cols="12" md="6">
            <label-component label="Номер СДИЗ" />
            <text-component>
              {{ model.withdrawal.sdiz_number }}
            </text-component>
          </v-col>
          <v-col v-if="model.withdrawal.lot_number" cols="12" md="6">
            <label-component label="Номер партии" />
            <text-component>
              {{ model.withdrawal.lot_number }}
            </text-component>
          </v-col>
        </template>
      </template>
      <withdrawal-find-dialog
        :key="isOpenModal"
        :is-open="isOpenModal"
        target="prescription"
        @isOpenFindWithdrawal="onShowModal"
        @onSelect="selectWithdrawal"
      />
    </v-row>
    <v-row>
      <v-col cols="12">
        <text-component class="font-weight-bold d-flex align-center text-bo" variant="span">
          <span>Предписание</span>
        </text-component>
      </v-col>
      <v-col cols="12" md="4">
        <select-request-component
          :key="model.id"
          v-model="model.prescription_type_id"
          label="Вид предписания"
          :disabled="disabledForm"
          placeholder="Выберите вид предписания"
          :custom-items="rshn.typePrescription"
        />
      </v-col>

      <v-col cols="12" md="4">
        <input-component
          :key="model.id"
          v-model="model.gp_row_number"
          clearable
          :disabled="disabledForm"
          label="Номер документа"
          placeholder="Введите номер"
        />
      </v-col>

      <v-col cols="6" md="4">
        <UiDateInput
          v-model="model.enter_date"
          :disabled="disabledForm"
          :limit-to="today"
          :format="'DD.MM.YYYY'"
          label="Дата документа"
          placeholder="Выберите дату"
        />
      </v-col>

      <v-col cols="12" md="4">
        <select-request-component
          v-model="model.restrictions_text"
          label="Сведения об ограничениях действий с партией"
          :disabled="disabledForm"
          placeholder="Выберите сведение"
          :custom-items="rshn.typePrescriptionRestrictions"
        />
      </v-col>
      <v-col cols="12" md="4">
        <select-request-component
          v-model="model.restrictions_bin"
          :disabled="disabledForm"
          label="Изолированное хранение"
          placeholder="Сведения об изолированном хранении"
          clearable
          :custom-items="rshn.restrictionsBinList"
        />
      </v-col>

      <v-col cols="12" md="6">
        <label-component label="Территориальное управление органов гос. власти" />
        <text-component>
          {{ model.legal_operator.name }}
        </text-component>
      </v-col>
      <v-col cols="12" md="6">
        <label-component label="Должностное лицо выдавшее предписание" />
        <text-component>
          {{ model.operator.full_name }}
        </text-component>
      </v-col>
    </v-row>
  </v-col>
</template>

<script lang="ts">
import { Component, Watch, Mixins } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import WithdrawalFindDialog from '@/views/rshn/subcomponents/Dialog/WithdrawalFindDialog.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import { RshnFormsMix } from '@/utils/mixins/rshn/rshnForms';
import { RshnWithdrawalShort } from '@/models/Rshn/ShortModel/RshnWithdrawalShort.vue';

@Component({
  components: {
    ButtonComponent,
    WithdrawalFindDialog,
    TextComponent,
    LabelComponent,
    DialogComponent,
    SelectRequestComponent,
    CheckboxComponent,
    InputComponent,
    UiDateInput,
  },
})
export default class PrescriptionFroms extends Mixins(RshnFormsMix) {
  async created() {
    await this.loadNestedData();
  }

  async loadNestedData() {
    let id;
    if (this.detail) {
      id = this.model.gw_id;
    }
    if (this.create) {
      id = this.$route.query.withdrawal_id;
    }

    if (id) await this.loadWithdrawalById(Number(id));
    if (this.create && id) {
      this.model.gw_id = Number(id);
    }
  }

  @Watch('edit')
  async handleEditChange(v) {
    if (!v) {
      await this.loadNestedData();
    }
  }

  async selectWithdrawal(data: RshnWithdrawalShort) {
    this.model.withdrawal = data;
    this.model.gw_id = data.id;
  }

  @Watch('signActionProcessed')
  async onIsSignActionProcessedChange(v) {
    if (!v) {
      await this.loadNestedData();
      this.$emit('sign-action-processed');
    }
  }
}
</script>
