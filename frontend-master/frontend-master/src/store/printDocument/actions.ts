import eventBus from '@/utils/bus/event-bus';
import { PrintDocumentService } from '@/api/printDocument/REST';
const PrintDocument = new PrintDocumentService();

export default {
  async getDocument({ commit }, params) {
    commit('setPrintDocumentData', null);
    commit('setPrintDocumentLoading', true);
    commit('setPrintDocumentError', null);

    try {
      const documentData = await PrintDocument.getDocument(params);
      commit('setPrintDocumentData', URL.createObjectURL(documentData));
    } catch (error) {
      eventBus.$emit('notification:message', {
        text: 'Ошибка получения сохраненного документа',
        title: 'Ошибка',
        type: 'error',
      });
      commit('setPrintDocumentError', error);
    } finally {
      commit('setPrintDocumentLoading', false);
    }
  },

  clearDocument({ commit }) {
    commit('setPrintDocumentData', null);
    commit('setPrintDocumentLoading', false);
    commit('setPrintDocumentError', null);
  },
};
