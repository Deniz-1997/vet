<template>
  <v-col cols="12">
    <v-row>
      <v-col cols="12">
        <text-component class="font-weight-bold d-flex align-center" variant="span">
          <span :key="type">{{ titleSection }}</span>
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
      <template v-if="!!model.sdiz.id">
        <v-container>
          <v-row :key="model.sdiz.id">
            <v-col cols="12" md="6">
              <label-component label="Дата формирования" />
              <text-component>
                {{ model.sdiz.enter_date }}
              </text-component>
            </v-col>
            <v-col cols="12" md="6">
              <label-component label="Собственник" />
              <text-component>
                {{ model.sdiz.authorized_person }}
              </text-component>
            </v-col>
            <v-col cols="12" md="6">
              <label-component label="Вид с\х культуры или продукта переработки" />
              <text-component>
                {{ model.sdiz.getLot().objects.okpd2.product_name_convert }}
              </text-component>
            </v-col>
            <v-col cols="12" md="6">
              <label-component label="Номер партии зерна/продуктов переработки зерна" />
              <text-component>
                {{ model.sdiz.getLot().getNumber() }}
              </text-component>
            </v-col>
            <v-col cols="12" md="6">
              <label-component label="Номер СДИЗ" />
              <text-component>
                {{ model.sdiz_number }}
              </text-component>
            </v-col>
            <v-col cols="12" md="6">
              <label-component label="Масса нетто, кг." />
              <text-component>
                {{ model.sdiz.amount_kg_original_mask }}
              </text-component>
            </v-col>
          </v-row>
        </v-container>
      </template>
      <withdrawal-find-dialog
        v-if="isWithdrawal"
        :key="isOpenModal"
        :is-open="isOpenModal"
        @isOpenFindWithdrawal="onShowModal"
        @onSelect="selectWithdrawal"
      />
      <sdiz-find-dialog
        v-if="!isWithdrawal"
        :key="isOpenModal"
        :type="type"
        :is-open="isOpenModal"
        @isOpen="onShowModal"
        @onSelect="selectSdiz"
      />
    </v-row>

    <v-row v-if="showSignOfConformity" class="mt-3 mb-3">
      <v-col cols="12">
        <checkbox-component
          v-model="model.isNotConducted"
          :disabled="disabledForm"
          label="Экспертиза не проводилась"
          class="checkbox-v"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="4">
        <UiDateInput
          v-model="model.decision_date"
          :disabled="disabledForm || model.is_not_conducted"
          :limit-to="today"
          :format="'DD.MM.YYYY'"
          label="Дата решения о проведении экспертизы"
          placeholder="Выберите дату"
        />
      </v-col>

      <v-col cols="4">
        <input-component
          v-model="model.expertise_number"
          label="Номер решения о проведении экспертизы"
          placeholder="Введите номер решения о проведении экспертизы"
          :disabled="disabledForm || model.is_not_conducted"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="6" md="4">
        <UiDateInput
          v-model="model.selection_date"
          :disabled="disabledForm || model.is_not_conducted"
          :limit-to="today"
          :format="'DD.MM.YYYY'"
          label="Дата отбора образца"
          placeholder="Выберите дату"
        />
      </v-col>
      <v-col cols="6" md="4">
        <input-component
          v-model="model.selection_number"
          clearable
          :disabled="disabledForm || model.is_not_conducted"
          label="Номер пробы"
          placeholder="Введите номер"
        />
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="6" md="4">
        <UiDateInput
          v-model="model.departure_date"
          :disabled="disabledForm || model.is_not_conducted"
          :limit-to="today"
          :format="'DD.MM.YYYY'"
          label="Дата направления образца"
          placeholder="Выберите дату"
        />
      </v-col>
      <v-col cols="6" md="4">
        <UiDateInput
          v-model="model.expertise_date"
          :disabled="disabledForm || model.is_not_conducted"
          :limit-to="today"
          :format="'DD.MM.YYYY'"
          label="Дата проведения экспертизы"
          placeholder="Выберите дату"
        />
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <text-component class="font-weight-bold d-flex align-center text-bo" variant="span">
          <span>Информация о результатах экспертизы</span>
        </text-component>
      </v-col>
      <v-col cols="12" md="6">
        <lot-tables-quality-indicators
          v-model="model.quality_indicators"
          :is-edit="(edit || create) && model.expertise_type === expertiseType.WITHDRAWAL"
          :okpd2-code="okpd2Code"
          :purposes="qualityIndicatorsPurposes"
          :country-alpha="countryAlpha"
          :is-restriction="true"
          :load-properties-on-detail="false"
        />
      </v-col>
      <v-col cols="12">
        <text-area-component
          v-model="model.test_report"
          :disabled="disabledForm || model.is_not_conducted"
          label="Протокол испытания отобранного образца партии зерна"
          placeholder="Введите (Максимум 250 символов)"
        />
      </v-col>
      <v-col cols="12">
        <text-area-component
          v-model="model.conclusion"
          :disabled="disabledForm || model.is_not_conducted"
          label="Заключения по результатам экспертизы"
          placeholder="Введите (Максимум 250 символов)"
        />
      </v-col>

      <v-col v-if="showSignOfConformity" cols="12">
        <checkbox-component
          v-model="model.conformity_sign"
          :disabled="disabledForm"
          label="Соответствует требованиям страны импортера"
          class="checkbox-v"
        />
      </v-col>
    </v-row>
  </v-col>
