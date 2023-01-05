/* eslint-disable max-lines-per-function */
import { Mapper } from '@/utils';
import { IManufacturerItemRequestOut, IManufacturerItemResponse } from '@/services/models/manufacturer';
import { ISubjectData, TMapperPlain } from '../models/common';
import { AddressMapperIn, AddressMapperOut } from '@/services/mappers/address';
import isEmpty from 'lodash/isEmpty';
import { FilterOut } from '@/services/mappers/common';
import { TManufacturerInnerFilter } from '@/services/models/manufacturer';

export class ManufacturerItemIn extends Mapper<IManufacturerItemResponse> {
  @Mapper.catch()
  get id() {
    return this.get(({ id }) => id).required.value;
  }

  @Mapper.catch()
  get isProcessor() {
    return this.get(({ is_processor, processor }) => is_processor || processor).required.value;
  }

  @Mapper.catch()
  get isEsia() {
    return this.get(({ subject }) => !!subject?.esia_id).optional.value;
  }

  @Mapper.catch()
  get exclusionReason() {
    return this.get(({ exclusion_reason }) => exclusion_reason?.name).optional.value;
  }

  @Mapper.catch()
  get registryExclusionDate() {
    return this.get(({ registry_exclusion_date }) => registry_exclusion_date).optional.value;
  }

  @Mapper.catch()
  get registryInclusionDate() {
    return this.get(({ registry_inclusion_date }) => registry_inclusion_date).optional.value;
  }

  @Mapper.catch()
  get inn() {
    return this.get(({ subject }) => subject?.subject_data?.inn).optional.value;
  }

  @Mapper.catch()
  get innKpp() {
    return this.get(({ subject }) => subject?.subject_data?.inn_kpp).optional.value;
  }

  @Mapper.catch()
  get kpp() {
    return this.get(({ subject }) => subject?.subject_data?.kpp).optional.value;
  }

  @Mapper.catch()
  get ogrn() {
    return this.get(({ subject }) => subject?.subject_data?.ogrn).optional.value;
  }

  @Mapper.catch()
  get name() {
    return this.get(({ subject }) => subject?.subject_data?.name).required.value;
  }

  @Mapper.catch()
  get opf() {
    return this.get(({ subject }) => subject?.subject_data?.opf).optional.value;
  }

  @Mapper.catch()
  get shortName() {
    return this.get(({ subject }) => subject?.subject_data?.short_name).optional.value;
  }

  @Mapper.catch()
  get subjectId() {
    return this.get(({ subject }) => subject?.subject_id).optional.value;
  }

  @Mapper.catch()
  get subjectDataId() {
    return this.get(({ subject }) => subject?.subject_data?.subject_data_id).optional.value;
  }

  @Mapper.catch()
  get subjectType() {
    return this.get(({ subject }) => subject?.subject_type).optional.value;
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
  get nza() {
    return this.get(({ subject }) => subject?.subject_data?.nza).optional.value;
  }

  @Mapper.catch()
  get status() {
    return this.get(({ subject }) => subject?.verification_status).optional.value;
  }

  @Mapper.catch()
  get address() {
    return this.get(({ subject }) => subject?.address && new AddressMapperIn(subject?.address).toJSON()).optional.value;
  }

  @Mapper.catch()
  get firstName() {
    return this.get(({ subject }) => subject?.subject_data?.first_name).optional.value;
  }

  @Mapper.catch()
  get secondName() {
    return this.get(({ subject }) => subject?.subject_data?.second_name).optional.value;
  }

  @Mapper.catch()
  get lastName() {
    return this.get(({ subject }) => subject?.subject_data?.last_name).optional.value;
  }

  @Mapper.catch()
  get identityType() {
    return this.get(({ subject }) => subject?.identity_doc?.type).optional.value;
  }

  @Mapper.catch()
  get docNumber() {
    return this.get(({ subject }) => subject?.identity_doc?.id_number).optional.value;
  }

  @Mapper.catch()
  get docSeries() {
    return this.get(({ subject }) => subject?.identity_doc?.series).optional.value;
  }

  @Mapper.catch()
  get docDate() {
    return this.get(({ subject }) => subject?.identity_doc?.doc_date).optional.value;
  }

  @Mapper.catch()
  get email() {
    return this.get(({ subject }) => subject?.email).optional.value;
  }

  @Mapper.catch()
  get phoneNumber() {
    return this.get(({ subject }) => subject?.phone_number).optional.value;
  }

  @Mapper.catch()
  get country() {
    return this.get(({ subject }) => subject?.country).optional.value;
  }

  @Mapper.catch()
  get created() {
    return this.get(({ created }) => created).optional.value;
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
      created: this.created,
      id: this.id,
      isProcessor: this.isProcessor,
      isEsia: this.isEsia,
      inn: this.inn,
      kpp: this.kpp,
      ogrn: this.ogrn,
      opf: this.opf,
      name: this.name,
      shortName: this.shortName,
      subjectType: this.subjectType,
      regionName: this.regionName,
      regionCode: this.regionCode,
      nza: this.nza,
      innKpp: this.innKpp,
      subjectId: this.subjectId,
      subjectDataId: this.subjectDataId,
      registryExclusionDate: this.registryExclusionDate,
      exclusionReason: this.exclusionReason,
      registryInclusionDate: this.registryInclusionDate,
      address: this.address,
      firstName: this.firstName,
      lastName: this.lastName,
      secondName: this.secondName,
      identityType: this.identityType,
      docSeries: this.docSeries,
      docNumber: this.docNumber,
      docDate: this.docDate,
      email: this.email,
      phoneNumber: this.phoneNumber,
      country: this.country,
      status: this.status,
      registers: this.registers,
    };
  }
}

