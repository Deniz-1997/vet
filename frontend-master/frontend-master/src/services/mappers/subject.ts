import { Mapper } from '@/utils';
import { ISubjectItemResponse, TSubjectInnerFilter } from '@/services/models/subject';
import { FilterOut } from '@/services/mappers/common';
import { AddressMapperIn, AddressMapperOut } from './address';
import { TMapperPlain } from '../models/common';
import isEmpty from 'lodash/isEmpty';

export class SubjectItemIn extends Mapper<ISubjectItemResponse> {
  @Mapper.catch()
  get inn_kpp() {
    return this.get(({ inn_kpp }) => inn_kpp).optional.value;
  }

  @Mapper.catch()
  get inn() {
    return this.get(({ inn }) => inn).optional.value;
  }

  @Mapper.catch()
  get kpp() {
    return this.get(({ kpp }) => kpp).optional.value;
  }

  @Mapper.catch()
  get name() {
    return this.get(({ name, short_name }) => short_name || name).optional.value;
  }

  @Mapper.catch()
  get ogrn() {
    return this.get(({ ogrn }) => ogrn).optional.value;
  }

  @Mapper.catch()
  get subject_id() {
    return this.get(({ subject_id }) => subject_id).optional.value;
  }

  @Mapper.catch()
  get subject_type() {
    return this.get(({ subject_type }) => subject_type).optional.value;
  }

  @Mapper.catch()
  get opf_name() {
    return this.get(({ opf_name }) => opf_name).optional.value;
  }

  @Mapper.catch()
  get propertyMap() {
    return this.get(({ propertyMap }) => propertyMap).optional.value;
  }

  @Mapper.catch()
  get region() {
    return this.get(({ region }) => region).optional.value;
  }

  toJSON() {
    return {
      inn_kpp: this.inn_kpp,
      inn: this.inn,
      kpp: this.kpp,
      name: this.name,
      ogrn: this.ogrn,
      subject_id: this.subject_id,
      subject_type: this.subject_type,
      opf_name: this.opf_name,
      propertyMap: this.propertyMap,
      region: this.region,
    };
  }
}

export class LabSubjectItemIn extends Mapper<any> {
  @Mapper.catch()
  get id() {
    return this.get(({ subject }) => subject.subject_id).required.value;
  }

  @Mapper.catch()
  get includeDate() {
    return this.get(({ lab_include_date }) => lab_include_date).optional.date().value;
  }

  @Mapper.catch()
  get exclusionDate() {
    return this.get(({ exclusion_date }) => exclusion_date).optional.date().value;
  }

  @Mapper.catch()
  get exclusionReason() {
    return this.get(({ reason_exclusion }) => reason_exclusion).optional.value;
  }

  @Mapper.catch()
  get inn() {
    return this.get(({ subject }) => subject?.subject_data?.inn ?? subject?.inn).optional.value;
  }

  @Mapper.catch()
  get innKpp() {
    return this.get(({ subject }) => subject?.subject_data?.inn_kpp ?? subject?.inn_kpp).optional.value;
  }

  @Mapper.catch()
  get created_by() {
    return this.get(({ subject }) => subject?.created_by ?? subject?.created_by).optional.value;
  }

  @Mapper.catch()
  get kpp() {
    return this.get(({ subject }) => subject?.subject_data?.kpp ?? subject?.kpp).optional.value;
  }

  @Mapper.catch()
  get ogrn() {
    return this.get(({ subject }) => subject?.subject_data?.ogrn ?? subject?.ogrn).optional.value;
  }

  @Mapper.catch()
  get name() {
    return this.get(({ subject }) => subject?.subject_data?.name ?? subject?.name).required.value;
  }

  @Mapper.catch()
  get opf() {
    return this.get(({ subject }) => subject?.subject_data?.opf ?? subject?.opf).optional.value;
  }

  @Mapper.catch()
  get shortName() {
    return this.get(({ subject }) => subject?.subject_data?.short_name ?? subject?.short_name).optional.value;
  }

  @Mapper.catch()
  get subjectId() {
    return this.get(({ subject }) => subject?.subject_id).optional.value;
  }

  @Mapper.catch()
  get subjectDataId() {
    return this.get(({ subject }) => subject?.subject_data?.subject_data_id ?? subject?.subject_data_id).optional.value;
  }

