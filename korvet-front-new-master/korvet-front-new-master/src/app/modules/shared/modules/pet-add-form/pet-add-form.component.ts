import {Component, EventEmitter, Inject, Input, OnDestroy, OnInit, Optional, Output} from '@angular/core';
import {FormArray, FormControl, FormGroup, Validators} from '@angular/forms';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {Observable, Subject, Subscription} from 'rxjs';
import {filter, first, takeUntil} from 'rxjs/operators';
import {CrudType} from 'src/app/common/crud-types';
import {ReferencePetAggressiveTypeModel} from 'src/app/models/reference/reference.pet.aggressive.type.models';
import {PetModel} from 'src/app/models/pet/pet.models';
import {ReferencePetTypeModel} from 'src/app/models/reference/reference.pet.type.models';
import {ReferenceBreedModel} from 'src/app/models/reference/reference.breed.models';
import {ReferencePetLearModel} from 'src/app/models/reference/reference.pet-lear.models';
import {ReferenceAnimalDeathModel} from 'src/app/models/reference/reference.animal.death.models';
import {VeterinaryPassportTypeModel} from 'src/app/models/veterinary-passport-type.models';
import {PetsService} from 'src/app/services/pets.service';
import {NotifyService} from 'src/app/services/notify.service';
import {BreadcrumbsService} from 'src/app/services/breadcrumbs.service';
import {previousDate} from '../../validators/previous_date';
import {maskValidator} from '../../validators/mask-validator';
import {GroupModel} from 'src/app/models/group.models';
import {OwnerModel} from 'src/app/models/owner/owner.models';
import {ReferencePetReasonRetiringModel} from '../../../../models/reference/reference.pet.reason.retiring.models';
import { BirthdayService } from 'src/app/services/birthday.service';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {getCrudModelData, getCrudModelDeleteLoading, getCrudModelGetLoading, getCrudModelStoreId} from 'src/app/api/api-connector/crud/crud.selectors';
import {CompleteGetListAction, LoadCreateAction, LoadGetAction, LoadGetListAction, LoadPatchAction} from 'src/app/api/api-connector/crud/crud.actions';
import {ApiResponse} from 'src/app/api/api-connector/api-connector.models';


declare var $: any;

@Component({
  selector: 'app-pet-add-form',
  templateUrl: './pet-add-form.component.html',
  styleUrls: ['./pet-add-form.component.css'],
})
export class PetAddFormComponent implements OnInit, OnDestroy {
  @Input() owner: OwnerModel;
  @Input() title = 'Добавить животное';
  @Input() id: string;

  @Output() cancelForm = new EventEmitter();
  @Output() afterSubmitForm = new EventEmitter();
  crudType = CrudType;
  public model: PetModel = new PetModel();
  public model$: Observable<PetModel>;
  type = CrudType.Pet;
  public petTypesItems: Observable<ReferencePetTypeModel[]>;
  public breedItems: Observable<ReferenceBreedModel[]>;
  public petLearItems: Observable<ReferencePetLearModel[]>;
  public animalDeathItems: Observable<ReferenceAnimalDeathModel[]>;
  public petRetiringItems: Observable<ReferencePetReasonRetiringModel[]>;
  public aggressiveTypeItems: Observable<ReferencePetAggressiveTypeModel[]>;
  public identifierItems: Observable<any>;
  public formGroup: FormGroup;
  loading$: Observable<boolean>;
  loadingRemove$: Observable<boolean>;
  showError = false;
  removeIdentifiers = null;
  isReadonly = false;
  veterinaryPassportType$: Observable<VeterinaryPassportTypeModel[]>;
  defaultVeterinaryPassportType: VeterinaryPassportTypeModel = null;
  toDay = new Date();
  private destroy$ = new Subject<any>();
  private subscriptions: Subscription[] = [];
  openDialog = false;
  backButton = false;

