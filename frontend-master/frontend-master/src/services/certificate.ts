import { Service } from '@/plugins/service';
import { CertificateItem } from './mappers/certificate';
import { FilterOut } from './mappers/common';
import { TInnerFilter } from './models/common';

export class SystemCertificate extends Service {
  async find(filter: TInnerFilter) {
    const response = await this.$axios.post('/api/security/system-certificate/', new FilterOut(filter));

    return { ...response, data: (response.data?.content || []).map((item) => new CertificateItem(item).toJSON()) };
  }

  async findOne(id: number) {
    const response = await this.$axios.get(`/api/security/system-certificate/${id}`);

    return { ...response, data: new CertificateItem(response.data).toJSON() };
  }

  create(file: File, onUploadProgress) {
    const data = new FormData();
    data.append('file', file);
    return this.$axios.post('/api/security/system-certificate/add', data, { onUploadProgress, ignoreStatuses: [500] });
  }

  delete(id, force = false) {
    return this.$axios.post('/api/security/system-certificate/delete',
        { id },
        { params: { force }, ignoreStatuses: [400] }
    );
  }

  async verify(id: number) {
    const response = await this.$axios.post(`/api/security/system-certificate/verify/${id}`, {});

    return {
      ...response,
      data: { message: response.data?.message, certificate: new CertificateItem(response.data.certificate).toJSON() },
    };
  }
}

export default class Certificate extends Service {
  system: SystemCertificate;

  constructor(ctx) {
    super(ctx);
    this.system = new SystemCertificate(ctx);
  }

  async find(filter: TInnerFilter) {
    const response = await this.$axios.post('/api/security/certificate/', new FilterOut(filter));

    return { ...response, data: (response.data?.content || []).map((item) => new CertificateItem(item).toJSON()) };
  }

  async findOne(id: number) {
    const response = await this.$axios.get(`/api/security/certificate/${id}`);

    return { ...response, data: new CertificateItem(response.data).toJSON() };
  }

  create(file: File, onUploadProgress) {
    const data = new FormData();
    data.append('file', file);
    return this.$axios.post('/api/security/certificate/add', data, { onUploadProgress, ignoreStatuses: [500] });
  }

  delete(id, force = false) {
    return this.$axios.post('/api/security/certificate/delete',
        { id },
        { params: { force }, ignoreStatuses: [400] }
    );
  }

  async verify(id: number) {
    const response = await this.$axios.post(`/api/security/certificate/verify/${id}`, {});

    return {
      ...response,
      data: { message: response.data?.message, certificate: new CertificateItem(response.data.certificate).toJSON() },
    };
  }
}
