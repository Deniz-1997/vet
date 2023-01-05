import { HeaderItem } from '@/models/Rshn/Extends/DataRshn.vue';
import { AvailableFilters } from '@/models/Common/Default.vue';
import { RshnWithdrawalData } from '@/models/Rshn/Withdrawal/RshnWithdrawalData.vue';
import { StatusEnum } from '@/utils/enums/RshnEnums';

export class RshnWithdrawalShort extends RshnWithdrawalData {
  constructor(o?) {
    super(o);
    this.status_id = StatusEnum.SUBSCRIBED;
  }

  available_filters: AvailableFilters[] = [
    ...this.getAvailableFilters(),
    { name: 'sdiz_number', operator: '%%', type: 'text' },
    { name: 'gpb_lot_number', operator: '%%', type: 'text' },
    { name: 'gw_number', operator: '%%', type: 'text' },
    { name: 'status_id', type: 'string', value: () => StatusEnum.SUBSCRIBED },
    { name: 'gw_type', type: 'string' },
    { name: 'departures_location_transit_id', type: 'number' },
    { name: 'okpd2_id', type: 'number' },
    { name: 'owner_id', type: 'number' },
    { name: 'current_location_id', type: 'number' },
    { name: 'square', type: 'number' },
    { name: 'gpb_lot_number', operator: '%%', type: 'text' },
    { name: 'current_transit_location_id', type: 'number' },
    { name: 'shipper_id', type: 'number' },
    { name: 'transport_number', operator: '%%', type: 'text' },
    { name: 'container_number', operator: '%%', type: 'text' },
  ];
  headers: HeaderItem[] = [
    { text: 'Действия', value: 'button' },
    { text: '', value: 'actions' },
    { text: 'ID', value: 'id' },
    { text: 'Статус', value: 'status_translate' },
    { text: 'Дата формирования', value: 'enter_date' },
    { text: 'Вид СХ культуры или продукта переработки зерна ', value: 'okpd2.product_name_convert' },
    { text: 'Собственник ', value: 'owner.name' },
    { text: 'Масса нетто, кг ', value: 'amount_kg_mask' },
    { text: 'Номер СДИЗ ', value: 'sdiz_number' },
    { text: 'Номер Партии', value: 'gpb_lot_number' },
    { text: 'Площадь земельного участка (га)', value: 'square' },
    { text: 'Пункт отправления', value: 'departures_location_transit_id' },
    { text: 'Пункт назначения', value: 'current_transit_location_id' },
    { text: 'Перевозчик', value: 'shipper_id' },
    { text: 'Номер транспортного средства', value: 'transport_number' },
    { text: 'Номер контейнера', value: 'container_number' },
    { text: 'Место изъятия', value: 'current_check_location.address' },
  ];

  component_name = 'withdrawalShort';
}
