import {Injectable} from '@angular/core';
import {PetModel} from '../../../../../models/pet/pet.models';
import {Observable} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {CrudType} from 'src/app/common/crud-types';
import {NotifyService} from '../../../../../services/notify.service';
import {Router} from '@angular/router';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelGetLoading, getCrudModelStoreId} from 'src/app/api/api-connector/crud/crud.selectors';

@Injectable({
  providedIn: 'root'
})
export class ViewService {
  pet: Observable<PetModel>;
  loading$: Observable<boolean>;
  type = CrudType.Pet;

  constructor(
    private store: Store<CrudState>,
    private notify: NotifyService,
    private router: Router,
  ) {
    this.loading$ = this.store.pipe(select(getCrudModelGetLoading, {type: this.type}));

  }

  private _id: string;

  get id(): string {
    return this._id;
  }

  set id(value: string) {
    this._id = value;
    if (this._id) {
      this.getPet().pet = this.store.pipe(select(getCrudModelStoreId, {type: this.type, params: this.id}));
    }
  }

  getDateFormat(date: string) {
    return (date || '').substr(0, 10) + ' - ' + (date || '').substr(11, 5);
  }

  getAge(date: string, petBirthday: string) {
    if (!date || !petBirthday) {
      return '';
    }
    const birth = new Date(petBirthday.replace(/(\d+).(\d+).(\d+)/, '$3/$2/$1'));
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

  getPet(): this {
    this.store.dispatch(new LoadGetAction({
      type: this.type, params: <any>{
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
                'owners': {0: 'id', 1: 'mainOwner', owner: ['id', 'name', 'address', 'fullName']},
                'actualWeight': ['id', 'date', 'value', 'deleted'],
                'actualTemperature': ['id', 'date', 'value', 'deleted'],
      }},
      onError: e => {
        let t = '';

        e.errors.map(a => t += a.message);

        t += '<br><br>Через 3 секунды вас перенаправит на страницу с питомцами';

        this.notify.handleMessage(t, 'danger', 1000);
        setTimeout(() => {
          this.notify.closeAllMessage();
          this.router.navigate(['/pets/']);
        }, 3000);
      }
    }));
    return this;
  }
}
