import {Component, EventEmitter, Input, OnChanges, OnDestroy, OnInit, Output, SimpleChanges} from '@angular/core';
import {BehaviorSubject, Observable, Subject} from 'rxjs';

import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';

import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';

import {DatePipe} from '@angular/common';

import {MatDialog} from '@angular/material/dialog';
import {takeUntil} from 'rxjs/operators';
import {CrudType} from '../../../../common/crud-types';
import {LeavingModel} from '../../../../models/leaving/leaving.models';
import {OwnerModel} from '../../../../models/owner/owner.models';
import {ReferenceProfessionModel} from '../../../../models/reference/reference.profession.models';
import {ReferenceLeavingTypeModel} from '../../../../models/reference/reference.leaving.type.models';
import {PetModel} from '../../../../models/pet/pet.models';
import {NotifyService} from '../../../../services/notify.service';
import {PetsService} from '../../../../services/pets.service';
import {OwnersService} from '../../../../services/owners.service';
import {AuthService} from '../../../../services/auth.service';
import {EditComponent} from '../../../main/pages/owners/edit/edit.component';
import {PetAddFormComponent} from '../pet-add-form/pet-add-form.component';
import {ReferenceReasonForLeavingModel} from '../../../../models/reference/reference.reason.for.leaving.models';
import {AuditionService} from '../../../../services/audition.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';
import {LoadCreateAction, LoadGetListAction, LoadMatchesAction} from 'src/app/api/api-connector/crud/crud.actions';



@Component({
  selector: 'app-leaving-add-form',
  templateUrl: './leaving-add-form.component.html',
  styleUrls: ['./leaving-add-form.component.css']
})

export class LeavingAddFormComponent implements OnInit, OnChanges, OnDestroy {

  @Input() title = 'Создать выезд';
  @Input() petId: number;
  @Input() formType: CrudType;
  @Input() ownerId: number;
  @Input() model: LeavingModel;
  @Input() loading: boolean;
  @Input() date = this.datePipe.transform(new Date(), 'dd.MM.yyyy');
  @Input() time = this.datePipe.transform(new Date(), 'HH:mm');
  @Input() user = null;
  @Input() placeholderPetitions = 'Животное хромает';
  @Input() placeholderProfession = 'Хирург';
  reasonForLeaving: Observable<ReferenceReasonForLeavingModel[]>;

  @Output() cancelForm = new EventEmitter();
  @Output() afterSaveEvent = new EventEmitter();
  owner: OwnerModel;


  owners: Observable<{ id: number, name: string }[]>;
  pets: Observable<{ id: number, name: string }[]>;
  professions: Observable<ReferenceProfessionModel[]>;
  buttonLoading = false;
  fields = {0: 'id', 1: 'name'};
  fieldsPet = ['id', 'name', 'owners', 'type'];

  leavingTypes: Observable<{ label: string, value: ReferenceLeavingTypeModel }[]>;
  previousLeavings: Observable<LeavingModel[]>;

  formGroup: FormGroup;
  types = CrudType;
  showError = false;

  pet = new PetModel();
  ownerItems = new BehaviorSubject([]);
  userAuth;
  lockOwner = false;
  lockPet = false;
  currentUnit;
  placeholderOwner = 'Иванов Иван Иванович';
  placeholderPet = 'Собака';

  private destroy$ = new Subject<any>();

