import { ManufacturerVueModel } from '@/models/Sdiz/Manufacturer.vue';
import {
  setTranslatePrescriptionRestrictions,
  setTranslateRestrictionsBin,
  setTranslateStatus,
} from '@/utils/getTranslateStatus';
import { AvailableFilters } from '@/models/Common/Default.vue';
import { DataRshn, HeaderItem } from '@/models/Rshn/Extends/DataRshn.vue';
import { PrescriptionDocData } from '@/models/Rshn/Prescription/PrescriptionDocData';
import { RshnWithdrawalData } from '@/models/Rshn/Withdrawal/RshnWithdrawalData.vue';
import { RshnExpertiseData } from '@/models/Rshn/Expertise/RshnExpertiseData.vue';
import { EAction } from '@/models/roles';
import { getDateObject } from '@/utils/date';

export interface RshnPrescriptionDataVueInterface {
  id: number | null;
  gw_stay_transit_number: number | null | string;
  gw_id: number | null;
  gp_row_number: string | null;
  enter_date: string | null;
  prescription_type_id: number | null;
  restrictions_text: number | null;
  restrictions_bin: number | null;
  legal_operator_id: number | null;
  operator_id: number | null;
  status_id: number | null;
  docs: Array<PrescriptionDocData>;
}

export class RshnPrescriptionData extends DataRshn implements RshnPrescriptionDataVueInterface {
  component_name = 'prescription';
  entity_name = 'prescription';
  list_apiendpoint = 'rshn/getListPrescription';
  create_apiendpoint = 'rshn/createPrescription';
  show_apiendpoint = 'rshn/showPrescription';
  update_apiendpoint = 'rshn/updatePrescription';
  delete_apiendpoint = 'rshn/deletePrescription';
  export_apiendpoint = 'rshn/withdrawal/prescription/export';
  export_canceled_apiendpoint = 'rshn/withdrawal/prescription/export/canceled';
  create_link = 'rshn_prescription_create';
  detail_link = 'rshn_prescription_detail';
  cancel_link = 'rshn_prescription_list';

  name_route_list = 'rshn_prescription_list';

  subscribe_service = 'rshn/withdrawal/prescription/subscribe';
  cancel_service = 'rshn/withdrawal/prescription/cancel';

  view_data_privileges = EAction.CREATE_PRESCRIPTION;

  id: number | null = null;
  gw_id: number | null = null;
  gw_stay_transit_number: number | null | string = null;
  gp_row_number: string | null = null;
  enter_date: string | null = null;
  prescription_type_id: number | null = null;
  restrictions_text: number | null = null;
  restrictions_text_convert: string | null = null;
  restrictions_bin: number | null = null;
  restrictions_bin_convert: string | null = null;
  legal_operator_id: number | null = null;
  legal_operator: ManufacturerVueModel = new ManufacturerVueModel();
  operator_id: number | null = null;
  status_id: number | null = null;
  operator: ManufacturerVueModel = new ManufacturerVueModel();
  withdrawal: RshnWithdrawalData = new RshnWithdrawalData();
  docs: Array<PrescriptionDocData> = [];
  expertise: Array<RshnExpertiseData> = [];
  available_filters: AvailableFilters[] = [
    ...this.getAvailableFilters(),
    { name: 'gp_row_number', operator: '%%', type: 'text' },
    { name: 'status_id', type: 'string' },
    { name: 'prescription_type_id', type: 'number' },
    { name: 'restrictions_bin', type: 'number' },
    { name: 'operator_id', type: 'number' },
    { name: 'restrictions_text', type: 'text' },
  ];
  //TODO последняя строчка под вопросом
  headers: HeaderItem[] = [
    { text: 'Действия', value: 'actions' },
    { text: 'ID', value: 'id' },
    { text: 'Номер документа', value: 'gp_row_number' },
    { text: 'Статус', value: 'status_translate' },
    { text: 'Дата формирования', value: 'enter_date' },
    { text: 'Сведения об ограничениях действия с партией', value: 'restrictions_text_convert' },
    { text: 'Изолированное хранение', value: 'restrictions_bin_convert' },
    { text: 'Территориальное управление органа гос. Власти', value: 'legal_operator.name' },
    { text: 'Должностное лицо, выдавшее предписание', value: 'operator.full_name' },
    // { text: 'Признак отсутствия документов на партию зерна ', value: 'amount_kg_mask' },
  ];

  constructor(o?: RshnPrescriptionData) {
    super(o);
    this.init(o, {
      withdrawal: RshnWithdrawalData,
      operator: ManufacturerVueModel,
      legal_operator: ManufacturerVueModel,
      docs: PrescriptionDocData,
    });
    this.status_translate = setTranslateStatus(this.status_id);
    if (this.status_id !== null) {
      this.restrictions_bin_convert = setTranslateRestrictionsBin(this.restrictions_bin);
      this.restrictions_text = Number(this.restrictions_text);
      this.restrictions_text_convert = setTranslatePrescriptionRestrictions(this.restrictions_text);
    }
  }

  public getDataForCreateOrUpdate(): any {
    return {
      gw_id: this.gw_id,
      gw_stay_transit_number: this.gw_stay_transit_number,
      gp_row_number: this.gp_row_number,
      enter_date: this.enter_date,
      prescription_type_id: this.prescription_type_id,
      restrictions_text: this.restrictions_text,
      restrictions_bin: this.restrictions_bin,
      legal_operator_id: this.legal_operator_id,
    };
  }

  public createNewModel(response: any) {
    return new RshnPrescriptionData(response);
  }

  public createNewModelDop(response: any) {
    return new PrescriptionDocData(response);
  }

  public getNumber() {
    return this.id;
  }

  getArrayDop() {
    return this.docs;
  }

  get withdrawalEnterDate(): Date | null {
    return this.withdrawal?.enter_date ? getDateObject(this.withdrawal?.enter_date) : null;
  }

  public getErrors(): Array<string> {
    const errors: Array<string> = [];
    if (!this.gw_id) errors.push('Выберите изъятие');
    if (!this.gp_row_number) errors.push('Укажите номер документа');
    if (!this.enter_date) errors.push('Укажите дату');
    if (!this.prescription_type_id) errors.push('Выберите тип предписания');
    if (!this.restrictions_text) errors.push('Укажите ограничение');
    if (this.enter_date && this.withdrawalEnterDate && getDateObject(this.enter_date) < this.withdrawalEnterDate)
      errors.push('Дата формирования предписания не может предшествовать дате формирования изъятия');
    return errors;
  }
}
