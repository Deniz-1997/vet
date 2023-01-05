<template>
  <v-row no-gutters>
    <v-col cols="12" lg="9" md="12" sm="12" xl="6">
      <v-row class="mb-10">
        <lot-tables-quality-indicators
          v-model="qualityIndicators"
          :versions="model.historyVersions"
          :is-edit="isEdit || isCreate || isAmmend"
          :country-alpha="countryAlpha"
          :is-destination-country-needed="model.purposeCode === lotsPurposeEnum.EXPORT_FROM_RUSSIA"
          :okpd2-code="model.objects.okpd2.code"
          :purposes="model.qualityIndicatorPurposes"
          :is-restriction="true"
        />
      </v-row>
    </v-col>

    <v-col cols="12" lg="9" md="12" sm="12" xl="6">
      <v-row>
        <slot
          :is-create="isCreate"
          :is-detail="isDetail"
          :is-edit="isEdit"
          :is-lock-filters="isLockFiltersFromLots"
          :model="model"
          :type="type"
          name="table-lots-moved"
        >
          <lot-tables-lots-moved
            v-if="showLotMovedTable"
            v-model="model.lotsMoved"
            :is-create="isCreate"
            :is-detail="isDetail"
            :type-lot="type"
            :is-not-repository-filter="isNotRepositoryFilter"
            :is-edit="isEdit"
            :is-lock-filters="isLockFiltersFromLots"
            :lot="model"
            @onChangeAmountKg="$emit('onChangeAmountKg', $event)"
            @onDeleteLotMoved="$emit('onDeleteLotMoved', $event)"
            @onEditLotsMoved="$emit('onEditLotsMoved', $event)"
            @onFirstLotGrainIsSelect="$emit('onFirstLotGrainIsSelect', $event)"
            @onOpenFindLotModal="$emit('onOpenFindLotModal', $event)"
          />
        </slot>
        <lot-tables-paper-store
          v-if="type === LotType.SDIZ || model.objects.sdiz_data.items.length > 0"
          v-model="model.objects.sdiz_data.items"
          :title-table="model.lot_tables_paper_store_title"
          :is-create="isCreate"
          :is-detail="isDetail"
          :is-edit="isEdit"
          @onChangeAmountKg="$emit('onChangeAmountKg', $event)"
        />

        <lot-tables-lots-docs
          v-model="model.objects.docs"
          :is-create="isCreate"
          :is-edit="isEdit"
          :lot="model"
          :show-lot-moved-table="showLotMovedTable"
        />
      </v-row>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Component, Model, Prop, Watch } from 'vue-property-decorator';
import EditableTable from '@/components/common/Table/index.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import { mixins } from 'vue-class-component';
import { AdditionalMix } from '@/utils/mixins/additional';
import LotTablesQualityIndicators from '@/views/Lot/components/Subcomponents/Tables/LotTablesQualityIndicators.vue';
import TextComponent from '@/components/common/TextComponent.vue';
import LotTablesLotsMoved from '@/views/Lot/components/Subcomponents/Tables/LotTablesLotsMoved.vue';
import LotTablesPaperStore from '@/views/Lot/components/Subcomponents/Tables/LotTablesPaperStore.vue';
import LotTablesLotsDocs from '@/views/Lot/components/Subcomponents/Tables/LotTablesLotsDocs.vue';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { LotElevatorDataVueModel } from '@/models/Lot/Data/LotElevatorData.vue';
import { LotType } from '@/utils/enums/LotType';
import { PropType } from 'vue';
import { HistoryEntryModel } from '@/models/Lot/HistoryEntry';
import { LotsPurposeEnum } from '@/utils/enums/lotsPurpose.enum';

@Component({
  name: 'lot-detail-tables',
  components: {
    LotTablesLotsDocs,
    LotTablesPaperStore,
    LotTablesLotsMoved,
    TextComponent,
    LotTablesQualityIndicators,
    DialogComponent,
    InputComponent,
    EditableTable,
  },
})
export default class LotDetailTables extends mixins(AdditionalMix) {
  @Model('change', { type: Object }) model!: LotDataVueModel | LotGpbDataVueModel | LotElevatorDataVueModel;

  @Prop({ type: Boolean, default: false }) isEdit!: boolean;
  @Prop({ type: Boolean, default: false }) isCreate!: boolean;
  @Prop({ type: Boolean, default: false }) isAmmend!: boolean;
  @Prop({ type: Boolean, default: false }) isDetail!: boolean;
  @Prop({ type: Boolean, default: false }) isLockFiltersFromLots!: boolean;
  @Prop({ required: true }) type!: LotType | null;
  @Prop({ type: Object as PropType<HistoryEntryModel>, default: () => null })
  selectedVersion!: HistoryEntryModel | null;
  lotsPurposeEnum = LotsPurposeEnum;

  LotType = LotType;
  sdizLot: Array<any> = [];

  @Watch('type')
  onType() {
    if (
      !this.model.objects.sdiz_data.items.length &&
      typeof this.model.objects.sdiz_data.amount_kg !== 'undefined' &&
      typeof this.model.objects.sdiz_data.lot_sp_number !== 'undefined'
    ) {
      this.model.objects.sdiz_data.items.push({
        value: this.model.objects.sdiz_data.amount_kg,
        lot_number: this.model.objects.sdiz_data.lot_sp_number,
      });
    }
  }

  get showLotMovedTable(): boolean {
    return (
      this.model.objects.sdiz_data.lot_sp_number === undefined &&
      (this.type === LotType.ANOTHER_BATCH_GRAIN ||
        (this.model.objects.lots_moved.length > 0 && this.type !== LotType.SDIZ))
    );
  }

  get isNotRepositoryFilter() {
    return this.type === LotType.IN_PRODUCT;
  }

  async created() {
    if (this.type === LotType.SDIZ) {
      this.sdizLot.push({
        value: this.model.objects.sdiz_data.amount_kg,
        lot_number: this.model.objects.sdiz_data.lot_sp_number,
      });
    }
  }

  get countryAlpha() {
    return this.model.objects.country_destination?.code_alpha2 || null;
  }

  get qualityIndicators() {
    return this.selectedVersion ? this.selectedVersion.quality_indicators : this.model.objects.quality_indicators;
  }

  set qualityIndicators(v) {
    this.model.objects.quality_indicators = v;
  }
}
</script>
