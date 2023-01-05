import {Injectable} from '@angular/core';
import {Store} from '@ngrx/store';
import {Observable, Subscriber} from 'rxjs';
import {CrudType} from '../common/crud-types';
import {AuthService} from './auth.service';
import {ReferenceBusinessEntityModel} from '../models/reference/reference.businessEntity.models';
import {ReferenceStationModel} from '../models/reference/reference.station.models';
import {CrudState} from '../api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from '../api/api-connector/crud/crud.actions';
import {Params} from '@angular/router';
import {SessionStorageService} from './sessionStorage.service';
import {StationListConvertService} from './stationListConvert.service';


@Injectable({
  providedIn: 'root'
})
export class UserObjectListService  {

  protected userObjectList: Array<ReferenceBusinessEntityModel | ReferenceStationModel>;
  protected currentObject: ReferenceBusinessEntityModel | ReferenceStationModel;
  protected typeObject: string;
  private subscriber: Subscriber<[ReferenceBusinessEntityModel
    | ReferenceStationModel, Array<ReferenceBusinessEntityModel
    | ReferenceStationModel>, string]>;
  protected stationList: Array<ReferenceStationModel>;
  private type: CrudType;

  constructor(private authService: AuthService,
              protected store: Store<CrudState>,
              private sessionStorageService: SessionStorageService,
              private stationListConvertService: StationListConvertService) { }

  getCurrentObjectList(): Observable<[ReferenceBusinessEntityModel | ReferenceStationModel,
    Array<ReferenceBusinessEntityModel | ReferenceStationModel>, string ]> {
    const param: Params = {};
    const result = new Observable<[ReferenceBusinessEntityModel | ReferenceStationModel,
      Array<ReferenceBusinessEntityModel | ReferenceStationModel>, string ]>((subscriber) => {
      this.subscriber = subscriber;
      if (!this.userObjectList && !this.currentObject) {
        this.authService.user$.subscribe( user => {
          if (user !== null) {
            switch (user.groups[0]['code']) {
              case 'ROLE_ROOT':
              case 'ROLE_GOVERNMENT':
                this.type = CrudType.ReferenceStation;
                param['fields'] = {0: 'name', 1: 'id', 'parent': ['id', 'name']};
                param['limit'] = 100;
                param['order'] = {sort: 'ASC'};
                this.typeObject = 'station';
                break;
              case 'ROLE_BUSINESS_ENTITY':
                this.type = CrudType.ReferenceBusinessEntity;
                this.typeObject = 'supervisedObjects';
                break;
            }
            param['filter'] = {users: {id: user.user.id}};
            if (!(!!this.sessionStorageService.getSessionStorageValue('userId'))) {
              this.sessionStorageService.saveToSessionStorageValue('userId', String(user.user.id));
            }
            if (this.sessionStorageService.getSessionStorageValue('userId') !==  String(user.user.id)) {
              this.sessionStorageService.clearSessionStorageItem();
              this.sessionStorageService.saveToSessionStorageValue('userId', String(user.user.id));
            }
            if (!(!!this.sessionStorageService.getSessionStorageObject(this.typeObject))) {
              this.store.dispatch(new LoadGetListAction({
                type: this.type,
                params: param,
                onSuccess: ({status, response}) => {
                  if (status && response) {
                    if (this.typeObject === 'station') {
                      this.setStationList(response.items);
                      this.sessionStorageService.saveToSessionStorageObject(this.typeObject, this.getConvertStationList());
                    } else {
                      this.sessionStorageService.saveToSessionStorageObject(this.typeObject, response.items);
                    }
                    this.sessionStorageService.saveToSessionStorageValue('objectType', this.typeObject);
                    this.setSubscriberObject();
                  }
                }
              }));
            } else {
              this.setSubscriberObject();
            }
          }
        });
      }
      else {
        this.setSubscriberObject();
      }

    });
    return result;
  }

  setCurrentObjectList(objectId: number): void {
    this.setObjectList(this.userObjectList.find(n => n.id === objectId));
  }
  setListStations(station: Array<ReferenceBusinessEntityModel | ReferenceStationModel>): void {
    this.subscriber.next([this.currentObject, station, this.typeObject]);
  }

  private setSubscriberObject(): void {
    this.userObjectList = this.sessionStorageService.getSessionStorageObject(this.typeObject);
    this.currentObject = this.userObjectList.length ? this.userObjectList[0] : null;
    this.subscriber.next([this.currentObject, this.userObjectList, this.typeObject]);
  }

  private setObjectList(object: ReferenceBusinessEntityModel | ReferenceStationModel): void {
    this.currentObject = object;
    this.subscriber.next([this.currentObject, this.userObjectList, this.typeObject]);
  }

  private getConvertStationList(): Array<ReferenceStationModel> {
    this.stationListConvertService.stationList = this.stationList;
    this.stationListConvertService.stationListConvert =
      this.stationListConvertService.formatListStation(this.stationListConvertService.stationList[0]?.parent);
    return  this.stationListConvertService.stationListConvert;
  }

  private setStationList(stationList: Array<ReferenceStationModel>): void {
    this.stationList = stationList;
    this.stationList.forEach(val => val['checked'] = false);
    this.sortedStationList();
  }

  private sortedStationList(): void {
    this.stationList.sort( (station_one, station_two) => {
      if (station_one.parent && station_two.parent) {
        return station_one.parent.id - station_two.parent.id;
      }
      return  station_one.id - station_two.id;
    });
  }
}
