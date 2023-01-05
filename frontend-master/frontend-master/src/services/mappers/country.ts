import { IAddressCountryItem, TMapperPlain } from '@/services/models/common';
import { Mapper } from '@/utils';

export class CountryMapperIn extends Mapper<IAddressCountryItem> {
  @Mapper.catch()
  get nameFull() {
    return this.get(({ name_full }) => name_full).required.value;
  }

  @Mapper.catch()
  get countryId() {
    return this.get(({ country_id }) => country_id).required.value;
  }

  @Mapper.catch()
  get otherFields() {
    return this.get((item) => item).optional.value;
  }

  toJSON() {
    return {
      countryId: this.countryId,
      nameFull: this.nameFull,
      otherFields: this.otherFields,
    };
  }
}

export class CountryMapperOut extends Mapper<TMapperPlain<CountryMapperIn>> {
  @Mapper.catch()
  get country_id() {
    return this.get(({ countryId }) => countryId).required.value;
  }

  @Mapper.catch()
  get name_full() {
    return this.get(({ nameFull }) => nameFull).required.value;
  }

  toJSON(): IAddressCountryItem {
    const other = this.get(({ otherFields }) => otherFields).optional.value;

    if (!other) {
      return {} as IAddressCountryItem;
    }

    return {
      ...other,
      country_id: this.country_id,
      name_full: this.name_full,
    };
  }
}
