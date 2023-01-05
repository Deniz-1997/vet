<template>
  <UiForm
    tag="div"
    :rules="rules"
    :messages="messages"
    class="address"
    @validation="(v) => (isValid = v.isValid)"
    @submit="saveAction"
  >
    <v-row>
      <v-col cols="12">
        <UiControl name="country" :value="selectCountry && selectCountry.country_id">
          <AutocompleteComponent
            id="address__select-country"
            v-model="selectCountry"
            return-object
            :items="options.country"
            item-value="country_id"
            item-text="name_full"
            label="Выберите страну"
            :is-disabled="!isForeign"
            auto-select-first
            autocomplete="off"
            name="country"
            @change="changeCountry"
          />
        </UiControl>
      </v-col>
    </v-row>

    <v-row v-if="selectCountry && selectCountry.country_id === 1">
      <v-col cols="12">
        <div class="context-search">
          <UiControl name="address" :value="selectAddress && selectAddress.aoguid">
            <label-component label="Введите адрес" />
            <Input-component
              id="address"
              v-model="addressString"
              autocomplete="off"
              :loading="isLoading"
              @click="isFocus = true"
            />
          </UiControl>
          <v-radio-group v-model="divType" @change="changeDivType">
            <v-row>
              <v-col cols="12">
                <v-radio label="Административно-территориальное деление" value="ADM" />
              </v-col>
              <v-col cols="12">
                <v-radio label="Муниципальное деление" value="MUN" />
              </v-col>
            </v-row>
          </v-radio-group>
          <ul
            v-if="options.address.length && isFocus"
            v-click-outside="handleClickOutside"
            class="context-search__list"
            name="options.address"
          >
            <li v-if="isLoading" class="spinner">
              <v-progress-circular indeterminate size="32"></v-progress-circular>
            </li>
            <template v-else>
              <li v-for="item in options.address" :key="item.address_id" :name="item.address" @click="selectElem(item)">
                {{ item.address }}
              </li>
            </template>
          </ul>
        </div>
      </v-col>
    </v-row>

    <v-row v-else>
      <v-col cols="12">
        <UiControl name="address" :value="notAddressFias">
          <Input-component v-model="notAddressFias" label="Введите адрес" autocomplete="off" />
        </UiControl>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <Input-component
          v-model="additional_info"
          label="Дополнительный адрес"
          autocomplete="off"
          name="additional_info"
        />
      </v-col>
    </v-row>

    <v-row justify="end">
      <DefaultButton variant="primary" title="Сохранить" type="submit" :disabled="!isValid" />
    </v-row>
  </UiForm>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch, Model } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import LabelComponent from '@/components/common/Label/Label.vue';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';
import debounce from 'lodash/debounce';
import memoize from 'lodash/memoize';
import { IAddressCountryItem, IAddressItem } from '@/services/models/common';
import { ESubjectType } from '@/services/enums/subject';

type CountryItem = {
  country_id: number;
  code: string;
  code_alpha3: string;
  global_id: number;
  name: string;
  name_full: string;
  startDate?: string;
  startTime?: string;
  start_date?: string;
  code_alpha2?: string;
};

const onClickOutside = memoize((el, callback) => (evt) => {
  const selectList = [...document.querySelectorAll('.context-search__list')];
  const isNotSelectElem = selectList.every((element) => evt.target !== element && !element.contains(evt.target));

  if (evt.target !== el && !el.contains(evt.target) && isNotSelectElem) {
    callback(evt);
  }
});

@Component({
  name: 'address-form',
  components: {
    AutocompleteComponent,
    DefaultButton,
    InputComponent,
    LabelComponent,
  },
  directives: {
    clickOutside: {
      bind(el, { value: handler }) {
        window.addEventListener('click', onClickOutside(el, handler), {
          capture: true,
        });
      },
      unbind(el, { value: handler }) {
        window.removeEventListener('click', onClickOutside(el, handler), {
          capture: true,
        });
      },
    },
  },
})
export default class AddressForm extends Vue {
  @Prop({ type: String, required: false }) readonly subjectType?: ESubjectType;
  @Model('input', { type: Object }) readonly address!: IAddressItem;
  isValid = true;
  selectCountry: CountryItem = {
    country_id: 1,
    code: '643',
    name: 'РОССИЯ',
    name_full: 'Российская Федерация',
    code_alpha2: 'RU',
    code_alpha3: 'RUS',
    global_id: 273330335,
  };
  divType = 'ADM';
  addressString = '';
  originalOptions: { address: IAddressItem[]; country: IAddressCountryItem[] } = {
    address: [],
    country: [],
  };
  addressObjectList: IAddressItem[] = [];
  isFocus = false;
  isLoading = false;
  selectAddress: any = {};
  additional_info = '';
  notAddressFias = '';

  get options() {
    return {
      address: this.originalOptions.address,
      country: this.originalOptions.country.filter(({ code_alpha2 }) => this.isForeign || code_alpha2 === 'RU'),
    };
  }

  get isForeign() {
    return !this.subjectType || [ESubjectType.IR, ESubjectType.IF].includes(this.subjectType);
  }

  get rules() {
    return {
      country: 'required',
      address: 'required',
    };
  }

