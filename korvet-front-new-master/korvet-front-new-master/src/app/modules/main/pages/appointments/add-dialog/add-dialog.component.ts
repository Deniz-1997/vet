import {Component, Inject, OnInit} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {Observable} from 'rxjs';
import {MainModule} from '../../admin/references/profession/main.module';
import {select, Store} from '@ngrx/store';
import {CrudType} from '../../../../../common/crud-types';
import {OwnerModel} from '../../../../../models/owner/owner.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelCreatePatchLoading, getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-appointments-add-dialog',
  templateUrl: './add-dialog.component.html',
  styleUrls: ['./add-dialog.component.css']
})
export class AddDialogComponent implements OnInit {
  professions$: Observable<MainModule>;
  owners$: Observable<OwnerModel[]>;
  loading$: Observable<boolean>;
  type = CrudType.Appointment;

  constructor(
    public dialogRef: MatDialogRef<AddDialogComponent>,
    private store: Store<CrudState>,
    @Inject(MAT_DIALOG_DATA) public data: any,
  ) {
    this.loading$ = store.pipe(select(getCrudModelCreatePatchLoading, {type: CrudType.Appointment}));

    this.professions$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceProfession}));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceProfession,
      params: {
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));

    this.owners$ = this.store.pipe(select(getCrudModelData, {type: CrudType.Owner}));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Owner,
      params: {
        fields: {0: 'id', 1: 'name'},
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));
  }

  ngOnInit() {
  }

  cancel(): void {
    this.dialogRef.close();
  }

  submit(value: Object): void {
    this.dialogRef.close(true);
  }

}
