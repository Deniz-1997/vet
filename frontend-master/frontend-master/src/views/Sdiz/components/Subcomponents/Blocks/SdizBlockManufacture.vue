<template>
  <v-row v-show="loading" dense>
    <v-col cols="12" sm="6" xl="3">
      <label-component label="ИНН" />
      <text-component>
        {{ inn }}
      </text-component>
    </v-col>
    <v-col cols="12" sm="6" xl="3">
      <label-component label="КПП" />
      <text-component>
        {{ kpp }}
      </text-component>
    </v-col>

    <v-col v-if="!isChange && isHasLocation" cols="12">
      <label-component :label="labelLocation" />
      <text-component>
        {{ getAddressFromSdiz }}
      </text-component>

      <text-component
        v-if="!isChange && sdizModel[locationName] !== null && typeof sdizModel[locationName] === 'string'"
        cols="12"
      >
        {{ sdizModel[locationName] }}
      </text-component>
    </v-col>

    <v-col v-if="locationIdName !== undefined && isChange" class="mt-3" cols="12">
      <autocomplete-priority-address
        v-model="sdizModel[locationIdName]"
        :item-value="itemValueLocation"
        :label="labelLocation"
        :placeholder="placeholderLocation"
        :is-disabled="sdizModel.disabledProperties.includes(locationIdName)"
      />
    </v-col>
    <v-col v-if="repositoryIdName !== undefined && hideForm" class="mt-3" cols="12">
      <elevator-autocomplete
        v-if="isChange"
        v-model="sdizModel[repositoryIdName]"
        :label="labelRepository"
        placeholder="Начните вводить наименование, ИНН, КПП или ОГРН"
      />

      <InputComponent v-else :label="labelRepository" :value="repositoryRegistrationNumber" disabled placeholder="-" />
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Component, Model, Prop, Watch, Mixins } from 'vue-property-decorator';
import LabelComponent from '@/components/common/Label/Label.vue';
import SelectRequestComponent from '@/components/Forms/Select/SelectRequestComponent.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import { FiasMix } from '@/utils/mixins/fias';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import { Manufactures } from '@/utils/mixins/manufactures';
import TextComponent from '@/components/common/TextComponent.vue';
import AutocompletePriorityAddress from '@/components/PriorityAddress/PriorityAddress.vue';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import ElevatorAutocomplete from '@/components/common/ElevatorAutocomplete/ElevatorAutocomplete.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';

@Component({
  name: 'sdiz-block-manufacture',
  components: {
    InputComponent,
    AutocompletePriorityAddress,
    TextComponent,
    AutocompleteComponent,
    SelectRequestComponent,
    LabelComponent,
    ElevatorAutocomplete,
  },
})
export default class SdizBlockManufacture extends Mixins(FiasMix, Manufactures) {
  @Model('change', { default: null, required: true }) readonly valueId!: number | string;

  @Prop({ required: true }) readonly sdizModel!: SdizVueModel | SdizGpbVueModel;

  @Prop({ required: true }) readonly manufacture!: any;

  @Prop({ type: Boolean, default: false }) isChange!: boolean;

  id = 0;

  name = '';
  inn = '-';
  kpp = '-';

  loading = true;

  currentManufacturer: any = null;

  get manufactureId() {
    return this.sdizModel[this.manufacture.info.manufactureIdName];
  }

  get itemValueLocation() {
    return this.manufacture.info.itemValue ?? 'id';
  }

  get mainLabel() {
    return this.manufacture.autocomplete.label ?? 'Полное наименование';
  }

  get labelRepository() {
    return this.manufacture.info.labelRepository ?? 'Реестровый номер организации';
  }

  get labelLocation() {
    return this.manufacture.info.labelLocation ?? 'Местоположение';
  }

  get placeholderLocation() {
    return this.manufacture.info.placeholderLocation ?? 'Выберите местоположение';
  }

  get locationIdName() {
    return this.manufacture.info.locationIdName;
  }

  get locationName() {
    return this.manufacture.info.locationName;
  }

  get isHasLocation() {
    return (
      this.sdizModel[this.manufacture.info.locationName] !== null &&
      this.sdizModel[this.manufacture.info.locationName] !== undefined
    );
  }

  get getAddressFromSdiz() {
    return this.sdizModel[this.manufacture.info.locationName].address;
  }

  get repositoryIdName() {
    return this.manufacture.info.repositoryIdName;
  }

  get repositoryRegistrationNumber() {
    return this.sdizModel[this.manufacture.info.repositoryRegistrationNumberName] || '-';
  }

  get prototypeSdiz() {
    return this.sdizModel.objects.operations.prototype_sdiz;
  }

  get hideForm() {
    if (this.repositoryIdName === 'shipper_repository_id' && this.prototypeSdiz === 2) return false;
    return !(this.repositoryIdName === 'consignee_repository_id' && this.prototypeSdiz === 3);
  }

  async created(): Promise<void> {
    await this.setModelManufacture();
  }

  @Watch('valueId')
  async onChangeManufacture() {
    await this.setModelManufacture();
  }

  async setModelManufacture(): Promise<void> {
    if (!this.valueId) {
      this.inn = '-';
      this.kpp = '-';
      return;
    }

    let item = await this.getManufacturerByIdMix(this.manufactureId);

    if (item !== '' && item !== null && item !== undefined) {
      const { id, kpp, inn, name } = typeof item.subject === 'undefined' ? item : item.subject;

      this.id = id;
      this.name = name;
      this.inn = inn;
      this.kpp = kpp || '-';
    }
  }
}
</script>
