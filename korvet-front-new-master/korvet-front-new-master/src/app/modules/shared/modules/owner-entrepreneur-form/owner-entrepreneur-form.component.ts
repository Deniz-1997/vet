import {Component, EventEmitter, Input, OnInit, Output, SimpleChanges} from '@angular/core';
import {BaseFormComponent} from '../../utils/base-form-component';
import {AppointmentModel} from '../../../../models/appointment/appointment.models';
import {FileModel} from '../../../../models/file/file.models';
import {FormBuilder, Validators} from '@angular/forms';
import {ReferenceOwnerActivityModel} from '../../../../models/reference/reference.owner.activity.models';
import {BehaviorSubject, Observable} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {FileMonitoredObjectModel} from '../../../../models/file/file.monitored.object.models';
import {ModalConfirmComponent} from '../../components/modal-confirm/modal-confirm.component';
import {MatDialog} from '@angular/material/dialog';
import {Router} from '@angular/router';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {getCrudModelCreateLoading} from 'src/app/api/api-connector/crud/crud.selectors';
import {LoadDeleteAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({
  selector: 'app-owner-entrepreneur-form',
  templateUrl: './owner-entrepreneur-form.component.html',
  styleUrls: ['./owner-entrepreneur-form.component.css']
})
export class OwnerEntrepreneurFormComponent extends BaseFormComponent implements OnInit {

  @Input() appointments: AppointmentModel[] = [];
  @Input() fileTypes$: Observable<FileMonitoredObjectModel[]>;
  @Input() files: FileModel[] = [];
  @Input() choicesActivities: ReferenceOwnerActivityModel[] = [];
  @Output() getMatches = new EventEmitter();
  @Input() backButton: boolean;

  files$ = new BehaviorSubject<FileModel[]>([]);
  getLoading$: Observable<boolean>;

  showError = false;
  type = CrudType.Owner;

  constructor(
    private fb: FormBuilder,
    protected store: Store<CrudState>,
    private dialog: MatDialog,
    private router: Router,
  ) {
    super();
    this.getLoading$ = this.store.pipe(select(getCrudModelCreateLoading, {type: this.type}));
  }

  ngOnInit() {
    this.formGroup = this.fb.group({
      id: [0],
      gender: [''],
      fullName: this.fb.group({
        lastName: ['', [Validators.required]],
        name: ['', [Validators.required]],
        middleName: [''],
      }),
      phone: ['', [Validators.required]],
      email: ['', [Validators.email]],
      name: [''],
      inn: [''],
      entrepreneur: this.fb.group({
        egrip: [''],
        ogrnip: [''],
      }),
      address: this.fb.group({
        full: [''],
        coordinates: ['']
      }),
      individualPerson: this.fb.group({
        householdAddress: [''],
        householdAddressIsPersonAddress: [false],
      }),
      customActivities: [''],
      activity: [[]],
      activities: [[]],
    });
    if (this.model) {
      this.resetForm(this.model);
      if (this.model.activities) {
        this.formGroup.controls.activity.setValue({id: this.model.activities.map(item => item.id)});
      }
    }
  }

  onChanges(changes: SimpleChanges): void {
    if (changes['files']) {
      this.files$.next(changes['files'].currentValue);
    }
  }

  onDelete() {
    const dialogRef = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: 'Вы точно хотите удалить владельца?',
        headComment: 'Действие необратимо <br> (' + this.formGroup.controls.name.value + ')',
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
        this.store.dispatch(new LoadDeleteAction({
            type: this.type,
            params: {id: this.formGroup.controls.id.value},
            onSuccess: (res) => {
              if (res.status === true) {
                this.router.navigate(['owners']).then();
              }
            }
          }
        ));
      }
    });
  }

  submit(): void {
    this.showError = true;
    if (this.formGroup && this.formGroup.valid) {

      if (this.formGroup.controls.activity
        && this.formGroup.controls.activity.value.id
        && this.formGroup.controls.activity.value.id.length > 0) {
        const ids = this.formGroup.controls.activity.value.id;
        const arrayIds = [];
        ids.map(
          item => {
            arrayIds.push({'id': item});
          }
        );
        this.formGroup.controls.activities.setValue(arrayIds);
      } else {
        this.formGroup.controls.activities.setValue([]);
      }

      this.submitForm.emit(this.formGroup.value);
    } else {
      console.log(this.formGroup);
    }
  }

}
