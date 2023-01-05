<template>
  <div>
    <autocomplete-component
      v-model="innerValue"
      :is-action-block="true"
      :items="addressList"
      :label="label"
      :placeholder="placeholder"
      :is-disabled="isDisabled"
      :clearable="clearable"
      item-text="address"
      item-value="id"
      @searchInputUpdate="searchAddress"
    >
      <template #action-item>
        <div class="v-list-item v-list-item--link theme--light">
          <div class="v-list-item__content" @click="toggleModal">
            <div class="v-list-item__title">
              <span class="settingsSpan">
                <img class="iconSettings" src="/icons/add.svg" alt="" />
                Добавить адрес
              </span>
            </div>
          </div>
        </div>
      </template>
    </autocomplete-component>
    <Dialog-component
      v-model="showModal"
      :prompt="false"
      cancel-title=""
      confirm-title=""
      controls-justify="justify-end"
      width="800"
      with-close-icon
    >
      <template #title> Адрес</template>
      <template #content>
        <Address v-model="address" @saveAction="addFields" />
      </template>
    </Dialog-component>
  </div>
</template>

<script lang="ts">
import { Component, Model, Prop, Vue } from 'vue-property-decorator';
import TextComponent from '@/components/common/Text/Text.vue';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import Datepicker from '@/components/common/Datepicker/Datepicker.vue';
import Address from '@/components/Address/Address.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import isEmpty from 'lodash/isEmpty';

@Component({
  name: 'autocomplete-priority-address',
  components: {
    TextComponent,
    DialogComponent,
    Datepicker,
    Address,
    AutocompleteComponent,
  },
})
export default class PriorityAddress extends Vue {
  @Model('change', { required: true }) value!: any;
  @Prop({ type: Boolean, required: false, default: false }) readonly isDisabled!: boolean;
  @Prop({ type: String, required: false, default: 'Место хранения' }) readonly label!: string;
  @Prop({ type: String, required: false, default: 'Выберите место' }) readonly placeholder!: string;
  @Prop({ type: Boolean, default: true }) readonly clearable!: boolean;

  additional_address = null;
  address = {
    address: '',
    additional_info: '',
  };
  showModal = false;

  response: any[] = [];

  get addressList(): any[] {
    return this.response;
  }

  set addressList(value) {
    this.$emit('onResponse', value);
    this.response = value;
  }

  get innerValue(): boolean {
    return this.value;
  }

  set innerValue(value: boolean) {
    this.$emit('change', value);
    this.$emit(
      'updateValue',
      this.addressList.find((e) => e.id === value)
    );
  }

  async fetchAddress(value = undefined): Promise<void> {
    const { content } = await this.$store.dispatch('priorityAddress/getList', {
      filter: value,
      pageable: {
        pageNumber: 0,
        pageSize: 100,
      },
      actual: true,
    });

    this.addressList = content;
  }

  async fetchAddressById(id): Promise<void> {
    if (!isEmpty(id)) {
      const response = await this.$store.dispatch('priorityAddress/showAddress', {
        id: id,
      });
      this.addressList = [...this.addressList, response];
    }
  }

  async created() {
    await this.fetchAddress();

    const addressVal = this.addressList.findIndex((address) => address.id === this.value);

    if (addressVal === -1) {
      await this.fetchAddressById(this.value);
    }
  }

  async searchAddress(value) {
    if (value === null) {
      return;
    }
    const itemIndex = this.addressList.findIndex((item: any) => item.address === value);
    if (itemIndex === -1) {
      await this.fetchAddress(value);
    }
  }

  toggleModal() {
    this.showModal = !this.showModal;
  }

  async addFields(data) {
    this.showModal = false;
    const { aoguid, houseguid, postalcode, additional_info, country } = data;

    const { id, address } = await this.$store.dispatch('priorityAddress/createAddress', {
      aoguid: aoguid,
      house_guid: houseguid,
      div_type: data.div_type,
      address: country.name_full + ', ' + data.address + ' ' + additional_info,
      postcode: postalcode,
      country: country,
    });

    this.addressList = [...this.addressList, { id: id, address: address }];
    this.innerValue = id;
  }
}
</script>

<style lang="scss" scoped>
.settingsSpan {
  display: flex;
  align-items: center;

  .iconSettings {
    display: block;
    margin-right: 5px;
  }
}
</style>
