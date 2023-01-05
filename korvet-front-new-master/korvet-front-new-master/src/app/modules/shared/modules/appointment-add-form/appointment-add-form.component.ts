import {Component, EventEmitter, Input, OnChanges, OnDestroy, OnInit, Output, SimpleChanges} from '@angular/core';
import {PetModel} from '../../../../models/pet/pet.models';
import {AppointmentModel} from '../../../../models/appointment/appointment.models';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BehaviorSubject, Observable, Subject} from 'rxjs';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../services/notify.service';
import {select, Store} from '@ngrx/store';
import {ReferenceAppointmentTypeModel} from '../../../../models/reference/reference.appointment.type.models';
import {PetsService} from '../../../../services/pets.service';
import {takeUntil} from 'rxjs/operators';
import {ReferenceProfessionModel} from '../../../../models/reference/reference.profession.models';
import {CrudType} from 'src/app/common/crud-types';
import {DatePipe} from '@angular/common';
import {AuthService} from 'src/app/services/auth.service';
import { AppointmentOwnerPetModels } from 'src/app/models/appointment/appointment-owner-pet.models';
import {MatDialog} from '@angular/material/dialog';
import {OwnersService} from '../../../../services/owners.service';
import {EditComponent} from '../../../main/pages/owners/edit/edit.component';
import {PetAddFormComponent} from '../pet-add-form/pet-add-form.component';
import {OwnerModel} from '../../../../models/owner/owner.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadMatchesAction, LoadCreateAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-appointment-add-form',
  templateUrl: './appointment-add-form.component.html',
  styleUrls: ['./appointment-add-form.component.css']
})
export class AppointmentAddFormComponent extends AppointmentOwnerPetModels implements OnInit, OnChanges, OnDestroy {

  @Input() title = 'Запись на прием';
  @Input() formType: CrudType;
  @Input() ownerId: number;
  @Input() petId: number;
  @Input() model: AppointmentModel;
  @Input() loading: boolean;
  @Input() date = this.datePipe.transform(new Date(), 'dd.MM.yyyy');
  @Input() time = this.datePipe.transform(new Date(), 'HH:mm');
  @Input() user = null;
  @Input() openDialog: boolean;
  @Input() backButton = true;
  @Input() placeholderPetitions = 'Животное хромает';
  @Input() placeholderProfession = 'Хирург';
  @Input() placeholderOwner = 'Иванов Иван Иванович';
  @Input() placeholderPet = 'Собака';


  @Output() cancelForm = new EventEmitter();
  @Output() afterSaveEvent = new EventEmitter();
  owner: OwnerModel;


  owners: Observable<{ id: number, name: string }[]>;
  pets: Observable<{ id: number, name: string }[]>;
  professions: Observable<ReferenceProfessionModel[]>;
  buttonLoading = false;
  fields = {0: 'id', 1: 'name'};
  fieldsPet = ['id', 'name', 'owners', 'type'];

  appointmentTypes: Observable<{ label: string, value: ReferenceAppointmentTypeModel }[]>;
  previousAppointments: Observable<AppointmentModel[]>;
  types = CrudType;
  showError = false;
  userAuth;
  currentUnit;

  private destroy$ = new Subject<any>();

