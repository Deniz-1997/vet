import { HeaderItem } from '@/models/Rshn/Extends/DataRshn.vue';
import { AvailableFilters } from '@/models/Common/Default.vue';
import moment from 'moment';
import { Sdiz } from '@/models/Sdiz/Extends/Sdiz.vue';
import { ExpertiseSdizType } from '@/utils/enums/RshnEnums';
import { applyMask } from '@/components/common/inputs/mask/decimalNumberMask';

export class SdizShortVue extends Sdiz {
  sdiz_number: string | null = null;
  sdiz_gpb_number: string | null = null;
  list_apiendpoint = 'sdiz/getList';
  lot_number: string | number | null = null;
  lotNumber = 'lot_number';
  lotAmountKg = 'lot_amount_kg_mask';
  okpd2_id: number | null = null;
  entity_name = 'sdiz_short';
  name_route_list = '';
  available_filters: AvailableFilters[] = [
    ...this.getAvailableFilters(),
    {
      name: 'sdiz_number',
      operator: '%%',
      type: 'text',
      value: () => this[this.getSdizNumberField(this.sdiz_type as number)],
    },
    {
      name: 'sdiz_gpb_number',
      operator: '%%',
      type: 'text',
      value: () => this[this.getSdizNumberField(this.sdiz_type as number)],
    },
    { name: 'status_id', type: 'number', operator: '=', value: () => 2 },
    {
      name: 'authorized_person',
      operator: '=',
      type: 'number',
      key: 'owner_id',
    },
    { name: 'shipper_id', type: 'number', operator: '=' },
    { name: 'consignee_id', type: 'number', operator: '=' },
    { name: 'shipper_location_id', type: 'number', operator: '=' },
    { name: 'consignee_location_id', type: 'number', operator: '=' },
    {
      name: 'objects',
      type: 'objects',
      child: {
        gpb: [
          {
            name: 'amount_kg_from',
            operator: '>=',
            key: 'amount_kg',
            type: 'number',
          },
          {
            name: 'amount_kg_to',
            operator: '<=',
            key: 'amount_kg',
            type: 'number',
          },
          { name: 'purposeCode', key: 'purpose.code' },
          {
            name: 'okpd2Code',
            key: 'okpd2.code',
          },
        ],

        lot: [
          {
            name: 'amount_kg_from',
            operator: '>=',
            key: 'amount_kg',
            type: 'number',
          },
          {
            name: 'amount_kg_to',
            operator: '<=',
            key: 'amount_kg',
            type: 'number',
          },
          { name: 'purposeCode', key: 'purpose.code' },
          {
            name: 'okpd2Code',
            key: 'okpd2.code',
          },
        ],
      },
    },
  ];

  headers: HeaderItem[] = [];

  constructor(o?: any) {
    super(o);
    this.init(o);
    this.lot_number = this.objects.lot.lot_number;
    this.headers = this.getHeader();

    this.amount_kg_original_mask = applyMask(this.amount_kg_original, true);
  }

  getAvailableFilters(): any[] {
    return [
      {
        name: 'date_from',
        operator: '>=',
        key: 'enter_date',
        value: (v) => moment(v, 'DD.MM.YYYY').startOf('day').format('DD.MM.YYYY'),
      },
      {
        name: 'date_to',
        operator: '<=',
        key: 'enter_date',
        value: (v) => moment(v, 'DD.MM.YYYY').endOf('day').format('DD.MM.YYYY'),
      },
    ];
  }

  get isGpb(): boolean {
    return !!this.objects.gpb.id;
  }

  /** Ссылка на объект партии / партии ППЗ */
  getLot() {
    return this.isGpb ? this.objects.gpb : this.objects.lot;
  }

  setLot(v: any) {
    if (this.isGpb) {
      this.objects.gpb = v;
    } else {
      this.objects.lot = v;
    }
  }

  getLotShowService() {
    return this.isGpb ? 'lot/showGpb' : 'lot/show';
  }

  getNumberLot() {
    return this.getLot().getNumber();
  }

  getHeader() {
    return [
      { text: 'Действия', value: 'button' },
      { text: '', value: 'actions' },
      { text: 'ID', value: 'id' },
      { text: 'Номер СДИЗ', value: 'sdiz_number' },
      { text: 'Операция', value: 'objects.operations.types' },
      { text: 'Дата создания', value: 'enter_date' },
      { text: 'Уполномоченное лицо', value: 'authorized_person' },
      { text: 'Масса нетто, кг ', value: this.lotAmountKg },
      { text: 'Номер партии', value: this.lotNumber },
    ];
  }

  getLotType(type: ExpertiseSdizType | number): object {
    return type === ExpertiseSdizType.SDIZ ? { is_grain: true } : { is_product: true };
  }

  /**
   * Возвращает объект партии из Сдиз
   * @param type 0 | 1
   */
  getLotObject(type: ExpertiseSdizType) {
    return type === ExpertiseSdizType.SDIZ ? this.objects.lot : this.objects.gpb;
  }

  getLotObjectKey(type: ExpertiseSdizType) {
    return type === ExpertiseSdizType.SDIZ ? 'lot' : 'gpb';
  }

  changeType(type: ExpertiseSdizType) {
    let apiEndPoint;
    let lotNumber;
    let lotAmountKg;
    let detail_link;
    switch (type) {
      case ExpertiseSdizType.GPB_SDIZ:
        apiEndPoint = 'sdiz/getListForGpb';
        lotNumber = 'objects.gpb.gpb_number';
        lotAmountKg = 'objects.gpb.amount_kg_mask';
        detail_link = 'sdiz_gpb_detail';
        this.objects.lot.okpd2Code = null;
        this.objects.lot.amount_kg_to = null;
        this.objects.lot.amount_kg_from = null;
        this.objects.lot.objects.purpose = null;
        break;
      default:
        apiEndPoint = 'sdiz/getList';
        lotNumber = 'lot_number';
        lotAmountKg = 'objects.lot.amount_kg_mask';
        detail_link = 'sdiz_detail';
        this.objects.gpb.okpd2Code = null;
        this.objects.gpb.amount_kg_to = null;
        this.objects.gpb.amount_kg_from = null;
        this.objects.gpb.objects.purpose = null;
        break;
    }
    this.sdiz_type = type;
    this.list_apiendpoint = apiEndPoint;
    this.sdiz_number = null;
    this.sdiz_gpb_number = null;
    this.lotNumber = lotNumber;
    this.lotAmountKg = lotAmountKg;
    this.detail_link = detail_link;
    this.headers = this.getHeader();
  }

  createNewModel(response: any) {
    return new SdizShortVue(response);
  }

  getSdizNumberField(sdizType: ExpertiseSdizType) {
    return sdizType === ExpertiseSdizType.SDIZ ? 'sdiz_number' : 'sdiz_gpb_number';
  }

  get component_name() {
    return this.sdiz_type === ExpertiseSdizType.GPB_SDIZ ? 'sdizShortGpb' : 'sdizShort';
  }

  set component_name(_v) {
    return;
  }

  get detail_link() {
    return this.sdiz_type === ExpertiseSdizType.GPB_SDIZ ? 'sdiz_gpb_detail' : 'sdiz_detail';
  }

  set detail_link(_v) {
    return;
  }
}
