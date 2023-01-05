import {constructByInterface} from '../../utils/construct-by-interface';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';

export interface ReferenceNotificationsChannelInterface {
  id: number;
  name: string;
}

export class ReferenceNotificationsChannelModel {
  'id': number;
  @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 12, required: true, })
  'name': string;

  constructor(o?: ReferenceNotificationsChannelInterface) {
    if (o) {
      constructByInterface(o, this);
    }
  }
}
