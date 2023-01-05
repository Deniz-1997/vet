<template>
  <v-container>
    <slot name="hidden-body">
      <lot-form
        v-if="isDataSet"
        v-model="innerValue"
        :is-create="true"
        :name="name"
        :type="type"
        :is-elevator-lot="isElevatorLot"
        @quality-indicators-loading="setIsLoading"
        @error="setError"
      >
        <template #[`manufacture-field`]="{ onSearchUpdateMix, items, model, disabled }">
          <slot
            :items="items"
            :model="model"
            :disabled="disabled"
            :onSearchUpdateMix="onSearchUpdateMix"
            name="manufacture-field"
          />
        </template>

        <template #[`date-create`]="{ model, disabled, newKey }">
          <slot :model="model" :disabled="disabled" :newKey="newKey" name="date-create" />
        </template>

        <template #[`date-lot`]="{ model, disabled }">
          <slot :model="model" :disabled="disabled" name="date-lot" />
        </template>

        <template #[`table-lots-moved`]="{ isCreate, isDetail, isEdit, isLockFiltersFromLots, model, type }">
          <slot
            :is-create="isCreate"
            :is-detail="isDetail"
            :is-edit="isEdit"
            :is-lock-filters="isLockFiltersFromLots"
            :model="model"
            :type="type"
            name="table-lots-moved"
          />
        </template>

        <template #[`product-type-field`]="{ isDisabledElement, onSelectedTypeOfCrop, model, isActive }">
          <slot
            :is-disabled-element="isDisabledElement"
            :model="model"
            :type="type"
            :lot-type="typesLot"
            :new-key="newKeyFiltersLot"
            :store-lot-type="storeLotType"
            :on-selected-type-of-crop="onSelectedTypeOfCrop"
            name="product-type-field"
            :isActive="isActive"
          >
            <select-request-component
              :key="newKeyFiltersLot"
              v-model="model.okpd2_id"
              :disabled="
                (isDisabledElement && type === 'another-batch-grain' && !isDisabledOkpd2Lock) || type === 'field'
              "
              :is-active="isActive"
              label="Вид с/х культуры"
              :lot-type="typesLot"
              :store-lot-type="storeLotType"
              placeholder="Выберите вид с/х культуры"
              type="nsi-okpd2-msh"
              @onChange="onSelectedTypeOfCrop"
            />
          </slot>
        </template>

        <template #buttons>
          <v-col cols="12">
            <v-row class="ma-0" justify="end">
              <v-col class="right-align mb-3 mr-4" cols="12">
                <text-component class="text-caption text-center orange--text" variant="span">
                  {{ getFirstError }}
                </text-component>
              </v-col>
              <default-button
                class="mr-7"
                size="micro"
                title="В реестр партий"
                @click="$router.push({ name: innerValue.name_route_list })"
              />

              <default-button
                :disabled="getFirstError !== null || disableActions"
                :loading="preloader || isLoading"
                size="micro"
                title="Сформировать"
                variant="primary"
                @click="handleCreate"
              />
            </v-row>
          </v-col>
        </template>
      </lot-form>

      <v-overlay :value="isLoading" z-index="10">
        <v-progress-circular indeterminate size="64"></v-progress-circular>
      </v-overlay>
    </slot>
  </v-container>
</template>

<script lang="ts">
import { Component, Model, Prop, Watch } from 'vue-property-decorator';
import Lot from '@/views/Lot/Lot.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import LotForm from '@/views/Lot/components/Form.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import { currentDay } from '@/utils';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';
import { LotsPurposeEnum } from '@/utils/enums/lotsPurpose.enum';
import { LotPurposeVueModel } from '@/models/Lot/LotPurpose.vue';
import { LotType } from '@/utils/enums/LotType';
import TextComponent from '@/components/common/TextComponent.vue';
import { LotsMovedVueModel } from '@/models/Lot/LotsMoved.vue';

@Component({
  name: 'lot-create',
  components: {
    DefaultButton,
    LotForm,
    SelectRequestComponent,
    TextComponent,
  },
})
export default class LotCreate extends Lot {
  name = '';
  @Model('change', { type: Object }) value!: LotDataVueModel | LotGpbDataVueModel | LotElevatorDataVueModel;
  @Prop({
    type: String,
    default: 'Формирование партии зерна по результатам государственного мониторинга',
  })
  public nameFromField!: string;

  @Prop({ type: Boolean, default: false })
  isElevatorLot!: boolean;

  typesLot: object = {};
  storeLotType = '';
  model: any = this.innerValue;
  disableActions = false;
  isDataSet = false;

  get newKeyFiltersLot() {
    return Object.keys(this.typesLot).length;
  }

  get subjectId(): number {
    return this.$store.state.auth.user['subject'].subject_id;
  }

  get innerValue(): any {
    return this.value;
  }

  set innerValue(value: any) {
    this.$emit('change', value);
  }

  get getFirstError() {
    return this.getErrors.length > 0 ? this.getErrors.shift() : null;
  }

  // TODO вернуть проверку в модель, не в компонент
  get getErrors(): Array<string> {
    return this.model.getErrors(this.type);
  }

  @Watch('$route')
  async onChangeRoute() {
    await this.onSetDataByRouteName();
  }

  async created(): Promise<void> {
    await Promise.all([this.onSetDataByRouteName(), this.fetchOkpd2Msh(this.model.lotType)]);

    this.isDataSet = true;

    if (!this.$store.getters['nsi/getLotsTarget'].length) {
      await this.fetchLotsTarget();
    }

    if (!this.$store.getters['nsi/getLotsPurpose'].length) {
      await this.fetchLotsPurpose();
    }
  }

