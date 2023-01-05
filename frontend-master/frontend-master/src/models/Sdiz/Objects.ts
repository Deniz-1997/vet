import { constructByInterface } from '@/utils/construct-by-interface';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import { OperationsVueModel } from '@/models/Sdiz/Operations.vue';
import { DocsTransportsOtherVueModel } from '@/models/Sdiz/DocsTransportsOther.vue';
import { SellerVueModel } from '@/models/Sdiz/Seller.vue';
import { BuyerVueModel } from '@/models/Sdiz/Buyer.vue';
import { ConsigneeVueModel } from '@/models/Sdiz/Consignee.vue';
import { ShipperVueModel } from '@/models/Sdiz/Shipper.vue';
import { OwnerModel } from '@/models/Sdiz/Owner';
import { StorageAgreementVueModel } from '@/models/Sdiz/StorageAgreement.vue';
import { DocsOtherVueModel } from '@/models/Sdiz/DocsOther.vue';
import { DocsAktVueModel } from '@/models/Sdiz/DocsAkt.vue';
import { StatusModelVue } from '@/models/Sdiz/StatusModel';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { SdizExtinguishCreateVueModel } from '@/models/Sdiz/SdizExtinguishCreate';
import { SdizExtinguishVueModel } from '@/models/Sdiz/SdizExtinguish';
import { SdizExtinguishRefusalModel } from '@/models/Sdiz/SdizExtinguishRefusal';

export interface ObjectsVueInterface {
  lot: LotDataVueModel;

  operations: OperationsVueModel;

  sdiz_status: StatusModelVue;

  extinguishs: SdizExtinguishVueModel[];
  extinguish_refusals: SdizExtinguishRefusalModel[];

  docs_transports_other: DocsTransportsOtherVueModel[];
  docs_other: DocsOtherVueModel[];
  docs_akt: DocsAktVueModel[];

  owner: OwnerModel;
  seller: SellerVueModel;
  buyer: BuyerVueModel;
  consignee: ConsigneeVueModel;
  shipper: ShipperVueModel;

  consignee_repository: ConsigneeVueModel;
  shipper_repository: ShipperVueModel;
  storage_agreement: StorageAgreementVueModel;
  sdiz_status_translate: string | null;
}

export class ObjectsVueModel implements ObjectsVueInterface {
  lot: LotDataVueModel = new LotDataVueModel();
  gpb: LotGpbDataVueModel = new LotGpbDataVueModel();
  extinguish: SdizExtinguishCreateVueModel = new SdizExtinguishCreateVueModel();

  operations: OperationsVueModel = new OperationsVueModel();

  sdiz_status: StatusModelVue = new StatusModelVue();
  sdiz_status_translate: string | null = null;

  docs_transports_other: DocsTransportsOtherVueModel[] = [];
  docs_other: DocsOtherVueModel[] = [];
  docs_akt: DocsAktVueModel[] = [];
  extinguishs: SdizExtinguishVueModel[] = [];
  extinguish_refusals: SdizExtinguishRefusalModel[] = [];

  owner: OwnerModel = new OwnerModel();
  seller: SellerVueModel = new SellerVueModel();
  buyer: BuyerVueModel = new BuyerVueModel();
  consignee: ConsigneeVueModel = new ConsigneeVueModel();
  shipper: ShipperVueModel = new ShipperVueModel();

  consignee_repository: ConsigneeVueModel = new ConsigneeVueModel();
  shipper_repository: ShipperVueModel = new ShipperVueModel();
  storage_agreement: StorageAgreementVueModel = new StorageAgreementVueModel();

  constructor(o?) {
    if (o !== undefined) {
      constructByInterface(
        o,
        this,
        {
          storage_agreement: StorageAgreementVueModel,
          lot: LotDataVueModel,
          gpb: LotGpbDataVueModel,
          sdiz_status: StatusModelVue,
          operations: OperationsVueModel,
          docs_other: DocsOtherVueModel,
          docs_akt: DocsAktVueModel,
          extinguishs: SdizExtinguishVueModel,
          extinguish_refusals: SdizExtinguishRefusalModel,
          owner: OwnerModel,
        },
        true
      );
      this.setTranslateStatus();

      this.operations = new OperationsVueModel(o);

      this.extinguishs.sort((a, b) => b.operation_date - a.operation_date);
    }
  }
  setTranslateStatus() {
    switch (this.sdiz_status.id) {
      case 1:
        this.sdiz_status_translate = 'Проект';
        break;

      case 2:
        this.sdiz_status_translate = 'Оформлен';
        break;

      case 3:
        this.sdiz_status_translate = 'Погашено';
        break;

      case 4:
        this.sdiz_status_translate = 'Аннулирован';
        break;

      case 5:
        this.sdiz_status_translate = 'Оформлен и подтвержден';
        break;
    }
  }
}
