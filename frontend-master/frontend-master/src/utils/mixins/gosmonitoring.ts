import Vue from 'vue';
import Component from 'vue-class-component';
import { Emit } from 'vue-property-decorator';

@Component
export class Gosmonitoring extends Vue {
  async getGosmonitoringById(thisRef: any, id): Promise<void> {
    const { status, response } = await this.$store.dispatch('gosmonitoring/getList', {
      url: thisRef.model.url,
      data: { filter: { options: id } },
    });

    if (!status) {
      throw new Error('Ошибка на стороне сервера');
    }

    if (thisRef.model) {
      return new thisRef.model.constructor(response[0]);
    }
  }

  watchForm(model: any): boolean {
    if (this.$route.params.id) return !!model.id;
    else return true;
  }

  async handleSubscription(thisRef: any, service: string) {
    try {
      thisRef.emitIsLoading(true);

      await thisRef.$store.dispatch('agreementDocument/signDocumentFromDescription', {
        id: thisRef.measureId,
        service: service,
      });

      const error = this.$store.state.agreementDocument.agreementDocumentSign.error;

      if (error) {
        throw new Error(error);
      }

      thisRef.$notify({ group: 'gosmonitoring', type: 'success', title: 'Операция успешно выполнена' });
    } catch (_err) {
      thisRef.$notify({ group: 'gosmonitoring', type: 'error', title: 'Ошибка при подписании' });
    } finally {
      thisRef.model = await thisRef.getGosmonitoringById(thisRef, [
        { field: 'id', operator: '=', value: parseInt(thisRef.model.id) },
      ]);
      thisRef.emitIsLoading(false);
    }
  }

  @Emit('is-loading')
  emitIsLoading(value: boolean) {
    return value;
  }
}
