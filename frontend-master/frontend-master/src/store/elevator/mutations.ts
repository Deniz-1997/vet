import { AxiosError } from 'axios';

export default {
  setExportDocumentData(_, data: any | null): void {
    _.exportDocument.data = data;
  },

  setExportDocumentError(_, error: AxiosError | null): void {
    _.agreementDocument.error = error;
  },

  setListGranaryType(_, value) {
    _.listGranaryType = value;
  },

  setListServiceType(_, value) {
    _.listServiceType = value;
  },

  setStorageMethodList(_, value) {
    _.storageMethodList = value;
  },

  setDocumentTypes: (_, value) => {
    _.documentTypes = value;
  },
}
