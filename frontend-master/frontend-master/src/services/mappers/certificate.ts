import { Mapper } from '@/utils';
import { TCertificateItemResponse } from '@/services/models/certificate';

export class CertificateItem extends Mapper<TCertificateItemResponse> {
  @Mapper.catch()
  get id() {
    return this.get(({ id }) => id).required.value;
  }

  @Mapper.catch()
  get number() {
    return this.get(({ cert_number }) => cert_number).required.value;
  }

  @Mapper.catch()
  get certificate() {
    return this.get(({ certificate }) => certificate).required.value;
  }

  @Mapper.catch()
  get subjectDN() {
    return this.get(({ subject_dn }) => subject_dn).required.value;
  }

  @Mapper.catch()
  get issuerDn() {
    return this.get(({ issuer_dn }) => issuer_dn).required.value;
  }

  @Mapper.catch()
  get isVerified() {
    return this.get(({ verified }) => verified).required.value;
  }

  @Mapper.catch()
  get isRoot() {
    return this.get(({ is_root }) => is_root).required.value;
  }

  @Mapper.catch()
  get startDate() {
    return this.get(({ start_date }) => start_date).required.date().value;
  }

  @Mapper.catch()
  get endDate() {
    return this.get(({ end_date }) => end_date).optional.date().value;
  }

  @Mapper.catch()
  get notValidBefore() {
    return this.get(({ not_valid_before }) => not_valid_before).required.date().value;
  }

  @Mapper.catch()
  get notValidAfter() {
    return this.get(({ not_valid_after }) => not_valid_after).required.date().value;
  }

  toJSON() {
    return {
      id: this.id,
      number: this.number,
      isRoot: this.isRoot,
      isVerified: this.isVerified,
      subjectDN: this.subjectDN,
      issuerDn: this.issuerDn,
      certificate: this.certificate,
      startDate: this.startDate,
      endDate: this.endDate,
      notValidBefore: this.notValidBefore,
      notValidAfter: this.notValidAfter,
    };
  }
}