  async handleCreate(): Promise<void> {
    try {
      const data = this.value.getDataForCreate() as any;
      this.isLoading = true;

      if (this.model.getTypeModel() === 'LotElevator') data.is_elevator_page = true;
      await this.createActionMix({ type: this.type, data: data });
      this.isLoading = false;
    } catch (_e) {
      this.isLoading = false;
    }
  }

  @Watch('model.objects.sdiz_data.items')
  async checkPaperSdizValidNumber(newVal: any, oldVal: any): Promise<void> {
    this.isLoading = true;
    if (oldVal[0]?.lot_sp_number !== newVal[0]?.lot_sp_number && newVal[0]?.lot_sp_number) {
      const { response } = await this.$store.dispatch(this.value.reserve_number_apiendpoit, {
        data: {
          filter: {
            options: [
              {
                field: this.value.number_type,
                operator: '=',
                value: this.value.objects.sdiz_data?.items[0]?.lot_sp_number,
              },
            ],
          },
        },
      });

      this.model.valid_paper_sdiz_number = response.length > 0;
    }
    this.isLoading = false;
  }

  /**
   * Изменить состояние компонента и выставить enter_date в модели.
   * @param params включает: name, typesLot, storeLotType, type.
   */
  setParams(params: Array<any>): void {
    this.model.enter_date = currentDay();
    this.name = params[0];
    this.typesLot = params[1];
    this.storeLotType = params[2];
    this.type = params[3];
  }

  setParamsForGpbFromInProduct() {
    this.setParams([
      'Формирование партии продуктов переработки зерна при производстве',
      this.model.lotType,
      this.model.storeLotType,
      LotType.IN_PRODUCT,
    ]);
    this.model.manufacturer_id = this.subjectId;
  }

  setFromRouterParam(field: string, param: string) {
    const routeParam = this.$route.params[param];

    if (routeParam) this.model[field] = Number(routeParam);
  }

  async selectRouter(router): Promise<void> {
    if (router === this.model.create_from_field) {
      this.setParams([this.nameFromField, this.model.lotType, this.model.storeLotType, LotType.FIELD]);
    }
    if (router === this.model.create_from_another_batch) {
      this.setParams([
        this.model.name_from_another_batch,
        this.model.lotType,
        this.model.storeLotType,
        LotType.ANOTHER_BATCH_GRAIN,
      ]);
      await this.onLoadOtherLot();
    }
    if (router === this.model.create_from_residues) {
      this.setParams([
        this.model.name_from_residues,
        this.model.lotUSePeriod,
        this.model.storeLotPeriod,
        LotType.RESIDUES,
      ]);
      await this.onLoadLaboratoryData();
    }
    if (router === this.model.create_from_sdiz) {
      this.setParams([this.model.name_from_sdiz, this.model.lotType, this.model.storeLotType, LotType.SDIZ]);
      await this.onLoadOtherLot();
    }
    if (router === this.model.create_from_imported) {
      this.setParams([this.model.name_from_imported, this.model.lotType, this.model.storeLotType, LotType.IMPORTED]);
      try {
        this.model.objects.purpose = await this.dictionaryRecordByCode(
          'nsi-lots-purpose',
          LotsPurposeEnum.IMPORT_TO_RUSSIA,
          true
        );
      } catch (_e) {
        this.$emit('error');
      }
    }
    if (router === 'lots_gpb_create_from_in_product') {
      this.setParamsForGpbFromInProduct();
    }
  }

  async onSetDataByRouteName(): Promise<void> {
    this.model = new this.model.constructor();
    this.$emit('change', this.model);
    await this.selectRouter(this.$route.name);
  }

  setParamsOtherLot(response) {
    this.model = new this.model.constructor();
    this.model.current_location_id = response.current_location_id;
    this.model.objects.purpose = new LotPurposeVueModel(response.purpose);
    this.model.okpd2_id = response.okpd2_id;
    this.model.target_id = response.target_id;
    this.model.tnved_id = response.tnved_id || null;
    this.model.repository_id = response.repository_id || null;
    this.model.create_date = response.create_date || null;
    this.model.manufacturer_id = response.manufacturer_id || null;

    const lotMoved = new LotsMovedVueModel(response);
    if (this.model instanceof LotGpbDataVueModel) {
      lotMoved.gpb_id = response.id;
    }
    this.model.objects[this.model.movedField].push(lotMoved);

    if (this.type === LotType.ANOTHER_BATCH_GRAIN) {
      this.model.enter_date = currentDay();
      this.innerValue = this.model;
    }
  }

  async onLoadOtherLot(): Promise<void> {
    const id = this.$route.query.lot_id;
    if (typeof id !== 'undefined') {
      const { status, response } = await this.$store.dispatch(this.model.show_apiendpoit, id);
      if (status) this.setParamsOtherLot(response);
    }
  }

  async onLoadLaboratoryData(): Promise<void> {
    const laboratory_monitor_id = this.$route.query.laboratory_monitor_id;
    if (typeof laboratory_monitor_id !== 'undefined') {
      const { status, response } = await this.$store.dispatch('gosmonitoring/getList', {
        url: 'register/research-register',
        data: { filter: { id: laboratory_monitor_id } },
      });

      if (status && response.length > 0) {
        const item = response[0];
        this.model.target_id = item.target_id;
        this.model.okpd2_id = item.okpd2_id;
      }
    }
  }

  get isDisabledOkpd2Lock() {
    return (this.type = LotType.ANOTHER_BATCH_GRAIN && this.model.lotType.is_grain);
  }

  setError(v) {
    this.disableActions = v;
  }
}
</script>
