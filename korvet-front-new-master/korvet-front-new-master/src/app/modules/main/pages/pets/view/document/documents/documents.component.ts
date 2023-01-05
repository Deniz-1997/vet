import {Component, Input, OnDestroy, OnInit} from '@angular/core';
import {BehaviorSubject, Observable, Subscription} from 'rxjs';
import {PetModel} from '../../../../../../../models/pet/pet.models';
import {ViewService} from '../../view.service';
import {FilesService} from '../../../../../../../services/files.service';
import {FileModel} from '../../../../../../../models/file/file.models';
import {select, Store} from '@ngrx/store';
import {ModalFileAddFormComponent} from '../../../../../../shared/components/modal-file-add-form/modal-file-add-form.component';
import {MatDialog} from '@angular/material/dialog';
import {FileMonitoredObjectModel} from '../../../../../../../models/file/file.monitored.object.models';
import {AppointmentModel} from '../../../../../../../models/appointment/appointment.models';
import {CrudType} from 'src/app/common/crud-types';
import {ApiResponse} from 'src/app/api/api-connector/api-connector.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelDeleteLoading, getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

declare var $: any;

@Component({
  selector: 'app-pets-view-documents',
  templateUrl: './documents.component.html'
})
export class DocumentsComponent implements OnInit, OnDestroy {
  @Input() limitRow = 0;
  type = CrudType.File;
  c = '#';
  d = 'demo';

  filter = {};

  sort: { [columnName: string]: 'ASC' | 'DESC' } = {'date': 'ASC'};

  limit = 500;

  offset = 0;

  items = new BehaviorSubject<FileModel[]>([]);
  query = '';
  pet = new PetModel();
  pedId: number;
  loading$ = new BehaviorSubject(true);
  fileRemove: FileModel;
  loadingRemove$: Observable<boolean>;
  fileTypes$: Observable<FileMonitoredObjectModel[]>;
  appointments = new BehaviorSubject<AppointmentModel[]>([]);
  private subscriptions: Subscription[] = [];

  constructor(
    public petsViewService: ViewService,
    private apiFilesService: FilesService,
    private store: Store<CrudState>,
    private dialog: MatDialog
  ) {
    this.loadingRemove$ = this.store.pipe(select(getCrudModelDeleteLoading, {type: this.type}));
    const s = petsViewService.pet.subscribe(pet => {
      if (pet && pet.id) {
        this.pedId = pet.id;
        this.pet = pet;
        this.filter = {pet: {id: this.pet.id}};
        this.store.dispatch(new LoadGetListAction({
          type: CrudType.Appointment,
          params: {'filter': {'pet': {'id': pet.id}}, order: {date: 'DESC'}}
        }));
      }
    });
    this.subscriptions.push(s);
  }

  ngOnInit() {
    this.getLoad();
    this.fileTypes$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceFileType}));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceFileType,
      params: {order: {'sort': 'ASC', 'name': 'ASC'}, limit: 10}
    }));
    this.store.pipe(select(getCrudModelData, {type: CrudType.Appointment})).subscribe(this.appointments);

  }

  ngOnDestroy() {
    this.subscriptions
      .forEach(s => s.unsubscribe());
  }

  getLoad() {
    this.loading$.next(true);
    const s = this.apiFilesService.Get(null, {
      order: this.sort,
      offset: this.offset,
      limit: this.limit,
      filter: this.filter
    })
      .subscribe((res: ApiResponse) => {
          if (res && res.status === true) {
            this.items.next(res.response.items);
            this.loading$.next(false);
          }
        },
        () => this.loading$.next(false));
    this.subscriptions.push(s);
    return s;
  }

  updateList() {
    return this.getLoad();
  }

  getDate(date: string) {
    return date.substr(0, 10);
  }

  getTime(date: string) {
    return date.substr(11, 5);
  }

  showAll($event) {
    if ($event) {
      $event.preventDefault();
    }
    this.limitRow = 0;
  }

  removeFile($event) {
    if ($event) {
      $event.preventDefault();
    }
    const file = this.fileRemove;
    return this.apiFilesService.removeFile(file.id).subscribe((res: ApiResponse) => {
      if (res && res.status === true) {
        $('[data-fancybox-close]').trigger('click');
        return this.getLoad();
      }
    });
  }

  addFile(): void {
    const dialog = this.dialog.open(ModalFileAddFormComponent, {
      data: {
        subject: this.pet,
        fileTypes$: this.fileTypes$,
        appointments: this.appointments.value,
      }
    });

    dialog.afterClosed().subscribe(answer => {
      if (answer) {
        this.updateList();
      }
    });
  }

  setRemoveFile(file: FileModel) {
    this.fileRemove = file;
  }
}