</template>

<script lang="ts">
import { Component, Mixins, Watch } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import CheckboxComponent from '@/components/common/inputs/CheckboxComponent.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import WithdrawalFindDialog from '@/views/rshn/subcomponents/Dialog/WithdrawalFindDialog.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import { ExpertiseEnum, ExpertiseSdizType } from '@/utils/enums/RshnEnums';
import TextAreaComponent from '@/components/common/inputs/TextAreaComponent.vue';
import LotTablesQualityIndicators from '@/views/Lot/components/Subcomponents/Tables/LotTablesQualityIndicators.vue';
import SdizFindDialog from '@/views/rshn/subcomponents/Dialog/SdizFindDialog.vue';
import { RshnFormsMix } from '@/utils/mixins/rshn/rshnForms';
import { SdizShortVue } from '@/models/Rshn/ShortModel/SdizShort.vue';
import { RshnWithdrawalShort } from '@/models/Rshn/ShortModel/RshnWithdrawalShort.vue';
import { loadQualityIndicatorsByParams, mergeQualityIndicators } from '@/utils/qualityIndicators';
import { CountryDestinationModel } from '@/models/Lot/CountryDestination';
import omit from 'lodash/omit';
import { IndicatorPurposeEnum } from '@/utils/enums/indicatorPurpose.enum';

