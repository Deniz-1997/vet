import {Component, OnInit} from '@angular/core';
import {LoadGetAction} from '../../../../../api/api-connector/crud/crud.actions';
import {CrudType} from '../../../../../common/crud-types';
import {Store} from '@ngrx/store';
import {CrudState} from '../../../../../api/api-connector/crud/crud-store.service';
import {SnackBarService} from '../../../../../services/snack-bar.service';

@Component({templateUrl: './maintenance.component.html'})
export class MaintenanceComponent implements OnInit {
  disabled = false;

  constructor(
    private store: Store<CrudState>,
    private snackBar: SnackBarService,
  ) {
  }


  synchronize(): void {
    this.disabled = true;
    this.store.dispatch(new LoadGetAction({
      type: CrudType.UsersSycn,
      onSuccess: ({status, response}) => {
        if (status) {
          this.disabled = !response.status;
          this.snackBar.handleMessage('Операция выполнена успешно', 'success-snackBar', 2000);
        }
      },
      onError: () => {
        this.disabled = false;
      },
    }));
  }

  ngOnInit(): void {
  }

}
