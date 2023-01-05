<template>
  <div class="breadcrumb">
    <v-breadcrumbs :items="breadcrumbList" large>
      <template #divider>
        <svg width="24" height="8" viewBox="0 0 24 8" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M19.8293 0.123719C19.6214 -0.0581225 19.3056 -0.0370638 19.1237 0.170755C18.9419 0.378573 18.9629 0.694454 19.1707 0.876295L22.1693 3.50001H0V4.50001H22.1693L19.1707 7.12372C18.9629 7.30556 18.9419 7.62144 19.1237 7.82926C19.3056 8.03708 19.6214 8.05814 19.8293 7.8763L23.8293 4.3763C23.9378 4.28135 24 4.14419 24 4.00001C24 3.85583 23.9378 3.71866 23.8293 3.62372L19.8293 0.123719Z"
            fill="#ffffff80"
          />
        </svg>
      </template>
    </v-breadcrumbs>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Watch } from 'vue-property-decorator';

@Component({
  props: ['route'],
  name: 'breadcrumbs-component',
  components: {},
})
export default class Breadcrumbs extends Vue {
  breadcrumbList: any = [];
  name = '';
  listNsi = [
    {path: 'nsi-tnved', name: 'ТН ВЭД'},
    { path: 'nsi-type-product', name: 'Вид сельскохозяйственной культуры' },
    { path: 'nsi-transport-type', name: 'Вид транспортных средств' },
    { path: 'nsi-lots-purpose', name: 'Назначение партии зерна' },
    { path: 'nsi-lots-target', name: 'Цель использования партии зерна' },
    { path: 'nsi-sample-type', name: 'Вид отбора проб' },
    { path: 'nsi-granary-type', name: 'Вид зернохранилища' },
    { path: 'nsi-document-type', name: 'Вид документов' },
    {
      path: 'nsi-quality-indicators',
      name: 'Справочник потребительских свойств зерна и (или) продуктов переработки зерна',
    },
    {
      path: 'nsi-quality-indicators-limit',
      name: 'Справочник допустимых значений потребительских свойств зерна и (или) продуктов переработки зерна',
    },
    { path: 'nsi-processing-method', name: 'Способ переработки' },
    { path: 'nsi-storage-method', name: 'Способ хранения' },
    { path: 'elevator-service-type', name: 'Тип услуги элеваторов' },
    { path: 'unit-of-measure', name: 'Справочник единиц измерения' },
    {
      path: 'indicator-purpose',
      name: 'Назначение потребительского свойства партии зерна и (или) партии продуктов переработки зерна',
    },
    { path: 'nsi-okpd2', name: 'ОКПД 2' },
    { path: 'property-right', name: 'Сведения о собственности' },
    { path: 'property-right-transfer-doc-type', name: 'Документы, подтверждающие переход права собственности' },
    { path: 'lot-document-type', name: 'Документы на партию' },
    { path: 'reason-write-off', name: 'Причина списания' },
    { path: 'storage-type', name: 'Тип хранения' },
    { path: 'lot-return-reason', name: 'Причины возврата партии' },
    { path: 'weight-disperancy-cause', name: 'Причины расхождения веса' },
    // {
    // {
    //   path: "grain-group",
    //   name: "Наименование зерна / продуктов переработки зерна",
    // },
  ];

  mounted() {
    this.updateList();
  }

  @Watch('$route')
  breadcrumbItem() {
    this.updateList();
  }

  //ToDo: Replace!!!
  updateList() {
    this.breadcrumbList = [];
    const main = {
      text: 'Главная',
      to: '/home',
      disabled: false,
    };

    this.breadcrumbList.push(main);
    this.$route.matched.forEach((item) => {
      if (item.meta.breadcrumb) {
        if (item.meta.breadcrumb?.[0]?.name !== 'Главная') {
          if (item.meta.breadcrumb?.[0]?.name === '') {
            this.listNsi.filter((item) => {
              if (item.path === this.$route.params.mask) {
                this.name = item.name;
              }
            });
          }
          const breadcrumb = this.getBreadcrumb(item);
          this.breadcrumbList.push(breadcrumb);
        }
      }
    });
    const lastIndex = this.breadcrumbList.length - 1;
    this.breadcrumbList[lastIndex]['disabled'] = true;
  }

  getBreadcrumb(item) {
    return {
      text:
        item.meta.breadcrumb.find(({ type }) => this.$route.params.type === type)?.name ||
        item.meta.breadcrumb?.[0]?.name ||
        this.name,
      to: Object.entries(this.$route.params).reduce(
        (result, [key, value]) => result.replace(`:${key}`, value),
        item.path
      ),
      exactPath: true,
      disabled: false,
    };
  }
}
</script>

<style lang="scss">
@import '@/assets/styles/_colors';
.breadcrumb {
  .v-breadcrumbs__item {
    font-weight: 400;
    font-size: 12px;
    line-height: 16px;
    color: rgba($white-color, 0.5) !important;
    opacity: 1 !important;

    &:hover {
      color: rgba($white-color, 1) !important;
    }
  }

  svg {
    fill: #ffffff;
  }

  .theme--light.v-breadcrumbs .v-breadcrumbs__item--disabled {
    color: $white-color !important;
    opacity: 1 !important;
  }
}
</style>
