import {AfterViewInit, Injectable, OnDestroy, OnInit, ViewChild} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {BehaviorSubject, Observable, Subject} from 'rxjs';
import {ListFilterElementInterface, ListFilterFieldInterface} from '../modules/shared/components/list/list-filter/list-filter.model';
import {ListFilterTypeEnum} from '../modules/shared/components/list/list-filter/list-filter.enum';
import {debounceTime, distinctUntilChanged, map, takeUntil} from 'rxjs/operators';
import {CrudType} from 'src/app/common/crud-types';
import {ListFilterViewComponent} from '../modules/shared/components/list/list-filter-view/list-filter-view.component';
import {ListFilterComponent} from '../modules/shared/components/list/list-filter/list-filter.component';
import {getCrudModelData, getCrudModelGetListLoading} from '../api/api-connector/crud/crud.selectors';
import {CrudState} from '../api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from '../api/api-connector/crud/crud.actions';

@Injectable()
export class SearchModels implements OnInit, AfterViewInit, OnDestroy {
  currentTypes = new BehaviorSubject([]);
  @ViewChild(ListFilterViewComponent) listFilterViewComponent: ListFilterViewComponent;
  @ViewChild(ListFilterComponent) listFilterComponent: ListFilterComponent;
  filterFields: Array<Array<ListFilterFieldInterface>>;
  breedField: ListFilterFieldInterface;
  users$: Observable<Array<{ id: number, fullName: string }>>;
  petTypesAttributes: ListFilterElementInterface = {options: []};
  breedsAttributes: ListFilterElementInterface = {options: []};
  statusesAttributes: ListFilterElementInterface = {options: []};
  PaymentStateEnum: ListFilterElementInterface = {options: []};
  PaymentTypeEnum: ListFilterElementInterface = {options: []};
  public destroy$ = new Subject<any>();
  protected store: Store<CrudState>;
  type: CrudType;
  statusType: CrudType;
  prop: string;
  head: string;
  propStatus: string;
  filter: string;

  constructor() {
  }

