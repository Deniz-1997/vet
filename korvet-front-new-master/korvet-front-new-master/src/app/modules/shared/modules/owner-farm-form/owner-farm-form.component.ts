import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {BaseFormComponent} from '../../utils/base-form-component';
import {AppointmentModel} from '../../../../models/appointment/appointment.models';
import {FileModel} from '../../../../models/file/file.models';
import {ReferenceOwnerActivityModel} from '../../../../models/reference/reference.owner.activity.models';
import {FormArray, FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {OwnerModel} from '../../../../models/owner/owner.models';
import {Observable} from 'rxjs';
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
  selector: 'app-owner-farm-form',
  templateUrl: './owner-farm-form.component.html',
  styleUrls: ['./owner-farm-form.component.css']
})
export class OwnerFarmFormComponent extends BaseFormComponent implements OnInit {

  @Input() appointments: AppointmentModel[];
  @Input() fileTypes$: Observable<FileMonitoredObjectModel[]>;
  @Input() files: FileModel[] = [];
  @Input() choicesActivities: ReferenceOwnerActivityModel[] = [];
  @Output() getMatches = new EventEmitter();
  @Input() backButton: boolean;

  showError = false;
  type = CrudType.Owner;
  getLoading$: Observable<boolean>;

  constructor(private fb: FormBuilder,
              protected store: Store<CrudState>,
              private dialog: MatDialog,
              private router: Router
  ) {
    super();
    this.getLoading$ = this.store.pipe(select(getCrudModelCreateLoading, {type: this.type}));
  }

  ngOnInit() {
    this.formGroup = this.fb.group({
      id: [0],
      gender: [''],
      phone: ['', [Validators.required]],
      email: ['', [Validators.email]],
      name: ['', [Validators.required]],
      inn: [''],
      farm: this.fb.group({
        egrip: [''],
        head: this.getPerson(),
        members: this.fb.array([]),
      }),
      farmMembers: this.fb.array([]),
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
      activities: [[]]
    });
    if (this.model) {
      this.resetForm(this.model);
      if (this.model.activities) {
        this.formGroup.controls.activity.setValue({id: this.model.activities.map(item => item.id)});
      }
    }
  }

  getPerson(): FormGroup {
    return this.fb.group({
      person: this.fb.group({
        fullName: this.fb.group({
          lastName: [''],
          name: [''],
          middleName: [''],
        }),
        phone: [''],
        email: ['', [Validators.email]],
      }),
      share: [null]
    });
  }
  getGenderFarmHead(gender) {
    this.formGroup.get('gender').setValue(gender);
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

  addMember(): void {
    (<FormArray>this.formGroup.get('farmMembers'))
      .push(this.fb.group({member: this.getPerson()}));
  }

  resetForm(model: OwnerModel): void {
    const formArray: FormArray = this.formGroup.get('farmMembers') as FormArray;
    formArray.controls.forEach((_, i) => formArray.removeAt(i));
    if (model.farmMembers && model.farmMembers.length) {
      model.farmMembers.forEach(person => formArray.push(this.fb.group({member: this.getPerson()})));
    } else {
      formArray.push(this.fb.group({member: this.getPerson()}));
    }
    super.resetForm(model);
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
    }
  }

}
