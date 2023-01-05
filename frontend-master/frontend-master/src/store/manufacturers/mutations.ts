import { AxiosError } from "axios";

export default {
  setList: (_, value) => {
    _.list = value;
  },

  appendList: (_, value) => {
    _.list = [..._.list, value];
  },
  
  setExportDocumentData(_, data: any | null): void {
    _.exportDocument.data = data;
  },

  setExportDocumentError(_, error: AxiosError | null): void {
    _.agreementDocument.error = error;
  },
}
