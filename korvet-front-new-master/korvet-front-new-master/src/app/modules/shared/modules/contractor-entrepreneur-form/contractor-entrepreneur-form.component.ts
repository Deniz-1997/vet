import {Component, EventEmitter, Input, OnInit, Output, SimpleChanges} from '@angular/core';
import {BaseFormComponent} from '../../utils/base-form-component';
import {AppointmentModel} from '../../../../models/appointment/appointment.models';
import {FileModel} from '../../../../models/file/file.models';
import {FormArray, FormBuilder, FormGroup, Validators} from '@angular/forms';
import {ReferenceOwnerActivityModel} from '../../../../models/reference/reference.owner.activity.models';
import {BehaviorSubject, Observable} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {FileMonitoredObjectModel} from '../../../../models/file/file.monitored.object.models';
import {ModalConfirmComponent} from '../../components/modal-confirm/modal-confirm.component';
import {MatDialog} from '@angular/material/dialog';
import {Router} from '@angular/router';
import {CrudType} from 'src/app/common/crud-types';
import {ReferenceContractorModel} from 'src/app/models/reference/contractor.model';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadDeleteAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelCreateLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-contractor-entrepreneur-form',
  templateUrl: './contractor-entrepreneur-form.component.html',
  styleUrls: ['./contractor-entrepreneur-form.component.css']
})
export class ContractorEntrepreneurFormComponent extends BaseFormComponent implements OnInit {

  @Input() appointments: AppointmentModel[] = [];
  @Input() fileTypes$: Observable<FileMonitoredObjectModel[]>;
  @Input() files: FileModel[] = [];
  @Input() choicesActivities: ReferenceOwnerActivityModel[] = [];
  @Output() getMatches = new EventEmitter();

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
      fullName: this.fb.group({
        lastName: ['', [Validators.required]],
        name: ['', [Validators.required]],
        middleName: [''],
      }),
      phone: ['', [Validators.required]],
      email: ['', [Validators.email]],
      name: ['', [Validators.required]],
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
      contactPersons: this.fb.array([this.getContractPerson()]),
    });
    if (this.model) {
      this.resetForm(this.model);
      if (this.model.activities) {
        this.formGroup.controls.activity.setValue({id: this.model.activities.map(item => item.id)});
      }
    }
  }

  resetForm(model: ReferenceContractorModel): void {
    const formArray: FormArray = this.formGroup.get('contactPersons') as FormArray;
    formArray.controls.forEach((_, i) => formArray.removeAt(i));
    if (model.contactPersons && model.contactPersons.length) {
      model.contactPersons.forEach(person => formArray.push(this.getContractPerson()));
    } else {
      formArray.push(this.getContractPerson());
    }
    super.resetForm(model);
  }

  getContractPerson(): FormGroup {
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
      position: [''],
      comment: [''],
      id: ['']
    });
  }

  addContactPerson(): void {
    (this.formGroup.get('contactPersons') as FormArray).push(this.getContractPerson());
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

  removeContactPerson(id: number) {
    const control = <FormArray>this.formGroup.controls['contactPersons'];
    let modalData = {};
    if (this.model['contactPersons'][id]['cullingRegistrationHistory'] && this.model['contactPersons'][id]['cullingRegistrationHistory'].length) {
      modalData = {
        head: 'Сотрудник используется в отловах животных',
        headComment: 'Удаление не возможно',
        actions: [
          {
            class: 'btn-st btn-st--gray',
            action: false,
            title: 'Закрыть'
          },
        ],
      };
    } else {
      modalData = {
        head: 'Вы точно хотите удалить сотрудника?',
        headComment: 'Действие необратимо <br> (' + control.controls[id].get('person').value.fullName.lastName +
          control.controls[id].get('person').value.fullName.name +
          control.controls[id].get('person').value.fullName.middleName + ')',
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
      };
    }
    const dialogRef = this.dialog.open(ModalConfirmComponent, {
      data: modalData
    });
    dialogRef.afterClosed().subscribe((result: boolean) => {
      if (result) {
        control.removeAt(id);
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
      const model = {...this.formGroup.value};
      if (model['contactPersons'] && model['contactPersons'].length) {
        for (const item of model['contactPersons']) {
          if (!item['id'] || item['id'].length) {
            delete item['id'];
          }
          if (!item['contractor']) {
            item['contractor'] = {id: model.id};
          }
        }
      }
      this.submitForm.emit(model);
    } else {
      console.log(this.formGroup);
    }
  }

}
