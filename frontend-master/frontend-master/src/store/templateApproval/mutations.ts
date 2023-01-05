
import { AxiosError } from 'axios';

export default {
  setExportDocumentData(_, data: any | null): void {
    _.exportDocument.data = data;
  },

  setExportDocumentError(_, error: AxiosError | null): void {
    _.agreementDocument.error = error;
  },

}