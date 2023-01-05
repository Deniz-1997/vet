<template>
  <v-col cols="12" lg="11" md="12" sm="12" xl="5">
    <sdiz-table
      v-model="innerValue"
      :is-create="isCreate"
      :is-edit="isEdit"
      :options-for-table="optionsForTable"
      :is-edit-action="isCreate || isEdit"
      :is-can-edit="isCreate || isEdit"
      :is-hide-action="false"
      :is-show-card-button="isCreate || isEdit"
      class_="edit-table-sdiz-docs-transport"
      empty-text="Не добавлены места перегрузки"
      title="Места перегрузки"
    />
  </v-col>
</template>

<script lang="ts">
import { Component, Model, Prop } from 'vue-property-decorator';
import EditableTable from '@/components/common/Table/index.vue';
import { mixins } from 'vue-class-component';
import { AdditionalMix } from '@/utils/mixins/additional';
import TextComponent from '@/components/common/TextComponent.vue';
import SdizTable from '@/views/Sdiz/components/Subcomponents/Table/SdizTable.vue';
import { CarrierLocationModel } from '@/models/Sdiz/AddressPriority';
import omit from 'lodash/omit';

@Component({
  name: 'sdiz-docs-transport-tables',
  components: {
    SdizTable,
    TextComponent,
    EditableTable,
  },
})
export default class SdizDocsTransportTables extends mixins(AdditionalMix) {
  @Model('change', { type: Array, required: true }) value!: CarrierLocationModel[];

  @Prop({ type: Boolean, default: false }) isEdit!: boolean;

  @Prop({ type: Boolean, default: false }) isCreate!: boolean;

  get innerValue(): CarrierLocationModel[] {
    return this.value;
  }

  set innerValue(value: CarrierLocationModel[]) {
    if (value.length > 0) {
      const uniqueByKey = (array, key) => [...new Map(array.map((item) => [item[key], item])).values()];

      this.$emit(
        'change',
        Object.keys(value[0]).length > 0
          ? uniqueByKey(
              value
                .filter((e) => !!e.location_id)
                .map((e: any) => {
                  const cachedData = this.addressCache.find((record) => record.location_id === e.location_id);

                  return {
                    ...e,
                    location_id: e.location_id,
                    address: e.address || cachedData?.address || '',
                    postcode: e.postcode || cachedData?.postcode || '',
                  };
                }),
              'location_id'
            )
          : []
      );
    } else {
      this.$emit('change', []);
    }
  }

  get optionsForTable(): Array<object> {
    return [
      {
        name: 'location_id',
        placeholder: 'Укажите место перегрузки',
        controlType: 'priorityAddress',
        exclude: true,
        width: 500,
        label: 'Адрес',
        style: { width: '100%', textAlign: 'left' },
        customRenderValue: (el: any) => {
          return `<span>${el.address || el.location.address}</span>`;
        },
        handleChange: this.handleAddressChange,
      },
    ];
  }

  addressCache: any[] = [];

  handleAddressChange(val) {
    this.addressCache.push(omit({ ...val, location_id: val.id }, ['id']));
  }
}
</script>
