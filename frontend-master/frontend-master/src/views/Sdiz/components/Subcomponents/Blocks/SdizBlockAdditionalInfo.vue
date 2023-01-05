<template>
  <v-row no-gutters>
    <v-col class="mb-5" cols="12">
      <v-row v-show="prototypeSdiz" class="mb-5">
        <v-col cols="4">
          <autocomplete-component
            v-model="model.laboratory_id"
            :is-disabled="!isChange"
            :items="labList"
            label="Аккредитованное лицо, проводившее лабораторные исследования"
            placeholder="Выберите значение"
            class="normalize-labels"
            clearable
            item-text="subject.short_name"
            item-value="id"
            @searchInputUpdate="searchLaboratoryes"
          />
        </v-col>

        <v-col cols="4">
          <input-component
            v-model="model.protocol_number"
            :disabled="!isChange"
            :maxlength="64"
            label="Номер протокола испытаний"
            placeholder="Введите номер протокола испытаний"
            class="normalize-labels"
          />
        </v-col>

        <v-col cols="4">
          <UiDateInput
            v-model="model.protocol_date"
            :disabled="!isChange"
            :limit-to="today"
            :format="'DD.MM.YYYY'"
            class="normalize-labels"
            label="Дата протокола испытаний"
            placeholder="Укажите дату протокола испытаний"
          />
        </v-col>

        <v-col cols="4">
          <input-component
            v-model="model.customs_declaration_number"
            :disabled="!isChange"
            mask="########/######/#######"
            label="Номер таможенной декларации"
            placeholder="Введите номер таможенной декларации"
          />
        </v-col>
      </v-row>
      <v-row>
        <v-col
          v-for="(manufacture, index) in model.options"
          :key="index + model[manufacture.autocomplete.model] + isChange"
          cols="4"
        >
          <div v-if="isShowBlock(manufacture)">
            <ManufacturerAutocomplete
              v-model="model[manufacture.autocomplete.model]"
              :label="manufacture.autocomplete.label"
              :placeholder="manufacture.autocomplete.placeholder"
              :is-disabled="!isChange"
              class="mb-2"
              clereables
              show-name-in-tooltip
            />
            <sdiz-block-manufacture
              v-if="model[manufacture.autocomplete.model] !== null"
              v-model="model[manufacture.autocomplete.model]"
              :is-change="isChange"
              :manufacture="manufacture"
              :sdiz-model="model"
            />
          </div>
        </v-col>
      </v-row>

      <SdizBlockCarriers
        v-model="model"
        :is-create="isCreate"
        :is-edit="isEdit"
        :is-change="isChange"
        v-if="model.objects.operations.detail.shipping"
      />
    </v-col>
    <v-col v-if="prototypeSdiz" cols="12">
      <v-row>
        <v-col cols="12">
          <text-component class="float-left" variant="h5">Контракт ВЭД</text-component>
        </v-col>
        <v-col class="pr-10 pr-xl-5" cols="12" md="4" sm="6" lg="4">
          <UiDateInput
            v-model="model.ved_con_date"
            :disabled="!isChange"
            label="Дата"
            :format="'DD.MM.YYYY'"
            placeholder="Выберите дату"
          />
        </v-col>

        <v-col class="pr-10 pr-xl-15" cols="12" md="4" sm="6" lg="4">
          <input-component
            v-model="model.ved_con_number"
            :disabled="!isChange"
            label="Номер"
            placeholder="Введите номер"
          />
        </v-col>
      </v-row>
      <v-row>
        <v-col class="pr-10 pr-xl-5" cols="12" md="4" sm="6" lg="4">
          <UiDateInput
            v-model="model.ved_dop_date"
            :disabled="!isChange"
            :format="'DD.MM.YYYY'"
            label="Дата дополнительного соглашения"
            placeholder="Выберите дату"
          />
        </v-col>
        <v-col class="pr-10 pr-xl-15" cols="12" md="4" sm="6" lg="4">
          <input-component
            v-model="model.ved_dop_number"
            :disabled="!isChange"
            label="Номер дополнительного соглашения"
            placeholder="Введите номер"
          />
        </v-col>
      </v-row>
    </v-col>

    <v-col cols="12">
      <v-row>
        <sdiz-docs-other-tables
          v-show="
            model.objects.operations.detail.shipment ||
            model.objects.operations.detail.acceptance ||
            model.objects.operations.detail.shipping
          "
          v-model="model.objects.docs_transports_other"
          :is-create="isCreate"
          :is-edit="isEdit"
          class="mt-8"
        />
      </v-row>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Component, Model, Prop, Mixins } from 'vue-property-decorator';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import SdizDocsOtherTables from '@/views/Sdiz/components/Subcomponents/Table/SdizDocsOtherTables.vue';
