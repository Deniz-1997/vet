<template>
  <v-row>
    <v-col cols="12" md="6" lg="4" xl="4">
      <div class="title-h2 thead">Предоставляемые услуги</div>

      <UiControl name="service" :value="form.elevator_info.elevator_info_service">
        <EditableTable
          v-model="form.elevator_info.elevator_info_service"
          :options="serviceHeaders"
          :is-showcase="isShowcase"
          :is-can-edit="!isShowcase"
          :max="999"
          @change:name="onChange"
          id-table="elevator_info_service"
        />
      </UiControl>
    </v-col>
    <v-col cols="12" md="6" lg="4" xl="4">
      <div class="title-h2 thead">Способы переработки</div>

      <UiControl name="conversion" :value="form.elevator_info.elevator_info_processing">
        <EditableTable
          v-model="form.elevator_info.elevator_info_processing"
          :options="conversionHeaders"
          :is-can-edit="!isShowcase"
          :is-showcase="isShowcase"
          :max="999"
          id-table="elevator_info_processing"
        />
      </UiControl>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import EditableTable from '@/components/common/Table/index.vue';
import FormMixin from '@/views/RegisterOrganizations/components/CardOrganization/components/LocationInfo/FormMixin.vue';

@Component({
  name: 'ServicesDataForm',
  components: { EditableTable },
})
export default class extends Mixins(FormMixin) {
  conversionType: any[] = [];
  serviceType: any[] = [];
  additionOption = {
    label: 'Дополнение',
    name: 'addition',
    exclude: true,
    controlType({ name }) {
      if (name?.value === 7) {
        return 'input';
      }

      return 'none';
    },
    width: 250,
  };

  get conversionHeaders() {
    return [
      {
        label: '',
        name: 'name',
        controlType: 'select',
        restrictions: (list, selected) =>
          this.conversionType.filter(({ value }) => {
            return (
              !list.some((item) => Number(item?.name?.code ?? item?.name?.value) === Number(value)) ||
              Number(selected?.value ?? selected?.code) == Number(value)
            );
          }),
        exclude: true,
        // width: 250
      },
    ];
  }

  get serviceHeaders() {
    return [
      {
        label: 'Вид услуги',
        name: 'name',
        controlType: 'select',
        restrictions: (list, selected) =>
          this.serviceType.filter(({ value }) => {
            return (
              !list.some((item) => (item?.name?.code ?? item?.name?.value) === value) ||
              (selected?.value ?? selected?.code) == value
            );
          }),
        exclude: true,
        width: 250,
      },
      this.additionOption,
    ];
  }

  created() {
    this.fetchConversionType();
    this.fetchServiceType();
  }

  async fetchConversionType() {
    const { content } = await this.$store.dispatch('elevator/getListProccessingMethod', { actual: true });
    this.conversionType = content.map(({ name, code }) => ({
      label: name,
      value: code,
    }));
  }

  async fetchServiceType() {
    //ToDo: Разобраться с типизацией
    const { content }: any = await this.$store.dispatch('elevator/getListServiceType', { actual: true });
    this.serviceType = content.map(({ name, id }) => ({
      label: name,
      value: id,
    }));
  }

  onChange(_, form) {
    form.addition = '';
  }
}
</script>

<style lang="scss" scoped>
.thead {
  padding: 12px 0;
}

.elementsInput {
  position: relative;
}
</style>