  constructor(private fb: FormBuilder,
              private notify: NotifyService,
              private store: Store<CrudState>,
              protected route: ActivatedRoute,
              private petsService: PetsService,
              private ownerService: OwnersService,
              private datePipe: DatePipe,
              private router: Router,
              protected authService: AuthService,
              private dialog: MatDialog,
              public auditionService: AuditionService,
  ) {
    this.owners = this.store.pipe(select(getCrudModelData, {type: CrudType.Owner}));
    this.professions = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceProfession}));
    this.leavingTypes = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceLeavingType}));
    this.store.dispatch(new LoadGetListAction({type: CrudType.ReferenceLeavingType}));
    this.reasonForLeaving = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceReasonForLeaving}));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceReasonForLeaving,
      params: {
        order: {surname: 'ASC'},
        offset: 0,
        limit: 30
      }
    }));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceProfession,
      params: {
        order: {surname: 'ASC'},
        offset: 0,
        limit: 30
      }
    }));
    this.auditionService.dataOwner$.subscribe(ownerModel => this.getOwner(ownerModel));
    this.auditionService.dataPet$.subscribe(petModel => this.getPet(petModel));
  }

  ngOnInit() {
    this.formGroup = this.fb.group({
      paymentState: ['NOT_PAID'],
      date: new FormControl(this.date, [Validators.required]),
      time: new FormControl(this.time, [Validators.required]),
      name: ['', Validators.required],
      user: new FormControl(this.user !== null ? {id: this.user.id, fullName: this.getFullName(this.user)} : null),
      leavingStatus: this.fb.group({
        id: [2]
      }),
      type: this.fb.group({
        code: ['PRIMARY', Validators.required]
      }),
      profession: new FormControl(this.user !== null ? {
        id: this.user.professions[0].id,
        name: this.user.professions[0].name
      } : null, [Validators.required]),
      previous: this.fb.group({
        id: null
      }),
    });
    this.formGroup.addControl('temperatureNotMeasured', new FormControl(false));
    this.formGroup.addControl('weightNotMeasured', new FormControl(false));
    this.formGroup.addControl('reasonForLeaving', new FormControl(null, Validators.required));

    switch (this.formType) {
      case CrudType.Pet:
        this.formGroup.addControl('owner', new FormControl(null));
        break;
      case CrudType.Owner:
        this.formGroup.addControl('pet', new FormControl(this.pet ? this.pet : null));
        break;
      case CrudType.Leaving:
        this.formGroup.addControl('owner', new FormControl(null));
        this.formGroup.addControl('pet', new FormControl(null));
        break;
    }

    if (!this.user) {
      this.authService.getAccountUser().subscribe((res) => {
        if (this.authService.inGroup('ROLE_RECEPTIONIST')
          || this.authService.inGroup('ROLE_LEAVING_ADMIN')
          || this.authService.inGroup('AdminLeavingAll')) {
          return;
        }
        if (!this.user && res.response && res.response['user'] && res.response['user']) {
          if (res.response['user']['professions'] && res.response['user']['professions'].length > 0) {
            this.formGroup.controls.profession.setValue({
              id: res.response['user']['professions'][0].id,
              name: res.response['user']['professions'][0].name
            });
            this.userAuth = res.response['user'];
            this.setCurrentUser();
          }
        }
      });
    }

    if (this.formType === CrudType.Pet) {
      this.setOwnerByPet();
    }

    if (this.formType === CrudType.Owner) {
      if (this.petId && !isNaN(this.petId)) {
        this.petsService.getById(this.petId).subscribe(res => {
          if (res && res.status === true) {
            this.pet = res.response;
            this.formGroup.controls.pet.setValue({id: this.pet.id, name: this.pet.name});
          }
        });
      } else {
        this.setPetByOwner();
      }
    }

    if (this.formType === CrudType.Owner || this.formType === CrudType.Leaving) {
      this.formGroup.controls.pet.valueChanges
        .pipe(
          takeUntil(this.destroy$)
        )
        .subscribe((pet) => {
          if (pet && pet.id) {
            this.petId = pet.id;
            this.setOwnerByPet();
          } else {
            this.petId = null;
          }
          if (pet instanceof PetModel) {
            this.getRepeatLeaving(pet.id, (data) => {
              if (data.response.totalCount === 0) {
                this.formGroup.controls.type.get('code').setValue('PRIMARY');
              } else {
                this.formGroup.controls.type.get('code').setValue('SECONDARY');
              }
            });
          }
        });
    }

    this.formGroup.controls.type.get('code').valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe((res: string) => {
        if (res === 'SECONDARY') {

          this.getRepeatLeaving(this.petId, (data) => {
            if (data.response.totalCount === 0) {
              this.formGroup.controls.type.get('code').setValue('PRIMARY');
              this.notify.handleMessage('У данного животного нет предыдущих выездов', 'warning');
            }
          });

        } else {
          this.formGroup.get('previous.id').setValue(null);
        }
      });

    this.formGroup.controls.profession.valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe((res) => {
        if (res) {
          this.setCurrentUser();
        }
      });


    if (this.formType === CrudType.Leaving) {
      this.formGroup.controls.owner.valueChanges
        .pipe(
          takeUntil(this.destroy$)
        )
        .subscribe(res => {
          if (res && res.id) {
            this.ownerId = res.id;
            this.setPetByOwner();
            this.getOwnerModel(this.ownerId);
          } else {
            this.petId = null;
            this.ownerId = undefined;
            this.formGroup.controls['pet'].setValue(null);
          }
        });
    }

    this.authService.user$.subscribe((res) => {
      if (res && res['user']) {
        this.currentUnit = res['user']['unit'];
      }
    });
  }

  setOwnerByPet() {
    if (!this.lockOwner) {
      this.lockPet = true;

      this.petsService.getById(this.petId).subscribe(res => {
        if (res && res.status === true) {
          this.pet = res.response;
          const ownerItems = [];
          for (const i in this.pet.owners) {
            if (this.pet.owners.hasOwnProperty(i) && this.pet.owners[i].hasOwnProperty('owner')) {
              ownerItems.push({id: this.pet.owners[i].owner.id, name: this.pet.owners[i].owner.name});
            }
          }
          this.ownerItems.next(ownerItems);
          if (this.pet.owners.length >= 1 && this.formGroup.controls.owner) {
            this.formGroup.controls.owner.setValue({
              id: this.pet.owners[0].owner.id,
              name: this.pet.owners[0].owner.name
            });
          }
        }
        this.lockPet = false;
      });
    }
  }

  setPetByOwner() {
    if (this.ownerId && !this.lockPet) {
      this.lockOwner = true;
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.Pet,
        params: {
          filter: {owners: {owner: {id: this.ownerId}}},
          order: {surname: 'ASC'},
          offset: 0,
          limit: 30
        },
        onSuccess: (res) => {
          if (res.status && res.response && res.response.items.length === 1) {
            this.pet = res.response.items[0];
            this.petId = res.response.items[0].id;
            this.formGroup.controls.pet.setValue({id: res.response.items[0].id, name: res.response.items[0].name});
          }
          this.lockOwner = false;
        }
      }));
    }
  }

  setCurrentUser() {
    console.log('set');
    if (this.formGroup.controls['profession'] && this.formGroup.controls['profession']['value'] && this.userAuth) {
      if (this.userAuth.professions.find(n => n.id === this.formGroup.controls['profession']['value']['id'])) {
        this.formGroup.controls.user.setValue({id: this.userAuth.id, fullName: this.getFullName(this.userAuth)});
      } else {
        this.formGroup.controls.user.setValue(null);
      }
    }
  }

  getRepeatLeaving(petId, callback?) {
    this.store.dispatch(new LoadMatchesAction({
      type: CrudType.Leaving,
      params: {
        fields: {0: 'id', 1: 'name', 2: 'date'},
        filter: {
          pet: {id: petId},
          type: 'PRIMARY'
        },
        offset: 0,
        limit: 5
      },
      onSuccess: result => {
        this.previousLeavings = result.response.items;
        if (result.response.totalCount > 0) {
          this.formGroup.get('previous.id').setValue(result.response.items[0].id);
        }
        if (callback) {
          callback(result);
        }
      }
    }));
  }

  isProfession() {
    return {
      professions: {id: this.formGroup.get('profession').value.id},
      groups: {'!id': 1},
      unit: {id: this.currentUnit ? this.currentUnit['id'] : null},
      status: true
    };
  }

  isDeadFilter() {
    return {isDead: false};
  }

  ngOnChanges(changes: SimpleChanges): void {
    if (this.formGroup) {
      if (changes['model'] && changes['model'].currentValue) {
        if (!this.formGroup.get('id')) {
          this.formGroup.addControl('id', this.fb.control(['']));
        }
        if (this.formGroup.value['id'] !== changes['model'].currentValue['id']) {
          const model = changes['model'].currentValue;
          if (model.date) {
            const datetime = model.date.split(' ');
            model['data'] = datetime[0];
            model['time'] = datetime[1];
          }
          this.formGroup.reset(model);
        }
      }
    }
  }

  hasProfession() {
    return !!(this.formGroup.controls.profession.value && this.formGroup.controls.profession.value.id);
  }

  submit(openLeaving: boolean = false): void {
    this.showError = true;

    if (this.formGroup.valid) {
      const model = {...this.formGroup.value};

      if (model.user && model.user.id === null) {
        delete model.user;
      }

      if (model.user && model.user.fullName) {
        delete model.user.fullName;
      }
      if (this.formType === CrudType.Pet) {
        model.pet = {id: this.petId};
      } else if (model.pet) {
        const petId = model.pet.id;
        delete model['pet'];
        model.pet = {id: petId};
      }
      if (this.formType === CrudType.Owner) {
        model.owner = {id: this.ownerId};
      }

      if (model.previous && !model.previous.id) {
        delete model['previous'];
        model.previous = null;
      }
      model.date = this.formGroup.controls.date.value + ' ' + this.formGroup.controls.time.value + ':00';
      console.log(model);

      this.loading = true;
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.Leaving,
        params: {
          filter: {
            user: {id: model?.user?.id},
            date: model.date
          }
        },
        onSuccess: response => {
          this.loading = false;
          if (response.status) {
            if (response.response.totalCount === 0) {
              this.buttonLoading = true;
              const action = (this.model && this.model.id) ? LoadMatchesAction : LoadCreateAction;
              this.store.dispatch(new action({
                type: CrudType.Leaving,
                params: model,
                onSuccess: (res) => {
                  console.log(res.response);
                  this.buttonLoading = false;
                  if (res.status === true && res.response && res.response.id) {
                    if (this.user === null) {
                      if (openLeaving) {
                        this.router.navigate(['leaving/' + res.response.id + '/leaving-edit'], {queryParams: {code: 'OPENED'}}).then();
                      } else {
                        this.router.navigate(['leaving/leaving-list']).then();
                      }
                    } else {
                      this.afterSaveEvent.emit(model);
                    }
                  }
                },
                onError: _ => {
                  this.buttonLoading = false;
                }
              }));

            } else {
              this.notify.handleMessage('В указанное время, на выбранного специалиста, существует выезд', 'warning');
            }
          } else {
            this.notify.handleMessage('Ошибка на стороне сервера', 'danger');
          }
        }, onError: error => {
          this.loading = false;
          this.notify.handleMessage('Ошибка на стороне сервера', 'danger');
        }
      }));
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
    }
  }

  getFullName(employee: { name?: string; surname: string; patronymic?: string; }): string {
    return (employee.surname + ' ' + employee.name + ' ' + employee.patronymic);
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }

  convertResultForPets(array, type) {
    return (type === CrudType.Pet) ? array.map(v => {
      let name = v.name;
      const owner = v.owners.map(o => {
        if (o.mainOwner) {
          return o.owner.name;
        }
      });
      name += ' - ' + v.type.name;
      name += ' - ' + owner;
      return {id: v.id, name: name};
    }) : array;
  }
  getId() {
    this.ownerId = this.formGroup.controls.owner.value?.id;
    this.getOwnerModel(this.ownerId);

  }
  getOwnerModel(ownerId) {
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Owner,
      params: {
        filter: {id:  ownerId}
      },
      onSuccess: (res) => {
        this.owner = res.response.items[0];
      }})
    );
  }
  getOwner(res) {
    this.ownerId = res.id;
    this.formGroup.get('owner').setValue({id: res.id, name: res.name});
  }
  getPet(res) {
    this.petId = res.id;
    this.formGroup.get('pet').setValue({id: res.id, name: res.name});
  }
}


