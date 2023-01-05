import {Component, OnDestroy, OnInit} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {Observable, Subscription} from 'rxjs';
import {OwnerModel} from '../../../../../models/owner/owner.models';
import {ActivatedRoute, NavigationEnd, Router} from '@angular/router';
import {filter} from 'rxjs/operators';
import {PersonType} from '../../../../../utils/person-type';
import {ViewService} from './view.service';
import {BreadcrumbsService} from '../../../../../services/breadcrumbs.service';
import {CrudType} from 'src/app/common/crud-types';
import {NotifyService} from '../../../../../services/notify.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelGetLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './view.component.html'})

export class ViewComponent implements OnInit, OnDestroy {

  owner: OwnerModel;
  loading$: Observable<boolean>;
  tabs: {link: string | string[], title: string}[];
  type = CrudType.Owner;
  private subscriptions: Subscription[] = [];

  constructor(
    private store: Store<CrudState>,
    private route: ActivatedRoute,
    private router: Router,
    private service: ViewService,
    private breadcrumbs: BreadcrumbsService,
    private notify: NotifyService,
  ) {
    this.route.params.pipe(filter(params => !!params['id'])).subscribe(params => {
      this.service.id = params['id'];
      this.store.dispatch(new LoadGetAction({
        type: this.type,
        params: <any>{
          id: this.id,
          fields: {
            0: 'id',
            1: 'type',
            2: 'name',
            3: 'phone',
            4: 'email',
            5: 'additionalContacts',
            6: 'inn',
            7: 'fullName',
            8: 'passport',
            9: 'address',
            10: 'individualPerson',
            11: 'legalEntity',
            12: 'legalForm',
            13: 'entrepreneur',
            14: 'farm',
            15: 'farmMembers',
            16: 'activities',
            17: 'customActivities',
            18: 'status',
            19: 'contactPersons',
            20: 'contractDateTo',
            21: 'gender',
            'pets': {
              0: 'id', 1: 'mainOwner', pet: ['id', 'name', 'type', 'breed', 'lear', 'description',
                'aggressive', 'aggressiveType', 'isSterilized', 'gender', 'birthday', 'vaccinationDate', 'chipNumber',
                'address', 'isDead', 'dateOfDeath'], owner: ['id']
            }
          }
        },
        onError: e => {
          let t = '';

          e.errors.map(a => t += a.message);

          t += '<br><br>Через 3 секунды вас перенаправит на страницу с пользователями';

          this.notify.handleMessage(t, 'danger', 1000);
          setTimeout(() => {
            this.notify.closeAllMessage();
            this.router.navigate(['/owners/']);
          }, 3000);
        }
      }));
    });
    this.loading$ = store.pipe(select(getCrudModelGetLoading, {type: this.type}));
  }

  get id() {
    return this.service.id;
  }

  get owner$() {
    return this.service.owner$;
  }

  ngOnInit() {
    const s = this.owner$.subscribe(owner => {
      this.owner = owner;
      this.breadcrumbs.replaceLabelByIndex(owner.name, 2);
      const l = this.router.events
        .pipe(filter(event => event instanceof NavigationEnd))
        .subscribe(event => this.breadcrumbs.replaceLabelByIndex(owner.name, 2));
      this.subscriptions.push(l);

      switch (owner.type) {
        case PersonType.INDIVIDUAL_PERSON:
          this.tabs = [
            {link: ['profile'], title: 'Профиль и животные'},
            {link: ['history'], title: 'История обращений'},
            {link: ['accounts'], title: 'Счета'},
            {link: ['documents'], title: 'Документы'},
            {link: ['researchs'], title: 'Анализы'},
            {link: ['payment-history'], title: 'История оплаты'},
          ];
          break;
        case PersonType.LEGAL_ENTITY:
        case PersonType.FARM:
        case PersonType.ENTREPRENEUR:
          this.tabs = [
            {link: ['profile'], title: 'Профиль компании'},
            {link: ['objects'], title: 'Подконтрольные объекты'},
            {link: ['pets'], title: 'Животные'},
            {link: ['accounts'], title: 'Счета'},
            {link: ['documents'], title: 'Документы'},
            {link: ['researchs'], title: 'Анализы'},
            {link: ['payment-history'], title: 'История оплаты'},
          ];
          break;
        default:
          this.tabs = [];
          break;
      }
    });
    this.subscriptions.push(s);
  }

  ngOnDestroy(): void {
    this.subscriptions
      .forEach(s => s.unsubscribe());
  }
}