@Component({
  components: {
    SdizFindDialog,
    LotTablesQualityIndicators,
    TextAreaComponent,
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
export default class ExpertiseForms extends Mixins(RshnFormsMix) {
  declare type: ExpertiseEnum;

  expertiseType = ExpertiseEnum;

  get qualityIndicatorsPurposes() {
    return this.isWithdrawal ? [IndicatorPurposeEnum.LOT_CREATION] : [IndicatorPurposeEnum.IMPORT_OR_EXPORT];
  }

  async selectWithdrawal(data: RshnWithdrawalShort) {
    this.model.withdrawal = data;
    this.model.gw_id = data.id;
    this.model.quality_indicators = await this.fetchQualityIndicators(data.okpd2.code);
  }

  async selectSdiz(data: { sdiz: SdizShortVue; sdizType: ExpertiseSdizType }) {
    const { sdiz, sdizType } = data;

    if (sdizType === ExpertiseSdizType.SDIZ) {
      this.model.sdiz_id = sdiz.id;
    } else {
      this.model.gpb_sdiz_id = sdiz.id;
    }

    this.model.sdiz = sdiz;
    this.model.sdiz_number = sdiz.sdiz_number;

    await this.setSdizLotData(true);
  }

  async fetchQualityIndicators(code) {
    return await loadQualityIndicatorsByParams({
      okpd2: code,
      purposes: this.qualityIndicatorsPurposes,
      country_alpha2: this.countryAlpha,
    });
  }

  get titleSection() {
    return this.type === ExpertiseEnum.WITHDRAWAL ? 'Сведения при изъятии' : 'Сведения о СДИЗ';
  }

  get showSignOfConformity() {
    return [ExpertiseEnum.IMPORT, ExpertiseEnum.EXPORT].includes(this.type);
  }

  get isWithdrawal() {
    return this.type === ExpertiseEnum.WITHDRAWAL;
  }

  get countryAlpha() {
    return this.type === ExpertiseEnum.EXPORT
      ? this.model.sdiz.getLot().objects.country_destination.code_alpha2 ?? 'RU'
      : 'RU';
  }

  async loadNestedData() {
    if (this.detail && this.model.attachedSdizType !== ExpertiseSdizType.NONE) {
      await this.loadSdiz();
      await this.setSdizLotData();
    } else {
      await this.handleLoadWithdrawal();
    }
  }

  async loadSdiz() {
    if (!this.model.attachedSdizId) return;

    try {
      const { status, response } = await this.$store.dispatch(
        this.model.getSdizShowService(),
        this.model.attachedSdizId
      );

      if (!status || !response?.id) throw new Error();

      this.model.sdiz = new SdizShortVue(response);
    } catch (_e) {
      this.$service.notify.push('error', { text: 'Ошибка при получении данных о СДИЗ' });
    }
  }

  // eslint-disable-next-line max-lines-per-function
  async setSdizLotData(setQuailtyIndicatorsFromLot = false) {
    try {
      let qualityIndicators;

      const { status, response } = await this.$store.dispatch(
        this.model.sdiz.getLotShowService(),
        this.model.sdiz.getLot().id
      );

      if (!status) throw new Error();

      this.model.sdiz.getLot().objects.country_destination = new CountryDestinationModel(response.country_destination);

      if (setQuailtyIndicatorsFromLot) {
        qualityIndicators = response.quality_indicators.map((e) => omit(e, 'id'));

        if (Array.isArray(qualityIndicators)) {
          qualityIndicators = qualityIndicators.map((e) => {
            e.valueTo = 0;
            e.valueFrom = 0;

            return e;
          });
        }
      } else {
        qualityIndicators = this.model.quality_indicators;
      }

      const actualIndicators = await this.fetchQualityIndicators(response.okpd2.code);

      this.model.quality_indicators = mergeQualityIndicators(qualityIndicators, actualIndicators, [
        'valueTo',
        'valueFrom',
        'type',
        'measure',
      ]);
    } catch (_e) {
      this.$service.notify.push('error', { text: 'Ошибка при получении данных о партии из СДИЗ' });
    }
  }

  async handleLoadWithdrawal() {
    let withdrawalId;
    if (this.detail) {
      withdrawalId = this.model.gw_id;
    } else {
      withdrawalId = this.$route.query.withdrawal_id;
    }
    if (withdrawalId) {
      await this.loadWithdrawalById(Number(withdrawalId));
    }

    if (withdrawalId && this.create) {
      this.model.quality_indicators = await this.fetchQualityIndicators(this.model.withdrawal.okpd2.code);
    }

    if (withdrawalId && (this.edit || this.detail)) {
      const actualIndicators = await this.fetchQualityIndicators(this.model.withdrawal.okpd2.code);

      this.model.quality_indicators = mergeQualityIndicators(actualIndicators, this.model.quality_indicators, [
        'value',
      ]);
    }

    if (this.create && withdrawalId) {
      this.model.gw_id = Number(withdrawalId);
    }
  }

  async created() {
    await this.loadNestedData();
  }

  @Watch('edit')
  async handleEditChange(v) {
    if (!v) {
      await this.loadNestedData();
    }
  }

  get okpd2Code() {
    return this.type === ExpertiseEnum.WITHDRAWAL
      ? this.model.withdrawal.okpd2?.code || null
      : this.model.sdiz.getLot().objects.okpd2?.code || null;
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