  ngOnInit(): void {
    // if (this.type === CrudType.Leaving) {
    //   this.type = CrudType.Leaving;
    //   this.prop = 'leaving';
    //   this.propStatus = 'leaving.leavingStatus.id';
    //   this.statusType = CrudType.ReferenceLeavingStatus;
    //   this.head = 'выезда';
    // } else  {
    //   this.type = CrudType.Appointment;
    //   this.prop = 'appointment';
    //   this.propStatus = 'appointment.status.id';
    //   this.statusType = CrudType.ReferenceAppointmentStatus;
    //   this.head = 'приема';
    // }

    this.store.dispatch(new LoadGetListAction({type: this.statusType}));

    this.users$ = this.store.pipe(select(getCrudModelData, {type: CrudType.User})).pipe(
      map(item => {
        return item.map(user => {
            return {id: user['id'], fullName: user.getFullName()};
          }
        );
      })
    );
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.User,
      params: {
        fields: {0: 'id', 1: 'surname', 2: 'name', 3: 'patronymic'},
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      } as any
    }));


    this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceBreed})).subscribe(item => this.breedsAttributes.options = item);

    // this.breedField = {
    //   mutableSearchType: CrudType.Pet,
    //   type: ListFilterTypeEnum.multiSelect,
    //   head: {value: 'Порода животного'},
    //   prop: 'pet.measurement-units.id',
    //   attributes: this.breedsAttributes,
    //   style: 'width: 242px'
    // };

    this.filterFields = [
      [
        {
          mutableSearchType: this.type,
          type: ListFilterTypeEnum.date,
          prop: this.prop + '.>=date',
          head: {value: 'Дата ' + this.head + ' от'}
        },
        {
          mutableSearchType: this.type,
          type: ListFilterTypeEnum.date,
          prop: this.prop + '.<=date',
          head: {value: 'до'}
        },
        {
          mutableSearchType: this.type,
          type: ListFilterTypeEnum.multiSelect,
          prop: this.prop + '.pet.measurement-units.type',
          head: {value: 'Вид животного'},
          attributes: this.petTypesAttributes,
        },
        // {
        //   mutableSearchType: this.type,
        //   type: ListFilterTypeEnum.autocomplete,
        //   prop: this.prop + '.unit',
        //   field: 'name',
        //   head: {value: 'Клиника'},
        //   attributes: {
        //     optionsType: CrudType.ReferenceUnit
        //   },
        // },
      ],
      [
        {
          mutableSearchType: this.type,
          type: ListFilterTypeEnum.autocomplete,
          prop: this.prop + '.user',
          field: 'fullName',
          head: {value: 'Специалист'},
          attributes: {
            options: this.users$,
            optionsType: CrudType.User
          },
        },
        {
          mutableSearchType: this.type,
          type: ListFilterTypeEnum.select,
          prop: this.propStatus,
          head: {value: 'Статус'},
          attributes: this.statusesAttributes,
        },
        {
          mutableSearchType: this.type,
          type: ListFilterTypeEnum.select,
          prop: this.prop + '.paymentType',
          head: {value: 'Метод оплаты'},
          attributes: this.PaymentTypeEnum
        },
        {
          mutableSearchType: this.type,
          type: ListFilterTypeEnum.select,
          prop: this.prop + '.paymentState',
          head: {value: 'Статус оплаты'},
          attributes: this.PaymentStateEnum
        }
      ],
// owner
      [
        // {
        //   mutableSearchType: CrudType.Owner,
        //   type: ListFilterTypeEnum.select,
        //   head: {value: 'Укажите тип контрагента'},
        //   prop: 'owner.type',
        //   attributes: {
        //     options: Object.keys(personTypeDict).map(key => ({
        //       name: personTypeDict[key],
        //       id: key,
        //     }))
        //   }
        // },
        // {
        //   mutableSearchType: CrudType.Owner,
        //   type: ListFilterTypeEnum.multiSelect,
        //   prop: 'owner.pets.pet.measurement-units.type',
        //   head: {value: 'Укажите вид животного'},
        //   attributes: this.petTypesAttributes,
        //   style: 'width: 242px'
        // }
      ],
// pet

      [
        // {
        //   mutableSearchType: CrudType.Pet,
        //   type: ListFilterTypeEnum.multiSelect,
        //   head: {value: 'Вид животного'},
        //   prop: 'pet.measurement-units.type',
        //   attributes: this.petTypesAttributes,
        // },
        this.breedField,
        // {
        //   mutableSearchType: CrudType.Pet,
        //   type: ListFilterTypeEnum.select,
        //   head: {value: 'Пол'},
        //   prop: 'pet.=gender',
        //   attributes: {
        //     options: [
        //       {value: 'MALE', name: 'Самец'},
        //       {value: 'FEMALE', name: 'Самка'},
        //     ]
        //   }
        // },
      ],
      [
        // {
        //   mutableSearchType: CrudType.Pet,
        //   type: ListFilterTypeEnum.date,
        //   head: {value: 'Дата рождения от'},
        //   prop: 'pet.>=birthday'
        // },
        // {
        //   mutableSearchType: CrudType.Pet,
        //   type: ListFilterTypeEnum.date,
        //   head: {value: 'до'},
        //   prop: 'pet.<=birthday'
        // },
        // {
        //   mutableSearchType: CrudType.Pet,
        //   type: ListFilterTypeEnum.select,
        //   head: {value: 'Животное мертво'},
        //   prop: 'pet.isDead',
        //   attributes: {
        //     value: '0',
        //     options: [
        //       {value: '1', title: 'Да'},
        //       {value: '0', title: 'Нет'}
        //     ]
        //   }
        // }
      ],
      [
        // {
        //   mutableSearchType: CrudType.Pet,
        //   type: ListFilterTypeEnum.date,
        //   head: {value: 'Дата обращения от'},
        //   prop: 'pet.' + this.prop + '.>=date'
        // },
        // {
        //   mutableSearchType: CrudType.Pet,
        //   type: ListFilterTypeEnum.date,
        //   head: {value: 'до'},
        //   prop: 'pet.' + this.prop + '.<=date'
        // },
        // {
        //   mutableSearchType: CrudType.Pet,
        //   type: ListFilterTypeEnum.select,
        //   head: {value: 'Агрессивное'},
        //   prop: 'pet.aggressive',
        //   attributes: {
        //     options: [
        //       {value: 1, name: 'Да'},
        //       {value: 0, name: 'Нет'}
        //     ]
        //   }
        // }
      ]
// FormTemplate

    ];
    // this.store.dispatch(new LoadGetListAction({type: CrudType.ReferencePetType, params: {order: {sort: 'ASC', name: 'ASC'}}}));
    // this.store.pipe(select(getCrudModelData, {type: CrudType.ReferencePetType}))
    //   .subscribe(data => this.petTypesAttributes.options = data);


    // this.store.dispatch(new LoadGetListAction({type: CrudType.ReferenceAppointmentStatus}));
    this.store.pipe(select(getCrudModelData, {type: this.statusType}))
      .subscribe(data => this.statusesAttributes.options = data);


    // this.store.dispatch(new LoadGetListAction({type: CrudType.ReferenceOwnerStatus}));
    // this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceOwnerStatus}))
    //   .subscribe(ownerStatuses => this.ownerStatusesAttributes.options = ownerStatuses);

    this.store.pipe(select(getCrudModelGetListLoading, {type: CrudType.ReferenceBreed}))
      .subscribe(loading => this.breedField.loading = loading);

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Enum,
      params: {
        filter: {
          id: [
            'PaymentStateEnum',
            'PaymentTypeEnum',
          ]
        }
      },
      onSuccess: (res) => {
        res.response.map(
          item => {
            this[item.id].options = item.items;
          }
        );
      }
    }));
  }

  ngAfterViewInit(): void {
    // if (this.type === CrudType.Leaving) {
    //   this.report = 'leaving';
    // } else {
    //   this.report = 'appointments';
    // }

    let breed_type = null;
    if (this.listFilterComponent !== undefined || this.listFilterViewComponent !== undefined) {
      const currentForm = this.listFilterViewComponent ?
        this.listFilterViewComponent.listFilterComponent.formGroup :
        this.listFilterComponent.formGroup;

      if (currentForm.get('report.' + this.filter + '.pet.measurement-units.type')
        && currentForm.get('report.' + this.filter + '.pet.measurement-units.type').value
        && currentForm.get('report.' + this.filter + '.pet.measurement-units.type').value.id.length > 0) {
        breed_type = currentForm.get('report.' + this.filter + '.pet.measurement-units.type').value;
      }

      if (currentForm.get('report.pet.measurement-units.type')
        && currentForm.get('report.pet.measurement-units.type').value
        && currentForm.get('report.pet.measurement-units.type').value.id.length > 0) {
        breed_type = currentForm.get('report.pet.measurement-units.type').value;
      }

      if (currentForm.get('report.owner.pets.pet.measurement-units.type')
        && currentForm.get('report.owner.pets.pet.measurement-units.type').value
        && currentForm.get('report.owner.pets.pet.measurement-units.type').value.id.length > 0) {
        breed_type = currentForm.get('report.owner.pets.pet.measurement-units.type').value;
      }

      if (breed_type) {
        currentForm.get('report.pet.measurement-units.type').setValue(breed_type);
        currentForm.get('report.' + this.filter + '.pet.measurement-units.type').setValue(breed_type);
        currentForm.get('report.owner.pets.pet.measurement-units.type').setValue(breed_type);
      }

      if (currentForm.get('report.pet.measurement-units.type')) {
        currentForm.get('report.pet.measurement-units.type')
          .valueChanges
          .pipe(
            takeUntil(this.destroy$),
            debounceTime(1000),
            distinctUntilChanged()
          )
          .subscribe(types => {

            if (types) {
              currentForm.get('report.' + this.filter + '.pet.measurement-units.type').setValue(types);
              currentForm.get('report.owner.pets.pet.measurement-units.type').setValue(types);
            }

            currentForm.get('report.pet.measurement-units.id').setValue(null);

            if (types && types.id && types.id.length > 0) {
              this.currentTypes.next(types['id']);

              this.store.dispatch(new LoadGetListAction({
                type: CrudType.ReferenceBreed,
                params: {
                  filter: {type: {id: types['id']}},
                  order: {sort: 'ASC', name: 'ASC'},
                  offset: 0,
                  limit: 500,
                }
              }));
            } else {
              this.breedsAttributes.options = [];
            }
          });
      }

      if (currentForm.get('report.measurement-units.type')) {
        currentForm.get('report.measurement-units.type')
          .valueChanges
          .pipe(
            takeUntil(this.destroy$),
            debounceTime(1000),
            distinctUntilChanged()
          )
          .subscribe(types => {
            if (types) {
              currentForm.get('report.measurement-units.type').setValue(types);
            }

            currentForm.get('report.measurement-units.id').setValue(null);

            if (types && types.id && types.id.length > 0) {
              this.currentTypes.next(types['id']);

              this.store.dispatch(new LoadGetListAction({
                type: CrudType.ReferenceBreed,
                params: {
                  filter: {type: {id: types['id']}},
                  order: {sort: 'ASC', name: 'ASC'},
                  offset: 0,
                  limit: 500,
                }
              }));
            } else {
              this.breedsAttributes.options = [];
            }
          });
      }

      if (currentForm.get('report.' + this.filter + '.pet.measurement-units.type')) {
        currentForm.get('report.' + this.filter + '.pet.measurement-units.type')
          .valueChanges
          .pipe(
            takeUntil(this.destroy$),
            debounceTime(1000),
            distinctUntilChanged()
          )
          .subscribe(types => {
            if (types) {
              currentForm.get('report.owner.pets.pet.measurement-units.type').setValue(types);
              currentForm.get('report.pet.measurement-units.type').setValue(types);
            }
          });
      }
      if (currentForm.get('report.owner.pets.pet.measurement-units.type')) {
        currentForm.get('report.owner.pets.pet.measurement-units.type')
          .valueChanges
          .pipe(
            takeUntil(this.destroy$),
            debounceTime(1000),
            distinctUntilChanged()
          )
          .subscribe(types => {
            if (types) {
              currentForm.get('report.' + this.filter + '.pet.measurement-units.type').setValue(types);
              currentForm.get('report.pet.measurement-units.type').setValue(types);
            }

          });
      }
    }
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }
}