  @Mapper.catch()
  get subjectType() {
    return this.get(({ subject }) => subject?.subject_type).optional.value;
  }

  @Mapper.catch()
  get region() {
    return this.get(({ subject }) => subject?.oker?.name_okato).optional.value;
  }

  @Mapper.catch()
  get address() {
    return this.get(({ subject }) => subject?.address && new AddressMapperIn(subject?.address).toJSON()).optional.value;
  }

  @Mapper.catch()
  get firstName() {
    return this.get(({ subject }) => subject?.subject_data?.first_name ?? subject?.first_name).optional.value;
  }

  @Mapper.catch()
  get secondName() {
    return this.get(({ subject }) => subject?.subject_data?.second_name ?? subject?.second_name).optional.value;
  }

  @Mapper.catch()
  get lastName() {
    return this.get(({ subject }) => subject?.subject_data?.last_name ?? subject?.last_name).optional.value;
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
    return this.get(({ subject }) => subject?.subject_data?.email ?? subject?.email).optional.value;
  }

  @Mapper.catch()
  get phoneNumber() {
    return this.get(({ subject }) => subject?.subject_data?.phone_number ?? subject?.phone_number).optional.value;
  }

  @Mapper.catch()
  get country() {
    return this.get(({ subject }) => subject?.subject_data?.country ?? subject?.country).optional.value;
  }

  @Mapper.catch()
  get certificates_string() {
    return this.get(({ certificates_string }) => certificates_string).optional.value;
  }

  @Mapper.catch()
  get location_concat() {
    return this.get(({ location_concat }) => location_concat).optional.value;
  }

  @Mapper.catch()
  get status() {
    return this.get(({ subject }) => subject?.verification_status).optional.value;
  }

  @Mapper.catch()
  get created() {
    return this.get(({ subject }) => subject.created).optional.value;
  }

  @Mapper.catch()
  get certificates() {
    return this.get(({ lab_certificates }) => this.mapCertificatesList(lab_certificates)).optional.value;
  }

  @Mapper.catch()
  get locations() {
    return this.get(({ lab_locations }) => lab_locations).optional.value;
  }

  @Mapper.catch()
  get headSubject() {
    return this.get(({ lab_head_subject }) => {
      if (!lab_head_subject) return null;
      return new SubjectItemIn(lab_head_subject).toJSON();
    }).optional.value;
  }

  @Mapper.catch()
  get isProcessor() {
    return this.get(({ manufacturer_is_processor }) => manufacturer_is_processor).optional.value;
  }

  @Mapper.catch()
  get nza() {
    return this.get(({ subject }) => subject?.subject_data?.nza ?? subject.nza).optional.value;
  }

  @Mapper.catch()
  get registers() {
    return {
      manufacturer: this.get(({ subject }) => subject.propertyMap.is_manufacturer).optional.value,
      laboratory: this.get(({ subject }) => subject.propertyMap.is_laboratory).optional.value,
      ogv: this.get(({ subject }) => subject.propertyMap.is_ogv).optional.value,
    };
  }
  // ToDo: Удалить в след версии (сделано для запрета исключения)
  @Mapper.catch()
  get involved_registries() {
    return {
      manufacturer: this.get(({ subject }) => subject.propertyMap.is_manufacturer).optional.value,
      laboratory: this.get(({ subject }) => subject.propertyMap.is_laboratory).optional.value,
      ogv: this.get(({ subject }) => subject.propertyMap.is_ogv).optional.value,
    };
  }

  @Mapper.catch()
  get attachedId() {
    return {
      manufacturer: this.get(({ manufacturer_id }) => manufacturer_id).optional.value,
      laboratory: this.get(({ lab_id }) => lab_id).optional.value,
      ogv: this.get(({ ogv_id }) => ogv_id).optional.value,
    };
  }

  @Mapper.catch()
  get ogv_id() {
    return this.get(({ ogv_id }) => ogv_id).optional.value;
  }

  @Mapper.catch()
  get manufacturer_id() {
    return this.get(({ manufacturer_id }) => manufacturer_id).optional.value;
  }

