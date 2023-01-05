<template>
  <v-row no-gutters class="sdiz-element-carrier my-8">
    <v-row>
      <v-col v-if="!isChange" cols="12" sm="6" xl="3">
        <label-component label="Наименование" class="mb-4" />
        <text-component> {{ value.carrier.name }} </text-component>
      </v-col>

      <v-col v-else cols="12" sm="6" xl="3">
        <ManufacturerAutocomplete
          v-if="isEditCarrier || !innerValue.carrier_id"
          v-model="innerValue.carrier"
          :is-disabled="!isChange"
          class="mb-2"
          placeholder="Начните вводить наименование, ИНН, КПП или ОГРН"
          label="Наименование"
          is-return-object
          show-name-in-tooltip
          @change="handleCarrierEdit"
        />

        <v-col v-else class="pa-0">
          <label-component label="Наименование" class="mb-4" />

          <text-component>{{ value.carrier.name }}</text-component>

          <img
            v-if="!isEditCarrier && isChange && innerValue.carrier_id"
            alt=""
            class="iconTable ml-2"
            src="/icons/edit.svg"
            @click="isEditCarrier = true"
          />
        </v-col>
      </v-col>

      <v-col v-if="value.carrier_id" cols="12" sm="6" xl="3">
        <label-component label="ИНН" class="mb-4" />
        <text-component>
          {{ inn }}
        </text-component>
      </v-col>

      <v-col v-if="value.carrier_id" cols="12" sm="6" xl="3">
        <label-component label="КПП" class="mb-4" />
        <text-component>
          {{ kpp }}
        </text-component>
      </v-col>

      <v-col>
        <v-btn v-if="isChange" icon :disabled="!isChange" class="mt-8" @click="handleDelete">
          <v-icon>mdi-delete</v-icon>
        </v-btn>
      </v-col>
    </v-row>

    <v-row>
      <sdiz-docs-transport-tables
        v-model="innerValue.doc_transports"
        :is-create="isCreate"
        :is-edit="isEdit"
        class="mt-8"
      />

      <SdizCarrierLocationsTable v-model="innerValue.locations" :is-create="isCreate" :is-edit="isEdit" class="mt-8" />
    </v-row>
  </v-row>
</template>

<script lang="ts">
import { Component, Model, Prop, Emit, Vue } from 'vue-property-decorator';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import SdizDocsTransportTables from '@/views/Sdiz/components/Subcomponents/Table/SdizDocsTransportTables.vue';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import { SdizCarrierModel } from '@/models/Sdiz/SdizCarrier';
import DeleteIcon from '@/components/common/IconComponent/icons/DeleteIcon.vue';
import TextComponent from '@/components/common/TextComponent.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import SdizCarrierLocationsTable from '@/views/Sdiz/components/Subcomponents/Table/SdizCarrierLocationsTable.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import IconButton from '@/components/common/buttons/IconButton.vue';

@Component({
  name: 'sdiz-element-carrier',
  components: {
    IconButton,
    DefaultButton,
    SdizCarrierLocationsTable,
    LabelComponent,
    TextComponent,
    DeleteIcon,
    AutocompletePriorityAddress,
    ManufacturerAutocomplete,
    SdizDocsTransportTables,
  },
})
export default class SdizElementCarrier extends Vue {
  @Model('change', { type: Object, required: true }) readonly value!: SdizCarrierModel;
  @Prop({ type: Number, required: true }) readonly carrierIdx!: number;
  @Prop({ required: true }) isEdit!: boolean;
  @Prop({ required: true }) isCreate!: boolean;
  @Prop({ required: true }) isChange!: boolean;

  carriers: object[] = [];

  @Emit('delete')
  handleDelete() {
    return this.carrierIdx;
  }

  isEditCarrier = false;

  handleCarrierEdit(value) {
    if (value?.subject_id) {
      this.innerValue.carrier_id = value.subject_id;
      this.setModelCarrier(value);
      this.isEditCarrier = false;
    } else {
      this.innerValue.carrier_id = null;
      this.innerValue.carrier = null;
    }
  }

  get innerValue() {
    return this.value;
  }

  set innerValue(val) {
    this.$emit('change', val);
  }

  setModelCarrier(item) {
    this.innerValue.carrier = item;
  }

  get inn() {
    return this.innerValue.carrier?.inn || '-';
  }

  get kpp() {
    return this.innerValue.carrier?.kpp || '-';
  }
}
</script>
