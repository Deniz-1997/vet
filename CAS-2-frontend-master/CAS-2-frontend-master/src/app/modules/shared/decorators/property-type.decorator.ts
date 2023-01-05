import 'reflect-metadata';

const formatMetadataKey = Symbol('propertyDataView');
const metadataKey = Symbol('propertiesList');

export function getPropertyesType(target: any, propertyKey: string): void {
  return Reflect.getMetadata(formatMetadataKey, target, propertyKey);
}

export function propertyesType(propertyData: PropertyData): Function {
  return (target: object, propertyKey: string): void => {
    propertyData.name = propertyKey;
    Reflect.defineMetadata(formatMetadataKey, propertyData, target, propertyKey);
    return registerProperty(target, propertyKey);
  };
}

function registerProperty(target: object, propertyKey: string): void {
  let properties: Array<string> = Reflect.getMetadata(metadataKey, target);
  if (properties) {
    properties.push(propertyKey);
  } else {
    properties = [propertyKey];
    Reflect.defineMetadata(metadataKey, properties, target);
  }
}

export function getAllProperties(origin: object): object {
  const properties: Array<string> = Reflect.getMetadata(metadataKey, origin);
  return properties;
}

export enum PropertyViewType {
  INPUT_STRING, CHECK_BOX, INPUT_INT, AUTOCOMPLETE, SELECT, DATE, DADATA_FULL_NAME, MULTISELECT, FIELDS
}
export enum PropertyViewObjectType {
  OBJECT, ARRAY_OBJECT, ANY, BOOLEAN
}

export class PropertyData {
  name?: string;
  enum?: Array<string> = [];
  label?: string;
  title?: string;
  linkString?: string;
  required ? = true;
  type: PropertyViewType;
  objectType?: PropertyViewObjectType;
  objectName?: string;
  fields?: object;
  col?: number;
   // Если использовать СrudType, то ошибка Warning: Circular dependency detected:
  crudType?: string;
}
