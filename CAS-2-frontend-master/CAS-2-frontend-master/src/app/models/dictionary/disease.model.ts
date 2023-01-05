import {constructByInterface} from '../../utils/construct-by-interface';
import {KindDiseaseRelationInterface, KindDiseaseRelationModel} from './kind-disease-relation.mode';
import {VaccineInterface, VaccineModel} from './vaccine.model';
import {
  propertyesType,
  PropertyViewObjectType,
  PropertyViewType
} from '../../modules/shared/decorators/property-type.decorator';

export interface DiseaseInterface {
  id: number;
  name: string;
  vaccines: Array<VaccineInterface>;
  kindDiseaseRelations: Array<KindDiseaseRelationInterface>;
  createdAt: string;
  isInvalid: boolean;
  updatedAt: string;
}

export class DiseaseModel implements DiseaseInterface {
    id: number;
    @propertyesType({type: PropertyViewType.INPUT_STRING, title: 'Наименование', col: 6, required: true})
    name: string;
    @propertyesType({type: PropertyViewType.MULTISELECT, col: 6, title: 'Вакцинации', required: false, crudType: 'DictionaryVaccine',
    objectType: PropertyViewObjectType.ARRAY_OBJECT, objectName: 'name'})
    vaccines: Array<VaccineModel>;
    kindDiseaseRelations: Array<KindDiseaseRelationModel>;
    createdAt: string;
    isInvalid: boolean;
    updatedAt: string;
  @propertyesType({type: PropertyViewType.FIELDS,
    fields: {
      0: 'id',
      1: 'name',
      'vaccines': ['name', 'id']}
  })
  field: object;

  constructor(o?: DiseaseInterface) {
    constructByInterface(o, this);
  }
}
