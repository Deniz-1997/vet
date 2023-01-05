import { AxiosError } from 'axios';
import { DocumentBytes } from '@/types';

export default {
  setPrintDocumentData(state, data: DocumentBytes | null): void {
    state.printDocument.data = data;
  },

  setPrintDocumentError(state, error: AxiosError | null): void {
    state.printDocument.error = error;
  },

  setPrintDocumentLoading(state, loading: boolean): void {
    state.printDocument.loading = loading;
  },
};
