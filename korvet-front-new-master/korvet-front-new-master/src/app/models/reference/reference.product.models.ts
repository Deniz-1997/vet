import {constructByInterface} from '../../utils/construct-by-interface';
import {ProductStockModel} from '../product/product-stock.models';
import { ReferenceCategoryNomenclatureInterface, ReferenceCategoryNomenclatureModel } from './reference.category.nomenclature.models';
import { ReferenceManufacturerInterface, ReferenceManufacturerModel } from './reference.manufacturer.models';
import { ReferenceReleaseFormInterface, ReferenceReleaseFormModel } from './refernce.release.form.models';
import {ReferenceCountriesInterface, ReferenceCountriesModel} from './reference.countries.models';
import {ReferenceMeasurementUnitsInterface, ReferenceMeasurementUnitsModel} from './reference.measurement.units.models';

export interface ReferenceProductInterface {
  id?: number;
  deleted: boolean;
  name: string;
  price: number;
  measure?: string;
  paymentObject: {
    code: string;
    title: string;
  };
  productCode: {
    type?: {
      code?: string;
    },
    gtin?: string;
    serial?: string;
  };
  vatRate: {
    code: string;
  };
  country: string;
  itemType: string;
  fullName: string;
  quantity: number;
  balance: number;
  imported: boolean;
  unit: {
    id: number;
  };
  stock: {
    id: number;
    name: string;
  };
  productStock: ProductStockModel[];
  releaseForm: ReferenceReleaseFormInterface;
  categoryNomenclature: ReferenceCategoryNomenclatureInterface;
  manufacturer: ReferenceManufacturerInterface;
  countries: ReferenceCountriesInterface;
  measurementUnits: {
    id: number;
    name: string;
  };
  budgetDrug: boolean;
  disease: {id: number; name: string};
}

export class ReferenceProductModel implements ReferenceProductInterface {
  id: number;
  deleted: boolean;
  name: string;
  price: number;
  measure: string;
  paymentObject: {
    code: string;
    title: string;
  };
  productCode: {
    type: {
      code: string;
    },
    gtin: string;
    serial: string;
  };
  vatRate: {
    code: string;
  };
  country: string;
  itemType: string;
  fullName: string;
  quantity: number;
  balance: number;
  imported: boolean;
  unit: {
    id: number;
  };
  stock: {
    id: number;
    name: string;
  };
  productStock: ProductStockModel[];
  priceWithCharge: number;
  amount: number;
  releaseForm: ReferenceReleaseFormModel;
  categoryNomenclature: ReferenceCategoryNomenclatureModel;
  manufacturer: ReferenceManufacturerModel;
  countries: ReferenceCountriesModel;
  measurementUnits: {
    id: number;
    name: string;
  };
  budgetDrug: boolean;
  disease: {id: number; name: string};


  constructor(o?: ReferenceProductInterface) {
    constructByInterface(o, this);
  }
}