  @Mapper.catch()
  get lab_id() {
    return this.get(({ lab_id }) => lab_id).optional.value;
  }

  @Mapper.catch()
  get head_subject_id() {
    return this.get(({ head_subject_id }) => head_subject_id).optional.value;
  }

  @Mapper.catch()
  get manufacturer_registry_inclusion_date() {
    return this.get(({ manufacturer_registry_inclusion_date }) => manufacturer_registry_inclusion_date).optional.value;
  }

  @Mapper.catch()
  get ogv_start_date() {
    return this.get(({ ogv_start_date }) => ogv_start_date).optional.value;
  }

  @Mapper.catch()
  get lab_start_date() {
    return this.get(({ lab_start_date }) => lab_start_date).optional.value;
  }

  private mapCertificatesList(list: any[]) {
    return list.map((item) => ({
      doc_num: item.document.doc_num,
      doc_id: item.document.id,
      ...item,
    }));
  }

  @Mapper.catch()
  get divisions() {
    return this.get(({ subject }) => this.mapDivisionsList(subject.divisions)).optional.value;
  }
  private mapDivisionsList(list: any[]) {
    return (list || []).map((item) => ({
      division_staff: (item.division_staff || []).map((staff) => ({
        ...staff,
        start_date: staff.start_date,
        subject_division_id: staff.subject_division_id,
        subject_division_staff_id: staff.subject_division_staff_id,
        user_id: staff.user_id,
        name: staff.user_full_name,
        staff: { ...staff },
      })),
      staff_division: item.division_staff_user_full_names,
      parent_division: {
        name: item.parent_division?.name,
        subject_division_id: item.parent_division?.subject_division_id,
        label: item.parent_division?.name,
        value: item.parent_division?.subject_division_id,
      },
      division_staff_user_full_names: item.division_staff_user_full_names,
      start_date: item.start_date,
      subject_division_id: item.subject_division_id,
      subject_id: item.subject_id,
      name: item.name,
    }));
  }

  // eslint-disable-next-line max-lines-per-function
  toJSON() {
    return {
      id: this.id,
      inn: this.inn,
      kpp: this.kpp,
      ogrn: this.ogrn,
      opf: this.opf,
      nza: this.nza,
      name: this.name,
      shortName: this.shortName,
      subjectType: this.subjectType,
      region: this.region,
      innKpp: this.innKpp,
      subjectId: this.subjectId,
      subjectDataId: this.subjectDataId,
      includeDate: this.includeDate,
      exclusionDate: this.exclusionDate,
      exclusionReason: this.exclusionReason,
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
      location_concat: this.location_concat,
      certificates_string: this.certificates_string,
      status: this.status,
      created: this.created,
      certificates: this.certificates,
      locations: this.locations,
      headSubject: this.headSubject,
      isProcessor: this.isProcessor,
      registers: this.registers,
      attachedId: this.attachedId,
      ogv_id: this.ogv_id,
      manufacturer_id: this.manufacturer_id,
      lab_id: this.lab_id,
      head_subject_id: this.head_subject_id,
      ogv_start_date: this.ogv_start_date,
      lab_start_date: this.lab_start_date,
      manufacturer_registry_inclusion_date: this.manufacturer_registry_inclusion_date,
      divisions: this.divisions,
      involved_registries: this.involved_registries,
      created_by: this.created_by,
    };
  }
}

export class SubjectFilterOut extends FilterOut<TSubjectInnerFilter> {
  @Mapper.catch()
  get registry() {
    return this.get(({ registry }) => registry).optional.value;
  }

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

  get in_registry() {
    return this.get(({ in_registry }) => in_registry).optional.value;
  }

  get actual() {
    return this.get(({ actual }) => actual).optional.value;
  }

  toJSON() {
    return {
      ...super.toJSON(),
      registry: this.registry,
      name: this.name,
      subject_type: this.subject_type,
      opf: this.opf,
      inn: this.inn,
      kpp: this.kpp,
      ogrn: this.ogrn,
      oker_id: this.oker_id,
      in_registry: this.in_registry,
      actual: this.actual,
    };
  }
}