  get messages() {
    return {
      'required.address':
        this.selectCountry?.country_id === 1 ? 'Выберите адрес из списка' : 'Поле обязательно для заполнения',
    };
  }

  async mounted() {
    this.setSelected();
    await Promise.all([this.findAddress(), this.getCountry()]);
  }

  handleClickOutside() {
    this.isFocus = false;
  }

  setSelected() {
    const { country, address, additional_info, div_type } = this.address || {};

    if (!address) {
      return;
    }

    this.selectAddress = { ...this.address };
    this.selectCountry = country || this.selectCountry;
    this.addressString = address || this.addressString;
    this.additional_info = additional_info || this.additional_info;
    this.notAddressFias = address || this.notAddressFias;
    this.divType = div_type || this.divType;
    this.addressObjectList = [];
  }

  async getCountry() {
    const { content } = await this.$store.dispatch('country/getListCountry');

    this.originalOptions.country = content;
    return;
  }

  async findAddressHandler() {
    this.addressObjectList = this.addressObjectList.filter((r) => this.addressString.includes(r.full_address));
    const list = this.addressString
      .split(',')
      .map((r) => r.trim())
      .filter((r) => r.length != 0);
    const lastAddressObject = list[this.addressString.trim().endsWith(',') ? list.length - 1 : list.length - 2];
    const ao_guid =
      this.addressObjectList.filter((r) => r.full_address === lastAddressObject).map((r) => r.aoguid)[0] || null;
    const address =
      ao_guid !== null
        ? this.addressString
            .replace(this.addressObjectList.map((r) => r.full_address).join(', '), '')
            .replace(',', '')
            .trim()
        : this.addressString;

    this.isLoading = true;
    const data = await this.$store.dispatch('fias/findAddress', {
      countryId: 1,
      address: address,
      div_type: this.divType,
      ao_guid: ao_guid,
    });
    this.originalOptions.address = data?.content ?? this.originalOptions.address;

    this.isLoading = false;
    return data;
  }

  findAddressDebounced = debounce(this.findAddressHandler, 200);

  changeDivType() {
    this.addressString = '';
    this.findAddress();
    this.isFocus = true;
  }

  findAddress() {
    this.isLoading = true;
    return this.findAddressDebounced();
  }

  changeCountry() {
    this.addressString = '';
    this.originalOptions.address = [];
    this.additional_info = '';
    this.notAddressFias = '';
    this.findAddress();
  }

  selectElem(item: IAddressItem) {
    if (item.fiastype === 'ADDRESS_OBJECT') {
      this.addressObjectList.push(item);
    }
    this.addressString = this.addressString
      .split(',')
      .filter((v) => !!v.trim())
      .map((part) =>
        item.address.trim().toLowerCase().includes(part.trim().toLowerCase()) ? item.address.trim() : part.trim()
      )
      .join(', ');

    if (
      !this.addressString
        .split(',')
        .map((v) => v.trim())
        .includes(item.address)
    ) {
      this.addressString = [this.addressString, item.address].filter((v) => !!v).join(', ');
    }
    this.addressString += ', ';
    this.selectAddress = {
      ...item,
      address: this.addressString,
    };
    this.isFocus = true;
    document.getElementById('address')?.focus();
  }

  saveAction() {
    let address = this.selectCountry?.country_id === 1 ? this.addressString : this.notAddressFias;

    if (address.trim().endsWith(',')) {
      address = address.trim().substring(0, address.trim().length - 1);
    }

    this.$emit('saveAction', {
      ...this.selectAddress,
      address,
      country: this.selectCountry,
      additional_info: this.additional_info,
      div_type: this.divType,
    });

    this.selectAddress = {};
  }

  @Watch('addressString')
  filterAddress() {
    if (!this.addressString.includes(this.selectAddress?.address)) {
      this.selectAddress = {};
    }
    this.findAddress();
  }
}
</script>

<style lang="scss">
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.context-search {
  position: relative;
  width: 100%;

  .v-text-field {
    padding-top: 0 !important;
    margin-top: 0 !important;
  }

  .address__input.primary--text {
    color: $black-color !important;
    caret-color: $input-border-color !important;
  }

  .address {
    &__input {
      input {
        border: 1px solid $input-border-color;
        min-height: 40px;
        border-radius: 4px;
        padding: 0 12px 0 16px !important;
      }

      .v-input__slot {
        &::before {
          display: none !important;
        }

        &::after {
          display: none !important;
        }
      }
    }
  }

  &__list {
    background: $white-color;
    position: absolute;
    top: 70px;
    left: 0;
    width: 100%;
    border-radius: 8px;
    z-index: 10;
    margin: 0;
    padding: 5px 0 !important;
    box-shadow: 0 3px 5px rgba($black-color, 0.2);
    max-height: 200px;
    overflow-y: auto;
    height: auto;

    li {
      cursor: pointer;
      padding: 5px;
      transition: all 0.3s ease;

      &:hover {
        background: $light-grey-color;
      }
    }

    .spinner {
      text-align: center;
      cursor: default;

      &:hover {
        background: $white-color;
      }
    }
  }
}
</style>
