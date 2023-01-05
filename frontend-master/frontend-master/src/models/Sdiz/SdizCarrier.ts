import { DocsTransportsVueInterface } from '@/models/Sdiz/DocsTransports.vue';
import { constructByInterface } from '@/utils/construct-by-interface';
import { CarrierVueModel } from '@/models/Sdiz/Carrier.vue';
import { CarrierLocationModel } from '@/models/Sdiz/AddressPriority';

export interface SdizCarrierInterface {
  carrier_id: number | null;
  carrier: CarrierVueModel | null;
  locations: CarrierLocationModel[];
  doc_transports: DocsTransportsVueInterface[];
}

export class SdizCarrierModel implements SdizCarrierInterface {
  carrier_id: number | null = null;
  carrier: CarrierVueModel | null = new CarrierVueModel();
  locations: CarrierLocationModel[] = [];
  doc_transports: DocsTransportsVueInterface[] = [];

  constructor(o?: SdizCarrierInterface) {
    constructByInterface(o, this, { carrier: CarrierVueModel });

    if (!this.carrier?.subject_id) {
      this.carrier = null;
    }
  }
}