  constructor(private fb: FormBuilder,
              private notify: NotifyService,
              protected store: Store<CrudState>,
              protected route: ActivatedRoute,
              protected petsService: PetsService,
              private ownerService: OwnersService,
              private datePipe: DatePipe,
              private router: Router,
              protected authService: AuthService,
              private dialog: MatDialog,
  ) {
    super(petsService, store);
    this.owners = this.store.pipe(select(getCrudModelData, {type: CrudType.Owner}));
    this.professions = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceProfession}));
    this.appointmentTypes = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceAppointmentType}));
    this.store.dispatch(new LoadGetListAction({type: CrudType.ReferenceAppointmentType}));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceProfession,
      params: {
        order: {surname: 'ASC'},
        offset: 0,
        limit: 30
      }
    }));
  }

  ngOnInit() {
    this.formGroup = this.fb.group({
      paymentState: ['NOT_PAID'],
      date: new FormControl(this.date, [Validators.required]),
      time: new FormControl(this.time, [Validators.required]),
      name: ['', Validators.required],
      user: new FormControl(this.user !== null ? {id: this.user.id, fullName: this.getFullName(this.user)} : null),
      status: this.fb.group({
        id: [1]
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

    switch (this.formType) {
      case CrudType.Pet:
        this.formGroup.addControl('owner', new FormControl(null, Validators.required));
        this.formGroup.addControl('pet', new FormControl(this.pet ? this.pet : null, Validators.required));
        break;
      case CrudType.Owner:
        this.formGroup.addControl('pet', new FormControl(this.pet ? this.pet : null, Validators.required));
        this.formGroup.addControl('owner', new FormControl(null, Validators.required));
        break;
      case CrudType.Appointment:
        this.formGroup.addControl('owner', new FormControl(null, Validators.required));
        this.formGroup.addControl('pet', new FormControl(null, Validators.required));
        break;
    }

    if (!this.user) {
      this.authService.getAccountUser().subscribe((res) => {
        if (this.authService.inGroup('ROLE_RECEPTIONIST')
          || this.authService.inGroup('ROLE_APPOINTMENT_ADMIN')
          || this.authService.inGroup('AdminAppointmenAll')) {
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
      if (this.petId && !isNaN(this.petId)) {
        this.petsService.getById(this.petId).subscribe(res => {
          if (res && res.status === true) {
            this.pet = res.response;
            this.formGroup.controls.pet.setValue({id: this.pet.id, name: this.pet.name});
          }
        });
      }
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
        this.setOwner();
      }
    }

    if (this.formType === CrudType.Owner || this.formType === CrudType.Appointment) {
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
            this.getRepeatAppointment(pet.id, (data) => {
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

          this.getRepeatAppointment(this.petId, (data) => {
            if (data.response.totalCount === 0) {
              this.formGroup.controls.type.get('code').setValue('PRIMARY');
              this.notify.handleMessage('У данного животного нет предыдущих приёмов', 'warning');
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


    if (this.formType === CrudType.Appointment) {
      this.formGroup.controls.owner.valueChanges
        .pipe(
          takeUntil(this.destroy$)
        )
        .subscribe(res => {
          if (res && res.id) {
            this.ownerId = res.id;
            this.getOwnerModel(this.ownerId);
            this.setPetByOwner();
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

  getRepeatAppointment(petId, callback?) {
    this.store.dispatch(new LoadMatchesAction({
      type: CrudType.Appointment,
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
        this.previousAppointments = result.response.items;
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

  submit(openAppointment: boolean = false): void {
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

      this.loading = true;
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.Appointment,
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
                type: CrudType.Appointment,
                params: model,
                onSuccess: (res) => {
                  this.buttonLoading = false;
                  if (res.status === true && res.response && res.response.id) {
                    if (this.user === null) {
                      if (openAppointment) {
                        this.router.navigate(['appointments/' + res.response.id + '/edit'], {queryParams: {code: 'OPENED'}}).then();
                      } else {
                        this.router.navigate(['/']).then();
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
              this.notify.handleMessage('В указанное время, на выбранного специалиста, существует прием', 'warning');
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

  addOwner($event): void {
    if ($event) {
      $event.preventDefault();
    }
    const dialogRef = this.dialog.open(EditComponent, {
      width: window.innerWidth > 960 ? '60%' : '90%',
      height: '100% - 50px',
      data: {
        openDialog : true,
      }

    });
    dialogRef.afterClosed().subscribe(res => {
      if (res) {
        this.ownerId = res.id;
        this.owner = res;
        this.formGroup.get('owner').setValue({id: res.id, name: res.name});
        if (!this.owner.pets || this.owner.pets.length == 0) {
          this.addPet($event);
        }
      }
    });
    }

  addPet($event): void {
    if ($event) {
      $event.preventDefault();
    }

    if (this.ownerId !== undefined) {
      const dialogRef = this.dialog.open(PetAddFormComponent, {
        width: window.innerWidth > 960 ? '60%' : '90%',
        height: '100% - 50px',
        data: {
          openDialog: true,
          owner: this.owner
        }
      });
      dialogRef.afterClosed().subscribe(res => {
        if (res) {
          this.petId = res.pet.id;
          this.formGroup.get('pet').setValue({id: res.pet.id, name: res.pet.name});
        }
      });
    }
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
}
