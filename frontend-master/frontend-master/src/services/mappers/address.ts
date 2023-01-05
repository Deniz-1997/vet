import { IAddressItem, TMapperPlain } from '@/services/models/common';
import { Mapper } from '@/utils';

export class AddressMapperIn extends Mapper<IAddressItem> {
  @Mapper.catch()
  get additionalInfo() {
    return this.get(({ additional_info }) => additional_info).optional.value;
  }

  @Mapper.catch()
  get addressText() {
    return this.get(({ address }) => address).optional.value;
  }

  @Mapper.catch()
  get country() {
    return this.get(({ country }) => country).optional.value;
  }

  @Mapper.catch()
  get otherFields() {
    return this.get((item) => item).optional.value;
  }

  @Mapper.catch()
  get divType() {
    return this.get(({ div_type }) => div_type).optional.value;
  }

  @Mapper.catch()
  get oker() {
    return this.get(({ oker }) => oker).optional.value;
  }

  toJSON() {
    return {
      additionalInfo: this.additionalInfo,
      country: this.country,
      addressText: this.addressText,
      divType: this.divType,
      otherFields: this.otherFields,
      oker: this.oker,
    };
  }
}

export class AddressMapperOut extends Mapper<TMapperPlain<AddressMapperIn>> implements Partial<IAddressItem> {
  @Mapper.catch()
  get additional_info() {
    return this.get(({ additionalInfo }) => additionalInfo).optional.value;
  }

  @Mapper.catch()
  get full_address() {
    return this.get(({ addressText }) => addressText).optional.value;
  }

  @Mapper.catch()
  get country() {
    return this.get(({ country }) => country).optional.value;
  }

  @Mapper.catch()
  get div_type() {
    return this.get(({ divType }) => divType).optional.value;
  }

  toJSON(): IAddressItem {
    const other = this.get(({ otherFields }) => otherFields).optional.value;

    if (!other) {
      return {} as IAddressItem;
    }

    return {
      ...other,
      additional_info: this.additional_info,
      country: this.country,
      full_address: this.full_address || '',
      div_type: this.div_type || '',
    };
  }
}

export class AddressMapperOutPartial extends Mapper<TMapperPlain<AddressMapperIn>> implements Partial<IAddressItem> {
  @Mapper.catch()
  get additional_info() {
    return this.get(({ additionalInfo }) => additionalInfo).optional.value;
  }

  @Mapper.catch()
  get full_address() {
    return this.get(({ addressText }) => addressText).optional.value;
  }

  @Mapper.catch()
  get country() {
    return this.get(({ country }) => country).optional.value;
  }

  toJSON() {
    const other = this.get(({ otherFields }) => otherFields).optional.value;

    if (!other) {
      return {};
    }

    return {
      ...other,
      additional_info: this.additional_info,
      country: this.country,
      full_address: this.full_address,
    };
  }
}