export class SubjectItemOut extends Mapper<TMapperPlain<LabSubjectItemIn>> {
  // eslint-disable-next-line max-lines-per-function
  @Mapper.catch()
  get subject() {
    return this.required({
      id: this.get(({ id }) => id).optional.value,
      subject_type: this.get(({ subjectType }) => subjectType).optional.value,
      // region: this.optional(() => ({
      //   subject_code: this.get(({ regionCode }) => regionCode).optional.value,
      //   name_okato: this.get(({ regionName }) => regionName).optional.value,
      // })),
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
      propertyMap: {
        is_manufacturer: this.get(({ registers }) => registers?.manufacturer).optional.value,
        is_laboratory: this.get(({ registers }) => registers?.laboratory).optional.value,
        is_ogv: this.get(({ registers }) => registers?.ogv).optional.value,
      },
      is_processor: this.get(({ isProcessor }) => isProcessor).optional.value,
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
      })) as any,
      divisions: this.get(({ divisions }) => this.mapDivisionOutList(divisions || [])).optional.value,
    });
  }

  private mapDivisionOutList(list: any[]) {
    return (list || []).map((item) => ({
      division_staff: (item.division_staff || []).map((staff) => ({
        ...staff,
      })),
      parent_division_id: item.parent_division.subject_division_id,
      front_parent_id: item.parent_division?.front_id,
      front_id: item?.front_id,
      start_date: item.start_date,
      subject_division_id: item.subject_division_id,
      subject_id: item.subject_id,
      name: item.name,
    }));
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
  get ogv_id() {
    return this.get(({ ogv_id }) => ogv_id).optional.value;
  }

  @Mapper.catch()
  get lab_id() {
    return this.get(({ lab_id }) => lab_id).optional.value;
  }

  @Mapper.catch()
  get manufacturer_id() {
    return this.get(({ manufacturer_id }) => manufacturer_id).optional.value;
  }

  @Mapper.catch()
  get manufacturer_is_processor() {
    const isManufacturer = this.get(({ registers }) => registers.manufacturer).optional.value;

    if (!isManufacturer) {
      return undefined;
    }

    return this.get(({ isProcessor }) => isProcessor).optional.value;
  }

  @Mapper.catch()
  get lab_include_date() {
    return this.get(({ includeDate }) => includeDate).optional.date().value;
  }

  @Mapper.catch()
  get exclusion_date() {
    return this.get(({ exclusionDate }) => exclusionDate).optional.date().value;
  }

  @Mapper.catch()
  get reason_exclusion() {
    return this.get(({ exclusionReason }) => exclusionReason).optional.value;
  }

  @Mapper.catch()
  get lab_certificates() {
    const isLab = this.get(({ registers }) => registers.laboratory).optional.value;

    if (!isLab) {
      return undefined;
    }

    const list = this.get(({ certificates }) => certificates).optional.value;

    return (list || []).map((item) => ({
      ...item,
      document: {
        doc_num: item.doc_num,
        id: item.doc_id,
      },
    }));
  }

  @Mapper.catch()
  get lab_locations() {
    return this.get(({ locations }) => locations).optional.value;
  }

  @Mapper.catch()
  get lab_head_subject() {
    return this.get(({ headSubject }) => headSubject).optional.value;
  }

  @Mapper.catch()
  get manufacturer_registry_inclusion_date() {
    return this.get(({ manufacturer_registry_inclusion_date }) => manufacturer_registry_inclusion_date).optional.value;
  }

  @Mapper.catch()
  get ogv_start_date() {
    return this.get(({ ogv_start_date }) => ogv_start_date).optional.value;
  }

  @Mapper.catch()
  get lab_start_date() {
    return this.get(({ lab_start_date }) => lab_start_date).optional.value;
  }

  toJSON() {
    return {
      id: this.id,
      manufacturer_is_processor: this.manufacturer_is_processor,
      subject_id: this.subject_id,
      subject: this.subject,
      ogv_id: this.ogv_id,
      manufacturer_id: this.manufacturer_id,
      lab_id: this.lab_id,
      lab_head_subject: this.lab_head_subject,
      lab_certificates: this.lab_certificates,
      lab_locations: this.lab_locations,
      manufacturer_registry_inclusion_date: this.manufacturer_registry_inclusion_date,
      ogv_start_date: this.ogv_start_date,
      lab_start_date: this.lab_start_date,
    };
  }
}
