/* eslint-disable max-lines-per-function */
import { Mapper } from '@/utils';
import { IStateAuthorityItemRequestOut, IStateAuthorityItemResponse, TStateAuthorityInnerFilter } from '@/services/models/stateAuthority';
import { AddressMapperIn } from '@/services/mappers/address';
import { ISubjectData, TMapperPlain } from '../models/common';
import { FilterOut } from '@/services/mappers/common';

export class StateAuthorityItemIn extends Mapper<IStateAuthorityItemResponse> {
  @Mapper.catch()
  get id() {
    return this.get(({ id }) => id).required.value;
  }

  @Mapper.catch()
  get inn_kpp() {
    return this.get(({ subject }) => subject?.subject_data?.inn_kpp).optional.value;
  }

  @Mapper.catch()
  get ogrn() {
    return this.get(({ subject }) => subject?.subject_data?.ogrn).optional.value;
  }

  @Mapper.catch()
  get inn() {
    return this.get(({ subject }) => subject?.subject_data?.inn).optional.value;
  }

  @Mapper.catch()
  get kpp() {
    return this.get(({ subject }) => subject?.subject_data?.kpp).optional.value;
  }

  @Mapper.catch()
  get name() {
    return this.get(({ subject }) => subject?.subject_data?.name).required.value;
  }

  @Mapper.catch()
  get subjectId() {
    return this.get(({ subject }) => subject?.subject_data?.subject_id).required.value;
  }

  @Mapper.catch()
  get subjectType() {
    return this.get(({ subject }) => subject?.subject_type).required.value;
  }

  @Mapper.catch()
  get opf() {
    return this.get(({ subject }) => subject?.subject_data?.opf).optional.value;
  }

  @Mapper.catch()
  get address() {
    return this.get(({ subject }) => subject?.address && new AddressMapperIn(subject?.address).toJSON()).optional.value;
  }

  @Mapper.catch()
  get regionName() {
    return this.get(({ subject }) => subject?.oker?.name_okato).optional.value;
  }

  @Mapper.catch()
  get regionCode() {
    return this.get(({ subject }) => subject?.oker?.subject_code).optional.value;
  }

  @Mapper.catch()
  get shortName() {
    return this.get(({ subject }) => subject?.subject_data?.short_name).optional.value;
  }

  @Mapper.catch()
  get created() {
    return this.get(({ created }) => created).optional.value;
  }

  @Mapper.catch()
  get status() {
    return this.get(({ subject }) => subject?.verification_status).optional.value;
  }

  @Mapper.catch()
  get registers() {
    return {
      manufacturer: this.get(({ subject }) => subject?.propertyMap.is_manufacturer).optional.value,
      laboratory: this.get(({ subject }) => subject?.propertyMap.is_laboratory).optional.value,
      ogv: this.get(({ subject }) => subject?.propertyMap.is_ogv).optional.value,
    };
  }

  toJSON() {
    return {
      id: this.id,
      ogrn: this.ogrn,
      name: this.name,
      inn_kpp: this.inn_kpp,
      subjectId: this.subjectId,
      inn: this.inn,
      kpp: this.kpp,
      address: this.address,
      subjectType: this.subjectType,
      opf: this.opf,
      regionName: this.regionName,
      regionCode: this.regionCode,
      shortName: this.shortName,
      status: this.status,
      created: this.created,
      registers: this.registers,
    };
  }
}

export class StateAuthorityItemOut
  extends Mapper<TMapperPlain<StateAuthorityItemIn>>
  implements IStateAuthorityItemRequestOut
{
  @Mapper.catch()
  get subject() {
    return this.required({
      id: this.get(({ id }) => id).optional.value,
      subject_id: this.get(({ subjectId }) => subjectId).optional.value,
      subject_data: this.optional(() => ({
        subject_id: this.get(({ subjectId }) => subjectId).optional.value,
        ogrn: this.get(({ ogrn }) => ogrn).optional.value,
        name: this.get(({ name }) => name).optional.value,
      })) as ISubjectData,
    });
  }

  @Mapper.catch()
  get subject_id() {
    return this.get(({ subjectId }) => subjectId).optional.value;
  }

  @Mapper.catch()
  get id() {
    return this.get(({ id }) => id).optional.value;
  }

  toJSON() {
    return {
      id: this.id,
      subject_id: this.subject_id,
      subject_data: this.optional(() => ({
        subject_id: this.get(({ subjectId }) => subjectId).optional.value,
        // subject_data_id: this.get(({ subjectDataId }) => subjectDataId).optional.value,
        // first_name: this.get(({ firstName }) => firstName).optional.value,
        // last_name: this.get(({ lastName }) => lastName).optional.value,
        // second_name: this.get(({ secondName }) => secondName).optional.value,
        // nza: this.get(({ nza }) => nza).optional.value,
        // email: this.get(({ email }) => email).optional.value,
        // phone_number: this.get(({ phoneNumber }) => phoneNumber).optional.value,
        inn: this.get(({ inn }) => inn).optional.value,
        kpp: this.get(({ kpp }) => kpp).optional.value,
        ogrn: this.get(({ ogrn }) => ogrn).optional.value,
        opf: this.get(({ opf }) => opf).optional.value,
        // short_name: this.get(({ shortName }) => shortName).optional.value,
        // country: this.get(({ country }) => country).optional.value,
        name: this.get(({ name }) => name).optional.value,
      })) as ISubjectData,
    };
  }
}

export class StateAuthorityFilterOut extends FilterOut<TStateAuthorityInnerFilter> {
  @Mapper.catch()
  get name() {
    return this.get(({ name }) => name).optional.value;
  }

  get subject_type() {
    return this.get(({ subject_type }) => subject_type).optional.value;
  }

  get opf() {
    return this.get(({ opf }) => opf).optional.value;
  }

  get inn() {
    return this.get(({ inn }) => inn).optional.value;
  }

  get kpp() {
    return this.get(({ kpp }) => kpp).optional.value;
  }

  get ogrn() {
    return this.get(({ ogrn }) => ogrn).optional.value;
  }

  get oker_id() {
    return this.get(({ oker_id }) => oker_id).optional.value;
  }

  get include_date_start() {
    return this.get(({ include_date_start }) => include_date_start).optional.value;
  }

  get include_date_end() {
    return this.get(({ include_date_end }) => include_date_end).optional.value;
  }

  get exclusion_date_start() {
    return this.get(({ exclusion_date_start }) => exclusion_date_start).optional.value;
  }

  get exclusion_date_end() {
    return this.get(({ exclusion_date_end }) => exclusion_date_end).optional.value;
  }

  get reason_exclusion() {
    return this.get(({ reason_exclusion }) => reason_exclusion).optional.value;
  }

  get actual() {
    return this.get(({ actual }) => actual).optional.value;
  }

  toJSON() {
    return {
      ...super.toJSON(),
      name: this.name,
      subject_type: this.subject_type,
      opf: this.opf,
      inn: this.inn,
      kpp: this.kpp,
      ogrn: this.ogrn,
      oker_id: this.oker_id,
      include_date_start: this.include_date_start,
      include_date_end: this.include_date_end,
      exclusion_date_start: this.exclusion_date_start,
      exclusion_date_end: this.exclusion_date_end,
      reason_exclusion: this.reason_exclusion,
      actual: this.actual,
    };
  }
}