import { constructByInterface } from '@/utils/construct-by-interface';
import { setTranslateRestrictions, setTranslateStatusRestriction } from '@/utils/getTranslateStatus';
import { ManufacturerVueModel } from '@/models/Sdiz/Manufacturer.vue';
import { RestrictionsEnum, StatusEnum } from '@/utils/enums/RshnEnums';

export interface RestrictionsDataInterface {
  id: number | null;
  enter_date: string | null;
  date_end: string | null;
  sdiz_number: string | null;
  batch_type: string | null;
  gpb_lot_number: string | null;
  restriction_type: RestrictionsEnum | number | null;
  status_id: StatusEnum | string | null;
  status_translate: string | null;
  restriction_type_translate: string | null;
  operator_id: number | null;
  gw_id: number | null;
  operator: ManufacturerVueModel;
}

export class RestrictionsData implements RestrictionsDataInterface {
  id: number | null = null;
  enter_date: string | null = null;
  date_end: string | null = null;
  sdiz_number: string | null = null;
  batch_type: string | null = null;
  gpb_lot_number: string | null = null;
  gw_id: number | null = null;
  restriction_type: RestrictionsEnum | number | null = null;
  restriction_type_translate: string | null = null;
  status_id: StatusEnum | number | null = null;
  status_translate: string | null = null;
  operator_id: number | null = null;
  operator: ManufacturerVueModel = new ManufacturerVueModel();
  action: any;
  entity_name = 'restriction';

  update_apiendpoint = 'rshn/updateWithdrawalRestriction';
  delete_apiendpoint = 'rshn/deleteWithdrawalRestriction';

  export_apiendpoint = 'rshn/withdrawal/restriction/export';
  export_canceled_apiendpoint = 'rshn/withdrawal/restriction/export/canceled';
  export_takeoff_apiendpoint = 'rshn/withdrawal/restriction/export/takeoff';

  subscribe_service = 'rshn/withdrawal/restriction/subscribe';
  takeoff_service = 'rshn/withdrawal/restriction/takeoff';
  cancel_service = 'rshn/withdrawal/restriction/cancel';

  public getDataForCreateOrUpdate(): any {
    const { gw_id, restriction_type, operator_id, enter_date } = this;

    return {
      gw_id,
      restriction_type,
      operator_id,
      enter_date,
    };
  }

  constructor(o?: RestrictionsData) {
    if (o) constructByInterface(o, this);
    this.status_translate = setTranslateStatusRestriction(this.status_id);
    this.restriction_type_translate = setTranslateRestrictions(this.restriction_type);
  }
}
