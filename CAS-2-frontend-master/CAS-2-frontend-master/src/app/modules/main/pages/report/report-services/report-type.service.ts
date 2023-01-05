import { Injectable } from '@angular/core';
import {Store} from '@ngrx/store';
import {BehaviorSubject, Observable} from 'rxjs';
import {CrudType} from 'src/app/common/crud-types';
import {ReportsListModel} from 'src/app/models/reports/report-model/reports-list.model';
import {CrudState} from '../../../../../api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from '../../../../../api/api-connector/crud/crud.actions';

@Injectable()
export class ReportTypeService {

  protected _report: ReportsListModel;
  protected _title: string;
  protected newTitle: string;
  private data = new BehaviorSubject<any>(undefined);
  reports = this.data.asObservable();

  constructor(protected store: Store<CrudState>) { }

  getReport(reportTitle: string): Observable<ReportsListModel> {
    const result = new Observable<ReportsListModel>((subscriber) => {
      if (!this._report || this._title !== reportTitle) {
        this._title = reportTitle;
        this.store.dispatch(new LoadGetListAction({
          type: CrudType.ReportList,
          params: {filter: {name: this._title}},
          onSuccess: ({response}) => {
            const {items} = response;
            if (items.length > 0) {
              this._report = items[0];
              subscriber.next(this._report);
            }
            else {
              subscriber.error('Report not found');
            }
          }
        }));
      }
      else {
        subscriber.next(this._report);
      }

    });
    return result;
  }

  getReports(reportTitle: string): void {
    if (!this.newTitle || this.newTitle !== reportTitle) {
      this.newTitle = reportTitle;
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.ReportList,
        params: {filter: {name: this.newTitle}},
        onSuccess: ({response}) => {
          const {items} = response;
          if (items.length > 0) {
            this.data.next(items[0]);
          } else {
            this.data.error('Report not found');
          }
        }
      }));
    }
  }
}
