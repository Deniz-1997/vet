import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';
import { BaseFormComponent } from '../../utils/base-form-component';
import { PersonType } from '../../../../utils/person-type';
import { OwnerModel } from '../../../../models/owner/owner.models';
import { select, Store } from '@ngrx/store';
import { Observable } from 'rxjs';
import { ModalConfirmComponent } from '../../components/modal-confirm/modal-confirm.component';
import { MatDialog } from '@angular/material/dialog';
import { Params, Router } from '@angular/router';
import { GroupModel } from '../../../../models/group.models';
import { CrudType } from 'src/app/common/crud-types';
import { EnumModel } from '../../../../models/enum .models';
import { PetsService } from 'src/app/services/pets.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {getCrudModelCreateLoading} from 'src/app/api/api-connector/crud/crud.selectors';
import {LoadDeleteAction, LoadGetAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({
  selector: 'app-owner-individual-form',
  templateUrl: './owner-individual-form.component.html',
  styleUrls: ['./owner-individual-form.component.css']
})
export class OwnerIndividualFormComponent extends BaseFormComponent implements OnInit {

  @Input() choicesActivities: { id: number, name: string, sort: number }[] = [];
  @Input() DocumentTypeEnum: EnumModel[];
  @Input() petId: string;
  @Input() isModal: boolean = false;
  @Output() getMatches = new EventEmitter();
  showError = false;
  type = CrudType.Owner;
  getLoading$: Observable<boolean>;
  panelPassportState = false;
  private personType = PersonType.INDIVIDUAL_PERSON;
  @Input() backButton: boolean;
  @Input() isDisabled = false;
  @Output() ownerSelected = new EventEmitter();

  constructor(
    private fb: FormBuilder,
    private petsService: PetsService,
    protected store: Store<CrudState>,
    private dialog: MatDialog,
    private router: Router,
  ) {
    super();
    this.getLoading$ = this.store.pipe(select(getCrudModelCreateLoading, { type: this.type }));
  }

  ngOnInit() {
    this.formGroup = this.fb.group({
      id: [0],
      name: [''],
      fullName: this.fb.group({
        lastName: ['', [Validators.required]],
        name: ['', [Validators.required]],
        middleName: [''],
      }),
      gender : [''],
      phone: ['', [Validators.required]],
      email: ['', [Validators.email]],
      addPassportData: [false],
      passport: this.fb.group({
        series: [''],
        number: [''],
        dateOfIssue: [null],
        issuer: [''],
        issuerCode: [''],
        documentType: this.fb.group({
          code: ['RF_CITIZEN_PASSPORT']
        })
      }),
      address: this.fb.group({
        full: ['', [Validators.required]],
        coordinates: [''],
        apartmentNumber: [''],
      }),
      individualPerson: this.fb.group({
        household: [false],
        householdAddress: [''],
        householdAddressIsPersonAddress: [false],
        householdInRent: [false],
        householdRentExpirationDate: [null],
      }),
      activities: [[]],
      customActivities: [''],
      additionalContacts: '',
      contractDateTo: null
    });
    this.formGroup.get('individualPerson').get('householdInRent');
    this.formGroup.get('individualPerson').get('householdInRent')
      .valueChanges.subscribe(
        value => {
          const control = this.formGroup.get('individualPerson').get('householdRentExpirationDate');
          if (value) {
            control.enable();
          } else {
            control.disable();
            control.setValue('');
          }
        }
      );
    if (this.model) {
      if (!this.model.passport) {
        this.model.passport = this.formGroup.get('passport').value;
        if (this.model.passport['documentType']) {
          this.model.passport['documentType']['code'] = 'RF_CITIZEN_PASSPORT';
        }
      }
      this.resetForm(this.model);
    }
    this.getPetAdress();
  }

  resetForm(model: OwnerModel): void {
    super.resetForm(model);
    this.panelPassportState = false;
    if (this.model.passport
      && this.model.passport.documentType) {
      this.panelPassportState = !!this.model.passport.documentType['code'];
      if (this.panelPassportState === false) {
        this.formGroup.get('passport.documentType.code').setValue('RF_CITIZEN_PASSPORT');
      }
    }
    this.formGroup.get('addPassportData').setValue(this.panelPassportState);
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
          params: { id: this.formGroup.controls.id.value },
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

  compareFn(o1: GroupModel, o2: GroupModel): boolean {
    return o1 && o2 ? o1.name === o2.name : o2 === o2;
  }

  submit(): void {
    this.showError = true;
    const value = this.formGroup.value['fullName'];
    this.formGroup.get('name').setValue([
      value['lastName'].trim(),
      value['name'].trim(),
      value['middleName'].trim(),
    ].join(' '));

    //const addPassport = this.formGroup.get('addPassportData');
    const passport = this.formGroup.get('passport'); // this.formGroup.value['passport'];
    if (!passport.value || !passport.value['number']) {
      passport.setValue({
        series: '',
        number: '',
        dateOfIssue: null,
        issuer: '',
        issuerCode: '',
        documentType: {
          code: ''
        }
      });
    } else {
      if (passport.value && passport.value['number']) {
        this.formGroup.get('passport').patchValue({
          number: passport.value['number'].replace('_', '')
        });
      }
    }
    super.submit();
  }

  addOwner(owner: OwnerModel) {
    if (this.petId) {
      const that = this;
      this.petsService.addOwner(+this.petId, owner.id, false, function (res) {
        if (res.status === true && res.response && res.response.id) {
          const n = ['pets', that.petId];
          if (this.isModal) {
            this.ownerSelected.emit(owner);
          } else {
            that.router.navigate(n).then();
          }
        }
        return this;
      });
    }
    else {
      if (this.isModal) {
        this.ownerSelected.emit(owner);
      } else {
        this.router.navigate(['/owners', owner.id, 'profile']);
      }
    }
  }

  getPetAdress() {
    if (this.petId && this.formGroup.get('address').get('full').value == '') {
      const params: Params = {};
      params.id = this.petId;
      this.store.dispatch(new LoadGetAction({
        type: CrudType.Pet,
        params: {
          id: this.petId
        },
        onSuccess: (res) => {
          if (res.status === true) {
            this.formGroup.get('address').get('full').setValue(res.response.address.full);
            this.formGroup.get('address').get('apartmentNumber').setValue(res.response.address.apartmentNumber);
          }
        },
      }));
    }
  }

}