export class ManufacturerItemOut
  extends Mapper<TMapperPlain<ManufacturerItemIn>>
  implements IManufacturerItemRequestOut
{
  @Mapper.catch()
  get subject() {
    return this.required({
      id: this.get(({ id }) => id).optional.value,
      processor: this.get(({ isProcessor }) => isProcessor).optional.value,
      subject_type: this.get(({ subjectType }) => subjectType).optional.value,
      region: this.optional(() => ({
        subject_code: this.get(({ regionCode }) => regionCode).optional.value,
        name_okato: this.get(({ regionName }) => regionName).optional.value,
      })),
      subject_id: this.get(({ subjectId }) => subjectId).optional.value,
      address: this.get(({ address }) =>
        address && !isEmpty(address) ? new AddressMapperOut(address).toJSON() : undefined
      ).optional.value,
      identity_doc: this.optional(() => ({
        type: this.get(({ identityType }) => identityType).required.value,
        series: this.get(({ docSeries }) => docSeries).required.value,
        id_number: this.get(({ docNumber }) => docNumber).required.value,
        doc_date: this.get(({ docDate }) => docDate).required.value,
      })),
      subject_data: this.optional(() => ({
        subject_id: this.get(({ subjectId }) => subjectId).optional.value,
        subject_data_id: this.get(({ subjectDataId }) => subjectDataId).optional.value,
        first_name: this.get(({ firstName }) => firstName).optional.value,
        last_name: this.get(({ lastName }) => lastName).optional.value,
        second_name: this.get(({ secondName }) => secondName).optional.value,
        nza: this.get(({ nza }) => nza).optional.value,
        email: this.get(({ email }) => email).optional.value,
        phone_number: this.get(({ phoneNumber }) => phoneNumber).optional.value,
        inn: this.get(({ inn }) => inn).optional.value,
        kpp: this.get(({ kpp }) => kpp).optional.value,
        ogrn: this.get(({ ogrn }) => ogrn).optional.value,
        opf: this.get(({ opf }) => opf).optional.value,
        short_name: this.get(({ shortName }) => shortName).optional.value,
        country: this.get(({ country }) => country).optional.value,
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

  @Mapper.catch()
  get manufacturer_is_processor() {
    return this.get(({ isProcessor }) => isProcessor).optional.value;
  }

  toJSON() {
    return {
      id: this.id,
      manufacturer_is_processor: this.manufacturer_is_processor,
      subject_id: this.subject_id,
      subject: this.subject,
    };
  }
}

export class ManufacturerFilterOut extends FilterOut<TManufacturerInnerFilter> {
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

  get is_processor() {
    return this.get(({ is_processor }) => is_processor).optional.value;
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
      is_processor: this.is_processor,
    };
  }
}