  constructor(
    private petsService: PetsService,
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    private brdSrv: BreadcrumbsService,
    private birthdayService: BirthdayService,
    @Optional() public dialogRef: MatDialogRef<PetAddFormComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , owner: OwnerModel}
  ) {
    this.model.gender = 'MALE';
    this.model.chipNumber = '';
    this.model.aggressive = false;
    this.model.isSterilized = false;
    this.model.useOwnerAddress = true;
    this.model.birthday = '';

    this.petTypesItems = this.petsService.getPetTypes();
    this.identifierItems = this.petsService.getPetIdentifierType();

    this.veterinaryPassportType$ = this.store.pipe(select(getCrudModelData, {type: CrudType.VeterinaryPassportType}));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.VeterinaryPassportType,
      params: {
        order: {surname: 'ASC'},
      }
    }));
    this.veterinaryPassportType$
      .pipe(
        filter(type => type.length > 0),
        first(),
      )
      .subscribe(
        type => {
          if (type) {
            type.map(
              item => {
                if (item.isDefault) {
                  this.defaultVeterinaryPassportType = item;
                  if (this.formGroup && !this.formGroup.controls.veterinaryPassportType.value) {
                    this.formGroup.controls.veterinaryPassportType.setValue(item);
                  }
                }
              }
            );
          }
        }
      );

    this.loadingRemove$ = this.store.pipe(select(getCrudModelDeleteLoading, {type: this.type}));
    this.passportTypMask = {mask: false, showMask: false};
  }

  private _passportTypMask = {};

  /**
   * setMask
   */

  get passportTypMask(): {} {
    return this._passportTypMask;
  }

  set passportTypMask(mask: {}) {
    this._passportTypMask = mask;
  }

  static getIdentifiersBase(id?: number, typeId?: number, value?: string) {
    typeId = typeId || null;
    value = value || '';
    // id = id || null;
    return new FormGroup({
      type: new FormGroup({
        id: new FormControl(typeId, [Validators.required]),
      }),
      value: new FormControl(value, [Validators.required]),
      /*id: new FormControl(id),*/
    });
  }

  ngOnInit() {
    if (this.data != null) {
      this.owner = this.data.owner;
      this.openDialog = true;
      this.backButton = true;
    }
    if (this.isEdit()) {
      this.store.dispatch(new LoadGetAction({type: this.type,
        params: <any>{
          id: this.id,
          fields: {0: 'id',
                  1: 'name',
                  2: 'type',
                  3: 'breed',
                  4: 'lear',
                  5: 'description',
                  6: 'aggressive',
                  7: 'aggressiveType',
                  8: 'isSterilized',
                  9: 'gender',
                  10: 'birthday',
                  11: 'vaccinationDate',
                  12: 'chipNumber',
                  13: 'veterinaryPassportType',
                  14: 'veterinaryPassportNumber',
                  15: 'address',
                  16: 'useOwnerAddress',
                  17: 'isDead',
                  18: 'dateOfDeath',
                  19: 'animalDeath',
                  20: 'identifiers',
                  21: 'isRetiring',
                  22: 'dateOfRetiring',
                  23: 'petRetiring',
                  'owners': {0: 'id', 1: 'mainOwner', owner: ['id', 'name', 'address', 'fullName']}
        }}}));
      this.model$ = this.store.pipe(select(getCrudModelStoreId, {type: this.type, params: this.id}), filter(data => !!data));
      const s = this.model$.pipe(
        takeUntil(this.destroy$)
      ).subscribe((model) => {
        this.model = model;
        if (!this.model.address) {
          this.model.address = {
            full: '',
            coordinates: '',
            apartmentNumber: '',
          };
        }
        if (this.model.useOwnerAddress) {
          this.isReadonly = true;
          this.model.address.full = this.findAddressMainOwner();
        } else if (!this.model.address.full) {
          this.model.address.full = '';
        }
        this.setModel();
        this.brdSrv.replaceLabelByIndex(this.model.name, 2);
        this.loading$ = this.store.pipe(select(getCrudModelGetLoading, {type: this.type}));
      });
      this.subscriptions.push(s);
      this.animalDeathItems = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceAnimalDeath}));
      this.petRetiringItems = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferencePetReasonRetiringType}));
      this.aggressiveTypeItems = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferencePetAggressiveType}));
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.ReferenceAnimalDeath,
        params: <any>{
          fields: {0: 'id', 2: 'name'},
          order: {fullName: {middleName: 'ASC'}},
          offset: 0,
          limit: 10
        }
      }));

    } else {
      this.setModel();
    }
  }

  isEdit() {
    return ['create', null, undefined].indexOf(this.id) < 0;
  }

  public setYearsAndMonthsByBirthday() {
    const birthday = this.formGroup.get('birthday');
    const years = this.formGroup.get('numberOfYears');
    const months = this.formGroup.get('numberOfMonths');
    const dateEnd = this.formGroup.get('dateOfDeath');
    const yearsAndMonths = this.birthdayService.getYearsAndMonthsFromBirthday(birthday.value, dateEnd.value);
    if (years.value !== yearsAndMonths['years']) {
      years.setValue(yearsAndMonths['years']);
    }
    if (months.value !== yearsAndMonths['months']) {
      months.setValue(yearsAndMonths['months']);
    }
  }

  public setBirthdayByYearsAndMonth() {
    const limitYears = 99;
    const birthday = this.formGroup.get('birthday');
    const years = this.formGroup.get('numberOfYears');
    const months = this.formGroup.get('numberOfMonths');
    const dateEnd = this.formGroup.get('dateOfDeath');
    if (years.value > limitYears) {
      years.setValue(limitYears);
      months.setValue(0);
      return false;
    }
    const yearsAndMonthSum = this.birthdayService.getYearsAndMonthsSum(years.value, months.value);
    if (yearsAndMonthSum > limitYears) {
      years.setValue(limitYears);
      months.setValue(0);
      return false;
    }
    if (years || months) {
      const date = this.birthdayService.getBirthdayFromYearsAndMonth(years.value, months.value);
      const formatDate = this.birthdayService.getValueByDate(date);
      if (birthday.value !== formatDate && yearsAndMonthSum <= limitYears) {
        this.formGroup.get('birthday').setValue(formatDate, {emitEvent: false});
      }
    }
  }

  findAddressMainOwner() {
    if (this.owner && this.owner.address) {
      return this.owner.address.full;
    }
    let ownerAddress = '';
    if (this.model && this.model.owners && this.model.owners.length === 1) {
      ownerAddress = this.model.owners[0].owner.address ? this.model.owners[0].owner.address.full : '';
    } else if (this.model && this.model.owners && this.model.owners.length > 1) {
      ownerAddress = this.model.owners.reduce((acc, item) => {
        return item.mainOwner && item.owner.address && item.owner.address.full ? item.owner.address.full : acc;
      }, '');
    }
    return ownerAddress;
  }

  hasType() {
    return this.formGroup.controls.type.value && typeof this.formGroup.controls.type.value === 'object';
  }

  initIdentifiers() {
    if (this.model && this.model.identifiers && this.model.identifiers.length > 0) {
      const control = new FormArray([]);
      let i;
      for (i in this.model.identifiers) {
        if (this.model.identifiers[i]) {
          control.push(PetAddFormComponent.getIdentifiersBase(
            this.model.identifiers[i].id,
            this.model.identifiers[i].type.id,
            this.model.identifiers[i].value));
        }
      }
      return control;
    }
    return new FormArray([]);
  }

  addIdentifier($event) {
    const control = <FormArray>this.formGroup.controls['identifiers'];
    control.push(PetAddFormComponent.getIdentifiersBase());
    $event.preventDefault();
  }

  setRemoveIdentifiers(ind, $event) {
    const control = <FormArray>this.formGroup.controls['identifiers'];
    if (!control.controls[ind].get('value').value) {
      control.removeAt(ind);
      $event.preventDefault();
    }
    this.removeIdentifiers = ind;
  }

  removeIdentifier($event) {
    $event.preventDefault();
    const control = <FormArray>this.formGroup.controls['identifiers'];
    control.removeAt(this.removeIdentifiers);
  }

  submit(): void {
    this.formGroup.markAsTouched();
    this.showError = true;

    if (this.formGroup.controls.veterinaryPassportNumber.invalid) {
      this.formGroup.controls.veterinaryPassportNumber.setValue('');
    }

    if (this.formGroup.valid) {
      const action = this.id ? LoadPatchAction : LoadCreateAction;
      const model = this.formGroup.value;
      if (!model.lear || !model.lear.id) {
        model.lear = null;
      }
      if (this.id) {
        model.id = parseInt(this.id, 10);
      }
      model.isSterilized = Boolean(model.isSterilized);
      this.store.dispatch(new action({
        type: this.type,
        params: model,
        onSuccess: (res) => {
          if (res.status === true && res.response && res.response.id) {
            this.id = res.response.id;
            if (this.owner) {
              this.addPetToOwner();
              if (this.openDialog) {
                return ;
              }
            }
            this.successNavigation();
          }
        }
      }));
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
    }
  }

  onChangeType() {
    this.formGroup.controls.breed.setValue(null);
    this.formGroup.controls.lear.setValue(null);
    const typeId = this.formGroup.value.type;
    if (typeId && typeId.id > 0) {
      this.breedItems = this.petsService.getBred10(typeId);
    } else {
      this.formGroup.controls.breed.setValue(null);
      this.breedItems = null;
    }
  }

  onChangeBreed() {
    this.formGroup.controls.lear.setValue(null);
  }

  getBackLink() {
    return ['create', null, undefined].indexOf(this.id) > -1 ? '/pets' : '/pets/' + this.id;
  }

  remove($event) {
    if ($event) {
      $event.preventDefault();
    }
    return this.petsService.remove(this.model.id).pipe(
      takeUntil(this.destroy$)
    ).subscribe((res: ApiResponse) => {
      if (res && res.status === true) {
        $('[data-fancybox-close]').trigger('click');
        const n = ['pets'];
        this.router.navigate(n).then();
      }
    });
  }

  vaccinationInvalid(): boolean {
    let isValid = false;
    let date;

    if (this.formGroup.controls.vaccinationDate.value) {
      date = new Date(this.formGroup.controls.vaccinationDate.value.replace(/(\d+).(\d+).(\d+)/, '$3/$2/$1'));
      date.setFullYear(date.getFullYear() + 1);
      const today = new Date();

      if (today >= date) {
        isValid = true;
      }
    }

    return isValid;
  }

  isDead() {
    if (this.formGroup && this.formGroup.controls.isDead.value) {
      return this.formGroup.controls.isDead.value;
    } else {
      return false;
    }
  }

  isRetiring() {
    if (this.formGroup && this.formGroup.controls.isRetiring.value) {
      return this.formGroup.controls.isRetiring.value;
    } else {
      return false;
    }
  }


  isAggressive() {
    if (this.formGroup && this.formGroup.controls.aggressive.value) {
      return this.formGroup.controls.aggressive.value;
    } else {
      return false;
    }
  }

  ngOnDestroy(): void {
    this.destroy$.next();
    this.subscriptions
      .forEach(s => s.unsubscribe());
    this.store.dispatch(new CompleteGetListAction({
      type: CrudType.Pet,
      params: null
    }));
  }

  onSetMask() {
    this.formGroup.get('veterinaryPassportNumber').setValue('');
    this.setMask(this.formGroup.get('veterinaryPassportType').value);
  }

  setMask(value?) {
    if (value && value.numberMaskFront) {
      let maskArray = value.numberMaskFront.split(',');

      maskArray = maskArray.map(item => {
        const regParts = item.match(/^\/(.*?)\/([gim]*)$/);
        if (regParts) {
          return new RegExp(regParts[1], regParts[2]);
        } else {
          return item;
        }
      });

      this.passportTypMask = {
        mask: maskArray,
        showMask: true
      };
      this.formGroup.get('veterinaryPassportNumber').setValidators([maskValidator]);
    } else {
      this.passportTypMask = {mask: false, showMask: false};
      this.formGroup.get('veterinaryPassportNumber').setValidators([]);
    }
  }

  compareFn(o1: GroupModel, o2: GroupModel): boolean {
    return o1 && o2 ? o1.name === o2.name : o2 === o2;
  }

  addPetToOwner() {
    this.store.dispatch(new LoadCreateAction({
      type: CrudType.PetToOwner,
      params: <any>{
        pet: {
          id: this.id
        },
        owner: {
          id: this.owner.id
        },
        mainOwner: true,
      },
      onSuccess: (res) => {
        if (this.openDialog) {
          return this.dialogClose(res.response);
        }
        this.successNavigation();
      }
    }));
  }

  successNavigation() {
    if (this.afterSubmitForm.observers.length > 0) {
      this.afterSubmitForm.emit();
    } else {
      const n = ['pets'];
      n.push(this.id);
      this.router.navigate(n).then();
    }
  }

  protected setModel() {
    if (this.owner && this.owner.address) {
      this.model.address = this.owner.address;
      this.isReadonly = true;
    }
    this.formGroup = new FormGroup({
      aggressive: new FormControl(this.model.aggressive, []),
      aggressiveType: new FormControl(this.model.aggressiveType, []),
      name: new FormControl(this.model.name, [Validators.required]),
      isSterilized: new FormControl(this.model.isSterilized),
      type: new FormControl((this.model.type && this.model.type.id) ? {
        id: this.model.type.id,
        name: this.model.type.name
      } : null, [Validators.required]),
      breed: new FormControl({id: this.model.breed.id, name: this.model.breed.name}, [Validators.required]),
      gender: new FormControl(this.model.gender, [Validators.required]),
      birthday: new FormControl(this.model.birthday, [Validators.required]),
      numberOfYears: new FormControl(''),
      numberOfMonths: new FormControl(''),
      lear: new FormControl({id: this.model.lear && this.model.lear.id, name: this.model.lear && this.model.lear.name}),
      description: new FormControl(this.model.description),
      vaccinationDate: new FormControl(this.model.vaccinationDate),
      chipNumber: new FormControl(this.model.chipNumber, []),
      veterinaryPassportType: new FormControl(this.model.veterinaryPassportType ?
        this.model.veterinaryPassportType : this.defaultVeterinaryPassportType),
      veterinaryPassportNumber: new FormControl(this.model.veterinaryPassportNumber ?
        this.model.veterinaryPassportNumber : '', []),
      address: new FormGroup({
        full: new FormControl((this.model.address && this.model.address.full) ? this.model.address.full : '', [Validators.required]),
        apartmentNumber: new FormControl(
          (this.model.address && this.model.address.apartmentNumber) ? this.model.address.apartmentNumber : ''),
      }),
      useOwnerAddress: new FormControl(!!(this.owner && this.owner.address || this.model.useOwnerAddress), []),
      isDead: new FormControl(this.model.isDead ? this.model.isDead : false),
      dateOfDeath: new FormControl(this.model.dateOfDeath ? this.model.dateOfDeath : null, [previousDate]),
      animalDeath: new FormControl(this.model.animalDeath ? {id: this.model.animalDeath.id, name: this.model.animalDeath.name} : null),
      identifiers: this.initIdentifiers(),
      isRetiring: new FormControl(this.model.isRetiring ? this.model.isRetiring : false),
      dateOfRetiring: new FormControl(this.model.dateOfRetiring ? this.model.dateOfRetiring : null, [previousDate]),
      petRetiring: new FormControl(this.model.petRetiring ? {id: this.model.petRetiring.id, name: this.model.petRetiring.name} : null),
    });

    if (this.model.birthday) {
      this.setYearsAndMonthsByBirthday();
    }
    this.formGroup.get('useOwnerAddress').valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(value => {
          if (value) {
            this.formGroup.get('address.full').setValue(this.findAddressMainOwner());
            this.isReadonly = true;
          } else {
            this.isReadonly = false;
          }
        }
      );

    if (this.model && this.model.veterinaryPassportType && this.model.veterinaryPassportType.numberMaskFront) {
      this.setMask(this.model.veterinaryPassportType);
    }

    this.formGroup.get('birthday').valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(value => {
          if (value) {
            const birthdayDate = this.birthdayService.getDateByValue(value);
            if (!birthdayDate) {
              return false;
            }
            const currentDate = new Date();
            if (birthdayDate.getTime() > currentDate.getTime()) {
              this.formGroup.get('birthday').setValue(this.birthdayService.getValueByDate(currentDate));
              return true;
            }
            if (value.indexOf('29.02') > -1) {
              birthdayDate.setDate(birthdayDate.getDate() - 1);
              this.formGroup.get('birthday').setValue(this.birthdayService.getValueByDate(birthdayDate));
              return true;
            }
          }
          this.setYearsAndMonthsByBirthday();
        }
      );
    this.formGroup.get('dateOfDeath').valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(value => {
          this.setYearsAndMonthsByBirthday();
        }
      );
    this.formGroup.controls.isDead.valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(value => {
        if (value) {
          this.formGroup.get('dateOfDeath').setValue(null);
          this.formGroup.get('animalDeath').setValue(null);
          this.formGroup.controls.dateOfDeath.enable();
          this.formGroup.controls.animalDeath.enable();
        } else {
          this.formGroup.controls.dateOfDeath.disable();
        }
      });
    this.formGroup.controls.isRetiring.valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(value => {
        if (value) {
          this.formGroup.get('dateOfRetiring').setValue(null);
          this.formGroup.get('petRetiring').setValue(null);
          this.formGroup.controls.dateOfRetiring.enable();
          this.formGroup.controls.petRetiring.enable();
        } else {
          this.formGroup.controls.dateOfRetiring.disable();
        }
      });


    if (!this.model.isDead) {
      this.formGroup.controls.dateOfDeath.disable();
    }

    if (!this.findAddressMainOwner()) {
      this.formGroup.controls.useOwnerAddress.setValue(false);
    }
  }
  dialogClose(id) {
    this.dialogRef.close(id);
  }

  addPet(pet: PetModel) {
    if (this.owner && this.owner.id) {
      const that = this;
      this.petsService.addOwner(pet.id, this.owner.id, false, function (res) {
        if (res.status === true && res.response && res.response.id) {
          const n = ['pets', pet.id];
        }
        return this;
      });
    }
    else {
        this.router.navigate(['/pets', pet.id, 'add-appointment']);
    }
  }

}

