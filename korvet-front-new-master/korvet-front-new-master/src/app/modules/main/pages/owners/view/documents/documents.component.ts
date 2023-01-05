import {Component, OnInit, ViewChild} from '@angular/core';
import {BehaviorSubject, Observable} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {ViewService} from '../view.service';
import {ActivatedRoute, Router} from '@angular/router';
import {FileModel} from '../../../../../../models/file/file.models';
import {MatDialog} from '@angular/material/dialog';
import {ModalFileAddFormComponent} from '../../../../../shared/components/modal-file-add-form/modal-file-add-form.component';
import {AppointmentModel} from '../../../../../../models/appointment/appointment.models';
import {ModalConfirmComponent} from '../../../../../shared/components/modal-confirm/modal-confirm.component';
import {ListFilterViewComponent} from '../../../../../shared/components/list-filter-view/list-filter-view.component';
import {FileMonitoredObjectModel} from '../../../../../../models/file/file.monitored.object.models';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadDeleteAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './documents.component.html'})

export class DocumentsComponent implements OnInit {
  fileTypes$: Observable<FileMonitoredObjectModel[]>;
  appointments = new BehaviorSubject<AppointmentModel[]>([]);
  type = CrudType.Owner;
  mainType = CrudType.FileOwner;
  @ViewChild(ListFilterViewComponent, {static: true}) listFilterView: ListFilterViewComponent;
  c = '#';
  d = 'demo';

  constructor(
    private store: Store<CrudState>,
    private service: ViewService,
    private router: Router,
    private route: ActivatedRoute,
    private dialog: MatDialog,
  ) {
    store.pipe(select(getCrudModelData, {type: CrudType.Appointment})).subscribe(this.appointments);
  }

  get owner$() {
    return this.service.owner$;
  }

  get owner() {
    return this.service.owner;
  }

  ngOnInit() {
    this.fileTypes$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceFileType}));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceFileType,
      params: {order: {'sort': 'ASC', 'name': 'ASC'}, limit: 10}
    }));
    this.owner$.subscribe(owner => {
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.Appointment,
        params: {filter: {owner: {id: owner.id}}}
      }));
      /*this.store.dispatch(new LoadGetListAction({
        type: CrudType.FileOwner,
        params: {filter: {owner: {id: this.owner.id}}}
      }));*/
      this.listFilterView.basicFilter = {owner: {id: this.owner.id}};
      this.listFilterView.type = this.mainType;
      this.listFilterView.dispatch();
    });
  }

  addFile(): void {
    const dialog = this.dialog.open(ModalFileAddFormComponent, {
      data: {
        subject: this.owner,
        fileTypes$: this.fileTypes$,
        appointments: this.appointments.value,
      }
    });
  }

  deleteFile(file: FileModel): void {
    const dialog = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: 'Вы точно хотите удалить документ?',
        headComment: 'Эта операция необратима (' + file.name + ')',
        actions: [
          {
            class: 'btn-st btn-st--left btn-st--gray',
            title: 'Отмена',
            action: false
          },
          {
            class: 'btn-st btn-st--right btn-st--red',
            title: 'Удалить',
            action: true,
          }
        ],
      }
    });
    dialog.afterClosed().subscribe(answer =>
      answer && this.store.dispatch(new LoadDeleteAction({
        type: CrudType.FileOwner,
        params: {id: file.id}
      }))
    );
  }
}