import SdizBlockManufacture from '@/views/Sdiz/components/Subcomponents/Blocks/SdizBlockManufacture.vue';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import SdizDocsAktTables from '@/views/Sdiz/components/Subcomponents/Table/SdizDocsAktTables.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import UiDateInput from '@/components/global/UiForm/components/UiDateInput.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import ManufacturerAutocomplete from '@/components/ManufacturerAutocomplete/ManufacturerAutocomplete.vue';
import SdizBlockCarriers from '@/views/Sdiz/components/Subcomponents/Blocks/SdizBlockCarriers.vue';
import { AdditionalMix } from '@/utils/mixins/additional';
import { Debounce } from '@/utils/global/decorators/method';

@Component({
  name: 'sdiz-block-additional-info',
  components: {
    SdizBlockCarriers,
    SdizBlockManufacture,
    SdizDocsOtherTables,
    AutocompleteComponent,
    SdizDocsAktTables,
    InputComponent,
    UiDateInput,
    TextComponent,
    ManufacturerAutocomplete,
  },
})
export default class SdizBlockAdditionalInfo extends Mixins(AdditionalMix) {
  @Model('change', { type: Object, required: true }) readonly model!: SdizGpbVueModel | SdizVueModel;

  @Prop({ required: true }) isEdit!: boolean;
  @Prop({ required: true }) isCreate!: boolean;
  labList: any = [];

  /** СДИЗ на Ввоз или Вывоз*/
  get prototypeSdiz(): boolean {
    return this.model.objects.operations.prototype_sdiz === 2 || this.model.objects.operations.prototype_sdiz === 3;
  }

  get isChange() {
    return this.isEdit || this.isCreate;
  }

  async created() {
    await this.loadLaboratoryById();
  }

  async loadLaboratoryById() {
    if (!this.model.laboratory_id) return;

    const content = await this.$store.dispatch('laboratories/getInfoLaboratory', { id: this.model.laboratory_id });

    if (content) this.labList.push(content);
  }

  @Debounce(4000)
  async getLaboratoryesList(value): Promise<void> {
    if (!value) return;

    const { content } = await this.$store.dispatch('laboratories/getList', {
      filter: value,
      actual: true,
    });
    this.labList = content;
  }

  async searchLaboratoryes(value) {
    if (value === null) {
      return;
    }
    const itemIndex = this.labList.findIndex((item: any) => item.subject.short_name === value);
    if (itemIndex === -1) {
      await this.getLaboratoryesList(value);
    }
  }

  isShowBlock(data) {
    let operations: Array<boolean> = [];
    data.autocomplete.condition.forEach((v) => operations.push(this.model.objects.operations.detail[v]));
    return operations.filter((v) => v).length > 0;
  }
}
</script>

<style lang="scss" scoped>
.normalize-labels::v-deep {
  @media (max-width: 1920px) {
    .label {
      display: flex;
      min-height: 32px !important;
      margin: 0 !important;
      padding: 0 !important;
    }
  }
}
</style>
