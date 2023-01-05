import {Component, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import {Title} from '@angular/platform-browser';
import {Observable} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {PetModel} from '../../../../../models/pet/pet.models';
import {EventModel} from '../../../../../models/event.models';
import {AppointmentModel} from '../../../../../models/appointment/appointment.models';
import {PetsService} from '../../../../../services/pets.service';
import {BreadcrumbsService} from '../../../../../services/breadcrumbs.service';
import {PetWeightModel} from '../../../../../models/pet/pet-weight.models';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetAction, LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelStoreId, getCrudModelData, getCrudModelGetLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({selector: 'app-pets-profile', templateUrl: './profile.component.html'})

export class ProfileComponent implements OnInit {
  type = CrudType.Pet;
  id: string;
  pet = new PetModel();
  pet$: Observable<PetModel>;
  events$: Observable<EventModel[]>;
  appointments$: Observable<AppointmentModel[]>;
  loading$: Observable<boolean>;
  tabs = 1;

  constructor(
    private titleService: Title,
    private store: Store<CrudState>,
    private router: Router,
    private route: ActivatedRoute,
    private brdSrv: BreadcrumbsService,
    private petsService: PetsService
  ) {
    route.paramMap.subscribe(routeParam => {
      this.id = routeParam.get('id');
      if (this.id !== 'create') {
        this.store.dispatch(new LoadGetAction({type: this.type, params: this.id}));
        this.store.dispatch(new LoadGetListAction({
          type: CrudType.Event,
          params: {'filter': {'pet': {'id': this.id}}, order: {date: 'DESC'}}
        }));
        this.store.dispatch(new LoadGetListAction({
          type: CrudType.Appointment,
          params: {'filter': {'pet': {'id': this.id}}, order: {date: 'DESC'}}
        }));
      }
    });
    this.pet$ = store.pipe(select(getCrudModelStoreId, {type: this.type, params: this.id}));
    this.events$ = store.pipe(select(getCrudModelData, {type: CrudType.Event}));
    this.appointments$ = store.pipe(select(getCrudModelData, {type: CrudType.Appointment}));
    this.loading$ = this.store.pipe(select(getCrudModelGetLoading, {type: this.type}));
  }

  setTabs(num: number, event) {
    this.tabs = num;
    event.preventDefault();
    return false;
  }

  ngOnInit() {
    this.store.dispatch(new LoadGetListAction({type: CrudType.ReferenceAppointmentStatus}));
    this.pet$.subscribe(pet => {
      if (typeof (pet) === 'object' && pet instanceof PetModel) {
        this.pet = pet;
        this.pet.weight = 0;
        /*заменяем хлебные крошки*/
        this.brdSrv.replaceLabelByIndex(this.pet.name, 2);
        /*заменяем хлебные крошки*/
      }
    });
  }

  getAge() {
    if (!this.pet || !this.pet.birthday) {
      return '';
    }
    const birth = new Date(this.pet.birthday.replace(/(\d+).(\d+).(\d+)/, '$3/$2/$1'));
    const year = birth.getFullYear();
    const today = new Date();
    const age = today.getFullYear() - year - (today.getTime() < birth.setFullYear(year) ? 1 : 0);
    const titles = ['год', 'года', 'лет'];
    const cases = [2, 0, 1, 1, 1, 2];
    return age + ' ' + titles[(age % 100 > 4 && age % 100 < 20) ? 2 : cases[(age % 10 < 5) ? age % 10 : 5]];
  }

  setOwnerAsMain(owner) {
    this.petsService.setOwnerAsMain(owner.id).subscribe((res) => {
      if (res && res.status === true) {
        this.store.dispatch(new LoadGetAction({type: this.type, params: this.id}));
      }
    });
  }

  deleteOwner(owner) {
    this.petsService.removeOwner(owner.id).subscribe((res) => {
      if (res && res.status === true) {
        this.store.dispatch(new LoadGetAction({type: this.type, params: this.id}));
      }
    });
  }

  getOwnersLength() {
    if (this.pet && this.pet.owners && this.pet.owners.length) {
      return this.pet.owners.length;
    } else {
      return 0;
    }
  }

  getPetType() {
    if (this.pet && this.pet.breed && this.pet.breed.type && this.pet.breed.type.name) {
      return this.pet.breed.type.name;
    } else {
      return '';
    }
  }

  getPetBreed() {
    if (this.pet && this.pet.breed && this.pet.breed.name) {
      return this.pet.breed.name;
    } else {
      return '';
    }
  }

  getWeight() {
    return this.pet.weight > 0 ? this.pet.weight : '-';
  }

  setWeight(currentWeight: PetWeightModel) {
    this.pet.weight = parseFloat(currentWeight.value);
  }
}
