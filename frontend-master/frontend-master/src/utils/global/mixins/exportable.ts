import { Component, Vue } from 'vue-property-decorator';
import { merge } from '../merge';

// eslint-disable-next-line max-lines-per-function
export default (apiUrl: string) => {
  @Component
  class Exportable extends Vue {
    total = 0;

    getExportFilter() {
      //
    }

    exportAction() {
      this.$service.notify.push('message', {
        text: '',
        params: {
          type: 'confirm-modal',
          title: 'Экспорт списка',
          text: 'Список будет сформирован, по готовности вам будет отправлено соответствующее уведомление.',
          persistent: false,
          buttons: [
            {
              label: 'Для текущей страницы',
              callback: () => this.exportActionCallback(false),
            },
            {
              label: 'Весь список',
              callback: () => this.exportActionCallback(true),
            },
          ],
        },
      });
    }

    exportActionCallback(showAll: boolean) {
      const filter = showAll
        ? merge(this.getExportFilter(), { pageable: { pageSize: this.total, pageNumber: 0 } })
        : this.getExportFilter();
      return this.$service.export.runExport(apiUrl, filter);
    }
  }

  return Exportable;
};
