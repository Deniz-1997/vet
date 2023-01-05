import {Component, ElementRef, EventEmitter, Input, OnDestroy, OnInit, Output, ViewChild} from '@angular/core';
import {BehaviorSubject, Observable, Subscription} from 'rxjs';
import {FilesService} from '../../../../../../../services/files.service';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {Store} from '@ngrx/store';
import {NotifyService} from '../../../../../../../services/notify.service';
import {DatePipe} from '@angular/common';
import {AppointmentModel} from '../../../../../../../models/appointment/appointment.models';
import {CrudType} from 'src/app/common/crud-types';
import {ApiResponse} from 'src/app/api/api-connector/api-connector.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadCreateAction} from 'src/app/api/api-connector/crud/crud.actions';

declare var $: any;

@Component({
  selector: 'app-pets-profile-document-add',
  templateUrl: './add.component.html'
})
export class AddComponent implements OnInit, OnDestroy {
  @Input() petId: Observable<number>;
  @Input() appointments: Observable<AppointmentModel[]>;
  @ViewChild('fileInput', {static: true}) fileInput: ElementRef;
  @Output() addFile: EventEmitter<any> = new EventEmitter();
  fileTypeItems = new BehaviorSubject([]);
  public formGroup: FormGroup;
  type = CrudType.File;
  isLoadFile = new BehaviorSubject(false);
  fileId = null;
  private subscriptions: Subscription[] = [];

  constructor(
    private apiFilesService: FilesService,
    private store: Store<CrudState>,
    private notify: NotifyService,
    private datePipe: DatePipe
  ) {

  }

  ngOnInit() {
    this.getTypes();
    this.fileTypeItems.subscribe(() => this.setModel());
  }

  ngOnDestroy() {
    this.subscriptions
      .forEach(s => s.unsubscribe());
  }

  getTypes() {
    const s = this.apiFilesService.getTypes()
      .subscribe((res: ApiResponse) => {
        if (res && res.status === true) {
          this.fileTypeItems.next(res.response.items);
        }
      });
    this.subscriptions.push(s);
    return s;
  }

  submit($event?): void {
    if ($event) {
      $event.preventDefault();
    }
    if (this.formGroup.valid) {

      const model = {...this.formGroup.value};
      model.uploadedFile = {id: this.fileId};
      model.createdAt = this.datePipe.transform(new Date(), 'dd.MM.yyyy HH:mm:ss');
      this.store.dispatch(new LoadCreateAction({
        type: this.type,
        params: model,
        onSuccess: (res) => {
          if (res.status === true && res.response && res.response.id) {
            this.setModel();
            this.addFile.emit(model);
            $('[data-fancybox-close]').trigger('click');
            this.isLoadFile.next(false);
          }
        }
      }));
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
    }
  }

  onFileChange(event) {
    if (event.target.files && event.target.files.length > 0) {
      this.isLoadFile.next(true);
      const formData = new FormData();
      formData.append('file', event.target.files[0]);
      this.apiFilesService.upload(formData).subscribe((res: any) => {
          if (res.type > 0 && res.status === 200 && res.body && res.body.response && res.body.response.id > 0) {
            this.fileId = res.body.response.id;
            this.submit();
          }
        },
        () => this.isLoadFile.next(false)
      );
    }
  }

  openFile($event): void {
    if ($event) {
      $event.preventDefault();
    }
    if (this.formGroup.valid) {
      $(this.fileInput.nativeElement).trigger('click');
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
    }
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl('', [Validators.required]),
      pet: new FormGroup({
        id: new FormControl(this.petId, [Validators.required]),
      }),
      appointment: new FormGroup({
        id: new FormControl(null, [Validators.required]),
      }),
      type: new FormGroup({
        id: new FormControl(null, [Validators.required]),
      })
    });
  }
}
