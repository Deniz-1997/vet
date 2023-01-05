import {constructByInterface} from '../../utils/construct-by-interface';

export interface StoreFtpErrorsItems {
  id: number;
  name: string;
  stock: {
    id: number;
    name: string;
  };
}

export interface StoreFtpHistoryInterface {
  id: number;
  fileName: string;
  operationType: string;
  date: string;
  imported: boolean;
  uploadImportExportFile: {
    uploadedFile: {
      name: string;
    }
  };
  report: {
    errors: [
      {
        type: string,
        externalId: string,
      }];
    countAddedStocks: number;
    countAddedProducts: number;
    countChangedProducts: number;
    countDeletedProducts: number;
  };
}

export class StoreFtpHistoryModel implements StoreFtpHistoryInterface {
  id: number;
  fileName: string;
  operationType: string;
  date: string;
  imported: boolean;
  uploadImportExportFile: {
    uploadedFile: {
      name: string;
    }
  };

  report: {
    countAddedStocks: number;
    countAddedProducts: number;
    countChangedProducts: number;
    countDeletedProducts: number;

    errors: [
      {
        type: string,
        externalId: string,
      }];

    stockData: StoreFtpErrorsItems;
    productData: StoreFtpErrorsItems;
  };

  constructor(o?: StoreFtpHistoryInterface) {
    constructByInterface(o, this);
  }
}
