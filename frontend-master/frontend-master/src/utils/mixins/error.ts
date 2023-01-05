import Vue from 'vue';
import Component from 'vue-class-component';

@Component
export class ErrorMix extends Vue {
  get errors() {
    return this.selectErrorDescription(this.nameErrors);
  }

  get nameErrors(): string {
    let error: string = '';
    Object.keys(this.$store.getters['errors/getErrorsList']).forEach((val) => {
      if (val != undefined) {
        Object.keys(this.$store.getters['errors/getErrorsList'][val]).forEach((value) => {
          if (typeof this.$store.getters['errors/getErrorsList'][val][value] !== 'string') {
            error = this.$store.getters['errors/getErrorsList'][val][value]['errors'][0]['name'];
            this.$store.commit('errors/clearErrorList');
          }
        });
      }
    });
    return error;
  }

  selectErrorDescription(error: string) {
    switch (error) {
      case 'ExtinguishNotCanceled':
        return this.$store.commit('errors/setErrorsList', 'Аннулирование запрещено, есть не аннулированные погашения');
      case 'haveNotCanceledLinkedSdizs':
        return this.$store.commit('errors/setErrorsList', 'Аннулирование запрещено, есть не аннулированные СДИЗ');
      case 'haveNotCanceledLinkedRecipientLots':
        return this.$store.commit('errors/setErrorsList', 'Аннулирование запрещено, есть не аннулированные партия');
      case 'haveNotCanceledLinkedRecipientGpbs':
        return this.$store.commit(
          'errors/setErrorsList',
          'Аннулирование запрещено, есть не аннулированные партия продуктов переработки'
        );
      case 'haveNotCanceledLinkedGpbSdizs':
        return this.$store.commit(
          'errors/setErrorsList',
          'Аннулирование запрещено, есть не аннулированные СДИЗ на продукт переработки'
        );
      default:
        return this.$store.commit('errors/setErrorsList', 'Ошибка при формировании запроса');
    }
  }
}
