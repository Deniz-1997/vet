import { AgreementDocumentService } from '@/api/agreementDocument/REST';
import { AgreementDocumentFormOrStoredParams } from '@/api/agreementDocument/types';

const AgreementDocument = new AgreementDocumentService();
declare const Buffer;

export default {
  async prepareDocumentForSigning({ commit }, params): Promise<void> {
    commit('setAgreementDocumentPreparationData', null);
    commit('setAgreementDocumentPreparationLoading', true);
    commit('setAgreementDocumentPreparationError', null);

    try {
      const fileUuid = await AgreementDocument.prepareDocumentForSigning(params);

      commit('setAgreementDocumentPreparationData', fileUuid);
    } catch (error) {
      commit('setAgreementDocumentPreparationError', error);
      throw error;
    } finally {
      commit('setAgreementDocumentPreparationLoading', false);
    }
  },

  async getNewOrStoredDocument({ commit }, params: AgreementDocumentFormOrStoredParams) {
    commit('setAgreementDocumentData', null);
    commit('setAgreementDocumentBase64', null);
    commit('setAgreementDocumentLoading', true);
    commit('setAgreementDocumentError', null);

    try {
      const documentData = await AgreementDocument.getNewOrStoredDocument(params);
      commit('setAgreementDocumentFile', documentData);
      commit('setAgreementDocumentData', URL.createObjectURL(documentData));
      const documentBase64 = new Buffer(await documentData.arrayBuffer(), 'binary').toString('base64');
      commit('setAgreementDocumentBase64', documentBase64);
    } catch (error) {
      commit('setAgreementDocumentError', error);
      throw error;
    } finally {
      commit('setAgreementDocumentLoading', false);
    }
  },

  async signDocument({ commit }, params): Promise<void> {
    commit('setAgreementDocumentSignData', null);
    commit('setAgreementDocumentSignLoading', true);
    commit('setAgreementDocumentSignError', null);

    try {
      const signatureIdentifier = await AgreementDocument.signDocument(params);
      commit('setAgreementDocumentSignData', signatureIdentifier);
      return signatureIdentifier;
    } catch (error) {
      commit('setAgreementDocumentSignError', error);
      throw error;
    } finally {
      commit('setAgreementDocumentSignLoading', false);
    }
  },

  async getDocumentFromDescription({ commit }, payload: { endpoint: string; params: any }): Promise<void> {
    const { endpoint, params } = payload;

    commit('setAgreementDocumentData', null);
    commit('setAgreementDocumentBase64', null);
    commit('setAgreementDocumentLoading', true);
    commit('setAgreementDocumentError', null);

    try {
      const documentData = await AgreementDocument.getDocumentFromDescription(endpoint, params);

      commit('setAgreementDocumentData', URL.createObjectURL(documentData));
      const documentBase64 = new Buffer(await documentData.arrayBuffer(), 'binary').toString('base64');
      commit('setAgreementDocumentBase64', documentBase64);
    } catch (error) {
      commit('setAgreementDocumentPreparationError', error);
      throw error;
    } finally {
      commit('setAgreementDocumentPreparationLoading', false);
    }
  },

  async signDocumentFromDescription({ commit, state }, payload: { service: string; id: number }) {
    try {
      const { esp_id } = state.agreementDocumentSign.data;

      await AgreementDocument.signDocumentFromDescription({ ...payload, esp_id });

      commit('setAgreementDocumentData', null);
      commit('setAgreementDocumentBase64', null);
      commit('setAgreementDocumentLoading', false);
      commit('setAgreementDocumentError', null);
      commit('setAgreementDocumentSignData', null);
    } catch (error) {
      commit('setAgreementDocumentSignError', error);
      throw error;
    }
  },
};
