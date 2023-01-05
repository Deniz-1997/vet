
import { AxiosError } from 'axios';
import { DocumentBytes, SignatureIdentifier } from '@/types';

export default {
  setAgreementDocumentPreparationData(state, fileUuid: string | null): void {
    state.agreementDocumentPreparation.fileUuid = fileUuid;
  },

  setAgreementDocumentPreparationError(state, error: AxiosError | null): void {
    state.agreementDocumentPreparation.error = error;
  },

  setAgreementDocumentPreparationLoading(state, loading: boolean): void {
    state.agreementDocumentPreparation.loading = loading;
  },

  setAgreementDocumentData(state, data: DocumentBytes | null): void {
    state.agreementDocument.data = data;
  },

  setAgreementDocumentFile(state, data: Blob): void {
    state.agreementDocument.file = data;
  },

  setAgreementDocumentBase64(state, base64: string | null): void {
    state.agreementDocument.base64 = base64;
  },

  setAgreementDocumentLink(state, link: string | null): void {
    state.agreementDocument.link = link;
  },

  setAgreementDocumentError(state, error: AxiosError | null): void {
    state.agreementDocument.error = error;
  },

  setAgreementDocumentLoading(state, loading: boolean): void {
    state.agreementDocument.loading = loading;
  },

  setAgreementDocumentSignData(state, data: SignatureIdentifier | null): void {
    state.agreementDocumentSign.data = data;
  },

  setAgreementDocumentSignError(state, error: AxiosError | null): void {
    state.agreementDocumentSign.error = error;
  },

  setAgreementDocumentSignLoading(state, loading: boolean): void {
    state.agreementDocumentSign.loading = loading;
  }
}
