import {Component, Input, OnInit} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {Observable} from 'rxjs';
import {PetTemperatureModel} from '../../../../../../models/pet/pet-temperature.models';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({selector: 'app-pets-profile-temperature', templateUrl: './temperature.component.html'})

export class TemperatureComponent implements OnInit {
  @Input() petId: string;
  @Input() petBirthday: string;
  type = CrudType.PetTemperature;
  temperatures$: Observable<PetTemperatureModel[]>;
  currentTemperatures = {};

  constructor(
    private store: Store<CrudState>,
  ) {

  }

  ngOnInit() {
    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: {filter: {pet: {id: this.petId}}, order: {date: 'DESC'}}
    }));

    this.temperatures$ = this.store.pipe(select(getCrudModelData, {type: this.type}));
    this.temperatures$.subscribe((res) => {
      if (res && res.length) {
        this.currentTemperatures = res[0];
      }
    });
  }

  getDateFormat(date: string) {
    return (date || '').substr(0, 10) + ' - ' + date.substr(11, 5);
  }

  getAge(date: string) {
    if (!date || !this.petBirthday) {
      return '';
    }
    const birth = new Date(this.petBirthday.replace(/(\d+).(\d+).(\d+)/, '$3/$2/$1'));
    const year = birth.getFullYear();
    const today = new Date(date.replace(/(\d+).(\d+).(\d+)/, '$3/$2/$1'));
    let age = today.getFullYear() - year - (today.getTime() < birth.setFullYear(year) ? 1 : 0);
    if (age < 0) {
      age = 0;
    }
    const titles = ['год', 'года', 'лет'];
    const cases = [2, 0, 1, 1, 1, 2];
    return age + ' ' + titles[(age % 100 > 4 && age % 100 < 20) ? 2 : cases[(age % 10 < 5) ? age % 10 : 5]];
  }

  addTemperature($event) {
    this.currentTemperatures = {...$event};
    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: {filter: {pet: {id: this.petId}}, order: {date: 'DESC'}}
    }));
  }

}
