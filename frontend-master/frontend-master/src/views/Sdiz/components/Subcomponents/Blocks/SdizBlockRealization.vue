<template>
  <v-row class="mt-5" no-gutters>
    <v-col class="mb-xl-4 mb-lg-5" cols="10">
      <v-row :class="[isChange ? 'pt-xl-2' : '']">
        <v-col
          v-for="manufacture in manufacturesListComp"
          :key="JSON.stringify(manufacture.autocomplete)"
          :lg="manufacture.cols.lg"
          :md="manufacture.cols.md"
          :sm="manufacture.cols.sm"
          :xl="manufacture.cols.xl"
          cols="6"
        >
          <v-row>
            <v-col cols="12">
              <ManufacturerAutocomplete
                v-model="model[manufacture.autocomplete.model]"
                :label="manufacture.autocomplete.label"
                :array-name="manufacture.autocomplete.arrayName"
                class="mb-2"
                clereables
                show-name-in-tooltip
                :is-disabled="manufacture.autocomplete.disabled"
                :placeholder="manufacture.autocomplete.placeholder"
              />
            </v-col>
            <v-col v-if="model[manufacture.autocomplete.model] !== null" :class="isChange ? '' : 'ma-0'" cols="12">
              <sdiz-block-manufacture
                v-model="model[manufacture.autocomplete.model]"
                :is-change="isChange"
                :manufacture="manufacture"
                :sdiz-model="model"
              />
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-col>

    <v-col class="mb-xl-4 mb-lg-5" cols="12">
      <sdiz-element-choose-block
        v-model="model"
        :is-checked-block="true"
        :is-create="isCreate"
        :is-edit="isEdit"
        :off-checkbox="true"
        elem-model-date="contract_date"
        elem-model-number="contract_number"
        exl="3"
        label="Сведения о гражданско-правовом договоре"
      />
    </v-col>

    <v-col class="mb-xl-4 mb-lg-5" cols="12">
      <sdiz-element-choose-block
        v-model="model"
        :is-create="isCreate"
        :is-edit="isEdit"
        :is-esiz="true"
        elem-model-number="eisz_number"
        elem-model-esiz-date="eisz_contract_date"
        elem-model-esiz-number="eisz_contract_number"
        exl="6"
        label="Закупка в Единой информационной системе"
      />
    </v-col>
    <v-col cols="12">
      <v-row>
        <sdiz-docs-akt-tables v-model="model.objects.docs_akt" :is-create="isCreate" :is-edit="isEdit" class="mt-8" />

        <sdiz-docs-other-tables
          v-model="model.objects.docs_other"
          :is-create="isCreate"
          :is-edit="isEdit"
          class="mt-8"
        />
      </v-row>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Component, Model, Prop, Vue } from 'vue-property-decorator';
import SdizBlockManufacture from '@/views/Sdiz/components/Subcomponents/Blocks/SdizBlockManufacture.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import SdizElementChooseBlock from '@/views/Sdiz/components/Subcomponents/Elements/SdizElementChooseBlock.vue';
import SdizDocsOtherTables from '@/views/Sdiz/components/Subcomponents/Table/SdizDocsOtherTables.vue';
import SdizDocsAktTables from '@/views/Sdiz/components/Subcomponents/Table/SdizDocsAktTables.vue';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';

@Component({
  name: 'sdiz-block-realization',
  components: {
    SdizDocsAktTables,
    SdizDocsOtherTables,
    SdizElementChooseBlock,
    SdizBlockManufacture,
    AutocompleteComponent,
    ManufacturerAutocomplete,
  },
})
export default class SdizBlockRealization extends Vue {
  @Model('change', { type: Object, required: true }) readonly model!: SdizGpbVueModel | SdizVueModel;

  @Prop({ type: Boolean, default: false }) isEdit!: boolean;
  @Prop({ type: Boolean, default: false }) isCreate!: boolean;

  manufactureCols = {
    lg: '6',
    md: '12',
    sm: '12',
    xl: '6',
  };

  get manufacturesListComp(): any[] {
    return [
      {
        cols: this.manufactureCols,
        autocomplete: {
          condition: true,
          model: 'seller_id',
          arrayName: 'sellers',
          label: 'Продавец',
          disabled: true,
          placeholder: 'Начните вводить наименование, ИНН, КПП или ОГРН',
        },
        info: {
          manufactureIdName: 'seller_id',
        },
      },
      {
        cols: this.manufactureCols,
        autocomplete: {
          condition: true,
          model: 'buyer_id',
          arrayName: 'buyers',
          disabled: !this.isChange,
          label: 'Покупатель',
          placeholder: 'Начните вводить наименование, ИНН, КПП или ОГРН',
        },
        info: {
          manufactureIdName: 'buyer_id',
        },
      },
    ];
  }

  get isChange() {
    return this.isEdit || this.isCreate;
  }
}
</script>
