import {Injectable} from '@angular/core';
import {ReferenceStationModel} from '../models/reference/reference.station.models';
import {ReferenceBusinessEntityModel} from '../models/reference/reference.businessEntity.models';


@Injectable({
  providedIn: 'root'
})
export class SessionStorageService {

  constructor() { }

  public saveToSessionStorageObject(key: string, objectList: Array<any> | object): void {
    sessionStorage.setItem(key , JSON.stringify(objectList));
  }
  public saveToSessionStorageValue(key: string, value: string): void {
    sessionStorage.setItem(key , value);
  }

  public getSessionStorageObject(key: string): Array<ReferenceStationModel | ReferenceBusinessEntityModel | any>  | null {
    const objectList: Array<ReferenceStationModel | ReferenceBusinessEntityModel | any> | null = JSON.parse(sessionStorage.getItem(key));
    if (!!objectList) {
      return objectList;
    }
    return null;
  }
  public getSessionStorageValue(key: string): string | null {
    const value: string | null = sessionStorage.getItem(key);
    if (!!value) {
      return value;
    }
    return null;
  }

  public removeSessionStorageItem(key: string): void {
    sessionStorage.removeItem(key);
  }

  public clearSessionStorageItem(): void {
    sessionStorage.clear();
  }
}
