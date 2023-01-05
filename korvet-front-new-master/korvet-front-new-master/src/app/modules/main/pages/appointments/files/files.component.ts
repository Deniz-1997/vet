import {AfterViewInit, Component, Input, OnInit} from '@angular/core';
import {MatDialog} from '@angular/material/dialog';
import {select, Store} from '@ngrx/store';
import {BehaviorSubject, Observable, Subject} from 'rxjs';
import {takeUntil} from 'rxjs/operators';
import {ApiResponse} from 'src/app/api/api-connector/api-connector.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';
import {CrudType} from 'src/app/common/crud-types';
import {AppointmentModel} from 'src/app/models/appointment/appointment.models';
import {FileModel} from 'src/app/models/file/file.models';
import {FileMonitoredObjectModel} from 'src/app/models/file/file.monitored.object.models';
import {ModalConfirmComponent} from 'src/app/modules/shared/components/modal-confirm/modal-confirm.component';
import {ModalFileAddFormComponent} from 'src/app/modules/shared/components/modal-file-add-form/modal-file-add-form.component';
import {FilesService} from 'src/app/services/files.service';
import {LeavingModel} from '../../../../../models/leaving/leaving.models';

@Component({
  selector: 'app-appointment-files',
  templateUrl: './files.component.html',
  styleUrls: ['./files.component.css']
})
export class AppointmentFilesComponent implements OnInit, AfterViewInit {

  fileLoading: boolean;
  files = new BehaviorSubject<FileModel[]>([]);
  fileTypes$: Observable<FileMonitoredObjectModel[]>;
  private destroy$ = new Subject<any>();
  model;

  constructor(private apiFilesService: FilesService,
    private dialog: MatDialog, private store: Store<CrudState>) {
  }

  _appointment: AppointmentModel;
  _leaving: LeavingModel;

  get appointment(): AppointmentModel {
    return this._appointment;
  }

  @Input() set appointment(value: AppointmentModel) {
    this._appointment = value;
    this.getFileList();
  }
  get leaving(): LeavingModel {
    return this._leaving;
  }

  @Input() set leaving(value: LeavingModel) {
    this._leaving = value;
    this.getFileList();
  }

  ngOnInit(): void {
  }

  ngAfterViewInit(): void {
    this.fileTypes$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceFileType}));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceFileType,
      params: {order: {'sort': 'ASC', 'name': 'ASC'}, limit: 10}
    }));
  }

  addFile($event): void {
    if ($event) {
      $event.preventDefault();
    }
    let data;
    if (this.appointment !== undefined) {
      data = {
        subject: this.appointment.pet,
        fileTypes$: this.fileTypes$,
        appointments: [{id: this.appointment.id, name: this.appointment.name}]
      };
    } else if (this.leaving !== undefined) {
      let subject;
      if (this.leaving.pet !== null && undefined) {
        subject = this.leaving.pet;
      } else {
        subject = this.leaving;
      }
      data = {
        subject: subject,
        fileTypes$: this.fileTypes$,
        leaving: [{id: this.leaving.id, name: this.leaving.name}]
      };
    }
    const dialog = this.dialog.open(ModalFileAddFormComponent, {
      width: window.innerWidth > 960 ? '25%' : '90%',
      height: '100% - 50px',
      data: data
    });
    dialog.afterClosed().subscribe(answer => {
      if (answer) {
        this.getFileList();
      }
    });
  }

  getFileList(): void {
    if ((!this.leaving || !this.leaving.id) && (!this.appointment || !this.appointment.id)) {
      return;
    }
    const that = this;
    let filter;
    this.fileLoading = true;
    if (this.appointment) {
      this.model = this.appointment;
      if (this.appointment.pet) {
        filter = {'pet': {'id': this.appointment.pet.id}, 'appointment': {'id': this.appointment.id}};
      } else {
        filter = {'appointment': {'id': this.appointment.id}};
      }
    } else if (this.leaving) {
      this.model = this.leaving;
      if (this.leaving.pet) {
        filter = {'pet': {'id': this.leaving.pet?.id}, 'leaving': {'id': this.leaving.id}};
      } else {
        filter = {'leaving': {'id': this.leaving.id}};
      }
    }
    if (this.model) {
      if (typeof this.model.pet !== undefined) {
        this.apiFilesService.Get(null, {
          order: {'id': 'DESC'},
          offset: 0,
          limit: 500,
          mode: ['data', 'actions', 'listActions', 'layout'],
          filter: filter
        }).pipe(
          takeUntil(this.destroy$)
        )
          .subscribe((res: ApiResponse) => {
            if (res && res.status === true) {
              if (res.response.totalCount === 0) {
                this.fileLoading = false;
              }
              this.files.next(res.response.items);
              this.fileLoading = false;
            }
          },
            () => this.fileLoading = false);
      }
    }
  }

  removeFile(file: FileModel, $event): void {
    if ($event) {
      $event.preventDefault();
    }
    this.onRemoveFile(file);
  }

  onRemoveFile(file: FileModel): void {
    const dialogRef = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: 'Вы точно хотите удалить файл?',
        headComment: 'Действие необратимо <br> (' + file.name + ')',
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

    dialogRef.afterClosed().subscribe((result: boolean) => {
      if (result) {
        return this.apiFilesService.removeFile(file.id).subscribe((res: ApiResponse) => {
          if (res && res.status === true) {
            return this.getFileList();
          }
        });
      }
    });
  }
}
