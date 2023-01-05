import {Injectable} from '@angular/core';
import {Store} from '@ngrx/store';
import {BehaviorSubject, Observable} from 'rxjs';
import {CrudType} from '../../../../../common/crud-types';
import {ReferenceSupervisedObjectModel} from '../../../../../models/reference/reference.supervisedObects.models';
import {ReferenceStationModel} from '../../../../../models/reference/reference.station.models';
import {CrudState} from '../../../../../api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from '../../../../../api/api-connector/crud/crud.actions';


@Injectable()
export class ReportFilterService {
  static minDate = new Date(2014, 0, 1).getFullYear();
  static maxDate = new Date().getFullYear();
  static currentMonth = new Date().getMonth();
  static month = [
    {monthNumber: 1, name: 'Январь'},
    {monthNumber: 2, name: 'Февраль'},
    {monthNumber: 3, name: 'Март'},
    {monthNumber: 4, name: 'Апрель'},
    {monthNumber: 5, name: 'Май'},
    {monthNumber: 6, name: 'Июнь'},
    {monthNumber: 7, name: 'Июль'},
    {monthNumber: 8, name: 'Август'},
    {monthNumber: 9, name: 'Сентябрь'},
    {monthNumber: 10, name: 'Октябрь'},
    {monthNumber: 11, name: 'Ноябрь'},
    {monthNumber: 12, name: 'Декабрь'},
  ];
  protected date = new Array<number>();
  protected _supervisedList: ReferenceSupervisedObjectModel;
  protected _stationList = new Array<ReferenceStationModel>();
  protected loading$ = new BehaviorSubject(false);

  constructor(
    protected store: Store<CrudState>) {

  }

  getDate(): Observable<any> {
    const result = new Observable(subscriber => {
      if (this.date.length === 0) {
        for (let newDate = ReportFilterService.minDate; newDate <= ReportFilterService.maxDate; newDate++) {
          this.date.push(newDate);
        }
        subscriber.next(this.date);
      } else {
        subscriber.next(this.date);
      }
    });
    return result;
  }



  getSupervisedObjects(id: number): Observable<any> {
    const result = new Observable(subscriber => {
      if (!this._supervisedList) {
        this.store.dispatch(new LoadGetListAction({
          type: CrudType.ReferenceSupervisedObject,
          params: {
            filter: {businessEntity_id: id}
          },
          onSuccess: (res) => {
            if (res.response.items.length > 0) {
              this._supervisedList = res.response.items;
              subscriber.next(this._supervisedList);
            }
          }
        }));
      } else {
        subscriber.next(this._supervisedList);
      }
    });
    return result;
  }

  getStationList(id: number): Observable<any> {
    const result = new Observable(subscriber => {
      if (!this._stationList || id !== this._stationList[0]?.id) {
        this.loading$.next(true);
        this.store.dispatch(new LoadGetListAction({
          type: CrudType.ReferenceStation,
          params: { filter: {id: id}
          },
          onSuccess: (res) => {
            if (res.status === true && res.response) {
              this._stationList = res.response.items;
              subscriber.next(this._stationList);
            }
          }
        }));
      } else {
        subscriber.next(this._stationList);
      }
    });
    return result;
  }
}
