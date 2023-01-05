import { XHRLayout } from '@/core/utils/xhr';
import { publicApi } from '@/core/consts';

export class RshnService {
  private xhr: XHRLayout = new XHRLayout();

  public showWithdrawal = (id) => {
    return this.xhr.get(`${publicApi}rshn/withdrawal/show/${id}`);
  };

  public createWithdrawal = (data) => {
    return this.xhr.post(`${publicApi}rshn/withdrawal/create`, data.data);
  };

  public getListWithdrawal = (data) => {
    return this.xhr.post(`${publicApi}rshn/withdrawal/list`, data);
  };

  public updateWithdrawal = (id, data) => {
    return this.xhr.put(`${publicApi}rshn/withdrawal/?id=${id}`, data);
  };

  public deleteWithdrawal = (id) => {
    return this.xhr.delete(`${publicApi}rshn/withdrawal/?id=${id}`);
  };

  public createWithdrawalRestriction = (data) => {
    return this.xhr.post(`${publicApi}rshn/withdrawal/restriction/create`, data);
  };
  public deleteWithdrawalRestriction = (id) => {
    return this.xhr.delete(`${publicApi}rshn/withdrawal/restriction/?id=${id}`);
  };
  public updateWithdrawalRestriction = (id, data) => {
    return this.xhr.put(`${publicApi}rshn/withdrawal/restriction/?id=${id}`, data);
  };

  public showPrescription = (id) => {
    return this.xhr.get(`${publicApi}rshn/withdrawal/prescription/show/${id}`);
  };

  public createPrescription = (data) => {
    return this.xhr.post(`${publicApi}rshn/withdrawal/prescription/create`, data.data);
  };

  public getListPrescription = (data) => {
    return this.xhr.post(`${publicApi}rshn/withdrawal/prescription/list`, data);
  };

  public updatePrescription = (id, data) => {
    return this.xhr.put(`${publicApi}rshn/withdrawal/prescription/?id=${id}`, data);
  };

  public deletePrescription = (id) => {
    return this.xhr.delete(`${publicApi}rshn/withdrawal/prescription/?id=${id}`);
  };

  public createPrescriptionDoc = (data) => {
    return this.xhr.post(`${publicApi}rshn/withdrawal/prescription/doc/create`, data);
  };
  public deletePrescriptionDoc = (id) => {
    return this.xhr.delete(`${publicApi}rshn/withdrawal/prescription/doc/?id=${id}`);
  };
  public updatePrescriptionDoc = (id, data) => {
    return this.xhr.put(`${publicApi}rshn/withdrawal/prescription/doc/?id=${id}`, data);
  };
  public cancelPrescriptionDoc = (id) => {
    return this.xhr.delete(`${publicApi}rshn/withdrawal/prescription/doc/cancel/?id=${id}`);
  };

  public showExpertise = (id) => {
    return this.xhr.get(`${publicApi}rshn/withdrawal/expertise/show/${id}`);
  };

  public createExpertise = (data) => {
    return this.xhr.post(`${publicApi}rshn/withdrawal/expertise/create`, data.data);
  };

  public getListExpertise = (data) => {
    return this.xhr.post(`${publicApi}rshn/withdrawal/expertise/list`, data);
  };

  public updateExpertise = (id, data) => {
    return this.xhr.put(`${publicApi}rshn/withdrawal/expertise/?id=${id}`, data);
  };

  public deleteExpertise = (id) => {
    return this.xhr.delete(`${publicApi}rshn/withdrawal/expertise/?id=${id}`);
  };

  public findSdizByNumber = (number: string) => {
    return this.xhr.get(`${publicApi}rshn/withdrawal/findSdizByNumber/${number}`);
  };

  public findLotByNumber = (number: string) => {
    return this.xhr.get(`${publicApi}rshn/withdrawal/findLotByNumber/${number}`);
  };
}
