import { constructByInterface } from '@/utils/construct-by-interface';
import { setTranslateDocks, setTranslateStatus } from '@/utils/getTranslateStatus';
import { ManufacturerVueModel } from '@/models/Sdiz/Manufacturer.vue';
import { StatusEnum } from '@/utils/enums/RshnEnums';

export interface PrescriptionDocInterface {
  id: number | null;
  gpd_number: string | null;
  enter_date: string | null;
  gpd_type: number | null;
  content: string | null;
  prescription_id: number | null;
  status_id: StatusEnum | number | null;
  status_translate: string | null;
  operator_id: number | null;
  operator: ManufacturerVueModel;
}

export class PrescriptionDocData implements PrescriptionDocInterface {
  id: number | null = null;
  gpd_number: string | null = null;
  enter_date: string | null = null;
  gpd_type: number | null = null;
  content: string | null = null;
  prescription_id: number | null = null;
  status_id: StatusEnum | number | null = null;
  status_translate: string | null = null;
  gpd_type_translate: string | null = null;
  operator_id: number | null = null;
  operator: ManufacturerVueModel = new ManufacturerVueModel();
  action: any;
  entity_name = 'prescription_doc';

  update_apiendpoint = 'rshn/updatePrescriptionDoc';
  delete_apiendpoint = 'rshn/deletePrescriptionDoc';

  export_apiendpoint = 'rshn/withdrawal/prescription/doc/export';
  export_canceled_apiendpoint = 'rshn/withdrawal/prescription/doc/export/canceled';

  subscribe_service = 'rshn/withdrawal/prescription/doc/sign';
  cancel_service = 'rshn/withdrawal/prescription/doc/sign/canceled';

  constructor(o?: PrescriptionDocData) {
    if (o) constructByInterface(o, this);
    this.status_translate = setTranslateStatus(this.status_id);
    this.gpd_type_translate = setTranslateDocks(this.gpd_type);
  }
}
