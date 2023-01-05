import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {FormArray, FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {Observable} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {MatDialog} from '@angular/material/dialog';
import {Router} from '@angular/router';
import {BaseFormComponent} from '../../utils/base-form-component';
import {AppointmentModel} from '../../../../models/appointment/appointment.models';
import {FileMonitoredObjectModel} from '../../../../models/file/file.monitored.object.models';
import {ReferenceOwnerLegalFormModel} from '../../../../models/reference/reference.owner.legal.form.models';
import {FileModel} from '../../../../models/file/file.models';
import {OwnerModel} from '../../../../models/owner/owner.models';
import {ModalConfirmComponent} from '../../components/modal-confirm/modal-confirm.component';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadDeleteAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelCreateLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-contractor-legal-form',
  templateUrl: './contractor-legal-form.component.html',
  styleUrls: ['./contractor-legal-form.component.css']
})
export class ContractorLegalFormComponent extends BaseFormComponent implements OnInit {
  crudType = CrudType;
  @Input() appointments: AppointmentModel[] = [];
  @Input() fileTypes$: Observable<FileMonitoredObjectModel[]>;
  @Input() files: FileModel[] = [];
  @Input() legalForms$: ReferenceOwnerLegalFormModel[] = [];
  @Output() getMatches = new EventEmitter();
  @Input() buttonName = 'владельца';
  @Input() showBlockFile = true;
  @Input() routeList: string[] = ['owners'];
  showError = false;
  @Input() type = CrudType.Owner;
  getLoading$: Observable<boolean>;

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
      phone: [''],
      email: ['', [Validators.email]],
      name: [''],
      inn: [''],

      legalForm: new FormControl({id: 1, name: 'ООО'}),
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
    });
    if (this.model) {
      this.resetForm(this.model);
    }
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

  submit(): void {
    this.showError = true;
    if (this.formGroup && this.formGroup.valid) {
      this.submitForm.emit(this.formGroup.value);
    } else {
      console.log(this.formGroup);
    }
  }

  setRemoveFile(id: number) {
    const control = <FormArray>this.formGroup.controls['contactPersons'];
    const dialogRef = this.dialog.open(ModalConfirmComponent, {
      data: {
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
      }
    });

    dialogRef.afterClosed().subscribe((result: boolean) => {
      if (result) {
        control.removeAt(id);
      }
    });
  }
}
