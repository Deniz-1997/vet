import {Component, Inject, OnInit} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialog, MatDialogRef} from '@angular/material/dialog';
import {EventModel} from '../../../../models/event.models';
import {ModalConfirmComponent} from '../../components/modal-confirm/modal-confirm.component';
import {select, Store} from '@ngrx/store';
import {Observable} from 'rxjs';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {getCrudModelDeleteLoading} from 'src/app/api/api-connector/crud/crud.selectors';
import {LoadDeleteAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({
  selector: 'app-modal-event-actions-view',
  templateUrl: './modal-event-actions-view.component.html',
  styleUrls: ['./modal-event-actions-view.component.css']
})
export class ModalEventActionsViewComponent implements OnInit {

  loading$: Observable<boolean>;

  constructor(
    @Inject(MAT_DIALOG_DATA) public data: EventModel,
    private dialogRef: MatDialogRef<ModalEventActionsViewComponent>,
    private dialog: MatDialog,
    private store: Store<CrudState>,
  ) {
  }

  ngOnInit() {
    this.dialogRef.afterOpened().subscribe(() => {
      this.loading$ = this.store.pipe(select(getCrudModelDeleteLoading, {type: CrudType.Event}));
    });
  }

  deleteEvent(): void {
    const dialog = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: 'Вы точно хотите удалить владельца у животного?',
        headComment: 'Владелец удалится только у животного, профиль владельца останется неизменным',
        actions: [
          {
            class: 'btn-st btn-st--left btn-st--gray',
            action: false,
            title: 'Отмена'
          },
          {
            class: 'btn-st btn-st--right btn-st--red',
            action: true,
            title: 'Удалить'
          },
        ],
      }
    });
    dialog.afterClosed().subscribe(answer => {
      if (answer) {
        this.store.dispatch(new LoadDeleteAction({
          type: CrudType.Event,
          params: {id: this.data.id},
          onSuccess: () => this.dialogRef.close()
        }));
      }
    });
  }
}
