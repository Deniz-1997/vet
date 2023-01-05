import Vue from 'vue';
import Component from 'vue-class-component';

@Component
export class GetDocumentForSignMix extends Vue {
  async getNewOrStoredDocument(measureId, service) {
    await this.$store.dispatch('agreementDocument/getNewOrStoredDocument', {
      measureId,
      service,
    });
  }

  async prepareDocumentFromDescription(endpoint, params) {
    await this.$store.dispatch('agreementDocument/getDocumentFromDescription', {
      endpoint,
      params,
    });
  }
}