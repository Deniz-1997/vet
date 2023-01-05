import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {BaseFormComponent} from '../../utils/base-form-component';
import {FormArray, FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {OwnerModel} from '../../../../models/owner/owner.models';
import {AppointmentModel} from '../../../../models/appointment/appointment.models';
import {FileModel} from '../../../../models/file/file.models';
import {ReferenceOwnerLegalFormModel} from '../../../../models/reference/reference.owner.legal.form.models';
import {Observable} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {FileMonitoredObjectModel} from '../../../../models/file/file.monitored.object.models';
import {MatDialog} from '@angular/material/dialog';
import {ModalConfirmComponent} from '../../components/modal-confirm/modal-confirm.component';
import {Router} from '@angular/router';
import {CrudType} from 'src/app/common/crud-types';
import {ReferenceOwnerActivityModel} from '../../../../models/reference/reference.owner.activity.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadDeleteAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelCreateLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-owner-legal-form',
  templateUrl: './owner-legal-form.component.html',
  styleUrls: ['./owner-legal-form.component.css']
})
export class OwnerLegalFormComponent extends BaseFormComponent implements OnInit {
  crudType = CrudType;
  @Input() appointments: AppointmentModel[] = [];
  @Input() fileTypes$: Observable<FileMonitoredObjectModel[]>;
  @Input() files: FileModel[] = [];
  @Input() legalForms$: ReferenceOwnerLegalFormModel[] = [];
  @Input() choicesActivities: ReferenceOwnerActivityModel[] = [];
  @Output() getMatches = new EventEmitter();
  @Input() buttonName = 'владельца';
  @Input() showBlockFile = true;
  @Input() routeList: string[] = ['owners'];
  showError = false;
  placeholder = 'ООО';
  @Input() type = CrudType.Owner;
  getLoading$: Observable<boolean>;
  @Input() backButton: boolean;

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
      phone: ['', [Validators.required]],
      email: ['', [Validators.email]],
      name: ['', [Validators.required]],
      inn: [''],
      gender: [''],

      legalForm: new FormControl({id: 1, name: ''}),
      //
      // legalForm: this.fb.group({}),
      legalEntity: this.fb.group({
        kpp: [''],
        juridicalAddress: [''],
        ogrn: [''],
        head: this.fb.group({
          fullName: this.fb.group({
            lastName: [''],
            name: [''],
            middleName: [''],
          }),
          position: [''],
          responsibilities: [''],
          phone: [''],
          email: ['', [Validators.email]],
        }),
        factAddressIsJuridicalAddress: [false],
        productionFacility: [false]
      }),
      address: this.fb.group({
        full: [''],
        coordinates: [''],
      }),
      contactPersons: this.fb.array([this.getContractPerson()]),
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

  getContractPerson(): FormGroup {
    return this.fb.group({
      gender: [''],
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
    });
  }

  addContactPerson(): void {
    (this.formGroup.get('contactPersons') as FormArray).push(this.getContractPerson());
  }

  resetForm(model: OwnerModel): void {
    const formArray: FormArray = this.formGroup.get('contactPersons') as FormArray;
    formArray.controls.forEach((_, i) => formArray.removeAt(i));
    if (model.contactPersons && model.contactPersons.length) {
      model.contactPersons.forEach(person => formArray.push(this.getContractPerson()));
    } else {
      formArray.push(this.getContractPerson());
    }
    super.resetForm(model);
  }

  onDelete() {
    const dialogRef = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: 'Вы точно хотите удалить ' + this.buttonName + '?',
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
                this.router.navigate(this.routeList).then();
              }
            }
          }
        ));
      }
    });
  }
  getGender(gender) {
    this.formGroup.get('gender').setValue(gender);
  }
  getGenderContact(gender) {
    this.formGroup.controls['contactPersons']['controls'].get('gender').setValue(gender);
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
