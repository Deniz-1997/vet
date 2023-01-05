import { constructByInterface } from '@/utils/construct-by-interface';
import { AddressFiasVueModel } from '@/models/Gosmonitoring/AddressFias.vue';
import { QualityIndicatorsVueModel } from '@/models/Lot/QualityIndicators.vue';
import { DocsVueModel } from '@/models/Lot/Docs.vue';
import { LotsMovedVueModel } from '@/models/Lot/LotsMoved.vue';
import { LaboratoryMonitorVueModel } from '@/models/Lot/LaboratoryMonitor.vue';
import { LotTargetVueModel } from '@/models/Lot/LotTarget.vue';
import { LotPurposeVueModel } from '@/models/Lot/LotPurpose.vue';
import { SdizDataVueModel } from '@/models/Lot/SdizData.vue';
import { DebitVueModel } from '@/models/Lot/Debit.vue';
import { ManufacturerVueModel } from '@/models/Sdiz/Manufacturer.vue';
import { Okpd2VueModel } from '@/models/Sdiz/Okpd2.vue';
import { LotProductTypeVueModel } from '@/models/Lot/LotProductType.vue';
import { CountryDestinationModel } from '@/models/Lot/CountryDestination';
import { HistoryEntryModel } from '@/models/Lot/HistoryEntry';

export type ObjectsLotVueInterface = {
  current_location: AddressFiasVueModel;
  origin_location: AddressFiasVueModel;
  laboratory_monitor: LaboratoryMonitorVueModel;
  target: LotTargetVueModel;
  purpose: LotPurposeVueModel;
  okpd2: Okpd2VueModel;
  trc_product_type: LotProductTypeVueModel;
  sdiz_data: SdizDataVueModel;
  quality_indicators: QualityIndicatorsVueModel[];
  versions: HistoryEntryModel[];
  lots_moved: LotsMovedVueModel[];
  gpb_moved: LotsMovedVueModel[];
  docs: DocsVueModel[];
  owner: ManufacturerVueModel;
  repository: ManufacturerVueModel;
  country_destination: CountryDestinationModel;
};

export class ObjectsLotVueModel implements ObjectsLotVueInterface {
  repository: ManufacturerVueModel = new ManufacturerVueModel();
  owner: ManufacturerVueModel = new ManufacturerVueModel();
  current_location: AddressFiasVueModel = new AddressFiasVueModel();
  origin_location: AddressFiasVueModel = new AddressFiasVueModel();
  laboratory_monitor: LaboratoryMonitorVueModel = new LaboratoryMonitorVueModel();
  target: LotTargetVueModel = new LotTargetVueModel();
  purpose: LotPurposeVueModel = new LotPurposeVueModel();
  okpd2: Okpd2VueModel = new Okpd2VueModel();
  trc_product_type: LotProductTypeVueModel = new LotProductTypeVueModel();
  sdiz_data: SdizDataVueModel = new SdizDataVueModel();
  country_destination: CountryDestinationModel = new CountryDestinationModel();
  debits: DebitVueModel[] = [];
  quality_indicators: QualityIndicatorsVueModel[] = [];
  docs: DocsVueModel[] = [];
  lots_moved: LotsMovedVueModel[] = [];
  previous_lots_moved: LotsMovedVueModel[] = [];
  gpb_moved: LotsMovedVueModel[] = [];
  previous_gpb_moved: LotsMovedVueModel[] = [];
  versions: HistoryEntryModel[] = [];

  constructor(o?) {
    if (o !== undefined) {
      constructByInterface(
        o,
        this,
        {
          sdiz_data: SdizDataVueModel,
          docs: DocsVueModel,
          debits: DebitVueModel,
          lots_moved: LotsMovedVueModel,
          target: LotTargetVueModel,
          purpose: LotPurposeVueModel,
          okpd2: Okpd2VueModel,
          trc_product_type: LotProductTypeVueModel,
          quality_indicators: QualityIndicatorsVueModel,
          laboratory_monitor: LaboratoryMonitorVueModel,
          gpb_moved: LotsMovedVueModel,
          country_destination: CountryDestinationModel,
          versions: HistoryEntryModel,
        },
        true
      );
      this.quality_indicators.sort((a: any, b: any) => {
        if (typeof a.name !== 'undefined' && typeof b.name !== 'undefined') {
          return a.name.localeCompare(b.name);
        }
      });

      this.previous_lots_moved = this.lots_moved;
      this.previous_gpb_moved = this.gpb_moved;
    }
  }
}
