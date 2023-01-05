import {Component, Inject, Input, OnInit, Optional} from '@angular/core';
import {CrudType} from '../../../../common/crud-types';
import {select, Store} from '@ngrx/store';
import { Observable} from 'rxjs';
import {LoadGetListAction} from '../../../../api/api-connector/crud/crud.actions';
import {getCrudModelGetListLoading} from '../../../../api/api-connector/crud/crud.selectors';
import {CrudState} from '../../../../api/api-connector/crud/crud-store.service';
import {EntityHistoryModel} from '../../../../models/entity-history.models';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';

@Component({
  selector: 'app-history-report-form',
  templateUrl: './history-report-form.component.html',
  styleUrls: ['./history-report-form.component.scss'],
})

export class HistoryReportFormComponent implements OnInit {
  private _reportDataId: number;
  @Input('reportDataId')
  get reportDataId(): number {
    return this._reportDataId;
  }
  set reportDataId(value: number) {
    this._reportDataId = Number(value);
  }
  loading$: Observable<boolean>;
  type =  CrudType.EntityHistory;
  dataList = new Array<EntityHistoryModel>();
  tableFields = ['action', 'date', 'status', 'user'];
  openDialog = false;

  constructor(
    @Optional() public dialogRef: MatDialogRef<HistoryReportFormComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean, id: number},
    protected store: Store<CrudState>) {
    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: this.type}));
  }


  historyReport(id: number): void {
    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: {
        order: {
          loggedAt: 'DESC'
        },
        filter: {
          'objectId': id,
          'objectClass': 'reportsData',
        },
      },
      onSuccess: ({status, response}) => {
        if (status) {
          this.dataList = response.items;
        }
      }
    }));
  }

  ngOnInit(): void {
    if (this.data) {
      this.openDialog = this.data.openDialog;
      this.reportDataId = this.data.id;
    }
    this.historyReport(this.reportDataId);
  }

  close(): void {
    this.dialogRef.close();
  }
}
